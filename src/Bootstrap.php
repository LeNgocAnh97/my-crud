<?php

namespace tongvanduc\mycrud;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * @author Tống Văn Đức <tongduc315@gmail.com>
 * @since 1.x
 */
class Bootstrap implements BootstrapInterface {

    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     */
    public function bootstrap($app) {
        Yii::setAlias("@mycrud", __DIR__);
        Yii::setAlias("@tongvanduc/mycrud", __DIR__);
        if ($app->hasModule('gii')) {
            if (!isset($app->getModule('gii')->generators['mycrud'])) {
                $app->getModule('gii')->generators['mycrud'] = 'tongvanduc\mycrud\generators\Generator';
                $app->getModule('gii')->generators['mymodel'] = 'tongvanduc\mycrud\model\Generator';
            }
        }
    }

}
