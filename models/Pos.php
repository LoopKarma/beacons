<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\validators\UniqueValidator;

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
            [['merchant_id'], 'required'],
            [['merchant_id'], 'integer'],
            [['address', 'beacon_identifier'], 'string', 'max' => 256],
            [['major', 'minor'], 'string', 'max' => 20],
            [['merchant_id'], 'validateUniqueMajorMinor']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pos_id' => 'Pos ID',
            'merchant_id' => 'Merchant',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'address' => 'Address',
            'beacon_identifier' => 'Beacon Identifier',
            'major' => 'Major',
            'minor' => 'Minor',
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
}
