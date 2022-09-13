<?php

// ���� �����ļ�;
function C($key=NULL, $value=NULL){
	static $_config = array();
	//���������,д����������,��ȫ��ĸ��д����ʽ����;
	if(is_array($key)){
		return $_config = array_merge($_config, array_change_key_case($key,CASE_UPPER));
	} 
	$key = strtoupper($key);	
	if(!is_null($value)) { 	return $_config[$key] = $value;}
	if(empty($key)) { return $_config;}	
	return isset($_config[$key]) ? $_config[$key] : NULL;
}

function V($k=nll,$v=null){
	$args = func_get_args();	 
	static $view;
	  
    $view=	Genv::factory('Genv_View');
	 
	if (is_array($k)){
		$args  = func_get_args();		
		foreach ($args as $arg){
			foreach ($arg as $key => $value){
				$view->assign($key, $value);
			}
		}
	}else{
		$view->assign($k, $v);
	}
	 
	return $view;

}
//db ��
function D($table=null,$model='Dev'){	 
    static $_model = array();
	
    if(empty($table)) {
         return Genv::factory('Genv_Model',array('table'=>'','model'=>$model));//new Genv_Model('',$model);
    }   
    if(isset($_model[$table])) {
        return $_model[$table];
    } 
	 
	$model =$_model[$table]=   Genv::factory('Genv_Model',array('table'=>$table,'model'=>$model));//new Genv_Model('',$model); new Genv_Model($table,$model);

	return $model;
     
} 
 //
function F($name=null,$value=''){	 
	
    static $_cache = array();
	$cache = Genv::factory('Genv_Cache');
	 
	if(empty($name)|| is_null($name) ){
	    //try{
		  $cache->deleteAll();//��ջ��棻	
		//}catch(Exception $e){};
		return ;
	}
	
	if(is_array($name)){
	     foreach($key as $k=>$v){
			$cache->save($k,$v);		 
		 }	
	}
	if(isset($_cache[$name])){ // ��̬����
		
		return $_cache[$name];
	}
	if($value !==''){
		 
		if(is_null($value)){ // ɾ������
			 
			$cache->delete($name);	
			
		}else{
			$cache->save($name,$value);			
		}		
	}else{	
		 
		$data=$cache->fetch($name);
		return $data;
	}
	//return $data;
    
     
} 

// ͨ�ÿ����ļ�����
function FC($name=null, $value='', $expire=-1, $p='/Data/Cache/'){
	// $value  '':��ȡ null:��� data:��ֵ
	static $_cache = array();

	$path =Genv_Config::get('Genv','appdir').$p;
	if( is_null($name) ){
		I("@.Lib.Dir"); 		 
        Dir::del($path);
		//$cache->deleteAll();//��ջ��棻	
	}
	$file = $path.$name.'.php'; 
 
	if($value !== ''){		
		if(is_null($value)){ // ɾ������
		 
			$result = @unlink($file);
			if($_cache[$name]){
				unset($_cache[$name]);
			}
			$result = null;			
		} else{ // ��������	
			$_cache[$name] = $value;
			$value = addslashes(serialize($value));				
			$content = "<?php\n!defined('IN_GENV') && die();\n//".sprintf('%012d',$expire)."\nreturn '$value';\n?>";
			$result  = file_write($file,$content);
			 
		}
		 
		return $result;
	}	
	if(isset($_cache[$name])){ // ��̬����
		return $_cache[$name];
	}
	if(is_file($file) && false !== $content = file_get_contents($file)){
		 
		$expire = substr($content, 38,12);	
	 
		// �������,ɾ���ļ�
		if($expire != -1 && time()>filemtime($file)+$expire){ 
			 @unlink($file);
			return false;
		}
		$value =unserialize(stripslashes(require $file));
		 
		$_cache[$name] = $value;
		return $value;
	} else{
		return false;
	}
}
  // ͨ�ÿ����ļ�����
