<?php

use app\modules\user\models\Authentication\BanType;
use app\modules\user\models\search\BanActionColumn;
use app\modules\user\models\search\UserSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var View $this */
/** @var ActiveDataProvider $list */
/** @var int $countPerPage */
/** @var bool $isAdmin */
/** @var UserSearch $searchModel */

?>

<div class="filter-form">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['list'],
    ]); ?>

    <div class="row d-flex justify-content-around">
        <div class="col-md-2">
            <?= $form->field($searchModel, 'bannedNickname')->textInput([
                'placeholder' => Yii::t('app', 'Banned User Nickname'),
            ]) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($searchModel, 'issuedByNickname')->textInput([
                'placeholder' => Yii::t('app', 'Banned by Nickname'),
            ]) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($searchModel, 'searchedType')->dropDownList(
                BanType::getBanTypes(),
                ['prompt' => 'Select Type']
            ) ?>
        </div>

        <div class="col-md-2">
            <label for="count"><?= Yii::t('app', 'Items Per Page') ?></label>
            <?= Html::dropDownList('count', $countPerPage, [
                10 => '10',
                20 => '20',
                50 => '50',
                100 => '100'
            ], [
                'class' => 'form-control',
                'prompt' => Yii::t('app', 'Select Count'),
            ]) ?>
        </div>
    </div>
    <div class="row d-flex justify-content-around">
        <div class="col-md-2">
            <?= $form->field($searchModel, 'startedAfter')->input('date', [
                'placeholder' => 'Started after date',
            ])->label('Started after date') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($searchModel, 'endedBefore')->input('date', [
                'placeholder' => 'Ended before date',
            ])->label('Ended before date') ?>
        </div>

    </div>
    <div class="row d-flex align-items-end flex-row-reverse">
        <div class="col-md-2">
            <div class="form-group">
                <?= Html::submitButton('Filter', ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
            </div>
        </div>


    </div>

    <?php ActiveForm::end(); ?>

</div>

<hr>

<?= GridView::widget([
    'dataProvider' => $list,
    'columns' => [
        //        ['class' => 'yii\grid\SerialColumn'],
        'id',
        'banned_visible_name',
        'type',
        'since',
        'until',
        'issued_by_visible_name',
        ['class' => BanActionColumn::class]
    ],
    'pager' => [
        'options' => ['class' => 'pagination pagination-sm'],
        'maxButtonCount' => 5,
        'nextPageLabel' => '>',
        'prevPageLabel' => '<',
        'firstPageLabel' => '<<',
        'lastPageLabel' => '>>',
        'activePageCssClass' => 'active',
        'disabledPageCssClass' => 'disabled',
    ],
]); ?>
