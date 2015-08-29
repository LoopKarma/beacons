<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "ai_merchant".
 *
 * @property integer $merchant_id
 * @property string $create_date
 * @property string $update_date
 * @property string $uuid
 * @property string $name
 * @property string $description
 * @property string $pass_type_id
 * @property string $cert_files
 */
class Merchant extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%merchant}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid', 'name', 'pass_type_id'], 'required'],
            [['cert_files'], 'string'],
            [['uuid'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 100],
            [['description', 'pass_type_id'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'merchant_id' => 'Merchant ID',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'uuid' => 'Uuid',
            'name' => 'Name',
            'description' => 'Description',
            'pass_type_id' => 'Pass Type ID',
            'cert_files' => 'Cert Files',
        ];
    }

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

    public function getPos()
    {
        return $this->hasMany(Pos::className(), ['merchant_id' => 'merchant_id']);
    }

    public static function getMerchantList()
    {
        $list = [];
        $items = self::find()->select(['merchant_id', 'name'])->asArray()->all();
        foreach ($items as $item) {
            $list[$item['merchant_id']] = $item['name'];
        }
        return $list;
    }
}
