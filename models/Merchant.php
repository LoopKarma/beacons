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
            [['cert_files'], 'required', 'on' => 'create'],
            [['uuid'], 'unique'],
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
            'merchant_id' => 'Мерчант ID',
            'create_date' => 'Дата создания',
            'update_date' => 'Дата изменения',
            'uuid' => 'UUID',
            'name' => 'Название',
            'description' => 'Описание',
            'pass_type_id' => 'Pass Type ID',
            'cert_files' => 'Файл сертификата .p12',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPos()
    {
        return $this->hasMany(Pos::className(), ['merchant_id' => 'merchant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCertFile()
    {
        return $this->hasOne(CertFile::className(), ['file_id' => 'cert_files']);
    }

    /**
     * @return array
     */
    public static function getMerchantList()
    {
        $list = ['' => '(нет значения)'];
        $items = self::find()->select(['merchant_id', 'name'])->asArray()->all();
        foreach ($items as $item) {
            $list[$item['merchant_id']] = $item['name'];
        }
        return $list;
    }
}
