<?php

namespace tongvanduc\ajaxcrud;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * @author Tống Văn Đức <tongduc3115@gmail.com>
 * @since 1.x
 */
class Bootstrap implements BootstrapInterface {

    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     */
    public function bootstrap($app) {
        Yii::setAlias("@ajaxcrud", __DIR__);
        Yii::setAlias("@tongvanduc/ajaxcrud", __DIR__);
        if ($app->hasModule('gii')) {
            if (!isset($app->getModule('gii')->generators['ajaxcrud'])) {
                $app->getModule('gii')->generators['ajaxcrud'] = 'tongvanduc\ajaxcrud\generators\Generator';
            }
        }
    }

}