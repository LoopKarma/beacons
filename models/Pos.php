<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "ai_pos".
 *
 * @property integer $pos_id
 * @property integer $merchant_id
 * @property string $create_date
 * @property string $update_date
 * @property string $address
 * @property string $beacon_identifier
 * @property string $major
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
            [['merchant_id', 'major', 'minor'], 'required'],
            [['merchant_id'], 'integer'],
            [['address', 'beacon_identifier'], 'string', 'max' => 256],
            [['major', 'minor'], 'string', 'max' => 20],
            [['merchant_id'], 'validateUniqueMajorMinor']
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
            'major' => 'Major маячка',
            'minor' => 'Minor маячка',
        ];
    }

    public function validateUniqueMajorMinor()
    {
        $currentSet = $this->merchant_id . $this->major . $this->minor;
        $merchantPos = self::find()
            ->select(['major', 'minor'])
            ->where(['merchant_id' => $this->merchant_id])
            ->all();
        foreach ($merchantPos as $pos) {
            if ($currentSet == $this->merchant_id . $pos->major . $pos->minor) {
                $this->addErrors([
                    'merchant_id' => 'Такая комбинация major и minor уже существует для данного мерчанта',
                    'major' => 'Небходимо изменить параметры точки',
                    'minor' => 'Небходимо изменить параметры точки',
                ]);
                break;
            }
        }
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

    public static function getPointsArray()
    {
        $points = [];
        $items = static::find()->select(['pos_id', 'address'])->all();
        foreach ($items as $item) {
            $points[$item->pos_id] = $item->address;
        }
        return $points;
    }
}
