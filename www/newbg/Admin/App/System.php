<?php
$_CFG=array();

class SystemAction extends GlobalAction{
	#系统配置
    public function _postConstruct(){   
			parent::_postConstruct();
			$this->CheckPower('index');
	}	
	//商城配置信息;
    public function index()	{
		#系统配置
		$menus = array (
			array('基本设置'),
			array('系统设置')			
		);
		
		 
		$this->show_menu($menus);	
	 	$setting = FC('system_config');
		 
		foreach(dhtmlspecialchars($setting) as $key=>$v){	
			
			$this->assign($key,$v);
		}

		 
		//$this->assign('ROOTPATH',ROOTPATH);
		//$this->pageinfo();		
		//$this->display();	
	}	
	 
	public function save(){
	   
        $abc=getgpc('rs'); 
		//dump($abc);
		 
		 
		D()->query('TRUNCATE TABLE '.gettable("sysoptions").'');			
		foreach($abc as $k=>$v)	{
			$sql = 'INSERT INTO ' . gettable('sysoptions') . '( code, value)' .
				   " VALUES('$k', '$v')";
			 //dump($v);
			D()->query($sql);	
			
		}	
		//exit;
		 D()->showsql();
		//admin_log('更新商城配置', 'add', 'shopconfig');
		 /* 清除缓存 */	
			 
		FC();
		 U('index','',true);
		$this->success('更新成功');	
	}
	
	
    
}
?>