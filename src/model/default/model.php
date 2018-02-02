<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $properties array list of properties (property => [type, name. comment]) */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;

/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($properties as $property => $data): ?>
 * @property <?= "{$data['type']} \${$property}"  . ($data['comment'] ? ' ' . strtr($data['comment'], ["\n" => ' ']) : '') . "\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php echo "\n"; endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{

<?php foreach ($labels as $name => $label): ?>
    <?php if ($name == 'is_active'): ?>
        const ACTIVE_NAME = 'Active';
        const NON_ACTIVE_NAME = 'NonActive';
    <?php echo "\n"; endif; ?>
    <?php if ($name == 'is_top'): ?>
        const TOP_NAME = 'Top';
        const NON_TOP_NAME = 'NonTop';
    <?php echo "\n"; endif; ?>
    <?php if ($name == 'is_new'): ?>
        const NEW_NAME = 'New';
        const NON_NEW_NAME = 'NonNew';
    <?php echo "\n"; endif; ?>
    <?php if ($name == 'is_hot'): ?>
        const HOT_NAME = 'Hot';
        const NON_HOT_NAME = 'NonHot';
    <?php echo "\n"; endif; ?>
<?php endforeach; ?>
<?php foreach ($labels as $name => $label): ?>
    <?php if (strpos($name,'img_') !== false):  ?>
        /**
        * @var UploadedFile
        */
        public $imageThumb;
        /**
        * @var UploadedFile
        */
        public $imageBanner;
        /**
        * @var UploadedFile
        */
        public $imageFeature;
    <?php echo "\n"; break; endif; ?>
<?php endforeach; ?>
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php echo "\n"; endif; ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [ <?= empty($rules) ? '' : ("\n            " . implode(",\n            ", $rules) . ",\n        ") ?>
            <?php foreach ($labels as $name => $label): ?>
                    <?php if (strpos($name,'img_') !== false):  ?>
[['imageThumb', 'imageBanner', 'imageFeature'], 'file', 'extensions' => 'jpg, gif, png']
                    <?php echo "\n"; break; endif; ?>
            <?php endforeach; ?>
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>

<?php foreach ($labels as $name => $label): ?>
    <?php
        $nameRoot = $name;
        $nameUcfirst = ucfirst(str_replace('is_', '', $name));
        $nameNew = strtoupper(str_replace('is_', '', $name));
    ?>
    <?php if (strpos($nameRoot,'is_') !== false): ?>

        public function get<?= $nameUcfirst ?>Name()
        {
            return ($this-><?= $nameRoot ?> == 1) ? static::<?= $nameNew ?>_NAME : static::NON_<?= $nameNew ?>_NAME;
        }
        public function get<?= $nameUcfirst ?>s()
        {
            return array(
            [
                'id' => 1,
                'name' => static::<?= $nameNew ?>_NAME
            ],
            [
                'id' => 0,
                'name' => static::NON_<?= $nameNew ?>_NAME
            ]
            );
        }

    <?php echo "\n"; endif; ?>
    <?php if ($name == 'lang') : ?>
        public function getLangName()
        {
            $name = '';
            switch ($this->lang) {
                case 'en':
                $name = 'English';
                break;
                case 'vi':
                $name = 'Viet Nam';
                break;
            }
            return $name;
        }
    <?php echo "\n"; endif; ?>
    <?php if (strpos($name,'img_') !== false): ?>
        <?php
            $img = str_replace('img_', '', $name);
        ?>
        public function upload<?= ucfirst($img) ?>()
        {
            if ($this->validate() && !empty($this->image<?= ucfirst($img) ?>)) {
                if (!empty($this-><?= $name ?>)) {
                    unlink($this->getPathImage('<?= $name ?>'));
                }
                $this->image<?= ucfirst($img) ?>->saveAs('uploads/' . self::tableName() . '/<?= $name ?>/' . time() . $this->image<?= ucfirst($img) ?>->baseName . '.' . $this->image<?= ucfirst($img) ?>->extension);
                $this->img_thumb = time() . $this->image<?= ucfirst($img) ?>->baseName . '.' . $this->image<?= ucfirst($img) ?>->extension;
                $this->image<?= ucfirst($img) ?> = null;
            } else {
                return false;
            }
        }
    <?php echo "\n"; endif; ?>
<?php endforeach; ?>
<?php foreach ($labels as $name => $label): ?>
    <?php if (strpos($name,'img_') !== false): ?>
        public function getImage($folder) {
            return Yii::$app->urlManager->baseUrl . '/uploads/' . self::tableName() . "/$folder/" . $this->{$folder};
        }

        public function getPathImage($folder) {
            return Yii::$app->basePath . '/web/uploads/' . self::tableName() . "/$folder/" . $this->{$folder};
        }
    <?php echo "\n"; break; endif; ?>
<?php endforeach; ?>
<?php if ($queryClassName): ?>
<?php
    $queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
?>
    /**
     * @inheritdoc
     * @return <?= $queryClassFullName ?> the active query used by this AR class.
     */
    public static function find()
    {
        return new <?= $queryClassFullName ?>(get_called_class());
    }
<?php echo "\n"; endif; ?>
}
