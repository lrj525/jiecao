<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/1
 * Time: 10:00
 */

namespace webapp\controllers;
use Yii;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;

class ErrorController extends Controller
{
    public function actionIndex()
    {
        $exception = Yii::$app->errorHandler->exception;
        return [
            'statusCode'=> $exception->statusCode,
            'message'   => $exception->getMessage(),
            'code' =>$exception->getCode()
        ];
        $exception = Yii::$app->errorHandler->exception;
        //404没找到
        if($exception instanceof  NotFoundHttpException)
        {
            //触发其他版本
            $routeVersionResult = static::triggerVersion($exception);
            if($routeVersionResult !== false)
            {
                $exception->statusCode = 200;
                return $routeVersionResult;
            }
            $data = [
                'result'=>false,
                'message'=>'没有找到该接口',
                'error_code'=>404
            ];
            return $data;
        }
        else if($exception instanceof \ErrorException)
        {
            $data = [
                'result'=>false,
                'message'=>'服务器错误',
                'error_code'=>500
            ];
            return $data;
        }
    }
    /**
     * 通过异常触发版本，如果当前版本未修改该方法，查找比当前版本小的方法
     * @author zhangjunliang
     * @return array
     * @date 2015-09-24
     */
    public static function triggerVersion($exception)
    {
        //获取到出错的路由
        $previous = $exception->getPrevious();
        $trace = $previous->getTrace();
        foreach($trace as $k => $v)
        {
            if($v['function'] == 'runAction')
            {
                $route = isset($v['args'][0]) ? $v['args'][0] : '';
                $params = isset($v['args'][1]) ? $v['args'][1] : '';
            }
            else
            {
                break;
            }
        }
        //验证路由是否符合规则 符合规则 开始修正路由
        if(!empty($route) && is_string($route))
        {
            $routeInfo = explode('/',$route);
            $apiVersion = Yii::$app->params['app_version'];
            //验证是否属于定义的模块
            if(in_array($routeInfo[0],$apiVersion))
            {
                //倒序 版本 方便处理
                krsort($apiVersion);
                //循环处理版本
                while ($apiVersionNum = current($apiVersion))
                {
                    //查找比当前小的版本是否存在该action
                    if($apiVersionNum !== $routeInfo[0] && $apiVersionNum < $routeInfo[0])
                    {
                        $routeInfo[0] = $apiVersionNum;
                        $route= implode('/',$routeInfo);
                        //生成controller
                        $parts = Yii::$app->createController($route);
                        if (is_array($parts))
                        {
                            list($controller, $actionID) = $parts;
                            $actionID = empty($actionID) ? 'index' : $actionID;
                            //通过$actionID生成action 如果不为null 调用执行action
                            $action = $controller->createAction($actionID);
                            if($action !== null)
                            {
                                return Yii::$app->runAction($route , $params);
                            }
                        }
                    }
                    next($apiVersion);
                }
            }
        }
        return false;
    }
}