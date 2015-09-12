<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\PosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Точки продаж';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать Точку продаж', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'pos_id',
            [
                'attribute' => 'merchant_id',
                'value' => 'merchant.name'
            ]
            ,
            //'create_date',
            //'update_date',
            'address',
            'beacon_identifier',
            // 'major',
            // 'minor',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
