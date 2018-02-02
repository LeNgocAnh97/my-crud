<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view">
 
    <?= "<?= " ?>DetailView::widget([
        'model' => $model,
        'attributes' => [
<?php
            $object = ltrim($generator->modelClass, '\\');

            if (($tableSchema = $generator->getTableSchema()) === false) {
                foreach ($generator->getColumnNames() as $name) {
                    echo "            '" . $name . "',\n";
                }
            } else {
                foreach ($generator->getTableSchema()->columns as $column) {
                    if ($column->name == "created_at" || $column->name == "updated_at") {
                        continue;
                    }
                    if (strpos($column->name, 'img_') !== false) {
                        echo "[\n";
                        echo "    'attribute' => 'img_thumb',\n";
                        echo "    'format' => 'raw',\n";
                        echo "    'value' => function(\$model) {\n";
                        echo "       /* @var \$model $object */\n";
                        echo "       return Html::img(\$model->getImage('$column->name'), ['width' => 100]);\n";
                        echo "    }\n";
                        echo "],\n";
                    } elseif (strpos($column->name, 'is_') !== false) {
                        $prefix = ucfirst(str_replace('is_', '', $column->name));
                        $funcName = "get{$prefix}Name";
                        echo "[\n";
                        echo "    'attribute' => '$column->name',\n";
                        echo "    'format' => 'text',\n";
                        echo "    'value' => function(\$model) {\n";
                        echo "       /* @var \$model $object */\n";
                        echo "       return \$model->$funcName();\n";
                        echo "    }\n";
                        echo "],\n";
                    } elseif ($column->name == 'type' || $column->name == 'status' || strpos($column->name, 'lang') !== false) {
                        $prefix = ucfirst($column->name);
                        $funcName = "get{$prefix}Name";
                        echo "[\n";
                        echo "    'attribute' => '$column->name',\n";
                        echo "    'format' => 'text',\n";
                        echo "    'value' => function(\$model) {\n";
                        echo "       /* @var \$model $object */\n";
                        echo "       return \$model->$funcName();\n";
                        echo "    }\n";
                        echo "],\n";
                    } else {
                        $format = $generator->generateColumnFormat($column);
                        echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                    }
                }
            }
            ?>
        ],
    ]) ?>

</div>