function S($name=null, $value='', $expire=-1, $p='/Data/Cache/'){
	// $value  '':��ȡ null:��� data:��ֵ
	static $_cache = array();
	
 
	$path =SYSPATH."/Admin/Data/Cache/";
 
	 
	$file = $path.$name.'.php'; 
 
	 
 
	if($value !== ''){		
		if(is_null($value)){ // ɾ������
		 
			$result = @unlink($file);
			if($_cache[$name]){
				unset($_cache[$name]);
			}
			$result = null;			
		} else{ // ��������	
			$_cache[$name] = $value;
			$value = addslashes(serialize($value));				
			$content = "<?php\n!defined('IN_GENV') && die();\n//".sprintf('%012d',$expire)."\nreturn '$value';\n?>";
			$result  = file_write($file,$content);
			 
		}
		 
		 
		return $result;
	}	

	
	if(isset($_cache[$name])){ // ��̬����
		return $_cache[$name];
	}
 
	if(is_file($file) && false !== $content = file_get_contents($file)){
		 
		$expire = substr($content, 38,12);	
	 
		// �������,ɾ���ļ�
		if($expire != -1 && time()>filemtime($file)+$expire){ 
			 @unlink($file);
			return false;
		}
		$value =unserialize(stripslashes(require $file));
		 
		$_cache[$name] = $value;
		return $value;
	} else{
		return false;
	}
}

 function M($table=null,$config=null){	 
    static $_model = array();
	 $class="Client/Model/".$table.".php";
	 require_once $class;
	 $table.="Model";
	 $obj = new $table($config);     
      
     if ($obj instanceof Genv_Factory) {           
            return $obj->factory();
      }
		
    return $obj;
	
     
} 
function I($class,$return=false){	 
    if (isset($GLOBALS['included_files'][$class])){
        return true;
	}else{
        $GLOBALS['included_files'][$class] = true;
    }
	 //echo "----$class----";

    $classStrut = explode(".", $class);	
	
    if ("@" == $classStrut[0]){
        $class = str_replace("@", APPPATH, $class);		 
        $file =  str_replace(".", "/", $class) . EXT;
    }else if ("X" == $classStrut[0]){
        $class = str_replace("X", X, $class);		 
        $file =  str_replace(".", "/", $class) . EXT;
		//echo $file;
    }else if ("D" == array_shift($classStrut)){
        $class = implode("/", $classStrut);
        $file = DT .  ucfirst($class) . EXT;
    }else{ 		
        $file =   str_replace(".", "/", $class) . EXT;
	}
	    
		 
    if (!is_readable($file)){
		 
		 die($file.'û�ҵ�');
        return false;
    }
	 
   
	if($return){	 
		return require_once($file);  
	}else{
		require_once($file);  
		return true;
	}
    //return ;
} 
//��õ�ǰ����;
function gettable($a){
 $d= D($a);
 return $d->_prefix.$a;
 

}
 
// ��ȡ���Զ���
function L($key=NULL, $value=NULL){
	static $_lang = array();
	//���������,д����������,��ȫ��ĸ��д����ʽ����;
	if(is_array($key)){
		return $_lang = array_merge($_lang, $key);
	} 
	//array_change_key_case($key,CASE_UPPER)
	//$key = strtoupper($key);
	//$key = strtoupper($key);
	if(!is_null($value)) { return $_lang[$key] = $value;}
	if(empty($key)) { return $_lang;}
	return isset($_lang[$key]) ? $_lang[$key] : 0;
}



//����Ӧ�õ����԰�
function LL($file){
    //���԰�λ��;	 
	require_once(APPPATH."/Language/".$file.".php");	 
	L($lang);	
}

