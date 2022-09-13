<?php
/*
框架配置文件;
暂时此文件还没有用到;
*/
!defined('IN_GENV') && die('Forbidden!');
return array( 
	   /* 系统设置 */
	'AUTOROUTE'=>true,  //自动路由;
	'CODETABLE'=>MDL.'/Lib/codetable/', //编码表存放位置;

    'APPCONF'  => APPPATH.'/Conf/', //配置文件路径		
	
	'APPCACHE'=>APPPATH."/Cache/", //缓存路径;

	//'SQLCACHE'=>APPPATH."/Data/Sqlcache/", //缓存路径;
	
	'APPLOGS'=>APPPATH.'/Logs/',  //模板缓存文件夹;

	'SQLLOG' =>APPPATH.'/DATA/Logs/',//数据库日志;
	'APPTPL'=>APPPATH."/View/",    //视图模板;
	'APPTPLCACHE'=>APPPATH."/Data/View/", //视图缓存路径;
	'LANGUAGE'=>APPPATH."/Language/", //视图缓存路径;

	'CACHETYPE'=>'File',    //默认缓存方式;	

	'APPLIB' => APPPATH.'/Lib/',
	'APPMODEL' => APPPATH.'/Model/', //模型所在路径;	
	
	'APPDATA'=>APPPATH.'/Data/',

    'DEFAULT_MODEL_APP'     =>   '@',   // 默认模型类所在的项目名称
	'AEXT'=>'Action',                //模块识别标识;
	'MEXT'=>'Model',                  //数据模块识别标识;
	'EXT' => '.php',                 //库文件后缀标识;
	'VEXT'=>'.html',				 //模板后缀名;

	//'TEMPLATE_SUFFIX'			=>	'.html',	 // 默认模板文件后缀

    'CACHFILE_SUFFIX'			=>	'.php',	// 默认模板缓存后缀

	'VAR_AJAX_SUBMIT'			=>	'is_ajax', // 默认的AJAX提交变量	
	'VAR_PAGE'					=>'p',//页变量;
	
	'AUTO_DETECT_THEME'=>true,//自动检查模板;

    /*数据配置*/

	'DB_SQLCACHE'=>TRUE,//查询语句自动文件缓存;.

	'DB_FIELDTYPE_CHECK'=>true,

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
?>