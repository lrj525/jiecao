<?php
namespace common\lib;

class SafetyUtils
{
    public static $cvt = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/"; // Base64字典表
    public static $fillchar = '=';
    // 扰码采用固定手机号码
    public static $mobile = "13370995823";

    /**
     * 加密
     *
     * @param input
     * @return
     */
    public static function encodeBase64($input)
    {
        if ($input) {
            try {
                $data = self::getBytes($input);
                $molen = strlen(self::$mobile);
                $c = '';
                $len = count($data);
                $ret  = '';
                for ($i = 0; $i < $len; $i++) {
                    // byte1.
                    $c = ($data[$i] >> 2) & 0x3f;

                    // 加入扰码
                    if ($i < $molen) {
                        $c ^= ord(self::$mobile[$i]);
                    }

                    $ret[] = self::$cvt[$c];
                    // byte2.
                    $c = ($data[$i] << 4) & 0x3f;
                    if (++$i < $len) {
                        $c |= ($data[$i] >> 4) & 0x0f;
                    }
                    $ret[] = self::$cvt[$c];
                    // byte3.
                    if ($i < $len) {
                        $c = ($data[$i] << 2) & 0x3f;
                        if (++$i < $len) {
                            $c |= ($data[$i] >> 6) & 0x03;
                        }
                        $ret[] = self::$cvt[$c];
                    } else {
                        ++$i;
                        $ret[] = self::$cvt[$c];
                    }
                    // byte4.
                    if ($i < $len) {
                        $c = $data[$i] & 0x3f;
                        $ret[] = self::$cvt[$c];
                    } else {
                        $ret[] = self::$cvt[$c];
                    }
                }
                return implode('',$ret);
            } catch (\Exception $ex) {
                // loggerwebutil.error("base64 encode 操作失败! " + ex);
            }
        }
        return $input;
    }
    /**
     * 解密
     *
     * @param input
     * @return
     */
    public static function decodeBase64($input) {
        if ($input) {
            try {
                $data = self::getBytes($input);
                $molen = strlen(self::$mobile);
                $c1 = '';
                $c2 = '';
                $len = count($data);
                $ret = [];
                for ($i = 0; $i < $len; $i++) {
                    // byte1.
                    $c1 = self::indexOf(self::$cvt,$data[$i]);
                    // 去掉扰码
                    if (($i - ($i / 4)) < $molen) {
                        $c1 ^= ord(self::$mobile[$i - $i / 4]);
                    }
                    ++$i;
                    $c2 =  self::indexOf(self::$cvt,$data[$i]);
                    $c1 = (($c1 << 2) | (($c2 >> 4) & 0x03));
                    $ret [] = chr($c1);
                    // byte2.
                    if (++$i < $len) {
                        $c1 = $data[$i];
                        if (self::$fillchar == $c1) {
                            break;
                        }
                        $c1 =  self::indexOf(self::$cvt, chr($c1));
                        $c2 = (($c2 << 4) & 0xf0) | (($c1 >> 2) & 0x0f);
                        $ret[] = chr($c2);
                    }
                    // byte3.
                    if (++$i < $len) {
                        $c2 = $data[$i];
                        if (self::$fillchar == $c2) {
                            break;
                        }
                        $c2 =  self::indexOf(self::$cvt, chr($c2));
                        $c1 = (($c1 << 6) & 0xc0) | $c2;
                        $ret[]=chr($c1);
                    }
                }
                return  implode('',$ret);
            } catch (\Exception $ex) {
                // loggerwebutil.error("base64 decode 操作失败! " + ex);
            }
        }
        return $input;
    }


    public static function getBytes($string) {
        $bytes = array();
        for($i = 0; $i < strlen($string); $i++){
            $bytes[] = ord($string[$i]);
        }
        return $bytes;
    }

    public static function indexOf($str,$find)
    {
        if(is_int($find)){
            $index = -1;

            for($i=0;$i<strlen($str);$i++){

                if(ord($str[$i]) == $find){
                    $index = $i;
                }

            }
        }
        else {
            $index = strpos($str,$find);
            if($index===false){
                $index = -1;
            }
        }
        return $index;
    }
}
