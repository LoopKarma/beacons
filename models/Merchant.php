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
 * @property string $major
 * @property string $name
 * @property string $description
 * @property string $pass_type_id
 * @property string $cert_files
 */
class Merchant extends \yii\db\ActiveRecord
{
    const CACHE_KEY = 'merchant_list';
    const CACHE_TIMEOUT = '3600';

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
            [['uuid', 'name', 'pass_type_id', 'major'], 'required'],
            [['cert_files'], 'required', 'on' => 'create'],
            [['uuid'], 'validateUniqueMerchant'],
            [['uuid'], 'string', 'max' => 36],
            [['major'], 'string', 'max' => 20],
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
            'major' => 'Major маячка',
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

    public function validateUniqueMerchant()
    {
        $merchant = self::find()->where(['uuid' => $this->uuid, 'major' => $this->major])->count('merchant_id');
        if ($merchant) {
            $error = 'Мерчант с таким набором uuid и major уже существует';
            $this->addError('uuid', $error);
            $this->addError('major', $error);
        }
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
        $list = Yii::$app->cache->get(static::CACHE_KEY);
        if (!$list) {
            $items = self::find()->select(['merchant_id', 'name'])->asArray()->all();
            foreach ($items as $item) {
                $list[$item['merchant_id']] = $item['name'];
            }
            Yii::$app->cache->set(static::CACHE_KEY, $list, static::CACHE_TIMEOUT);
        }

        return $list;
    }
}
