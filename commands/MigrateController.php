<?php

namespace app\commands;

use yii\gii\generators\model\Generator;

class MigrateController extends \yii\console\controllers\MigrateController
{
    public $migrationTable = 'migration';

    public $ignoredTablesInAutogeneratingActiveRecords = ['migration'];

    public function afterAction($action, $result)
    {
        if (in_array($action->id, ['down', 'fresh', 'redo', 'to', 'up'])) {
            echo PHP_EOL . 'Generating active-models for following tables:' . PHP_EOL;
            $tables = array_diff(
                $this->db->createCommand('show tables')->queryColumn(),
                $this->ignoredTablesInAutogeneratingActiveRecords
            );
            $counter = 0;
            foreach ($tables as $tableName) {
                $generator = new Generator([
                    'ns' => 'app\models\generated',
                    'tableName' => $tableName,
                    'baseClass' => 'yii\db\ActiveRecord',
                ]);
                $files = $generator->generate();
                foreach ($files as $file) {
                    /* @var $file \yii\gii\CodeFile */
                    $file->save();
                }
                echo '    > ', $tableName . PHP_EOL;
                $counter++;
            }
            echo $counter . ' active-models were generated.' . PHP_EOL;
        }
        parent::afterAction($action, $result);
    }
}
