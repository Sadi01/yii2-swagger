<?php
/**
 * Created by PhpStorm.
 * User: ignatenkovnikita
 * Web Site: http://IgnatenkovNikita.ru
 */

namespace sadi01\swagger\controllers;

use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class DefaultController
 * @package sadi01\swagger\controllers
 */
class DefaultController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = '_clear';
        return $this->render('index', [
            'url' => Url::to(['default/json'], true)
        ]);
    }

    /**
     *
     */
    public function actionJson()
    {
        return $this->getContent();
    }

    public function getContent()
    {
        $content = '';
        if ($this->module->path) {
            $path = \Yii::getAlias($this->module->path);
            $content = \OpenApi\Generator::scan([$path]);
        }

        if ($this->module->url) {
            $content = file_get_contents($this->module->url);
        }

        if (is_callable($this->module->afterRender)) {
            $content = call_user_func($this->module->afterRender, $content);
        }

        header('Content-Type: application/json');
        return $content->toJson();
    }
}