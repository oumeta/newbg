<?php
/*
��������ļ�;
��ʱ���ļ���û���õ�;
*/
!defined('IN_GENV') && die('Forbidden!');
return array( 
	   /* ϵͳ���� */
	'AUTOROUTE'=>true,  //�Զ�·��;
	'CODETABLE'=>MDL.'/Lib/codetable/', //�������λ��;

    'APPCONF'  => APPPATH.'/Conf/', //�����ļ�·��		
	
	'APPCACHE'=>APPPATH."/Cache/", //����·��;

	//'SQLCACHE'=>APPPATH."/Data/Sqlcache/", //����·��;
	
	'APPLOGS'=>APPPATH.'/Logs/',  //ģ�建���ļ���;

	'SQLLOG' =>APPPATH.'/DATA/Logs/',//���ݿ���־;
	'APPTPL'=>APPPATH."/View/",    //��ͼģ��;
	'APPTPLCACHE'=>APPPATH."/Data/View/", //��ͼ����·��;
	'LANGUAGE'=>APPPATH."/Language/", //��ͼ����·��;

	'CACHETYPE'=>'File',    //Ĭ�ϻ��淽ʽ;	

	'APPLIB' => APPPATH.'/Lib/',
	'APPMODEL' => APPPATH.'/Model/', //ģ������·��;	
	
	'APPDATA'=>APPPATH.'/Data/',

    'DEFAULT_MODEL_APP'     =>   '@',   // Ĭ��ģ�������ڵ���Ŀ����
	'AEXT'=>'Action',                //ģ��ʶ���ʶ;
	'MEXT'=>'Model',                  //����ģ��ʶ���ʶ;
	'EXT' => '.php',                 //���ļ���׺��ʶ;
	'VEXT'=>'.html',				 //ģ���׺��;

	//'TEMPLATE_SUFFIX'			=>	'.html',	 // Ĭ��ģ���ļ���׺

    'CACHFILE_SUFFIX'			=>	'.php',	// Ĭ��ģ�建���׺

	'VAR_AJAX_SUBMIT'			=>	'is_ajax', // Ĭ�ϵ�AJAX�ύ����	
	'VAR_PAGE'					=>'p',//ҳ����;
	
	'AUTO_DETECT_THEME'=>true,//�Զ����ģ��;

    /*��������*/

	'DB_SQLCACHE'=>TRUE,//��ѯ����Զ��ļ�����;.

	'DB_FIELDTYPE_CHECK'=>true,

  /* URL���� */
	'URL_CASE_INSENSITIVE'  => false,   // URL��ַ�Ƿ����ִ�Сд
    'URL_ROUTER_ON'         => false,   // �Ƿ���URL·��
    'URL_DISPATCH_ON'       => false,	// �Ƿ�����Dispatcher
    'URL_MODEL'      => 1,       // URL����ģʽ,��ѡ����0��1��2��3,������������ģʽ��
    // 0 (��ͨģʽ); 1 (PATHINFO ģʽ); 2 (REWRITE  ģʽ); 3 (����ģʽ) ��URL_DISPATCH_ON��������Ч; Ĭ��ΪPATHINFO ģʽ���ṩ��õ��û������SEO֧��
    'URL_PATHINFO_MODEL'    => 2,       // PATHINFO ģʽ,ʹ������1��2��3������������ģʽ:
    // 1 ��ͨģʽ(����û��˳��,����/m/module/a/action/id/1);
    // 2 ����ģʽ(ϵͳĬ��ʹ�õ�ģʽ�����Զ�ʶ��ģ��Ͳ���/module/action/id/1/ ���� /module,action,id,1/...);
    // 3 ����ģʽ(ͨ��һ��GET������PATHINFO���ݸ�dispather��Ĭ��Ϊs index.php?s=/module/action/id/1)
    'URL_PATHINFO_DEPR'     => '/',	// PATHINFOģʽ�£�������֮��ķָ����
    'URL_HTML_SUFFIX'       => '.html',  // URLα��̬��׺����

 
);
?>