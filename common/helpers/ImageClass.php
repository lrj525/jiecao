<?php
namespace common\helpers;

//引入图片基类

/**
 * ImageClass
 * 
 * 该类是图片功能类，亦在处理图片接口统一
 * 
 * @example
 * 
 *  图片上传
 *  	$upload = new ImageClass($_FILES);
 *  	$upload->imageDir = $imagePath;
 *  	$upload->imageUpload('test.jpg');
 *
 *  图片缩放
 *  	ImageClass::imageZoom($srcFile, $dstpath, 400, 400, 2, 1, $water=array()); 等比压缩补白，具体看方法说明
 *
 *  头像裁切
 *  	ImageClass::avatarCut($srcFile, $dstpath, $size = '100_100', $xPoint = 0, $yPoint = 0, $width = 50, $height = 50);
 *
 * 	图片裁切
 * 		ImageClass::imageCut($srcFile, $dstpath, $cutx, $cuty, $cutw, $cuth);
 *
 *  图片复制
 *  	ImageClass::imageCopy($srcFile, $dstFile);
 *  
 */
class ImageClass extends ImageBase{

	private $name;
	private $tmp_name;
	public  $imageDir;

	//默认水印图及位置
	public static $_waterConfig = array(
		'waterName'=>array("images/water.png","images/topwater.png","images/bottomwater.png"),
		'waterPos'=>array(10,1,9)
	);

	//初始化上传文件信息
	public function __construct($files){
		$this->name     = $files['name'];
		$this->tmp_name = $files['tmp_name'];
	}

	/**
	 * 图片上传
	 * 
	 * @param  string $photo_name 图片名
	 * @return string | array()
	 */
	public function imageUpload($photo_name = ''){
		$link = '';
		
		if(is_array($this->name)){
			foreach ($this->name as $key=>$name){
				if(!empty($name)){
					$link[] = $this->moveFile($name, $this->tmp_name[$key], $photo_name);
				}
			}
		}else{
			$link = $this->moveFile($this->name, $this->tmp_name, $photo_name);
		}
		
		return $link;
	}

	/**
	 * 图片缩放
	 * 
	 * @param  string  $srcFile 源文件
	 * @param  string  $dstpath 目标文件
	 * @param  string  $width   目标图片宽度
	 * @param  string  $height  目标图片高度
	 * @param  integer $type    裁切方式1、2
	 *   注：type等于1
	 * 			先按等比裁切出最大的图片，再缩放
	 * 		 type等于2, 有三种情况
	 * 			定宽度，缩放
	 * 			定高度，缩放
	 * 			定宽、高度缩放，补白
	 * 	   
	 * @param  array   $water   水印参数配置
	 * @param  integer $filler  是否补白，1补白0不补白
	 * @return array            array('status' => 1, 'image' => '/wwwroot/upload/images/first_500_600_3.jpg')
	 */
	public static function imageZoom($srcFile, $dstpath, $width = 0, $height = 0, $type = 1, $filler = 0, $water=array()){
		$image = array();
		
		//源文件
		$image['srcFile']    = $srcFile;
		//保存文件路径
		$image['path']       = dirname($dstpath);
		$image['newImgName'] = basename($dstpath);

		//图片是否补白
		$image['thumb']['filler'] = $filler == 1 ? 1 : 0;
		
		if($type == 1){
			//先按等比裁切出最大的图片，再缩放，不易失真
			
			list($image['cut']['x'], $image['cut']['y'], $image['cut']['width'], $image['cut']['height']) = self::cutMainPic($srcFile, $width, $height);
			$image['thumb']['width']  = $width;
			$image['thumb']['height'] = $height;
			if(empty($width) && empty($height)){
				list($image['thumb']['width'], $image['thumb']['height']) = getimagesize($srcFile);
			}
		}elseif($type == 2){
			//指定宽高缩放，易失真，作补白处理
			
			list($imgInfo["width"], $imgInfo["height"]) = getimagesize($srcFile);
			$image['thumb']['height'] = $imgInfo["height"];
			$image['thumb']['width']  = $imgInfo["width"] ;
			
			if((!empty($width) && empty($height)) || (empty($width) && !empty($height)) || (!empty($width) && !empty($height))){
				if(empty($width) && !empty($height)){ 
					//高度不变，缩放
					$image['thumb']['width']  = round(($imgInfo["width"] * $height) / $imgInfo["height"]) ;
					$image['thumb']['height'] = $height;
				}else if(empty($height) && !empty($width)){ 
					//宽度不变，缩放
					$image['thumb']['width']  = $width;
					$image['thumb']['height'] = round(($imgInfo["height"] * $width) / $imgInfo["width"]);
				}else{
					//指定宽度、高度，缩放
					if($image['thumb']['filler']){
						$image['thumb']['truecolor_width']  = $width;
						$image['thumb']['truecolor_height'] = $height;
					}
					$radio = round($imgInfo['width'] / $imgInfo['height'], 2);
					if($radio > 1){
						if($width > $imgInfo['width']){
							$image['thumb']['width']  = $imgInfo['width'];
							$image['thumb']['height'] = $imgInfo['height'];
						}else{
							$image['thumb']['width']  = $width;
							$image['thumb']['height'] = ($width * $imgInfo['height']) / $imgInfo['width'];
						}
					}else{
						if($height > $imgInfo['height']){
							$image['thumb']['width']  = $imgInfo['width'];
							$image['thumb']['height'] = $imgInfo['height'];
						}else{
							$image['thumb']['width']  = ($height * $imgInfo['width']) / $imgInfo['height'];
							$image['thumb']['height'] = $height;
						}
					}
				}
			}
		}
		
		//加水印配置
		if(!empty($water)){
			$image['waterMarsk']['waterName'] = isset($water['waterName']) ? $water['waterName'] : '';
			$image['waterMarsk']['waterPos']  = isset($water['waterPos']) ? $water['waterPos'] : '';
		}
		
		//图片缩放
		$result = array();
		$result = parent::getInstance()->set($image)->save();
		
		if(parent::$errorMsg){
			$result['status']    = 0;
			$result['error_msg'] = parent::$errorMsg;
		}

		self::emptyBaseClass();

		return $result;
	}

