<?php
namespace app\api\modules\v1\models;

use Yii;
use yii\base\Model;
use app\models\Pos;
use app\models\Merchant;
use app\models\Coupon;
use yii\db\ActiveRecord;
use app\models\CouponTemplate;
use app\models\BarcodeMessage;

class CouponGenerator extends Model
{
    public $client;
    public $uuid;
    public $major;
    public $minor;
    public $coupPath;

    public $error;
    /** @var  \app\models\Merchant $merchant */
    public $merchant;
    /** @var  \app\models\Pos $pos */
    public $pos;
    /** @var  \app\models\CouponTemplate $template */
    public $template;
    /** @var  \app\models\BarcodeMessage $message */
    public $message;

    /** @var string */
    public $serialNumber;
    /** @var string */
    public $messageText;

    /** @var \app\components\Pass $pass */
    protected $pass;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client', 'uuid', 'major', 'minor'], 'required'],
            [['client', 'uuid', 'major', 'minor'], 'string'],
            [['merchant', 'pos', 'template'], 'validateObjectExist', 'skipOnEmpty' => false],
            ['merchant', 'validateIfCouponAlreadyExist']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'merchant' => 'Merchant',
            'pos' => 'Point of sale',
            'template' => 'Template',
        ];
    }

    public function beforeValidate()
    {
        $this->merchant = Merchant::find()->where(['uuid' => $this->uuid, 'major' => $this->major])->one();
        if ($this->merchant) {
            $this->pos = Pos::find()->where([
                'merchant_id' => $this->merchant->primaryKey,
                'minor' => $this->minor
            ])->one();
            if ($this->pos) {
                $this->template = CouponTemplate::find()->joinWith(['templatePos'])->where([
                    'merchant_id' => $this->merchant->primaryKey,
                    'pos_id' => $this->pos->primaryKey,
                    'active' => 1,
                ])->one();
            }
        }
        return true;
    }

    public function validateObjectExist($attribute)
    {
        if (!$this->{$attribute} || !($this->{$attribute} instanceof ActiveRecord)) {
            $this->addError($attribute, $this->getAttributeLabel($attribute) . ' is not found');
        }
    }

    public function validateIfCouponAlreadyExist()
    {
        if (isset($this->template) && !$this->template->send_unlimited && !$this->hasErrors()) {
            $coupon = Coupon::find()->where([
                'client' => $this->client,
                'merchant_id' => $this->merchant->primaryKey,
                'pos_id' => $this->pos->primaryKey,
                'template_id' => $this->template->primaryKey,
            ])->one();
            if ($coupon) {
                $this->addError('error', 'You already have a coupon for this point');
            }
        }
    }

    public function afterValidate()
    {
        if (!$this->hasErrors() && !$this->template->without_barcode) {
            $this->messageText = $this->getBarcodeMessage();
        }
        $this->pass = Yii::$app->pass;
        parent::afterValidate();
    }

    public function generateCoupon()
    {
        $this->serialNumber = $this->getSerialNumber();
        if ($this->coupPath = $this->pass->generatePass($this)) {
            $this->createCouponRecord();
            return $this->coupPath;
        } else {
            $this->addError('error', 'Error while creating coupon: ' . $this->pass->error);
        }
        return false;
    }

    public function getTemplateSendScenario()
    {
        /** @var \app\models\CouponTemplate $sendScenario */
        $sendScenario = $this->template->send_scenario;
        if ($sendScenario == CouponTemplate::SEND_ON_ENTER) {
            return [
                'scenario' => CouponTemplate::SEND_ON_ENTER,
                'send' => 'on enter',
            ];
        } else {
            return [
                'scenario' => CouponTemplate::SEND_ON_LEAVING,
                'send' => 'on leave',
            ];
        }
    }

    public function getBarcodeMessage()
    {
        $message = $this->findMessage();
        if (!$message) {
            $message = BarcodeMessage::generateMessage($this->merchant->primaryKey);
        }
        return $message;
    }

    public function createCouponRecord()
    {
        $coupon = new Coupon();
        $coupon->setAttributes([
            'template_id' => $this->template->primaryKey,
            'merchant_id' => $this->merchant->primaryKey,
            'pos_id' => $this->pos->primaryKey,
            'client' => $this->client,
            'message' => $this->messageText,
            'uuid' => $this->uuid,
            'major' => $this->major,
            'minor' => $this->minor,
            'serial_number' => $this->serialNumber,
        ]);
        if ($coupon->save(false) && $this->message) {
            $this->message->updateAttributes(['utilize' => true]);
        }
    }

    protected function getSerialNumber()
    {
        return Coupon::generateSerialNumber($this->merchant->primaryKey);
    }

    private function findMessage()
    {
        /** @var \app\models\BarcodeMessage $message */
        $message = BarcodeMessage::find()
            ->where([
                'merchant_id' => $this->merchant->primaryKey,
                'utilize' => 0
            ])
            ->orderBy(['create_date' => SORT_ASC])
            ->one();

        if ($message) {
            $this->message = $message;
        }
        return isset($message->message) ? $message->message : false;
    }
}
