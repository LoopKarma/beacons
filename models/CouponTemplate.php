<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;

/**
 *
 * @property integer $template_id
 * @property string $name
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
 * @property string $show_serial
 * @property string $do_not_generate_messages
 * @property string $barcode_format
 * @property string $barcode_message_encoding
 * @property string $send_unlimited
 * @property string $send_scenario
 * @property string $icon
 * @property string $icon2x
 * @property string $icon3x
 * @property string $logo
 * @property string $logo2x
 * @property string $logo3x
 * @property string $strip
 * @property string $strip2x
 * @property string $strip3x
 * @property string $foreground_color
 * @property string $background_color
 */
class CouponTemplate extends \yii\db\ActiveRecord
{
    const DEF_ORGANIZATION_NAME = 'GetCoupon';
    const DEF_TEAM_IDENTIFIER = '8V4MJ9GE5G';
    const DEF_BEACON_REALEVANT_TEXT = 'Воспользуйтесь купоном!';
    const BARCODE_FORMAT = [
        'PKBarcodeFormatPDF417',
        'PKBarcodeFormatQR',
        'PKBarcodeFormatAztec',
        'PKBarcodeFormatCode128',
    ];
    const DEF_BARCODE_MESSAGE_ENCODING = 'iso-8859-1';
    const COUPON_JSON_KEYS = [
        'coupon', 'headerFields', 'primaryFields', 'secondaryFields', 'auxiliaryFields', 'backFields'
    ];

    const SEND_ON_ENTER = 0;
    const SEND_ON_LEAVING = 1;

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
                    'send_unlimited',
                    'send_scenario',
                ],
                'integer'
            ],
            [['merchant_id', 'coupon', 'name', 'label_color'], 'required'],
            [['icon', 'logo', 'strip'], 'required', 'on' => 'create'],
            [['coupon'], 'string'],
            [
                ['organization_name', 'team_identifier', 'logo_text', 'description', 'beacon_relevant_text'],
                'string',
                'max' => 256
            ],
            [['name'], 'string', 'max' => 50],
            [['barcode_format', 'barcode_message_encoding'], 'string', 'max' => 100],
            [['foreground_color', 'background_color', 'label_color'], 'string', 'max' => 16, 'min' => 10],
            [['send_unlimited', 'active', 'show_serial', 'do_not_generate_messages'], 'in', 'range' => [0, 1]],
            [
                ['foreground_color', 'background_color', 'label_color'],
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
            [['without_barcode'], 'safe']
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'template_id' => 'ID Шаблона',
            'name' => 'Название шаблона',
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
            'label_color' => 'Label Color',
            'beacon_relevant_text' => 'Beacon Relevant Text',
            'without_barcode' => 'Без barcode',
            'show_serial' => 'Показывать серийный номер купона',
            'do_not_generate_messages' => 'Не генерировать случайные сообщения для купонов',
            'barcode_format' => 'Barcode Format',
            'barcode_message_encoding' => 'Barcode Message Encoding',
            'send_unlimited' => 'Неограниченное количество купонов',
            'send_scenario' => 'Когда отправлять купон',
            //images
            'icon' => 'Icon',
            'icon2x' => 'Icon@2x',
            'icon3x' => 'Icon@3x',
            'logo' => 'Logo',
            'logo2x' => 'Logo@2x',
            'logo3x' => 'Logo@3x',
            'strip' => 'Strip',
            'strip2x' => 'Strip2x',
            'strip3x' => 'Strip3x',
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
     * @param $attribute
     */
    public function validateIsJsonValid($attribute)
    {
        $json = '{' . $this->{$attribute} . '}';
        if (!json_decode($json)) {
            $this->addError($attribute, 'Неверная Json-структура');
        }
    }

    /**
     * @param $attribute
     */
    public function validateKeys($attribute)
    {
        foreach (static::COUPON_JSON_KEYS as $key) {
            if (strpos($this->{$attribute}, $key) === false) {
                $this->addError($attribute, 'Отсутствует обязательный ключ Json-струкутры "' . $key . '"');
            }
        }
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        if (mb_substr($this->coupon, -1) == ',') {
            $this->coupon = mb_substr($this->coupon, 0, -1);
        }
        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
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
     * @param $attribute_name
     * @return array|bool|null|\app\models\TemplateFile
     */
    public function getFile($attribute_name)
    {
        if (in_array($attribute_name, $this->attributes()) && $this->$attribute_name) {
            return $this->hasOne(TemplateFile::className(), ['file_id' => $attribute_name])->one();
        }
        return false;
    }

    public function getFilePath($attribute_name)
    {
        /** @var \app\models\TemplateFile $file */
        if ($file = $this->getFile($attribute_name)) {
            $path = $file->getPath();
            return $path;
        }
        return false;
    }

    /**
     * @param $attribute_name
     * @return bool|string
     */
    public function getFileUrlPath($attribute_name)
    {
        /** @var \app\models\TemplateFile $file */
        if ($file = $this->getFile($attribute_name)) {
            return $file->getUrlPath();
        }
        return false;
    }

    /**
     * @param $attribute_name
     * @param bool|false $options
     * @return bool|string
     */
    public function getHtmlImage($attribute_name, $options = false)
    {
        if ($path = $this->getFileUrlPath($attribute_name)) {
            return Html::img($path, $options);
        }
        return false;
    }

    /**
     * @param bool|false $attribute_name
     * @return array
     */
    public function getPoses($attribute_name = false)
    {
        $poses = $this->pos;
        if (!empty($poses)) {
            foreach ($poses as $pos) {
                if ($attribute_name) {
                    $list[$pos->primaryKey] = $pos->{$attribute_name};
                } else {
                    $list[] = $pos->primaryKey;
                }
                return $list;
            }
        }
        return $list = [];
    }
}