function U($url='',$params=array(),$redirect=false,$suffix=true) {
	//$params=array_merge($params,array('menuid'=>getgpc('menuid','G')));
	if(empty($params['mid'])){
	      $params['mid']=getgpc('mid','G');
	}
	 
	$appname=Genv_Config::get("Genv","appname");
	if($url===''){
	    $url=G('APP')."/".G('ACT');
	
	}
    if(0===strpos($url,'/')) {
        $url   =  substr($url,1);
    }
    if(!strpos($url,'://')) {// û��ָ����Ŀ�� ʹ�õ�ǰ��Ŀ��
        $url   = $appname.'://'.$url;
    }
    if(stripos($url,'@?')) { // ��·�ɴ��ݲ���
        $url   =  str_replace('@?','@think?',$url);
    }elseif(stripos($url,'@')) { // û�в�����·��
        $url   =  $url.G('APP');
    }
	
	 
    // ����URL��ַ
    $array   =  parse_url($url);
 
	 
	  
    $app      =  isset($array['scheme'])?   $array['scheme']  :$appname;
    $route    =  isset($array['user'])?$array['user']:'';
    if(isset($array['path'])) {
        $action  =  substr($array['path'],1);
        if(!isset($array['host'])) {
            // û��ָ��ģ����
            $module = G('APP');
        }else{// ָ��ģ��
            $module = $array['host'];
        }
    }else{ // ָֻ������
        $module =  G('APP');
        $action   =  $array['host'];
    }
	//echo $module."<br>";

    if(isset($array['query'])) {
        parse_str($array['query'],$query);
        $params = array_merge($query,$params);
    }
    if(URL_DISPATCH_ON && URL_MODEL>0) {
        $depr = URL_PATHINFO_MODEL==2?URL_PATHINFO_DEPR:'/';
        $str    =   $depr;
        foreach ($params as $var=>$val)
            $str .= $var.$depr.$val.$depr;
        $str = substr($str,0,-1);
        //$group   = isset($group)?$group.$depr:'';
        if(!empty($route)) {
            $url    =   str_replace($appname,$app,__APP__).'/'.$group.$route.$str;
        }else{
            $url    =   str_replace($appname,$app,__APP__).'/'.$module.$depr.$action.$str;
        }
        if($suffix && URL_HTML_SUFFIX)
            $url .= URL_HTML_SUFFIX;
		  // $url=str_replace(G('HOMEPAGE').'/','',$url);
    }else{
		 
        $params =   http_build_query($params);
		 //echo APP_NAME;
		 //echo $app;
        $url    =   str_replace($appname,$app,__APP__).'/'.$module.'/'.$action.'/?'.$params;

        //$url    = getstartfile().'?'.C('VAR_APP').'='.$module.'&'.C('VAR_ACT').'='.$action.'&'.$params;
    }
	// echo $url."<br>";
	//dump(G());
	//$url=str_replace(G('HOMEPAGE').'/','',$url).C('HTML_URL_SUFFIX');
    //$url .= C('HTML_URL_SUFFIX');

    if($redirect) {		
		 
        redirect($url);
    }else{
        return $url;
    }
}
 function redirect($location, $exit=true, $code=302, $headerBefore=NULL, $headerAfter=NULL){
        if($headerBefore!=NULL){
            for($i=0;$i<sizeof($headerBefore);$i++){
                header($headerBefore[$i]);
            }
        }
        header("Location: $location", true, $code);
        if($headerAfter!=NULL){
            for($i=0;$i<sizeof($headerBefore);$i++){
                header($headerBefore[$i]);
            }
        }
        if($exit)
            exit;
 }
function escape($str){

$res = @unpack("H*",iconv("utf-8","UCS-2",$str));
if (!eregi("WIN",PHP_OS)){
	preg_match_all("/(.{4})/is", $res[1],$res);
   $ret='';
   foreach($res[0] as $key=>$v){

    $tmpString=substr($v,2,2).substr($v,0,2);
   $ret.="%u".$tmpString;
   }
   
}else{
$ret = preg_replace("/(.{4})/is","%u\\1",$res[1]);
}

return $ret;
}
  
function unescape($str) { 
         $str = rawurldecode($str); 
         preg_match_all("/%u.{4}|&#x.{4};|&#\d+;|.+/U",$str,$r); 
         $ar = $r[0]; 
         foreach($ar as $k=>$v) { 
                  if(substr($v,0,2) == "%u") 
 $ar[$k] =!eregi("WIN",PHP_OS)?iconv("UCS-2","utf8",strrev(pack("H4",substr($v,-4)))):iconv("UCS-2","gb2312",pack("H4",substr($v,-4))); 
                  elseif(substr($v,0,3) == "&#x") 
                           $ar[$k] = iconv("UCS-2","utf8",pack("H4",substr($v,3,-1))); 
                  elseif(substr($v,0,2) == "&#") { 
                           $ar[$k] = iconv("UCS-2","utf8",pack("n",substr($v,2,-1))); 
                  } 
         } 
         return join("",$ar); 
} 
/**
 * ��ȡ�ļ���չ��
 * @param <type> $filename
 * @return <type>
 */
