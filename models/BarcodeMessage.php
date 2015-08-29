<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "ai_barcode_message".
 *
 * @property integer $message_id
 * @property string $create_date
 * @property string $update_date
 * @property integer $merchant_id
 * @property string $message
 * @property integer $utilize
 */
class BarcodeMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%barcode_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_date', 'merchant_id'], 'required'],
            [['create_date', 'update_date'], 'safe'],
            [['merchant_id', 'utilize'], 'integer'],
            [['message'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'message_id' => 'Message ID',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'merchant_id' => 'Merchant ID',
            'message' => 'Message',
            'utilize' => 'Utilize',
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
