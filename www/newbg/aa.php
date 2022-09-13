<?php
/*
框架核心;
*/
//框架路径;


header("Cache-Control: public");
  header("Pragma: cache");

  $offset = 1000*60*60*24; // cache 1 year
  $ExpStr = "Expires: ".gmdate("D, d M Y H:i:s", time() + $offset)." GMT";
  header($ExpStr);

define("SYSPATH", str_replace("\\", "/", dirname(__FILE__)));




//应用目录; 
define("SYSPATH", str_replace("\\", "/", dirname(__FILE__)));

define("GenvPath", SYSPATH."/Genv/");
 
 
require GenvPath.'Genv.php';
 
Genv::client("Admin");

Genv::start();

$rs=D('sysmenu')->findall();
dump($rs);

Genv::stop();

  