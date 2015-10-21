<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'active',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    $value = $model->{$column->attribute};
                    switch ($value) {
                        case 1:
                            $class = 'success';
                            $label = 'Активен';
                            break;
                        default:
                            $class = 'default';
                            $label = 'Неактивен';
                    };
                    $html = Html::tag('span', Html::encode($label), ['class' => 'label label-' . $class]);
                    return $value === null ? $column->grid->emptyCell : $html;
                }
            ],
            'user_id',
            [
                'attribute' => 'login',
                'format' => 'raw',
                'value'=>function ($data) {
                    return Html::a($data->login, \yii\helpers\Url::to(['user/view', 'id' => $data->user_id]));
                },
            ],
            'email:email',
            [
                'attribute' => 'role',
                'value' => function ($model, $key, $index, $column) {
                    /** @var $model \app\models\User */
                    if ($model->role == $model::ROLE_ADMIN) {
                        return 'Администратор';
                    } else {
                        return 'Мерчант';
                    }
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'role',
                    [
                        \app\models\User::ROLE_MERCHANT => 'Мерчант',
                        \app\models\User::ROLE_ADMIN => 'Администратор',
                    ],
                    ['class'=>'form-control','prompt' => '(нет значения)']
                ),
            ],
            [
                'attribute' => 'merchant_id',
                'value' => function ($model, $key, $index, $column) {
                    /** @var $model \app\models\User */
                    if ($model->merchant) {
                        return $model->merchant->name;
                    } else {
                        return '';
                    }
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'merchant_id',
                    \app\models\Merchant::getMerchantList(),
                    [
                        'class'=>'form-control',
                        'prompt' => 'Выберите мерчанта'
                    ]
                ),
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
