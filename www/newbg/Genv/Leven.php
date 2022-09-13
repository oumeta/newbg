<?php
/*

╪стьнд╪Ч;
*/
class Genv_Leven extends Genv_Base{
  
    protected static $_file;    
   
    protected function _postConstruct()
    {
        parent::_postConstruct();
       // $config=$this->_config;

		//dump($config);
		//$config=Genv_File::load("config.php");
		//dump($config);
    }
	public function preview(){
	
		 $a=array("_public"=>"http://www.zheli.com/test/Public/",
				'_url'=>__URL__,
				'_action'=>__ACTION__,
				'_self'=>__SELF__,
				'_app'=>__APP__,
				'random_num'=>rand(),
				'mid'=>getgpc('mid'),
				'WEB_PUBLIC_URL'=>WEB_PUBLIC_URL,
				//"pageinfo"=>$this->pageinfo(),
				);
		  $view=Genv::factory('Genv_View');

		  dump($a);
		  $view->assign($a);
	
	}
	public static function abc($a){
	 dump($a);
	
	}
    
}