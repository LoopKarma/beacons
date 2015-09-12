<?php
namespace app\components;

use app\models\CouponTemplate;
use Yii;
use yii\base\component;
use yii\base\Exception;
use PKPass\PKPass;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\helpers\VarDumper;

class Pass extends Component
{
    public $passFilePath;
    public $wwdrCertPath;
    public $teamIdentifier;


    public function generatePass(ActiveRecord $template, ActiveRecord $merchant, ActiveRecord $pos, $serialNumber)
    {
        $pkPass = new PKPass();
        $pkPass->setTempPath($this->getPassFilePath());
        /** @var \app\models\CertFile $p12CertFile */
        $p12CertFile = $merchant->certFile;
        $certPath = $p12CertFile->getPath() ?: false;
        if (!$certPath) {
            Yii::error('Merchant has no cert file. ID = '.$merchant->primaryKey);
            return false;
        }
        $pkPass->setCertificate($certPath);
        $pkPass->setCertificatePassword($this->getCertificatePassword());
        $pkPass->setWWDRcertPath($this->getWwdrCertPath());

        $standardKeys = [
            'description'        => $template->description,
            'formatVersion'      => 1,
            'organizationName'   => $template->merchant->name,
            'passTypeIdentifier' => $template->merchant->uuid,
            'serialNumber'       => $serialNumber,
            'teamIdentifier'     => $this->teamIdentifier,
        ];
        $styleKeys = json_decode('{'. $template->coupon . '}', true);
        if (!$styleKeys) {
            Yii::error('template->coupon is broken. Template ID = '.$template->primaryKey);
            return false;
        }
        $associatedAppKeys    = [];
        $relevanceKeys        = [];
        if ($template->without_barcode) {
            $visualAppearanceKeys = [
                'barcode'         => [
                    'altText' => ''
                ],
                'foregroundColor' => $template->foreground_color,
                'backgroundColor' => $template->background_color,
                'logoText'        => $template->logo_text,
            ];
        } else {
            $visualAppearanceKeys = [
                'barcode'         => [
                    'format'          => $template->barcode_format,
                    /** TODO сделать правильное сообщение */
                    'message'         => 'Std message',
                    'messageEncoding' => $template->barcode_message_encoding
                ],
                'foregroundColor' => $template->foreground_color,
                'backgroundColor' => $template->background_color,
                'logoText'        => $template->logo_text,
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
        $pkPass->addFile($template->iconFile->getPath(), 'icon.png');
        if ($template->logoFile) {
            $pkPass->addFile($template->logoFile->getPath(), 'logo.png');
        }
        if ($template->stripImageFile) {
            $pkPass->addFile($template->stripImageFile->getPath(), 'strip.png');
        }
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

    private function getP12Certificate()
    {
        return Yii::getAlias('@webroot'.'/files/cert/GetCouponPassCertificate.p12');
    }


    private function getCertificatePassword()
    {
        return 'getcoupon123';
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