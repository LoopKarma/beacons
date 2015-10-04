<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "ai_pos".
 *
 * @property integer $pos_id
 * @property integer $merchant_id
 * @property string $create_date
 * @property string $update_date
 * @property string $address
 * @property string $beacon_identifier
 * @property string $minor
 *
 * @property TemplatePos[] $templatePos
 */
class Pos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pos}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id', 'minor'], 'required'],
            [['merchant_id'], 'integer'],
            [['address', 'beacon_identifier'], 'string', 'max' => 256],
            [['minor'], 'string', 'max' => 20],
            [['minor'], 'checkIfUnique']
        ];
    }

    /**
     * @inheritdocесм
     */
    public function attributeLabels()
    {
        return [
            'pos_id' => 'Точка продажи ID',
            'merchant_id' => 'Мерчант',
            'create_date' => 'Дата создания',
            'update_date' => 'Дата изменения',
            'address' => 'Адрес',
            'beacon_identifier' => 'Идентификатор маячка',
            'minor' => 'Minor маячка',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplatePos()
    {
        return $this->hasMany(TemplatePos::className(), ['pos_id' => 'pos_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant()
    {
        return $this->hasOne(Merchant::className(), ['merchant_id' => 'merchant_id']);
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

    public function checkIfUnique($attribute)
    {
        $currentSet = $this->merchant_id . $this->{$attribute};
        $merchantPos = self::find()
            ->select(['minor'])
            ->where(['merchant_id' => $this->merchant_id])
            ->all();
        foreach ($merchantPos as $pos) {
            if ($currentSet == $this->merchant_id . $pos->minor) {
                $this->addErrors([
                    'merchant_id' => 'Такая комбинация major и minor уже существует для данного мерчанта',
                    $attribute => 'Небходимо изменить параметры точки',
                ]);
                break;
            }
        }
    }

    public static function getPointsArray($merchant = false)
    {
        $points = [];
        $query = static::find()->select(['pos_id', 'address']);
        if ($merchant) {
            $query->where(['merchant_id' => (int)$merchant]);
        }

        $items = $query->all();
        if ($items) {
            foreach ($items as $item) {
                $points[$item->pos_id] = $item->address;
            }
        }

        return $points;
    }
}
