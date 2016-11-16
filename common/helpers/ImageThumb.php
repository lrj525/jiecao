<?php
namespace common\helpers;

use common\helpers\Helper;
class ImageThumb
{
	
	
	/**
	 * 根据用户ID以及 图片尺寸返回用户头像地址
	 * 
	 * @param  integer $uid  用户ID
	 * @param  string  $size 头像尺寸
	 * @return string        头像地址
	 * @author xi
	 * @since  2014-6-20
	 */
	public static function getAvatar($uid, $size = 'pic') {
		$uid = abs(intval($uid));
		$uid = sprintf("%09d", $uid);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		
		$baseUrl = public_path().'/res/upload/avatar/';
		
		if(!file_exists($baseUrl.$dir1.'/'.$dir2.'/'.$dir3.'/'))
			mkdir($baseUrl.$dir1.'/'.$dir2.'/'.$dir3.'/',0777,true);
		
		return $baseUrl. $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2)."_avatar_$size.jpg";
	}
	
	/**
	 * 
	 * 取头像用 
	 * @param int $uid 用户id
	 * @param string $size 头像大小
	 * @return string
	 * @author xi
	 * @since 2014-6-20
	 */
	public static function viewAvatar($uid,$size='pic'){
		$uid = abs(intval($uid));
		$uid = sprintf("%09d", $uid);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		
		$baseUrl = \Config::get('app.res_url').'/avatar/';
		$filename = $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2)."_avatar_$size.jpg";
		
		if(!file_exists(public_path().'/res/upload/avatar/'.$filename)){
			if($size=='pic')
				$size = '300_300';
			return $baseUrl.'default/'.$size.'.png';
		}
		
		return $baseUrl.$filename;
	}
	
	/**
	 * 
	 * 上传图片
	 * @param array $file  $_FILE
	 * @param string $saveDir 保存的路径
	 * @return array
	 * @author xi
	 * @since 2014-7-8
	 */
	public static function uploadImage($file,$saveDir)
	{
		$result= array(
			'status'  => 0,
			'message' => ''
		);
		if(file_exists($saveDir)==false){
			mkdir($saveDir, 0777, true);
		}

		if($file['error'] > 0){
			$result['message'] = '图片大小超过了限制';
			return $result;
		}

		$size = 5242880;
		if($file['size'] > $size){
			$result['message'] = '图片大小不能超过5MB';
			return $result;
		}
		
		list($width, $height) = getimagesize($file['tmp_name']);
		if($width>5000 || $height>5000){
			$result['message'] = '图片像素不能超过5000px';
			return $result;
		}
		
		$type = array("image/jpeg"=>"jpg","image/gif"=>"gif","image/png"=>"png","image/pjpeg"=>"jpg","image/x-png"=>"png",'application/octet-stream'=>preg_replace('/.*?\./','',$file['name']));
		if(! isset($type[strtolower($file['type'])])){
			$result['message'] = '请选择正确的图片格式';
			return $result;
		}

		$filename = $saveDir.date('YmdHis').'_'.rand(100000,999999).'.'.$type[$file['type']];
		move_uploaded_file($file['tmp_name'], $filename);

		$result['status']   = 1;
		$result['message']  = '上传成功';
		$result['imageDir'] = $filename;
		$result['width']    = $width;
		$result['height']   = $height;
		return $result;
	}

	/**
	 * 上传base64流图片
	 *
	 * @param string $file  
	 * @param string $saveDir 保存的路径
	 * @param string $fileName 文件名称	 
	 * @return void
	 * @author hcj
	 * @version 1.0
	 **/
	public static function upBase64($file,$fileName,$saveDir)
	{

		$result= array(
			'status'  => 0,
			'message' => ''
		);

		$img = base64_decode(str_replace('data:image/jpg;base64,','' ,$file));
		if(file_exists($saveDir)==false){
			@mkdir($saveDir, 0777, true);
		}

		$size = 5242880;
		if(strlen($img) > $size){
			$result['message'] = '图片大小不能超过5MB';
			return $result;
		}

		$fileExt = trim(strtolower(strrchr($fileName, '.')),'.');
		if(!in_array($fileExt, array("jpg","gif","png","jpeg"))){
			$result['message'] = '请选择正确的图片格式';
			return $result;
		}
		
		$filename = $saveDir.date('YmdHis').'_'.rand(100000,999999).'.'.$fileExt;
		@file_put_contents($filename, $img);
		
		/* $postData = [
		    'base64Str' => $file,
		    'filename'  => $filename,
			'PHPSESSID' => '1'
		];
		Helper::curlPost(\Yii::$app->params['api_url'].'upload/index', $postData); */

		$result['status']  = 1;
		$result['message'] = '上传成功';
		$result['imageDir'] = $filename;
		return $result;
	}
    
    
    /**
	 * 
	 * 上传图片(不改变名)
	 * @param array $file  $_FILE
	 * @param string $saveDir 保存的路径
	 * @return array
	 * @author xi
	 * @since 2014-7-8
	 */
	public static function uploadImageName($file,$saveDir,$filename)
	{
		$result= array(
			'status'  => 0,
			'message' => ''
		);
		if(file_exists($saveDir)==false){
			mkdir($saveDir, 0777, true);
		}

		if($file['error'] > 0){
			$result['message'] = '图片大小超过了限制';
			return $result;
		}

		$size = 5242880;
		if($file['size'] > $size){
			$result['message'] = '图片大小不能超过5MB';
			return $result;
		}
		
		list($width, $height) = getimagesize($file['tmp_name']);
		if($width>5000 || $height>5000){
			$result['message'] = '图片像素不能超过5000px';
			return $result;
		}
		
		$type = array("image/jpeg"=>"jpg","image/gif"=>"gif","image/png"=>"png","image/pjpeg"=>"jpg","image/x-png"=>"png",'application/octet-stream'=>preg_replace('/.*?\./','',$file['name']));
		if(! isset($type[strtolower($file['type'])])){
			$result['message'] = '请选择正确的图片格式';
			return $result;
		}

		//$filename = $saveDir.date('YmdHis').'_'.rand(100000,999999).'.'.$type[$file['type']];
		move_uploaded_file($file['tmp_name'], $filename);

		$result['status']  = 1;
		$result['message'] = '上传成功';
		$result['imageDir'] = $filename;
		return $result;
	}
}