function getFileExt($filename)
{
    $ext = strrchr($filename,'.');
    // ����û����չ��
    if ( empty($ext) )
    {
        return null;
    }
    return $ext;
}
/*
��ȡ�ļ�;
*/
function startfile(){
		//$url = $_SERVER['PHP_SELF']; 
		//$filename = end(explode('/',$url)); 
		$a=$_SERVER['SCRIPT_NAME'];
		 //echo basename($a)
		return  basename($a);
}
 
/**
 * ��ȡ�����ļ���
 * @param <type> $filename
 * @return <type>
 */
function getFilename($filename){
    return str_replace(getFileExt($filename),'', $filename);
}

function getFileBasename($filename){
    $filename = str_replace('\\', '/', $filename);
    $filename = strrchr($filename, '/');
    $filename = str_replace(getFileExt($filename),'', $filename);
    return str_replace('/', '', $filename);
}

/*ȫ�ֱ����趨�ͻ�ȡ*/

function G($key=null, $val = null){
	$key = strtoupper($key);
    $vkey = $key ? strtokey("{$key}", '$GLOBALS[\'MDL_\']') : '$GLOBALS[\'MDL_\']';


	 
    if ($val === null) {
        /* ���ظ�ָ���������� */
        $v = eval('return ' . $vkey . ';');
        return $v;
    }else{
        /* ����ָ���������� */
        eval($vkey . ' = $val;');
        return $val;
    }
}

/**
 *    ��default.abc����ַ���תΪ$default['abc']
 *    @param     string $str
 *    @return    string
 */
function strtokey($str, $owner = ''){
    if (!$str){
        return '';
    }
    if ($owner){
        return $owner . '[\'' . str_replace('.', '\'][\'', $str) . '\']';
    }else{
        $parts = explode('.', $str);
        $owner = '$' . $parts[0];
        unset($parts[0]);
        return strtokey(implode('.', $parts), $owner);
    }
}
function file_write($f, $c=''){
	$dir = dirname($f);
	if(!is_dir($dir)){
		dirs_mk($dir);
	}
	return @file_put_contents($f, $c);
}

//
function file_read($f){
	return @file_get_contents($f);
}
/*
����Ŀ¼;�ɶ༶;
*/
function dirs_mk($l1, $l2 = 0777){
	if(!is_dir($l1)){
		dirs_mk(dirname($l1), $l2);		
		return @mkdir($l1, $l2);
	}
	return true;
}

 function dump($str){
	 echo Genv::dump($str);
 
 }

 function abc($a){
 dump($a);
 
 }


function getgpc($k) {
	$var=array_merge($_GET,$_POST);
	/*switch($t) {
		case 'P': $var = &$_POST; break;
		case 'G': $var = &$_GET; break;
		case 'C': $var = &$_COOKIE; break;
		case 'R': $var = &$_REQUEST; break;
	}*/
	return isset($var[$k]) ? (is_array($var[$k]) ? $var[$k] : trim($var[$k])) : NULL;
}
function getgpc2($k, $t='R') {
	switch($t) {
		case 'P': $var = &$_POST; break;
		case 'G': $var = &$_GET; break;
		case 'C': $var = &$_COOKIE; break;
		case 'R': $var = &$_REQUEST; break;
	}
	return isset($var[$k]) ? (is_array($var[$k]) ? $var[$k] : trim($var[$k])) : NULL;
}
 

/**
 * ��õ�ǰ��������ʱ���ʱ���
 *
 * @return  integer
 */
function gmtime(){
    return (time() - date('Z'));
}

/**
 * ��GMTʱ�����ʽ��Ϊ�û��Զ���ʱ������
 *
 * @param  string       $format
 * @param  integer      $time       �ò���������һ��GMT��ʱ���
 *
 * @return  string
 */

 
