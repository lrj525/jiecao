<?php
namespace webapp\controllers;

use Yii;
use yii\web\Controller;
/**
 * Site controller
 */
class BaseController extends Controller
{
    //给视图传参
    public $_view = [];
    public $layout='main';
    /**
     * 重写render 加个参数
     * @author xi
     * @see \yii\base\Controller::render()
     * @date 2015-3-20
     */
    public function render($view, $params = [])
    {
        $this->_view = array_merge($this->_view,$params);
        return parent::render($view,$this->_view);
    }
}
