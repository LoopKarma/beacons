<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->login .'['. $model->user_id . ']';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->user_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->user_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Хотите удалить пользователя? Процедуру нельзя отменить.',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'active',
                'value' => $model->active ? 'Да' : 'Нет',
            ],
            'user_id',
            'create_date',
            'update_date',
            'login',
            'email:email',
            //'password',
            [
                'attribute' => 'role',
                'value' => $model->role == $model::ROLE_MERCHANT ? 'Мерчант' : 'Администратор',
            ],
            //'auth_key',
            [
                'attribute' => 'merchant_id',
                'value' => $model->merchant ? "{$model->merchant->name} [{$model->merchant->merchant_id}]" : '-',
            ],
        ],
    ]) ?>

</div>
