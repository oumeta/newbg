<?php

define('ABSPATH', dirname(__FILE__).'/');
 
$cache = false;//Gzipѹ
$cachedir = 'zipcache/';//gzļĿ¼ȷд

$gzip = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');
$deflate = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate');
$encoding = $gzip ? 'gzip' : ($deflate ? 'deflate' : 'none');

if(!isset($_SERVER['QUERY_STRING'])) exit();

$key=array_shift(explode('?', $_SERVER['QUERY_STRING']));
$key=str_replace('../','',$key);

$filename=ABSPATH.$key;

$symbol='^';

$rel_path=str_replace(ABSPATH,'',dirname($filename));
$namespace=str_replace('/',$symbol,$rel_path);

$cache_filename=ABSPATH.$cachedir.$namespace.$symbol.basename($filename).'.gz';//gzļ·


echo $cache_filename;
$type="Content-type: text/html"; //ĬϵϢ

$ext = array_pop(explode('.', $filename));//ݺ׺жļϢ
switch ($ext){
   case 'css':
   $type="Content-type: text/css";
   break;
   case 'js':
   $type="Content-type: text/javascript";
   break;
   default:
   exit();
}

if($cache){
if(file_exists($cache_filename)){//gzļ


   $mtime = filemtime($cache_filename);
   $gmt_mtime = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';

   if( (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) &&
         array_shift(explode(';', $_SERVER['HTTP_IF_MODIFIED_SINCE'])) == $gmt_mtime)
    ){

    // cacheеļ޸Ƿһ£304
    header ("HTTP/1.1 304 Not Modified");
    header("Expires: ");
    header("Cache-Control: ");
    header("Pragma: ");
    header($type);
    header("Tips: Cache Not Modified (Gzip)");
    header ('Content-Length: 0');


   }else{

    //ȡgzļ
    $content = file_get_contents($cache_filename);
    header("Last-Modified:" . $gmt_mtime);
    header("Expires: ");
    header("Cache-Control: ");
    header("Pragma: ");
    header($type);
    header("Tips: Normal Respond (Gzip)");
    header("Content-Encoding: gzip");
    echo $content;
   }


}else if(file_exists($filename)){ //ûжӦgzļ

   $mtime = mktime();
   $gmt_mtime = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';

   $content = file_get_contents($filename);//ȡļ
   $content = gzencode($content, 9, $gzip ? FORCE_GZIP : FORCE_DEFLATE);//ѹļ

   header("Last-Modified:" . $gmt_mtime);
   header("Expires: ");
   header("Cache-Control: ");
   header("Pragma: ");
   header($type);
   header("Tips: Build Gzip File (Gzip)");
   header ("Content-Encoding: " . $encoding);
        header ('Content-Length: ' . strlen($content));
   echo $content;

   if ($fp = fopen($cache_filename, 'w')) {//дgzļ´ʹ
                fwrite($fp, $content);
                fclose($fp);
            }

}else{
   header("HTTP/1.0 404 Not Found");
}
}else{ //���ʹGzipģʽµԭͬ
if(file_exists($filename)){
   $mtime = filemtime($filename);
   $gmt_mtime = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';

   if( (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) &&
   array_shift(explode(';', $_SERVER['HTTP_IF_MODIFIED_SINCE'])) == $gmt_mtime)
   ){

   header ("HTTP/1.1 304 Not Modified");
   header("Expires: ");
   header("Cache-Control: ");
   header("Pragma: ");
   header($type);
   header("Tips: Cache Not Modified");
   header ('Content-Length: 0');

}else{

   header("Last-Modified:" . $gmt_mtime);
   header("Expires: ");
   header("Cache-Control: ");
   header("Pragma: ");
   header($type);
   header("Tips: Normal Respond");
   $content = readfile($filename);
   echo $content;

   }
}else{
   header("HTTP/1.0 404 Not Found");
}
}

?>
