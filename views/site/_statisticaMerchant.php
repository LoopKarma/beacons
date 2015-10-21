<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model \app\models\Coupon */
$merchant = Yii::$app->user->identity->merchant_id;

$maxPosId = $model->getAttributeValueAmongAll('pos_id', 'max', $merchant);
$maxTemplateId = $model->getAttributeValueAmongAll('template_id', 'max', $merchant);
$minPosId = $model->getAttributeValueAmongAll('pos_id', 'min', $merchant);
$minTemplateId = $model->getAttributeValueAmongAll('template_id', 'min', $merchant);
?>

<div class="col-lg-6">
    <h2>Больше всего купонов:</h2>
    <ul class="list-group">
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
        <?php if ($minPosId && $minPos = \app\models\Pos::findOne($minPosId)):?>
            <li class="list-group-item">
                У точки продаж:
                <?=Html::a($minPos->address, ['/pos/view', 'id' => $minPosId])?>
            </li>
        <?php endif?>

        <?php if ($minTemplateId && $minTemplate = \app\models\CouponTemplate::findOne($minTemplateId)):?>
            <li class="list-group-item">
                У шаблона:
                <?=Html::a(
                    $minTemplate->name  . ' [' . $minTemplateId . ']',
                    ['/template/view', 'id' => $minTemplateId]
                )?>
            </li>
        <?php endif?>
    </ul>
</div>
