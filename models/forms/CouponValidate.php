<?php
namespace app\models\forms;

use app\models\Coupon;
use yii\base\Model;

class CouponValidate extends Model
{
    public $serialNumber;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['serialNumber'], 'required'],
            [['serialNumber'], 'string', 'max' => Coupon::SERIAL_NUMBER_LENGTH],
            [
                ['serialNumber'],
                'exist',
                'targetAttribute' => 'serial_number',
                'targetClass' => Coupon::className(),
                'filter' => [
                    'confirmed' => 0,
                    'merchant_id' => \Yii::$app->user->identity->merchant_id
                ],
                'message' => 'Купон не найден'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'serialNumber' => 'Серийный номер купона',
        ];
    }

    public function validateCoupon()
    {
        /** @var \app\models\Coupon $coupon */
        $coupon = Coupon::findOne(['serial_number' => $this->serialNumber]);
        if ($coupon->updateAttributes(['confirmed' => 1])) {
            return true;
        }
        return false;
    }
}
