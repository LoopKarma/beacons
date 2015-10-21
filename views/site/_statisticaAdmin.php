<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model \app\models\Coupon */
$maxMerchantId = $model->getAttributeValueAmongAll('merchant_id', 'max');
$maxPosId = $model->getAttributeValueAmongAll('pos_id', 'max');
$maxTemplateId = $model->getAttributeValueAmongAll('template_id', 'max');

$minMerchantId = $model->getAttributeValueAmongAll('merchant_id', 'min');
$minPosId = $model->getAttributeValueAmongAll('pos_id', 'min');
$minTemplateId = $model->getAttributeValueAmongAll('template_id', 'min');
?>

<div class="col-lg-6">
    <h2>Больше всего купонов:</h2>
    <ul class="list-group">
        <?php ?>
        <?php if ($maxMerchantId && $maxMerchant = \app\models\Merchant::findOne($maxMerchantId)):?>
            <li class="list-group-item">
                У мерчанта: <?=Html::a($maxMerchant->name, ['/merchant/view', 'id' => $maxMerchantId])?>
            </li>
        <?php endif?>
        <?php ?>
        <?php if ($maxPosId && $maxPos = \app\models\Pos::findOne($maxPosId)):?>
            <li class="list-group-item">
                У точки продаж:
                <?=Html::a($maxPos->address, ['/pos/view', 'id' => $maxPosId])?>
            </li>
        <?php endif?>
        <?php if ($maxTemplateId && $maxTemplate = \app\models\CouponTemplate::findOne($maxTemplateId)):?>
            <li class="list-group-item">
                У шаблона:
                <?=Html::a(
                    $maxTemplate->name . ' [' . $maxTemplateId . ']',
                    ['/template/view', 'id' => $maxTemplateId]
                )?>
            </li>
        <?php endif?>
    </ul>
</div>
<div class="col-lg-6">
    <h2>Меньше всего купонов:</h2>
    <ul class="list-group">
        <?php if ($minMerchantId && $minMerchant = \app\models\Merchant::findOne($minMerchantId)):?>
            <li class="list-group-item">
                У мерчанта: <?=Html::a($minMerchant->name, ['/merchant/view', 'id' => $minMerchantId])?>
            </li>
        <?php endif?>

        <?php if ($minPosId && $minPos = \app\models\Pos::findOne($minPosId)):?>
            <li class="list-group-item">
                У точки продаж: <?=Html::a($minPos->address, ['/pos/view', 'id' => $minPosId])?>
            </li>
        <?php endif?>
        <?php if ($minTemplateId && $minTemplate = \app\models\CouponTemplate::findOne($minTemplateId)):?>
            <li class="list-group-item">
                У шаблона:
                <?=Html::a(
                    $minTemplate->name . ' [' . $minTemplateId . ']',
                    ['/template/view', 'id' => $minTemplateId]
                )?>
            </li>
        <?php endif?>
    </ul>
</div>