	/**
	 * 用户头像裁切（缩放）
	 * 注：自定义起始位置、宽高裁切图片(鼠标拖拽截图，上传头像用)
	 * 
	 * @param  string  $srcFile 源文件
	 * @param  string  $dstpath 目标文件
	 * @param  string  $size    目标文件宽高拼接，格式如100_100, 120_120等
	 * @param  integer $xPoint    裁剪起始X坐标
	 * @param  integer $yPoint    裁剪起始Y坐标
	 * @param  integer $width    裁剪的宽度
	 * @param  integer $height    裁剪的高度
	 * @return array            array('status' => 1, 'image' => '/wwwroot/upload/images/first_500_600_3.jpg')
	 */
	public static function avatarCut($srcFile, $dstpath, $size = '100_100', $xPoint = 0, $yPoint = 0, $width = 50, $height = 50){
		//源文件
		$image['srcFile'] = $srcFile;
		//保存文件路径
		$image['path'] = dirname($dstpath);
		//新名字
		$image['newImgName'] = basename($dstpath);
		list($srcWidth, $srcHeight) = getimagesize($srcFile);

		// 如果图片太小，没有画的区域大那么就以该图片的最小边琏切
		if($width > $srcWidth){
			$px = 0;
			if($srcHeight > $srcWidth){
				$px = $srcWidth;
			}else{
				$px = $srcWidth;
			}
			$width = $px;
			$height = $px;
			$xPoint = 0;
			$yPoint = 0;
		}else if($height > $srcHeight){ // 要切的图高小于画的区域那么以该图片的高来切
			$width = $srcHeight;
			$height = $srcHeight;
			$xPoint = 0;
			$yPoint = 0;
		}
		
		if($xPoint >= $width){
			$xPoint = 0;
		}
		if($yPoint >= $height){
			$yPoint = 0;
		}

		$size = explode('_', $size);
		
		$image['cut']['cut']        = 1; //1: 表示使用 imagecopyresampled() 方式裁切，否则使用imagecopy()方式裁切
		$image['cut']['x']          = $xPoint; //裁切原图起始X坐标
		$image['cut']['y']          = $yPoint; //裁切原图起始Y坐标 
		$image['cut']['width']      = $size[0]; //目标图宽度
		$image['cut']['height']     = $size[1]; //目标图高度
		$image['cut']['cut_width']  = $width; //裁切原图宽度
		$image['cut']['cut_height'] = $height; //裁切原图高度

		$result = array();
		$result = parent::getInstance()->set($image)->save();
		if(parent::$errorMsg){
			$result['status']    = 0;
			$result['error_msg'] = parent::$errorMsg;
		}

		self::emptyBaseClass();

		return $result;
	}

