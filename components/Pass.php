<?php
namespace app\components;

use Yii;
use PKPass\PKPass;
use yii\helpers\Json;
use yii\base\component;
use app\models\CouponTemplate;
use app\api\modules\v1\models\CouponGenerator;

class Pass extends Component
{
    const LOG_CATEGORY = 'pass_generator';
    public $error;
    public $passFilePath;
    public $wwdrCertPath;
    public $teamIdentifier;
    public $certificatePassword;

    /**
     * @param CouponGenerator $model
     * @return bool|string
     */
    public function generatePass(CouponGenerator $model)
    {
        $pkPass = new PKPass();
        $pkPass->setTempPath($this->getPassFilePath());
        /** @var \app\models\CertFile $p12CertFile */
        $p12CertFile = $model->merchant->certFile;
        $certPath = $p12CertFile->getPath() ?: false;
        if (!$certPath) {
            Yii::error('Merchant has no cert file. ID = '.$model->merchant->primaryKey, static::LOG_CATEGORY);
            return false;
        }
        $pkPass->setCertificate($certPath);
        $pkPass->setCertificatePassword($this->certificatePassword);
        $pkPass->setWWDRcertPath($this->getWwdrCertPath());

        $standardKeys = [
            'description'        => $model->template->description,
            'formatVersion'      => 1,
            'organizationName'   => $model->template->merchant->name,
            'passTypeIdentifier' => $model->template->merchant->pass_type_id,
            'serialNumber'       => $model->serialNumber,
            'teamIdentifier'     => $this->teamIdentifier,
        ];
        $styleKeys = json_decode('{'. $model->template->coupon . '}', true);
        if (!$styleKeys) {
            $this->error = 'Template coupon field is broken';
            Yii::error($this->error . 'Template ID = '.$model->template->primaryKey, static::LOG_CATEGORY);
            return false;
        }
        $associatedAppKeys    = [];
        $relevanceKeys        = [];
        if ($model->template->without_barcode) {
            $visualAppearanceKeys = [
                'foregroundColor' => isset($model->template->foreground_color)?$model->template->foreground_color : '',
                'backgroundColor' => isset($model->template->background_color)?$model->template->background_color : '',
                'labelColor'      => isset($model->template->label_color) ? $model->template->label_color : '',
                'logoText'        => isset($model->template->logo_text) ? $model->template->logo_text : '',
            ];
        } else {
            if (isset($model->template->show_serial) && $model->template->show_serial) {
                $altText = $model->serialNumber;
            } else {
                $altText = '';
            }
            $visualAppearanceKeys = [
                'barcode'         => [
                    'altText'         => $altText,
                    'format'          => $model->template->barcode_format,
                    'message'         => mb_strtoupper($model->messageText),
                    'messageEncoding' => $model->template->barcode_message_encoding
                ],
                'barcodes'         => [
                    [
                        'altText'         => $altText,
                        'format'          => $model->template->barcode_format,
                        'message'         => mb_strtoupper($model->messageText),
                        'messageEncoding' => $model->template->barcode_message_encoding
                    ]
                ],
                'foregroundColor' => isset($model->template->foreground_color)?$model->template->foreground_color : '',
                'backgroundColor' => isset($model->template->background_color)?$model->template->background_color : '',
                'labelColor'      => isset($model->template->label_color) ? $model->template->label_color : '',
                'logoText'        => isset($model->template->logo_text) ? $model->template->logo_text : '',
            ];
        }
        $webServiceKeys = [];
        $passData = array_merge(
            $standardKeys,
            $associatedAppKeys,
            $relevanceKeys,
            $styleKeys,
            $visualAppearanceKeys,
            $webServiceKeys
        );
        $pkPass->setJson(Json::encode($passData));
        $this->addPossibleImages($pkPass, $model->template);
        $filePath = $this->getPassFilePath().time().'.pkpass';
        if (!$res = $pkPass->create(false)) {
            $this->error = $pkPass->getError();
            Yii::error($this->error, static::LOG_CATEGORY);
            return false;
        } else {
            if (file_put_contents($filePath, $res)) {
                return $filePath;
            }
        }
        return false;
    }

    /**
     * @param PKPass $pkPass
     * @param \app\models\CouponTemplate $template
     */
    protected function addPossibleImages(PKPass &$pkPass, CouponTemplate $template)
    {
        $pkPass->addFile($template->getFilePath('icon'), 'icon.png');
        $pkPass->addFile($template->getFilePath('icon2x'), 'icon@2x.png');
        $pkPass->addFile($template->getFilePath('icon3x'), 'icon@3x.png');

        $pkPass->addFile($template->getFilePath('logo'), 'logo.png');
        $pkPass->addFile($template->getFilePath('logo2x'), 'logo@2x.png');
        $pkPass->addFile($template->getFilePath('logo3x'), 'logo@3x.png');

        $pkPass->addFile($template->getFilePath('strip'), 'strip.png');
        $pkPass->addFile($template->getFilePath('strip2x'), 'strip@2x.png');
        $pkPass->addFile($template->getFilePath('strip3x'), 'strip@3x.png');
    }

    /**
     * @return bool|string
     */
    protected function getPassFilePath()
    {
        return Yii::getAlias($this->passFilePath);
    }

    /**
     * @return bool|string
     */
    protected function getWwdrCertPath()
    {
        return Yii::getAlias($this->wwdrCertPath);
    }
}