<?php
/*
框架核心;
*/
//框架路径;

error_reporting(0);
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
error_reporting(0);
Genv::client("Admin");

Genv::start();

/*
G('asdf.ddd','3333');
G('homepage',startfile());
dump(G());

echo PHP_FILE;
*/
 G('autodisplay',true);//自动加载模板；
$front=Genv_Registry::get('front');
//dump($front);
$str=$front->fetch(); 

 
if(G('autodisplay')){
echo $str;
}


// $uri = Genv::factory('Genv_Uri');
//echo $uri->get();
// dump($uri);
//$rewrite = Genv_Registry::get('rewrite');
//$rewrite->setRule('blog/{:id}/edit', 'blog/edit/$1');
  
  // longhand form is 'rule-name', and an array of information.
  // this is what lets you generate named actions using getPath().
 /* $rewrite->setRule('blog-edit', array(
	  // the pattern to match
	  'pattern' => 'blog/{:id}/edit',
	  // rewrite to this
	  'rewrite' => 'blog/edit/$1',
	  // custom replacement tokens just for this rule
	  'replace' => array(
		  '{:id}' => '(\d*)'
	  ),
  ));
 
*/


Genv::stop();

