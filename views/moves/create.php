<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Moves */

$this->title = 'Create Moves';
$this->params['breadcrumbs'][] = ['label' => 'Moves', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="moves-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
