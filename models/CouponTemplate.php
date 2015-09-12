<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 *
 * @property integer $template_id
 * @property integer $active
 * @property string $create_date
 * @property string $update_date
 * @property integer $merchant_id
 * @property string $organization_name
 * @property string $team_identifier
 * @property string $logo_text
 * @property string $description
 * @property string $label_color
 * @property string $coupon
 * @property string $beacon_relevant_text
 * @property string $without_barcode
 * @property string $barcode_format
 * @property string $barcode_message_encoding
 * @property string $icon
 * @property string $icon_retina
 * @property string $logo
 * @property string $logo_retina
 * @property string $strip_image
 * @property string $strip_image_retina
 * @property string $foreground_color
 * @property string $background_color
 *
 */
class CouponTemplate extends \yii\db\ActiveRecord
{
    const DEF_ORGANIZATION_NAME = 'GetCoupon';
    const DEF_TEAM_IDENTIFIER = '8V4MJ9GE5G';
    const DEF_BEACON_REALEVANT_TEXT = 'Воспользуйтесь купоном!';
    const BARCODE_FORMAT = ['PKBarcodeFormatPDF417', 'PKBarcodeFormatQR'];
    const DEF_BARCODE_MESSAGE_ENCODING = 'iso-8859-1';
    const COUPON_JSON_KEYS = [
        'coupon', 'headerFields', 'primaryFields', 'secondaryFields', 'auxiliaryFields', 'backFields'
    ];

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
            [
                [
                    'active',
                    'merchant_id',
                ],
                'integer'
            ],
            [['merchant_id', 'coupon'], 'required'],
            [['icon', 'logo', 'strip_image'], 'required', 'on' => 'create'],
            [['coupon'], 'string'],
            [
                ['organization_name', 'team_identifier', 'logo_text', 'description', 'beacon_relevant_text'],
                'string',
                'max' => 256
            ],
            [['barcode_format', 'barcode_message_encoding'], 'string', 'max' => 100],
            [['foreground_color', 'background_color'], 'string', 'max' => 16, 'min' => 10],
            [
                ['foreground_color', 'background_color'],
                'match',
                'pattern' => '/^rgb\(\d+,\d+,\d+\)$/',
                'message' => 'Поле должно содержать данные вида rgb(x,x,x)'
            ],
            [['coupon'], 'validateKeys'],
            [['coupon'], 'validateIsJsonValid'],
            [['organization_name'], 'default', 'value' => static::DEF_ORGANIZATION_NAME],
            [['team_identifier'], 'default', 'value' => static::DEF_TEAM_IDENTIFIER],
            [['beacon_relevant_text'], 'default', 'value' => static::DEF_BEACON_REALEVANT_TEXT],
            [['barcode_format'], 'in', 'range' => static::BARCODE_FORMAT],
            [['barcode_message_encoding'], 'default', 'value' => static::DEF_BARCODE_MESSAGE_ENCODING],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'template_id' => 'ID Шаблона',
            'active' => 'Активность',
            'merchant_id' => 'Мерчант',
            'create_date' => 'Дата создания',
            'update_date' => 'Дата измения',
            'organization_name' => 'Organization Name',
            'team_identifier' => 'Team Identifier',
            'logo_text' => 'Logo Text',
            'description' => 'Description',
            'coupon' => 'Coupon',
            'foreground_color' => 'Foreground Color',
            'background_color' => 'Background Color',
            'beacon_relevant_text' => 'Beacon Relevant Text',
            'without_barcode' => 'Без barcode',
            'barcode_format' => 'Barcode Format',
            'barcode_message_encoding' => 'Barcode Message Encoding',
            'icon' => 'Изображение Icon',
            'icon_retina' => 'Изображение Icon Retina',
            'logo' => 'Изображение Logo',
            'logo_retina' => 'Изображение Logo Retina',
            'strip_image' => 'Изображение Strip',
            'strip_image_retina' => 'Изображение Strip Retina',
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

    public function validateIsJsonValid($attribute)
    {
        $json = '{' . $this->{$attribute} . '}';
        if (!json_decode($json)) {
            $this->addError($attribute, 'Неверная Json-структура');
        }
    }

    public function validateKeys($attribute)
    {
        foreach (static::COUPON_JSON_KEYS as $key) {
            if (strpos($this->{$attribute}, $key) === false) {
                $this->addError($attribute, 'Отсутствует обязательный ключ Json-струкутры "' . $key . '"');
            }
        }
    }

    public function beforeValidate()
    {
        if (mb_substr($this->coupon, -1) == ',') {
            $this->coupon = mb_substr($this->coupon, 0, -1);
        }
        return true;
    }

    public function getTemplatePos()
    {
        return $this->hasMany(TemplatePos::className(), ['template_id' => 'template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPos()
    {
        return $this->hasMany(Pos::className(), ['pos_id' => 'pos_id'])->via('templatePos');

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
    public function getIconFile()
    {
        return $this->hasOne(TemplateFile::className(), ['file_id' => 'icon']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIconRetinaFile()
    {
        return $this->hasOne(TemplateFile::className(), ['file_id' => 'icon_retina']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogoFile()
    {
        return $this->hasOne(TemplateFile::className(), ['file_id' => 'logo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogoRetinaFile()
    {
        return $this->hasOne(TemplateFile::className(), ['file_id' => 'logo_retina']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStripImageFile()
    {
        return $this->hasOne(TemplateFile::className(), ['file_id' => 'strip_image']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStripImageRetinaFile()
    {
        return $this->hasOne(TemplateFile::className(), ['file_id' => 'strip_image_retina']);
    }

    public function getPoses($attr = false)
    {
        $list = [];
        $poses = $this->pos;
        if (is_array($poses)) {
            foreach ($poses as $pos) {
                if ($attr) {
                    $list[] = $pos->{$attr};
                } else {
                    $list[] = $pos->primaryKey;
                }
            }
        }
        return $list;
    }
}
