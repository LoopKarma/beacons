<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "ai_coupon".
 *
 * @property integer $coupon_id
 * @property string $create_date
 * @property string $update_date
 * @property integer $template_id
 * @property integer $merchant_id
 * @property integer $pos_id
 * @property string $client
 * @property integer $confirmed
 * @property integer $message
 * @property string $uuid
 * @property string $major
 * @property string $minor
 * @property string $serial_number
 */
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_id', 'merchant_id', 'pos_id', 'client', 'message', 'uuid', 'major', 'minor'], 'required'],
            [['template_id', 'merchant_id', 'pos_id', 'confirmed', 'message'], 'integer'],
            [['client', 'serial_number'], 'string', 'max' => 100],
            [['uuid', 'major', 'minor'], 'string', 'max' => 32],
            [['serial_number'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coupon_id' => 'ID купона',
            'create_date' => 'Дата создания',
            'template_id' => 'ID шаблона',
            'merchant_id' => 'ID мерчанта',
            'pos_id' => 'Точка продаж',
            'client' => 'Client',
            'confirmed' => 'Подтверждено',
            'message' => ' ID',
            'uuid' => 'Uuid',
            'major' => 'Major',
            'minor' => 'Minor',
            'serial_number' => 'Серийный номер',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_date',
                'updatedAtAttribute' => false,
                'value' => function () {
                    return date("Y-m-d H:i:s");
                },
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant()
    {
        return $this->hasOne(Merchant::className(), ['merchant_id' => 'merchant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(CouponTemplate::className(), ['template_id' => 'template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPos()
    {
        return $this->hasOne(Pos::className(), ['pos_id' => 'pos_id']);
    }
}