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
 * @property integer $message_id
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
            [['create_date', 'template_id', 'merchant_id', 'pos_id', 'client', 'message_id', 'uuid', 'major', 'minor', 'serial_number'], 'required'],
            [['create_date', 'update_date'], 'safe'],
            [['template_id', 'merchant_id', 'pos_id', 'confirmed', 'message_id'], 'integer'],
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
            'coupon_id' => 'Coupon ID',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'template_id' => 'Template ID',
            'merchant_id' => 'Merchant ID',
            'pos_id' => 'Pos ID',
            'client' => 'Client',
            'confirmed' => 'Confirmed',
            'message_id' => 'Message ID',
            'uuid' => 'Uuid',
            'major' => 'Major',
            'minor' => 'Minor',
            'serial_number' => 'Serial Number',
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
                'updatedAtAttribute' => 'update_date',
                'value' => function () {
                    return date("Y-m-d H:i:s");
                },
            ],
        ];
    }
}
