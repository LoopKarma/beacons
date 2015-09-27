<?php
namespace app\api\modules\v1\models;

use Yii;
use yii\base\Model;
use app\models\Pos;
use app\models\Merchant;
use app\models\Coupon;

use app\models\CouponTemplate;
use yii\db\ActiveRecord;
use yii\helpers\VarDumper;

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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client', 'uuid', 'major', 'minor'], 'required'],
            [['client', 'uuid', 'major', 'minor'], 'string'],
            ['merchant', 'validateObjectExist'],
            ['pos', 'validateObjectExist'],
            ['template', 'validateObjectExist'],
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

    public function generateCoupon()
    {
        $serialNumber = Yii::$app->security->generateRandomString(10);
        if ($this->coupPath = Yii::$app->pass->generatePass($this->template, $this->merchant, $this->pos, $serialNumber)) {
            $coupon = new Coupon();
            $coupon->template_id = $this->template->primaryKey;
            $coupon->merchant_id = $this->merchant->primaryKey;
            $coupon->pos_id = $this->pos->primaryKey;
            $coupon->client = $this->client;
            /** TODO сделать правильное сообщение */
            $coupon->message = 'Std message';
            $coupon->uuid = $this->uuid;
            $coupon->major = $this->major;
            $coupon->minor = $this->minor;
            $coupon->serial_number = $serialNumber;
            $coupon->save(false);

            return $this->coupPath;
        } else {
            $this->addError('error', 'Error while creating coupon');
        }
    }
}
