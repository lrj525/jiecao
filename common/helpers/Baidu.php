<?php
namespace common\helpers;

use common\helpers\Helper;

class Baidu
{
    const AK = "96c6ff711d7eeb0b31e81d02532fff29";

    /**
     * 根据坐标搜索信息
     * @param unknown $keyword
     * @param unknown $lon
     * @param unknown $lat
     * @param number $radius
     * @return array
     * @author xi
     * @date 2015-5-7
     */
    public static function searchLocal($keyword,$lon,$lat,$radius=2000)
    {
        $url = "http://api.map.baidu.com/place/v2/search?query=$keyword&location=$lat,$lon&radius=$radius&output=json&page_num=1&ak=".self::AK;
        $data = Helper::curlGet($url);

        return json_decode($data,true);
    }

    /**
     * 根据城市明查出中心点
     * @param unknown $city
     * @return array
     * @author xi
     * @date 2015-5-7
     */
    public static function getCityPoint($city)
    {
        $url = "http://api.map.baidu.com/geocoder/v2/?address=$city&output=json&ak=".self::AK;
        $jsonStr = Helper::curlGet($url);
        $arr = json_decode($jsonStr,true);

        if(isset($arr['result']['location'])){
            return [
                'lon' => $arr['result']['location']['lng'],
                'lat' => $arr['result']['location']['lat']
            ];
        }
        else {
            return [
                'lon' => 0,
                'lat' => 0
            ];
        }
    }

    /**
     * 通过经度纬度获取城市名称 不带市
     * @param double $lng 企业百度经度
     * @param double $lat 企业百度纬度
     * @return array
     * @author zhangjunliang
     * @date 2015-8-19
     */
    public static function getAddressByLocation($lng,$lat)
    {
        $url = 'http://api.map.baidu.com/geocoder/v2/?ak=' . static::AK . '&location=' . $lat . ',' . $lng . '&output=json&pois=1';
        $jsonStr = Helper::curlGet($url);
        $result = json_decode($jsonStr,true);
        if(isset($result['result']['addressComponent']))
        {
            return [
                'country' => $result['result']['addressComponent']['country'],
                'province' => $result['result']['addressComponent']['province'],
                'city' => str_replace('市', '', $result['result']['addressComponent']['city']),
                'district' => $result['result']['addressComponent']['district'],
            ];
        }
        else
        {
            return [
                'country' => '',
                'province' => '',
                'city' => '',
                'district' => '',
            ];
        }
    }
}