function check_file_type($filename, $realname = '', $limit_ext_types = '')
{
    if ($realname)
    {
        $extname = strtolower(substr($realname, strrpos($realname, '.') + 1));
    }
    else
    {
        $extname = strtolower(substr($filename, strrpos($filename, '.') + 1));
    }

    if ($limit_ext_types && stristr($limit_ext_types, '|' . $extname . '|') === false)
    {
        return '';
    }

    $str = $format = '';

    $file = @fopen($filename, 'rb');
    if ($file)
    {
        $str = @fread($file, 0x400); // ��ȡǰ 1024 ���ֽ�
        @fclose($file);
    }
    else
    {
        if (stristr($filename, ROOTPATH) === false)
        {
            if ($extname == 'jpg' || $extname == 'jpeg' || $extname == 'gif' || $extname == 'png' || $extname == 'doc' ||
                $extname == 'xls' || $extname == 'txt'  || $extname == 'zip' || $extname == 'rar' || $extname == 'ppt' ||
                $extname == 'pdf' || $extname == 'rm'   || $extname == 'mid' || $extname == 'wav' || $extname == 'bmp' ||
                $extname == 'swf' || $extname == 'chm'  || $extname == 'sql' || $extname == 'cert')
            {
                $format = $extname;
            }
        }
        else
        {
            return '';
        }
    }

    if ($format == '' && strlen($str) >= 2 )
    {
        if (substr($str, 0, 4) == 'MThd' && $extname != 'txt')
        {
            $format = 'mid';
        }
        elseif (substr($str, 0, 4) == 'RIFF' && $extname == 'wav')
        {
            $format = 'wav';
        }
        elseif (substr($str ,0, 3) == "\xFF\xD8\xFF")
        {
            $format = 'jpg';
        }
        elseif (substr($str ,0, 4) == 'GIF8' && $extname != 'txt')
        {
            $format = 'gif';
        }
        elseif (substr($str ,0, 8) == "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A")
        {
            $format = 'png';
        }
        elseif (substr($str ,0, 2) == 'BM' && $extname != 'txt')
        {
            $format = 'bmp';
        }
        elseif ((substr($str ,0, 3) == 'CWS' || substr($str ,0, 3) == 'FWS') && $extname != 'txt')
        {
            $format = 'swf';
        }
        elseif (substr($str ,0, 4) == "\xD0\xCF\x11\xE0")
        {   // D0CF11E == DOCFILE == Microsoft Office Document
            if (substr($str,0x200,4) == "\xEC\xA5\xC1\x00" || $extname == 'doc')
            {
                $format = 'doc';
            }
            elseif (substr($str,0x200,2) == "\x09\x08" || $extname == 'xls')
            {
                $format = 'xls';
            } elseif (substr($str,0x200,4) == "\xFD\xFF\xFF\xFF" || $extname == 'ppt')
            {
                $format = 'ppt';
            }
        } elseif (substr($str ,0, 4) == "PK\x03\x04")
        {
            $format = 'zip';
        } elseif (substr($str ,0, 4) == 'Rar!' && $extname != 'txt')
        {
            $format = 'rar';
        } elseif (substr($str ,0, 4) == "\x25PDF")
        {
            $format = 'pdf';
        } elseif (substr($str ,0, 3) == "\x30\x82\x0A")
        {
            $format = 'cert';
        } elseif (substr($str ,0, 4) == 'ITSF' && $extname != 'txt')
        {
            $format = 'chm';
        } elseif (substr($str ,0, 4) == "\x2ERMF")
        {
            $format = 'rm';
        } elseif ($extname == 'sql')
        {
            $format = 'sql';
        } elseif ($extname == 'txt')
        {
            $format = 'txt';
        }
    }

    if ($limit_ext_types && stristr($limit_ext_types, '|' . $format . '|') === false)
    {
        $format = '';
    }

    return $format;
}
 /**
 * ��÷�������ʱ��
 *
 * @return  integer
 */
function server_timezone()
{
    if (function_exists('date_default_timezone_get'))
    {
		
        return date_default_timezone_get();
    }
    else
    {
        return date('Z') / 3600;
    }
}

