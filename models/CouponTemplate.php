<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "ai_coupon_template".
 *
 * @property integer $template_id
 * @property integer $active
 * @property string $create_date
 * @property string $update_date
 * @property integer $merchant_id
 * @property integer $pos
 * @property string $organization_name
 * @property string $team_identifier
 * @property string $logo_text
 * @property string $description
 * @property string $foreground_color
 * @property string $background_color
 * @property string $label_color
 * @property string $coupon_fields
 * @property string $beacon_relevant_text
 * @property string $barcode_format
 * @property string $barcode_message_encoding
 *
 * @property TemplatePos[] $templatePos
 */
class CouponTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_template}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active', 'merchant_id', 'pos'], 'integer'],
            [['merchant_id', 'coupon_fields'], 'required'],
            [['coupon_fields'], 'string'],
            [
                ['organization_name', 'team_identifier', 'logo_text', 'description', 'beacon_relevant_text'],
                'string',
                'max' => 256
            ],
            [['foreground_color', 'background_color', 'label_color'], 'string', 'max' => 30],
            [['barcode_format', 'barcode_message_encoding'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'template_id' => 'Template ID',
            'active' => 'Active',
            'merchant_id' => 'Merchant ID',
            'pos' => 'Pos',
            'organization_name' => 'Organization Name',
            'team_identifier' => 'Team Identifier',
            'logo_text' => 'Logo Text',
            'description' => 'Description',
            'foreground_color' => 'Foreground Color',
            'background_color' => 'Background Color',
            'label_color' => 'Label Color',
            'coupon_fields' => 'Coupon Fields',
            'beacon_relevant_text' => 'Beacon Relevant Text',
            'barcode_format' => 'Barcode Format',
            'barcode_message_encoding' => 'Barcode Message Encoding',
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
        return $this->hasMany(Pos::className(), ['template_id' => 'pos_id'])
                    ->viaTable('{{%template_pos}}', ['template_id' => 'template_id']);
    }
}
