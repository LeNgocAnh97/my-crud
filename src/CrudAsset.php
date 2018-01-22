<?php 

namespace tongvanduc\mycrud;

use yii\web\AssetBundle;

/**
 * @author Tống Văn Đức <tongduc315@gmail.com>
 * @since 1.x
 */
class CrudAsset extends AssetBundle
{
    public $sourcePath = '@mycrud/assets';

//    public $publishOptions = [
//        'forceCopy' => true,
//    ];

    public $css = [
        'ajaxcrud.css'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'kartik\grid\GridViewAsset',
    ];
    
   public function init() {
       // In dev mode use non-minified javascripts
       $this->js = YII_DEBUG ? [
           'ModalRemote.js',
           'ajaxcrud.js',
       ]:[
           'ModalRemote.min.js',
           'ajaxcrud.min.js',
       ];

       parent::init();
   }
}
