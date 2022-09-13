<?php 
class CacheAction extends GlobalAction{
	#缓存管理 
	public function index(){
		#清空缓存
		$admin="manage";
		$manage=APPPATH;
		//$client=CLIENTPATH;
		 
		$this->assign("cache",array(			
			"$manage(后台)"=>array("静态数据缓存(Cache)"=>"$manage/Cache","日志目录(Logs)"=>"$manage/Data/Logs","模板缓存(View)"=>"$manage/Data/View","sql缓存(Sqlcache)"=>"$manage/Data/Sqlcache"),
		
			//"$client(前台)"=>array("数据缓存(Cache)"=>"$client/Cache","日志目录(Logs)"=>"$client/Data/Logs","模板缓存(Viewc)"=>"$client/Data/View")			
		));
		 
		//V()->display();
	}
	public function cachesave(){
		$dirs = getgpc('keyid');
		$say="";
		// dump($dirs);
		foreach($dirs as $value){
				 
				$this->clearCache($type=0,$path=$value);
				$say.= "清理缓存文件夹成功! ".$value."\n";
		}
		
		dump($say);
		U('index','',true);
		//$this->success($say);
	}

	//清除缓存目录
    function clearCache($type=0,$path=NULL) {       
		I("@.Lib.Dir"); 
		//echo $path."<br>";
        Dir::del($path);
		
    }
 
	 

}	 
?>