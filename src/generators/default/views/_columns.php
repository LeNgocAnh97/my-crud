<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$actionParams = $generator->generateActionParams();
$colorPluginOptions =  [
    'showPalette' => true,
    'showPaletteOnly' => true,
    'showSelectionPalette' => true,
    'showAlpha' => false,
    'allowEmpty' => false,
    'preferredFormat' => 'name',
    'palette' => [
        [
            "white", "black", "grey", "silver", "gold", "brown",
        ],
        [
            "red", "orange", "yellow", "indigo", "maroon", "pink"
        ],
        [
            "blue", "green", "violet", "cyan", "magenta", "purple",
        ],
    ]
];
echo "<?php\n";

?>
use yii\helpers\Url;
use <?= ltrim($generator->modelClass, '\\') ?>;
use kartik\editable\Editable;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '50px',
        'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model, $key, $index, $column) {
            return Yii::$app->controller->renderPartial('_expand-row-details', ['model' => $model]);
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'expandOneOnly' => true
    ],
<?php
$count = 0;
foreach ($generator->getColumnNames() as $name) {
    if ($name == 'created_at'||$name == 'updated_at'){
        echo "    // [\n";
        echo "        // 'class'=>'\kartik\grid\EditableColumn',\n";
        echo "        // 'contentOptions' => ['class' => 'col-md-1 nowrap'],\n";
        echo "        // 'attribute'=>'" . $name . "',\n";
        echo "    // ],\n";
    } else if (++$count < 20) {
        if ($name == 'parent_id' || $name == 'type' || $name == 'status' || $name == 'lang') {

            $prefix = null;
            if ($name == 'parent_id')   $prefix = 'Parent';
            elseif ($name == 'type')    $prefix = 'Type';
            elseif ($name == 'lang')    $prefix = 'Lang';
            else                        $prefix = 'Status';

            echo "    [\n";
            echo "        'class'=>'\kartik\grid\EditableColumn',\n";
            echo "        'contentOptions' => ['class' => 'col-md-1 nowrap'],\n";
            echo "        'attribute'=>'" . $name . "',\n";
            if ($name == "parent_id") {
                echo "     return \$model->parent[\"name\"];\n";
            } else {
                echo "     return \$model->get{$prefix}Name();\n";
            }
            echo "         'hAlign'=>'center',\n";
            echo "         'vAlign'=>'middle',\n";
            echo "         'editableOptions' => function (\$model) {\n";
            echo "              return array(\n";
            echo "                  'header' => '". $prefix ."',\n";
            echo "                  'inputType' => Editable::INPUT_SELECT2,\n";
            echo "                  'options' => [\n";
            echo "                  'attribute' => '". "$name',\n";
            echo "                  'data' => ArrayHelper::map(\\$generator->modelClass::find()->all(), 'id', 'name'),\n";
            echo "                          'options' => [ \n";
            echo "                              'placeholder' => 'Please select...',\n";
            echo "                              'multiple' => false,\n";
            echo "                          ],\n";
            echo "                          'pluginOptions' => [\n";
            echo "                              'allowClear' => true,\n";
            echo "                              'closeOnSelect' => true\n";
            echo "                          ],\n";
            echo "                  ],\n";
            echo "                  'asPopover' => true,\n";
            echo "              );\n";
            echo "          },\n";
            echo "          'filterType'=>GridView::FILTER_SELECT2,\n";
            echo "          'filter'=> ArrayHelper::map(\\$generator->modelClass::find()->orderBy('name')->asArray()->all(), 'id', 'name'),\n";
            echo "          'filterWidgetOptions'=>[\n";
            echo "              'pluginOptions'=>['allowClear'=>true],\n";
            echo "          ],\n";
            echo "          'filterInputOptions'=>['placeholder'=>'Any ". $prefix ."']\n";
            echo "    ],\n";

        } elseif (strpos($name, 'is_') !== false) {

            $prefix = null;
            if ($name == 'is_active')   $prefix = 'active';
            elseif ($name == 'is_hot')  $prefix = 'hot';
            elseif ($name == 'is_new')  $prefix = 'new';
            else                        $prefix = 'top';

            echo "    [\n";
            echo "        'class'=>'\kartik\grid\EditableColumn',\n";
            echo "        'contentOptions' => ['class' => 'col-md-1 nowrap'],\n";
            echo "        'attribute'=>'" . $name . "',\n";
            echo "        'value' => function(\$model) {\n";
            echo "              return \$model->get".ucfirst($prefix)."Name();\n";
            echo "         },\n";
            echo "         'hAlign'=>'center',\n";
            echo "         'vAlign'=>'middle',\n";
            echo "         'editableOptions' => function (\$model) {\n";
            echo "              return array(\n";
            echo "                  'header' => '".ucfirst($prefix)."',\n";
            echo "                  'inputType' => Editable::INPUT_SELECT2,\n";
            echo "                  'options' => [\n";
            echo "                      'attribute' => '". $name ."',\n";
            echo "                      'data' => ArrayHelper::map(\$model->{$prefix}s, 'id', 'name'),\n";
            echo "                      'options' => [\n";
            echo "                          'placeholder' => 'Please select...',\n";
            echo "                          'multiple' => false,\n";
            echo "                      ],\n";
            echo "                      'pluginOptions' => [\n";
            echo "                          'allowClear' => true,\n";
            echo "                          'closeOnSelect' => true\n";
            echo "                      ],\n";
            echo "                  ],\n";
            echo "                  'asPopover' => true,\n";
            echo "              );\n";
            echo "          },\n";
            echo "          'filterType'=> GridView::FILTER_SELECT2,\n";
            echo "          'filter'=> ArrayHelper::map((new \\$generator->modelClass())->get".ucfirst($prefix)."s(), 'id', 'name'),\n";
            echo "          'filterWidgetOptions'=>[\n";
            echo "                      'pluginOptions'=>['allowClear'=>true],\n";
            echo "                  ],\n";
            echo "          'filterInputOptions'=>['placeholder'=>'Any ". ucfirst($prefix) ."']\n";
            echo "    ],\n";

        } elseif ($name == 'description' || $name == "content" || $name == 'overview') {
            echo "    [\n";
            echo "        'class' => '\kartik\grid\EditableColumn',\n";
            echo "        'contentOptions' => ['class' => 'col-md-1 nowrap'],\n";
            echo "        'attribute'=>'" . $name . "',\n";
            echo "        'format' => 'html',\n";
            echo "         'hAlign'=>'center',\n";
            echo "         'vAlign'=>'middle',\n";
            echo "    ],\n";
        } elseif ($name == 'birth_date' || $name == "publish_date") {
            $prefix = null;
            if ($name == 'birth_date') $prefix = 'Birth date';
            else                       $prefix = 'Publish date';
            echo "    [\n";
            echo "        'headerOptions' => ['class' => 'kv-sticky-column'],\n";
            echo "        'contentOptions' => ['class' => 'col-md-2 nowrap'],\n";
            echo "        'attribute'=>'" . $name . "',\n";
            echo "        'hAlign'=>'center',\n";
            echo "        'vAlign'=>'middle',\n";
            echo "        'attribute'=>'". $name . "',\n";
            echo "        'format' => 'date',\n";
            echo "         'xlFormat' => \"dd-MM-yyyy\",\n";
            echo "        /*'filterType'=> GridView::FILTER_DATE,\n";
            echo "        'filterWidgetOptions'=>[\n";
            echo "              'pluginOptions'=>['allowClear'=>true],\n";
            echo "         ],\n";
            echo "         'readonly' => function(\$model, \$key, \$index, \$widget) {\n";
            echo "                  return (!\$model->is_active);\n";
            echo "         },\n";
            echo "         'editableOptions' => function (\$model) {\n";
            echo "              return array(\n";
            echo "                  'header' => '".ucfirst($prefix)."',\n";
            echo "                  'size' => 'md',\n";
            echo "                  'inputType' => \kartik\editable\Editable::INPUT_WIDGET,\n";
            echo "                  'widgetClass' =>  'kartik\datecontrol\DateControl'\n";
            echo "                  'options' => [\n";
            echo "                      'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,\n";
            echo "                      'displayFormat' => 'dd-MM-yyyy',\n";
            echo "                      'saveFormat' => 'php:Y-m-d',\n";
            echo "                   ]\n";
            echo "                  ],\n";
            echo "               );\n";
            echo "          },*/\n";
            echo "    ],\n";
        } elseif ($name == 'color') {
            echo "    [\n";
            echo "        'class' => '\kartik\grid\EditableColumn',\n";
            echo "        'contentOptions' => ['class' => 'col-md-1 nowrap'],\n";
            echo "        'attribute'=>'" . $name . "',\n";
            echo "         'hAlign'=>'center',\n";
            echo "         'vAlign'=>'middle',\n";
            echo "        'value' => function (\$model, \$key, \$index, \$widget) {\n";
            echo "                return \"<span class='badge' style='background-color: {\$model->color}'> </span>  <code>\" . \$model->color . '</code>';\n";
            echo "          },\n";
            echo "          'width' => '8%',\n";
            echo "          'filterType' => GridView::FILTER_COLOR,\n";
            echo "          'filterWidgetOptions' => [\n";
            echo "                  'showDefaultPalette' => false,\n";
            echo "                  'pluginOptions' => $colorPluginOptions,\n";
            echo "          ],\n";
            echo "          'vAlign' => 'middle',\n";
            echo "          'format' => 'raw',\n";
            echo "          'noWrap' => $this->noWrapColor\n";
            echo "    ],\n";

        } else {
            echo "    [\n";
            echo "        'hAlign'=>'center',\n";
            echo "        'vAlign'=>'middle',\n";
            echo "        'class'=>'\kartik\grid\EditableColumn',\n";
            echo "        'contentOptions' => ['class' => 'col-md-1 nowrap'],\n";
            echo "        'attribute'=>'" . $name . "',\n";
            echo "    ],\n";
        }
    } else {
        echo "    // [\n";
        echo "        // 'class'=>'\kartik\grid\EditableColumn',\n";
        echo "        // 'contentOptions' => ['class' => 'col-md-1 nowrap'],\n";
        echo "        // 'attribute'=>'" . $name . "',\n";
        echo "    // ],\n";
    }
}
?>
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action,'<?=substr($actionParams,1)?>'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete',
            'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
            'data-request-method'=>'post',
            'data-toggle'=>'tooltip',
            'data-confirm-title'=>'Are you sure?',
            'data-confirm-message'=>'Are you sure want to delete this item'
        ],
    ],

];