<?php
namespace app\components;

use Yii;
use yii\base\component;
use PKPass\PKPass;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use app\api\modules\v1\models\CouponGenerator;

class Pass extends Component
{
    public $passFilePath;
    public $wwdrCertPath;
    public $teamIdentifier;
    public $certificatePassword;

    public function generatePass(CouponGenerator $model)
    {
        $pkPass = new PKPass();
        $pkPass->setTempPath($this->getPassFilePath());
        /** @var \app\models\CertFile $p12CertFile */
        $p12CertFile = $model->merchant->certFile;
        $certPath = $p12CertFile->getPath() ?: false;
        if (!$certPath) {
            Yii::error('Merchant has no cert file. ID = '.$model->merchant->primaryKey);
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
            Yii::error('template->coupon is broken. Template ID = '.$model->template->primaryKey);
            return false;
        }
        $associatedAppKeys    = [];
        $relevanceKeys        = [];
        if ($model->template->without_barcode) {
            $visualAppearanceKeys = [
                'barcode'         => [
                    'altText' => ''
                ],
                'foregroundColor' => $model->template->foreground_color,
                'backgroundColor' => $model->template->background_color,
                'logoText'        => $model->template->logo_text,
            ];
        } else {
            $visualAppearanceKeys = [
                'barcode'         => [
                    'format'          => $model->template->barcode_format,
                    'message'         => mb_strtoupper($model->message),
                    'messageEncoding' => $model->template->barcode_message_encoding
                ],
                'foregroundColor' => $model->template->foreground_color,
                'backgroundColor' => $model->template->background_color,
                'logoText'        => $model->template->logo_text,
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
        $pkPass->setJSON(Json::encode($passData));
        $this->addPossibleImages($pkPass, $model->template);
        $filePath = $this->getPassFilePath().mktime().'.pkpass';
        if (!$res = $pkPass->create(false)) {
            Yii::error('Error: '.$pkPass->getError());
            return false;
        } else {
            if (file_put_contents($filePath, $res)) {
                return $filePath;
            }
        }
        return false;
    }

    protected function addPossibleImages(PKPass &$pkPass, ActiveRecord $template)
    {
        $pkPass->addFile($template->iconFile->getPath(), 'icon.png');
        if ($template->logoFile) {
            $pkPass->addFile($template->logoFile->getPath(), 'logo.png');
        }
        if ($template->stripImageFile) {
            $pkPass->addFile($template->stripImageFile->getPath(), 'strip.png');
        }
        //TODO добавить все остальные файлы
    }

    protected function getPassFilePath()
    {
        return Yii::getAlias($this->passFilePath);
    }

    protected function getWwdrCertPath()
    {
        return Yii::getAlias($this->wwdrCertPath);
    }
}