<?php
namespace app\models\forms;

use yii\base\Model;
use app\models\Merchant;
use yii\web\UploadedFile;
use app\helpers\CsvParser;
use yii\helpers\ArrayHelper;
use app\models\BarcodeMessage;
use yii\base\InvalidConfigException;

class MessageUpload extends Model
{
    public $file;
    public $merchant_id;
    public $saveCount = 0;

    protected $messageList;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id'], 'required'],
            [['merchant_id'], 'integer'],
            [['merchant_id'], 'exist', 'targetAttribute' => 'merchant_id', 'targetClass' => Merchant::className()],
            [['file'], 'file', 'extensions' => 'csv'],
            ['file', 'validateFile', 'skipOnEmpty' => false]
        ];
    }

    public function attributeLabels()
    {
        return [
            'file' => 'Файл с сообщениями',
            'merchant_id' => 'Мерчант'
        ];
    }

    public function validateFile($attribute)
    {
        $file = UploadedFile::getInstance($this, $attribute);
        if ($file) {
            $messages = [];
            $fileMessages = (new CsvParser())->getData($file->tempName, ['message']);
            foreach ($fileMessages as $message) {
                $messages[] = $message['message'];
            }
            $messages = array_filter($messages);
            $duplicates = BarcodeMessage::find()->select('message')
                ->where(['merchant_id' => $this->merchant_id, 'message' => $messages])->asArray()->all();

            if (!empty($duplicates)) {
                $this->addError(
                    $attribute,
                    "В системе уже есть сообщения, которые вы пытаетесь загрузить: " .
                    implode(", ", ArrayHelper::getColumn($duplicates, 'message'))
                );
            } else {
                $this->messageList = $messages;
            }
        } else {
            $this->addError($attribute, 'Файл с сообщениями не загружен');
        }
    }

    public function uploadMessages()
    {
        if (empty($this->messageList)) {
            throw new InvalidConfigException('MessageList is not array');
        }
        foreach ($this->messageList as $message) {
            $model = new BarcodeMessage();
            $model->setAttributes([
                'merchant_id' => $this->merchant_id,
                'message' => $message,
            ]);
            if ($model->save()) {
                $this->saveCount++;
            }
        }
        return true;
    }
}