G('timezone',0);
/**
 *  ����һ���û��Զ���ʱ�����ڵ�GMTʱ���
 *
 * @access  public
 * @param   int     $hour
 * @param   int     $minute
 * @param   int     $second
 * @param   int     $month
 * @param   int     $day
 * @param   int     $year
 *
 * @return void
 */
function local_mktime($hour = NULL , $minute= NULL, $second = NULL,  $month = NULL,  $day = NULL,  $year = NULL)
{
     $timezone = G('timezone');

    /**
    * $time = mktime($hour, $minute, $second, $month, $day, $year) - date('Z') + (date('Z') - $timezone * 3600)
    * ����mktime����ʱ������ټ�ȥdate('Z')ת��ΪGMTʱ�䣬Ȼ������Ϊ�û��Զ���ʱ�䡣�����ǻ������
    **/
    $time = mktime($hour, $minute, $second, $month, $day, $year) - $timezone * 3600;

    return $time;
}


/**
 * ��GMTʱ�����ʽ��Ϊ�û��Զ���ʱ������
 *
 * @param  string       $format
 * @param  integer      $time       �ò���������һ��GMT��ʱ���
 *
 * @return  string
 */

function local_date($format, $time = NULL)
{
      $timezone = G('timezone');

    if ($time === NULL)
    {
        $time = gmtime();
    }
    elseif ($time <= 0)
    {
        return '';
    }

    $time += ($timezone * 3600);

    return date($format, $time);
}


/**
 * ת���ַ�����ʽ��ʱ����ʽΪGMTʱ���
 *
 * @param   string  $str
 *
 * @return  integer
 */
function gmstr2time($str)
{
    $time = strtotime($str);

    if ($time > 0)
    {
        $time -= date('Z');
    }

    return $time;
}

/**
 *  ��һ���û��Զ���ʱ��������תΪGMTʱ���
 *
 * @access  public
 * @param   string      $str
 *
 * @return  integer
 */
function local_strtotime($str)
{
     $timezone = G('timezone');

	 
    /**
    * $time = mktime($hour, $minute, $second, $month, $day, $year) - date('Z') + (date('Z') - $timezone * 3600)
    * ����mktime����ʱ������ټ�ȥdate('Z')ת��ΪGMTʱ�䣬Ȼ������Ϊ�û��Զ���ʱ�䡣�����ǻ������
    **/
    $time = strtotime($str) - $timezone * 3600;

    return $time;

}

/**
 * ����û�����ʱ��ָ����ʱ���
 *
 * @param   $timestamp  integer     ��ʱ���������һ�����������ص�ʱ���
 *
 * @return  array
 */
function local_gettime($timestamp = NULL)
{
    $tmp = local_getdate($timestamp);
    return $tmp[0];
}

/**
 * ����û�����ʱ��ָ�������ں�ʱ����Ϣ
 *
 * @param   $timestamp  integer     ��ʱ���������һ�����������ص�ʱ���
 *
 * @return  array
 */
function local_getdate($timestamp = NULL)
{
    $timezone = G('timezone');

    /* ���ʱ���Ϊ�գ����÷������ĵ�ǰʱ�� */
    if ($timestamp === NULL)
    {
        $timestamp = time();
    }

    $gmt        = $timestamp - date('Z');       // �õ���ʱ��ĸ�������ʱ��
    $local_time = $gmt + ($timezone * 3600);    // ת��Ϊ�û�����ʱ����ʱ���

    return getdate($local_time);
}

// �Ż���require_once
function require_cache($filename) {
    static $_importFiles = array();
    $filename = realpath($filename);
    if (!isset($_importFiles[$filename])) {
        if (file_exists_case($filename)) {
            require $filename;
            $_importFiles[$filename] = true;
        } else {
            $_importFiles[$filename] = false;
        }
    }
    return $_importFiles[$filename];
}
// ���ִ�Сд���ļ������ж�
function file_exists_case($filename) {
    if (is_file($filename)) {
        if (IS_WIN ) {
            if (basename(realpath($filename)) != basename($filename))
                return false;
        }
        return true;
    }
    return false;
}


?>