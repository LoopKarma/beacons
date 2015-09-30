<?php
namespace app\api\modules\v1\models;

use app\models\BarcodeMessage;
use Yii;
use yii\base\Model;
use app\models\Pos;
use app\models\Merchant;
use app\models\Coupon;
use yii\db\ActiveRecord;
use app\models\CouponTemplate;
use yii\helpers\VarDumper;
use app\helpers\RandomString;

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
    /** @var string */
    public $serialNumber;
    /** @var string */
    public $message;

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
        $this->merchant = Merchant::find()->where(['uuid' => $this->uuid])->one();
        if ($this->merchant) {
            $this->pos = Pos::find()->where([
                'merchant_id' => $this->merchant->primaryKey,
                'major' => $this->major,
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
        $coupon = Coupon::find()->where([
            'merchant_id' => $this->merchant->primaryKey,
            'pos_id' => $this->pos->primaryKey,
            'template_id' => $this->template->primaryKey,
        ])->one();
        if ($coupon) {
            $this->addError('error', 'You already have a coupon for this point');
        }
    }

    public function afterValidate()
    {
        if (!$this->template->without_barcode) {
            $this->message = $this->getBarcodeMessage();
        }
        parent::afterValidate();
    }

    public function generateCoupon()
    {
        $this->serialNumber = Yii::$app->security->generateRandomString(10);
        if ($this->coupPath = Yii::$app->pass->generatePass($this)) {
            $this->createCouponRecord($this->serialNumber);
            return $this->coupPath;
        } else {
            $this->addError('error', 'Error while creating coupon');
        }
    }

    public function getBarcodeMessage()
    {
        $message = $this->findMessage();
        if (!$message) {
            $message = $this->generateMessage();
        }
        return $message;
    }

    private function findMessage()
    {
        $message = BarcodeMessage::find()
            ->select('message')
            ->where([
                'merchant_id' => $this->merchant->primaryKey,
                'utilize' => null
            ])
            ->orderBy(['create_date' => SORT_ASC])
            ->one();

        return $message;
    }

    private function generateMessage()
    {
        $lastCoupon = Coupon::find()->select('coupon_id')->orderBy(['coupon_id' => SORT_DESC])->asArray()->one();
        $nextCouponId = $lastCoupon['coupon_id'] + 1;
        return RandomString::generate(BarcodeMessage::MESSAGE_LENGTH - strlen($nextCouponId)).$nextCouponId;
    }

    public function createCouponRecord($serialNumber)
    {
        $coupon = new Coupon();
        $coupon->setAttributes([
            'template_id' => $this->template->primaryKey,
            'merchant_id' => $this->merchant->primaryKey,
            'pos_id' => $this->pos->primaryKey,
            'client' => $this->client,
            'message' => $this->message,
            'uuid' => $this->uuid,
            'major' => $this->major,
            'minor' => $this->minor,
            'serial_number' => $serialNumber,
        ]);
        $coupon->save(false);
    }
}
