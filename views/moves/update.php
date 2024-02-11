<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Moves */

$this->title = 'Update Moves: ';
$this->params['breadcrumbs'][] = ['label' => 'Moves', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['view', 'id' => '']];
$this->params['breadcrumbs'][] = 'Update';
?>
    <form action="<?= yii\helpers\Url::base() ?>/?r=moves/update" method="post">

        <?php if ( !isset($_POST['details']) ) {?>
            <textarea id="details" name="details" rows="4" cols="50">
            </textarea>

            <div id="submit_div" class="container-login100-form-btn" style="padding-top: 0px;">
                <button id="submit" class="btn btn-primary btn-block" style="position: absolute; left: 3.02612px; top: 142.232px; width: 133.149px; height: 32.5046px;">
                    Submit
                </button>
            </div>
        <?php }else{ ?>
            <h2 >
                <textarea id="json" name="json" rows="4" cols="50"><?php
                    echo $json;
                 ?></textarea>
            </h2>
        <?php } ?>

    </form>

