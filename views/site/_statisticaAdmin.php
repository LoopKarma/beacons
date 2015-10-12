<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model \app\models\Coupon */
?>

<div class="col-lg-4">
    <h2>Больше всего купонов:</h2>
    <ul class="list-group">
        <?php $maxMerchantId = $model->getAttributeValueAmongAll('merchant_id', 'max');?>
        <?php if ($maxMerchantId):?>
            <li class="list-group-item">
                У мерчанта:
                <?=Html::a(
                    \app\models\Merchant::findOne($maxMerchantId)->name,
                    ['/merchant/view', 'id' => $maxMerchantId]
                )?>
            </li>
        <?php endif?>
        <?php $maxPosId = $model->getAttributeValueAmongAll('pos_id', 'max');?>
        <?php if ($maxPosId):?>
            <li class="list-group-item">
                У точки продаж:
                <?=Html::a(\app\models\Pos::findOne($maxPosId)->address, ['/pos/view', 'id' => $maxPosId])?>
            </li>
        <?php endif?>
        <?php $maxTemplateId = $model->getAttributeValueAmongAll('template_id', 'max');?>
        <?php if ($maxTemplateId):?>
            <li class="list-group-item">
                У шаблона:
                <?=Html::a(
                    \app\models\CouponTemplate::findOne($maxTemplateId)->name . ' [' . $maxTemplateId . ']',
                    ['/template/view', 'id' => $maxTemplateId]
                )?>
            </li>
        <?php endif?>
    </ul>
</div>
<div class="col-lg-4">
    <h2>Меньше всего купонов:</h2>
    <ul class="list-group">
        <?php $minMerchantId = $model->getAttributeValueAmongAll('merchant_id', 'min');?>
        <?php if ($minMerchantId):?>
            <li class="list-group-item">
                У мерчанта:
                <?=Html::a(
                    \app\models\Merchant::findOne($minMerchantId)->name,
                    ['/merchant/view', 'id' => $minMerchantId]
                )?>
            </li>
        <?php endif?>
        <?php $minPosId = $model->getAttributeValueAmongAll('pos_id', 'min');?>
        <?php if ($minPosId):?>
            <li class="list-group-item">
                У точки продаж:
                <?=Html::a(\app\models\Pos::findOne($minPosId)->address, ['/pos/view', 'id' => $minPosId])?>
            </li>
        <?php endif?>
        <?php $minTemplateId = $model->getAttributeValueAmongAll('template_id', 'min');?>
        <?php if ($minTemplateId):?>
            <li class="list-group-item">
                У шаблона:
                <?=Html::a(
                    \app\models\CouponTemplate::findOne($minTemplateId)->name . ' [' . $minTemplateId . ']',
                    ['/template/view', 'id' => $minTemplateId]
                )?>
            </li>
        <?php endif?>
    </ul>
</div>
<div class="col-lg-4">
    <h2>Heading</h2>

    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
        dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
        ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
        fugiat nulla pariatur.</p>

    <p></p>
</div>