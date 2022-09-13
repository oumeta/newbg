<?php 
ini_set('max_execution_time', '180');
set_time_limit(0);
/*上传管理*/

class AlbumAction extends GlobalAction{
	#上传管理 
 
	public function index(){
		 #上传图片
		 $session_id=1;
		 $this->assign('session_id',$session_id);
	}	 
	function save(){
        $ret_info = array(); // 返回到客户端的信息
        $file = $_FILES['Filedata'];
		// 没有文件被上传
        if ($file['error'] == UPLOAD_ERR_NO_FILE){
            $this->error('3423432');
            exit();
        }		 
		 I('@.Lib.Base'); // 导入上传类
        I('@.Lib.Uploader'); // 导入上传类
		//I('@.Lib.Imagefunc'); // 导入上传类
       // import('image.func');
        $uploader = new Uploader();
		$IMAGE_FILE_TYPE="gif|jpg|jpeg|png|bmp";
        $uploader->allowed_type($IMAGE_FILE_TYPE); // 限制文件类型
        $uploader->allowed_size(2048000000); // 限制单个文件大小2M
        $uploader->addFile($_FILES['Filedata']);		 
        if (!$uploader->file_info()){
			//$uploader->get_error();
            $this->error('3423432');
            exit();
        }

        /* 指定保存位置的根目录 */
      
		$uploader->root_dir(SYSPATH);
        $dirname = 'Public/Upload';                
        $filename  = $uploader->random_filename();
        $file_path = $uploader->save($dirname, $filename);
        if (!$file_path){
            $this->error('file_save_error');
            exit();
        }
		// F('file',$file);
        $file_type = $this->_return_mimetype($file_path);
		//dump($this->visitor);
		//$nd=Mylib::img_process($file_path);
		//dump($nd);
        /* 文件入库 */
        $data = array(
            'userid'  =>1,
			'typeid' => 1,
            'file_original' => $filename,
			'file_type' =>  $file_type,
            'file_size' => $file['size'],
            'file_name' => $file['name'],
            'file_path' => $file_path, 		 
            'add_time'  => gmtime(),
        ); 

		/* 返回客户端 */
        $ret_info =array(
            'file_id'   => 1,
			
        );
		 
        $file_id = D('sysupload')->add($data);
		 
        if (!$file_id)
        {
            $this->error('file_add_error');
            return false;
        }

        /* 返回客户端 */
        $ret_info =array(
            'file_id'   => $file_id,			
			'file_name'=>$file['name'],
            'file_path' => $file_path
        );		
        $this->json_result($ret_info);
    }
	function showimg(){

	
		$image_id = isset($_GET["id"]) ? $_GET["id"] : false;

		$rs=D('sysupload')->find($image_id);
		 
		echo $rs['file_path'];
		exit(0);
	
	}


	function json_result ($retval = '', $msg = '', $jqremote = false){
        if (!empty($msg))
        {
            $msg = $msg;
        }
       // $this->json_header();
	   $j=new Genv_Json();
        $json = $j->encode(array('done' => true , 'msg' => $msg , 'retval' => $retval));
        if ($jqremote === false)
        {
            $jqremote = isset($_GET['jsoncallback']) ? trim($_GET['jsoncallback']) : false;
        }
        if ($jqremote)
        {
            $json = $jqremote . '(' . $json . ')';
        }

        echo $json;
    } 
	/* @return  void
     */
    function fferror ($msg='', $retval=null, $jqremote = false)
    {
        if (!empty($msg))
        {
            $msg = L($msg);
        }
        $result = array('done' => false , 'msg' => $msg);
        if (isset($retval)) $result['retval'] = $retval;

        $this->json_header();
		 $j=new Genv_Json();
        $json = $j->encode($result);
        if ($jqremote === false)
        {
            $jqremote = isset($_GET['jsoncallback']) ? trim($_GET['jsoncallback']) : false;
        }
        if ($jqremote)
        {
            $json = $jqremote . '(' . $json . ')';
        }

        echo $json;
    }

    /**
     * Send a Header
     *
     * @author weberliu
     *
     * @return  void
     */
    function json_header()
    {
        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
        header("Content-type:text/plain;charset=utf-8" , true);
    }
    function _return_mimetype($filename)
    {
        preg_match("|\.([a-z0-9]{2,4})$|i", $filename, $fileSuffix);
        switch(strtolower($fileSuffix[1]))
        {
            case "js" :
                return "application/x-javascript";

            case "json" :
                return "application/json";

            case "jpg" :
            case "jpeg" :
            case "jpe" :
                return "image/jpeg";

            case "png" :
            case "gif" :
            case "bmp" :
            case "tiff" :
                return "image/".strtolower($fileSuffix[1]);

            case "css" :
                return "text/css";

            case "xml" :
                return "application/xml";

            case "doc" :
            case "docx" :
                return "application/msword";

            case "xls" :
            case "xlt" :
            case "xlm" :
            case "xld" :
            case "xla" :
            case "xlc" :
            case "xlw" :
            case "xll" :
                return "application/vnd.ms-excel";

            case "ppt" :
            case "pps" :
                return "application/vnd.ms-powerpoint";

            case "rtf" :
                return "application/rtf";

            case "pdf" :
                return "application/pdf";

            case "html" :
            case "htm" :
            case "php" :
                return "text/html";

            case "txt" :
                return "text/plain";

            case "mpeg" :
            case "mpg" :
            case "mpe" :
                return "video/mpeg";

            case "mp3" :
                return "audio/mpeg3";

            case "wav" :
                return "audio/wav";

            case "aiff" :
            case "aif" :
                return "audio/aiff";

            case "avi" :
                return "video/msvideo";

            case "wmv" :
                return "video/x-ms-wmv";

            case "mov" :
                return "video/quicktime";

            case "rar" :
            return "application/x-rar-compressed";

            case "zip" :
                return "application/zip";

            case "tar" :
                return "application/x-tar";

            case "swf" :
                return "application/x-shockwave-flash";

            default :
            if(function_exists("mime_content_type"))
            {
                $fileSuffix = mime_content_type($filename);
            }
            return "unknown/" . trim($fileSuffix[0], ".");
        }
    }
}	 
?>