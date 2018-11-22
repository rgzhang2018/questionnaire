<?php
namespace ext;
	class verimg{
		private $width;             //验证码图片的宽度
		private $height;            //验证码图片的高度
		private $codeNum;           //验证码字符的个数
		private $disturbColorNum;   //干扰元素数量
		private $checkCode;         //验证码字符
		private $image;             //验证码资源
		private $fontSize;          //字符尺寸

		/**
		 * 构造方法       
		 * @param	int	$width		设置验证码图片的宽度
		 * @param	int	$height		设置验证码图片的高度
		 * @param	int	$codeNum	设置验证码中字符的个数
		 * @param	int	$fontSize	设置验证码中字符的尺寸 
		 */ 
		function __construct($width=100,$height=38,$codeNum=4,$fontSize=14) {
			$this->width=$width;
			$this->height=$height;
			$this->codeNum=$codeNum;
			$this->fontSize = $fontSize;
			$number=floor($height*$width/15);
			if($number > 240-$codeNum) $this->disturbColorNum=240-$codeNum;
			else $this->disturbColorNum=$number;
			$this->checkCode=$this->createCheckCode();
		}

		/**
		 * 输出图像并把验证码保存到SESSION
		 * @param  string $name [SESSION中验证码的键名]
		 * @return [type]       [description]
		 */
		function img($name='_verimgcode'){
			$_SESSION[$name] = md5(strtolower($this->checkCode));
			$this->out();
		}

		/**
		 * 检查验证码是否正确
		 * @param  string $code [用户输入的验证码（不区分大小写）]
		 * @param  string $name [SESSION中保存验证码的键名]
		 * @return boolean      [description]
		 */
		static function check($code,$name='_verimgcode'){
			if(md5(strtolower($code)) == $_SESSION[$name]){
				unset($_SESSION[$name]);
				return true;
			}
			else return false;
		}

		/**
		 * 输出图像
		 * @return [type] [description]
		 */
		private function out(){
			$this->getCreateImage();                 //创建画布并对其进行初使化
			$this->setDisturbColor();                //向图像中设置一些干扰像素
			$this->outputText();                     //向图像中输出随机的字符串
			$this->outputImage();                    //生成相应格式的图像并输出
		}

		/**
		 * 创建图像资源，并初使化背影
		 * @return [type] [description]
		 */
		private function getCreateImage(){
			$this->image=imagecreatetruecolor($this->width,$this->height);
			$backColor = imagecolorallocate($this->image, rand(150,255),rand(150,255),rand(150,255));    //背景色（随机）
			@imagefill($this->image, 0, 0, $backColor);
		//	$border=imageColorAllocate($this->image, 0, 0, 0);
		//	imageRectangle($this->image,0,0,$this->width-1,$this->height-1,$border);
			imageRectangle($this->image,0,0,$this->width,$this->height);
		}

		/**
		 * 随机生成指定个数的字符,去掉容易混淆的字符oOLlz和数字012
		 * @return [string] [description]
		 */
		private function createCheckCode(){
			$str = '';
			$code="3456789abcdefghijkmnpqrstuvwxyABCDEFGHIJKMNPQRSTUVWXY";
			for($i=0;$i<$this->codeNum;$i++){
				$char = $code{rand(0,strlen($code)-1)};
				$str .= $char;
			}
			return $str;
		}

		/**
		 * 设置干扰像素，向图像中输出不同颜色的100个点
		 */
		private function setDisturbColor() {
			// for($i=0;$i<=$this->disturbColorNum;$i++){
			// 	$color = imagecolorallocate($this->image, rand(0,255), rand(0,255), rand(0,255));
   // 				imagesetpixel($this->image,rand(1,$this->width-2),rand(1,$this->height-2),$color);
			// }
			imagesetthickness($this->image,rand(3,6));
			
			for($i=0;$i<3;$i++){
				$color=imagecolorallocate($this->image,rand(100,200),rand(100,200),rand(100,200));
				// imagearc($this->image,rand(-10,$this->width-10),rand(-10,$this->height-10),rand(30,300),rand(20,200),55,44,$color);
				imagearc($this->image,rand(-10,$this->width-10),rand(-10,$this->height-10),rand(30,2*$this->width),rand(20,2*$this->height),50,20,$color);
			}
			for($i=0;$i<5;$i++){
				$char = rand(0,9);
				$color = imagecolorallocate($this->image, rand(150,255), rand(150,255), rand(150,255));
				imagechar($this->image, 5, rand(1,$this->width-2),rand(1,$this->height-2), $char, $color);
			}
		}

		/**
		 * 随机颜色、随机摆放、
		 * @return [type] [description]
		 */
		private function outputText(){
			for ($i=0;$i<=$this->codeNum;$i++){
				$fontcolor = imagecolorallocate($this->image, rand(0,128), rand(0,128), rand(0,128));
				//$o = [10,15,20,25,30,350,345,340,335,330];
				//$ii = rand(0,9);
				$ii = rand(-30,30);
				//$x = floor($this->width/$this->codeNum)*$i + 5;
				$x = $i ? floor($this->width/$this->codeNum)*$i + rand(-3,5) : 5;
			//	$y = rand($this->height - $this->fontSize,2*$this->height-$this->fontSize);
				$y = rand(($this->fontSize + 5),($this->height - 5));
				//imagettftext($this->image,$this->fontSize,$o[$ii],$x,$y,$fontcolor,LIB."ext/ttfs/1.ttf",$this->checkCode{$i});
				imagettftext($this->image,$this->fontSize,$ii,$x,$y,$fontcolor,LIB."ext/ttfs/1.ttf",$this->checkCode{$i});
 			}
		}

		/**
		 * 自动检测GD支持的图像类型，并输出图像
		 * @return [type] [description]
		 */
		private function outputImage(){          
		    ob_clean();
			if(imagetypes() & IMG_GIF){
				header("Content-type: image/gif");
				imagegif($this->image);
			}elseif(imagetypes() & IMG_JPG){
				header("Content-type: image/jpeg");
				imagejpeg($this->image, "", 2);
			}elseif(imagetypes() & IMG_PNG){
				header("Content-type: image/png");
				imagepng($this->image);
			}elseif(imagetypes() & IMG_WBMP){
				 header("Content-type: image/vnd.wap.wbmp");
				 imagewbmp($this->image);
			}else{
				die("不支持图像创建！");
			}	
		}

		/**
		 * 销毁图像资源释放内存
		 */
		function __destruct(){
 			imagedestroy($this->image);
		}
	}
