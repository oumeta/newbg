<?php
$config['Genv']['ini_set'] = array(
       'error_reporting'   => (E_ERROR | E_WARNING | E_PARSE),//(E_ALL | E_STRICT);
	//'error_reporting'   => (E_ALL | E_STRICT),
    'display_errors'    => true,
    'html_errors'       => true,
    'session.save_path' => $config['Genv']['appdir']."/Temp/Session",
     'date.timezone'     => 'Asia/Shanghai',//UTC',
);

$config['Genv']['defines'] = array(
    'IN_GENV'   => true, 
	'tpldir'            =>$config['Genv']['appdir']."/View",
	'tplcachedir'       =>$config['Genv']['appdir']."/Data/View",
	  /* URL设置 */
	'URL_CASE_INSENSITIVE'  => false,   // URL地址是否不区分大小写
    'URL_ROUTER_ON'         => false,   // 是否开启URL路由
    'URL_DISPATCH_ON'       => false,	// 是否启用Dispatcher
    'URL_MODEL'      => 1,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式) 当URL_DISPATCH_ON开启后有效; 默认为PATHINFO 模式，提供最好的用户体验和SEO支持
    'URL_PATHINFO_MODEL'    => 2,       // PATHINFO 模式,使用数字1、2、3代表以下三种模式:
    // 1 普通模式(参数没有顺序,例如/m/module/a/action/id/1);
    // 2 智能模式(系统默认使用的模式，可自动识别模块和操作/module/action/id/1/ 或者 /module,action,id,1/...);
    // 3 兼容模式(通过一个GET变量将PATHINFO传递给dispather，默认为s index.php?s=/module/action/id/1)
    'URL_PATHINFO_DEPR'     => '/',	// PATHINFO模式下，各参数之间的分割符号
    'URL_HTML_SUFFIX'       => '.html',  // URL伪静态后缀设置
    
);
/*
	define('__ROOT__',WEBURL);
		define('__APPDIR__',WEBURL.'/'.APPNAME);
		// echo APPPATH;
		//当前项目地址       
		define('__APP__',PHP_FILE);

		//echo PHP_FILE;
		//当前页面地址
		define('__SELF__',$_SERVER['PHP_SELF']);  
		define('APPPUBLIC',__APPDIR__.'/Public/');

		define('WEB_PUBLIC_URL',__ROOT__.'/Public/');
*/
$config['App'] = array(
    'action'			=> 'Action',
	'auth_key'=>'www.zheli.com',
    
);

$config['Genv']['registry_set'] = array(
    'db'              => 'Genv_Db',
    'query'             => 'Genv_Query',  
	'sql'              => 'Genv_Sql',
	'front'     =>'Genv_Action',   
);



$path = $_SERVER['REQUEST_URI'];
$config['Genv']['home_page']=basename($_SERVER['SCRIPT_NAME']);

 
$pos = strpos($path, "/".$config['Genv']['home_page']);
if ($pos !== false) {
    // strip "/index.php" and everything after it
    $path = substr($path, 0, $pos);
}
$path = rtrim($path, '/');

$config['Genv_Uri_Action']['path'] = "$path/".$config['Genv']['home_page'];

$rule = array(
    'pattern' => 'blog/{:id}/edit', // the URI pattern to match
    'rewrite' => 'blog/edit/$1',    // rewrite the URI like this
    'replace' => array(             // custom replacement tokens
        '{:id}' => '(\d+)',
    ),
    'default' => array(
        'id' => '88',
    ),
);

$config['Genv_Action'] = array(
    'classes' => array($config['Genv']['appname'].'_App'),
    'disable' => array(),
    'default' => 'Default',
    //'rewrite' => array('blog-edit'=>$rule),
    'routing' => array(),
    'explain' => true,
	'replace' => array(),
	'global' =>array('Global'),
);

