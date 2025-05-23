<?php

namespace app\models;

use Yii;

class AssetBundle extends \yii\web\AssetBundle
{
    public $dynamicJs;
    public function registerAssetFiles($view): void
    {
        foreach ($this->dynamicJs as $dynamicJsFile => $args) {
            Yii::$app->view->registerJs(
                Yii::$app->view->renderPhpFile(
                    $this->basePath
                    . ( str_ends_with($this->basePath, '/') ? '' : '/' )
                    . $dynamicJsFile, $args
                )
            );
        }
        parent::registerAssetFiles($view);
    }
}
