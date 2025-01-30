<?php

use app\modules\user\models\Authentication\Role;
use app\modules\user\models\search\AdminActionColumn;
use app\modules\user\models\search\UserActionColumn;
use app\modules\user\models\search\UserSearch;
use app\modules\user\widgets\ban\BanWidget;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var View $this */
/** @var ActiveDataProvider $list */
/** @var int $countPerPage */
/** @var bool $isAdmin */
/** @var UserSearch $searchModel */

$adminColumns = [
    'id',
    'visible_name',
    'role' => [
        'value' => function ($model) {
            return Role::getRoles()[$model->role];
        },
        'attribute' => 'role',
        ],
    'email',
    'modified_at:datetime',
    'modified_by',
    'created_at',
    ['class' => AdminActionColumn::class]
];
$otherColumns = [
    'visible_name',
    'created_at',
    ['class' => UserActionColumn::class]
];
?>

<div class="filter-form">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['profiles'],
    ]); ?>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($searchModel, 'visible_name')->textInput([
                'placeholder' => Yii::t('app', 'Nickname'),
            ]) ?>
        </div>

        <?php if ($isAdmin) : ?>
            <div class="col-md-2">
                <?= $form->field($searchModel, 'id')->textInput([
                    'placeholder' => Yii::t('app', 'Id'),
                ]) ?>
            </div>

        <div class="col-md-2">
            <?= $form->field($searchModel, 'role')->dropDownList(
                ArrayHelper::map(Role::getRoles(), function ($key) { return $key; }, function ($value) { return $value; }),
                ['prompt' => 'Select Role']
            ) ?>
        </div>
        <?php endif; ?>

        <div class="col-md-2">
            <?= $form->field($searchModel, 'created_at_start')->input('date', [
                'placeholder' => 'Start date',
            ])->label('Created At (Start)') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($searchModel, 'created_at_end')->input('date', [
                'placeholder' => 'End date',
            ])->label('Created At (End)') ?>
        </div>
    </div>
    <div class="row d-flex justify-content-around align-items-center flex-row">
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

        <div class="col-md-2">
            <div class="form-group">
                <?= Html::submitButton('Filter', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>


    </div>

    <?php ActiveForm::end(); ?>

</div>

<hr>

<?= GridView::widget([
    'dataProvider' => $list,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        ...($isAdmin ? $adminColumns : $otherColumns),
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

<?= BanWidget::widget(); ?>
