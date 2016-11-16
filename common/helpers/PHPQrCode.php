<?php
namespace common\helpers;

/**
 * Class QrCode 二维码
 * @package common\models\qrcode
 */
class PHPQrCode
{
    /**
     * 生成QRCode
     * @param string $text 内容
     * @param string $type 类型  png text eps svg raw jpg
     * @param string $info =['pathName'=>"路径",
     *                'ecc'=>"错误纠正码 从少到多 array('L','M','Q','H')",
     *                'size'=>"尺寸 1-10",
     *                'margin'=>"外边距",
     *                'back_color'=>背景色(0x0000aa #ffffff),
     *                'fore_color'=>前景色];
     *               false 时直接返回图片二进制流内容（png和svg和jpg和eps 好使）
     * @return bool
     */
    public static function create($text, $type = 'png', $info = false)
    {
        /*存储路径*/
        if (!isset ($info['pathName']))
        {
            //未定义路径
           $path = false;
        }
        else
        {
            //有定义存储路径
            if (!file_exists(dirname($info['pathName'])))
            {
                $path = false;
            }
            else
            {
                $path = $info['pathName'];
            }
        }
        /*预设参数*/
        //错误纠正码等级
        if (!isset ($info['ecc']))
        {
            $info['ecc'] = 'Q';
        }
        //图片尺寸
        if (!isset ($info['size']))
        {
            $info['size'] = '8';
        }
        //外边距
        if (!isset ($info['margin']))
        {
            $info['margin'] = '2';
        }
        //质量
        if (!isset($info['quality']))
        {
            $info['quality'] = 85;
        }
        //背景色
        if (!isset ($info['back_color']))
        {
            $info['back_color'] = 0xFFFFFF;
        }
        else
        {
            $info['back_color'] = hexdec(str_replace('#', '0x', $info['back_color']));
        }
        //前景色
        if (!isset ($info['fore_color']))
        {
            $info['fore_color'] = 0x000000;
        }
        else
        {
            $info['fore_color'] = hexdec(str_replace('#', '0x', $info['fore_color']));
        }

        include_once  realpath(dirname(__DIR__).'/../extend/qrcode/qrlib.php');

        if ($type == 'png' || $type == 'eps' || $type == 'svg')
        {
            $data = \QRcode :: $type ($text, $path, $info['ecc'], $info['size'], $info['margin'], false, $info['back_color'], $info['fore_color']);
        }
        elseif ($type == 'text' || $type == 'raw')
        {
            $data = \QRcode :: $type ($text, $path, $info['ecc'], $info['size'], $info['margin']);
        }elseif($type == 'jpg')
        {
            $data = \QRcode :: $type($text,$path, $info['ecc'], $info['size'], $info['margin'],$info['quality'],$info['back_color'], $info['fore_color']);
        }
        return $data;
    }
}