$config['Genv_Db']['Dev1']=array(
    'type'=>'Mysql',
    'host' => 'bdm-007.hichina.com', // the database server host
	'port' =>'3306',
    'name' => 'bdm0070130_db',  // the database name
    'user' => 'bdm0070130',  // authenticate as this user
    'pass' => 'p7t5y4d6e4',  // authenticate with this password
    'charset' => 'utf8',
	'prefix' => 'jp_',
);

$config['Genv_Db']['Dev']=array(
	'type'=>'Mysql',
	'host' => 'mysql5', // the database server host
	'port' =>'3306',
	'name' => 'levenbg',  // the database name
	'user' => 'root',  // authenticate as this user
	'pass' => '56os_com',  // authenticate with this password
	'charset' => 'utf8',
	'prefix' => 'jp_',
);
$config['Genv_Sql']['adapter'] = 'Genv_Sql_Adapter_Mysql';

$config['Genv_Sql_Adapter_Mysql'] =$config['Genv_Db']['Dev'];

/*array(
    'host' => 'www.zheli.com', // the database server host
	'port' =>'3306',
    'name' => 'zhelidbtest',  // the database name
    'user' => 'dede',  // authenticate as this user
    'pass' => '56os.com',  // authenticate with this password
    'charset' => 'utf8',
);*/
/*
$config['Genv_Db']['Test1']=array(
    'type'=>'Mysql',
    'host' => 'localhost', // the database server host
	'port' =>'3306',
    'name' => 'genv',  // the database name
    'user' => 'root',  // authenticate as this user
    'pass' => 'root',  // authenticate with this password
    'charset' => 'utf8',
	'prefix' => 'jp_',
);
$config['Genv_Db']['Test']=array(
    'type'=>'Mysql',
    'host' => 'localhost', // the database server host
	'port' =>'3306',
    'name' => 'zhelidb',  // the database name
    'user' => 'root',  // authenticate as this user
    'pass' => 'root',  // authenticate with this password
    'charset' => 'utf8',
	'prefix' => 'g_',
);
$config['Genv_Db']['Cat']=array(
    'type'=>'Mysql',
    'host' => 'localhost', // the database server host
	'port' =>'3306',
    'name' => 'zhelidbtest',  // the database name
    'user' => 'root',  // authenticate as this user
    'pass' => 'root',  // authenticate with this password
    'charset' => 'utf8',
	'prefix' => 'g_',
);
$config['Genv_Db']['Dd']=array(
    'type'=>'Mysql',
     'host' => 'www.zheli.com', // the database server host
	'port' =>'3306',
    'name' => 'zhelidbtest',  // the database name
    'user' => 'dede',  // authenticate as this user
    'pass' => '56os.com',  // authenticate with this password
    'charset' => 'utf8',
	'prefix' => 'g_',
);*/

$config['Genv_Cache']['adapter'] = 'Genv_Cache_Adapter_File';
$config['Genv_Cache_Adapter_File'] = array(
    'path' => $config['Genv']['appname'].'/Cache/', // the database server host
   
);

/*
$config['Genv_Cache_Adapter_Memcache'] = array(
    'port' => '123456', // the database server host
   
);*/
//启动的时候回调;
// $config['Genv']['start'] = array(array("Genv_Ok","abc",333));


/*
邮件发送配置;
*/
$config['Genv_Mail_Transport_Adapter_Smtp'] = array(
            'smtp' => array(
               'adapter'  => 'Genv_Smtp_Adapter_PlainAuth',
               'username' => 'cs@zheli.com',
               'password' => 'zheli1234!',
               'host'     => 'smtp.gmail.com',
               'port'     => '25',
                ),
 );



 /*
 cs@zheli.com
 zheli1234!

*/

/*
$config['Genv_Sql']['adapter'] = 'Genv_Sql_Adapter_Mysql';

$config['Genv_Sql_Adapter_Mysql'] = array(
    'host' => 'localhost', // the database server host
	'port' =>'3306',
    'name' => 'zhelidb',  // the database name
    'user' => 'root',  // authenticate as this user
    'pass' => 'root',  // authenticate with this password
    'charset' => 'utf8',
);*/
 ?>
