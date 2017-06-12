<?php
/*——————————————————————————————————--------------G
 *单文件上传封装	---------------------------------L
 *@FileInfo 上传文件 -----------------------------I
 *@allowExt 允许上传类型 默认jpg、jpeg、png、gif---X
 *@maxSize  允许上传最大值 默认2M -----------------U
 *@uploadPath  上传路径 --------------------------N
 *@$flag   是否验证图片的真实性 -------------------A
 *2017-06-12 athor:guolixun
 *——————————————————————————————————-------------*/

function upload($FileInfo,$allowExt = array('jpg','jpeg','png','gif'),$maxSize = 2097152,$uploadPath = 'uploads',$flag = true){
	//判断错误类型
	if($FileInfo['error'] > 0){
		switch ($FileInfo['error']){
			case 1:
				$mes = '上传文件超出配置文件中upload_max_filesize选项的值';
				break;
			case 2:
				$mes = '上传文件超过表单MAX_FILE_SIZE选项的值';
				break;
			case 3:
				$mes = '文件部分上传';
				break;
			case 4:
				$mes = '没有选择上传文件';
				break;
			case 5:
			case 6:
				$mes = '上传错误';
				break;	
		}
		exit($mes);
	}
	//判断是否为真实图片
	if($flag){
		if(!getimagesize($FileInfo['tmp_name'])){
			exit('请上传真实的图片');
		}
	}
	

	$ext = pathinfo($FileInfo['name'],PATNINFO_EXTENSION);
	//$allowExt = array('jpg','jpeg','png');
	//判断文件类型
	if(!in_array($ext,$allowExt)){
		exit('非法文件类型');
	}

	//$maxSize = 2097152 //默认2M
	//检测上传文件大小是否符合规范
	if($FileInfo['size'] > maxSize){
		exit('上传文件超出限制');
	}
	//检测上传方式
	if(!is_uploaded_file($FileInfo['tmp_name'])){
		exit('文件上传方式错误');
	}

	//$uploadPath = 'uploads';
	$uniName = md5(uniqid(microtime(true),true)).'.'.$ext;
	if(!file_exists($uploadPath)){
		mkdir($uploadPath,0777);
		chmod($uploadPath,0777);
	}
	$destination = $uploadPath.'/'.$uniName;
	//移动文件
	if(!@move_uploaded_file($FileInfo['tmp_name'], $destination)){
		exit('文件移动失败');
	}

	return $destination;
}
