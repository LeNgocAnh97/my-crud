<?php
/**
 * Created by PhpStorm.
 * User: HY
 * Date: 1/4/2016
 * Time: 3:33 PM
 */

namespace common\widgets;

use iutbay\yii2kcfinder\KCFinderAsset;
use yii\helpers\ArrayHelper;

/**
 * Class FCKEditor
 * @package common\widgets
 */
class DCKEditor extends \dosamigos\ckeditor\CKEditor
{

    /**
     * @var bool
     */
    public $enableKCFinder = true;

    /**
     * Registers CKEditor plugin
     */
    protected function registerPlugin()
    {
        if ($this->enableKCFinder) {
            $this->registerKCFinder();
        }

        parent::registerPlugin();
    }

    /**
     * Registers KCFinder
     */
    protected function registerKCFinder()
    {
        $register = KCFinderAsset::register($this->view);
        $kcfinderUrl = $register->baseUrl;

        $kcfOptions = array_merge(\iutbay\yii2kcfinder\KCFinder::$kcfDefaultOptions, [
            'uploadURL' => \Yii::getAlias('@web') . '/upload/editor',
            'filebrowserBrowseUrl' => $kcfinderUrl . '/browse.php?opener=ckeditor&type=files',
            'filebrowserUploadUrl' => $kcfinderUrl . '/upload.php?opener=ckeditor&type=files',
            'access' => [
                'files' => [
                    'upload' => true,
                    'delete' => false,
                    'copy' => false,
                    'move' => false,
                    'rename' => false,
                ],
                'dirs' => [
                    'create' => true,
                    'delete' => false,
                    'rename' => false,
                ],
            ],
        ]);

        \Yii::$app->session->set('KCFINDER', $kcfOptions);

        $this->clientOptions = ArrayHelper::merge($kcfOptions, $this->clientOptions);
    }

}