	/**
	 * 图片裁切(不缩放)
	 *
	 * @param  string  $srcFile 源文件
	 * @param  string  $dstpath 目标文件
	 * @param  integer $cutx    裁剪起始X坐标
	 * @param  integer $cuty    裁剪起始Y坐标
	 * @param  integer $cutw    裁剪的宽度
	 * @param  integer $cuth    裁剪的高度
	 * @return array            array('status'=>1, 'image' => '/wwwroot/upload/images/first_500_600_3.jpg')
	 */
	public static function imageCut($srcFile, $dstpath, $cutx, $cuty, $cutw, $cuth){
		//源文件
		$image['srcFile'] = $srcFile;
		//保存文件路径
		$image['path'] = dirname($dstpath);
		//新名字
		$image['newImgName'] = basename($dstpath);

		list($srcWidth, $srcHeight) = getimagesize($srcFile);
		//要裁切的宽高大于原图的宽高的话，就等比缩放
		if($cutw > $srcWidth - $cutx || $cuth > $srcHeight - $cuty){
			return self::imageZoom($srcFile, $dstpath, $cutw, $cuth, 2);
		}

		$image['cut']['x']      = $cutx;
		$image['cut']['y']      = $cuty;
		$image['cut']['width']  = $cutw;
		$image['cut']['height'] = $cuth;

		$result = array();
		$result = parent::getInstance()->set($image)->save();
		if(parent::$errorMsg){
			$result['status']    = 0;
			$result['error_msg'] = parent::$errorMsg;
		}

		self::emptyBaseClass();

		return $result;
	}

	/**
	 * 图片复制
	 * @param  string $srcFile 源文件
	 * @param  string $dstFile 目标文件
	 * @return boolean          
	 */
	public static function imageCopy($srcFile, $dstFile){
		if(file_exists($srcFile)){
			if(!is_dir(dirname($dstFile))){
				@mkdir(dirname($dstFile), 0777, true);
			}
			if(copy($srcFile, $dstFile)){
				return true;
			}
		}
		return false;
	}

	/**
	 * 图片上传原宽高缩放，相当于复制
	 * 
	 * @param  string $name       源文件
	 * @param  string $temp_name  临时文件
	 * @param  string $photo_name 图片名
	 * @return string             图片地址             
	 */
	private function moveFile($name, $temp_name, $photo_name = ''){
		//获取图片扩展名
		preg_match("/(\.){1}(\w){3,4}/",$name,$matches);

		if(!is_dir($this->imageDir)){
			mkdir($this->imageDir, 0777, true);
		}
		
		if(empty($photo_name)){
			$newName = date("YmdHis").rand(100000, 999999).strtolower($matches[0]);
		}else{
			$newName = $photo_name;
		}

		$this->imageDir = rtrim($this->imageDir, '/') . '/';
		$srcFile = $temp_name;
		$dstFile = $this->imageDir . $newName;
		list($width, $height) = getimagesize($temp_name);

		//宽高等比缩放
		$result = self::imageZoom($srcFile, $dstFile, $width, $height, 2);

		return isset($result['status']) && $result['status'] == 1 ? $result['image'] : '';
	}

	/**
	 * 根据目标图片的宽高，通过比例，算出裁切的开始位置及最大裁切宽度、高度
	 * @param  string  $srcfile 原图地址
	 * @param  integer $dst_x   目标图宽度
	 * @param  integer $dst_y   目标图高度
	 * @return array            开始位置、裁切宽高的数组
	 */
	private static function cutMainPic($srcfile, $dst_x=430, $dst_y=270){
		$imgInfo = getimagesize($srcfile) ;
	
		$src_x = $imgInfo[0] ;
		$src_y = $imgInfo[1] ;
		$img_type = $imgInfo[2] ;
	
		//判断要裁剪图片的x ，y 先裁剪出一个最大的比例，再压缩成目标尺寸
		$start_x = 0 ; //开始x位置
		$start_y = 0 ; //开始Y位置
		$cx = $src_x ; //要裁剪的宽度
		$cy = $src_y ; //要裁剪的高度
	
		if($dst_x * $src_y > $dst_y * $src_x){
			//裁 y  高度
			$cy = intval($dst_y * $src_x / $dst_x) ;
			$start_y = intval(($src_y - $cy)/2);
	
		}else if($dst_x * $src_y < $dst_y * $src_x){
			//裁 x  宽度
			$cx = intval($dst_x * $src_y / $dst_y) ;
			$start_x = intval(($src_x - $cx)/2);
		}

		return array($start_x, $start_y, $cx, $cy);
	}

	/**
	 * 清空基类对象
	 */
	private static function emptyBaseClass(){
		if(parent::getInstance() !== null){
			$baseClass = parent::getInstance();
			foreach ($baseClass as $key => $val) {
				unset($baseClass->$key);
			}
		}
	}
	
	/**
	 * 压缩多个尺寸
	 */
	public static function batchZoom($srcFile,$sizes)
	{
	    $extension = pathinfo($srcFile, PATHINFO_EXTENSION);
	    foreach ($sizes as $val){
	        list($width,$height) = explode('_',$val);
    	    $dstpath = str_replace('.'.$extension,  '_'.$val.'.'.$extension,$srcFile);
    	    ImageClass::imageZoom($srcFile, $dstpath, $width, $height,1);
	    }
	}
	
	
}