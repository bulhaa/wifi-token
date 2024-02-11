<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Moves';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="moves-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Moves', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'from_x',
            'from_y',
            'to_x',
            'to_y',
            //'from_piece',
            //'from_color',
            //'to_piece',
            //'to_color',
            //'time',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
