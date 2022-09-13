<?php 
/*节点管理*/

class NodeAction extends GlobalAction{
	#节点管理
	//禁止添加节点;
	public $no_add_node=array('Form','Tempact','Public','Global');	
	
	public function _postConstruct(){   
			parent::_postConstruct();
			$this->CheckPower('index');
	}	
	 
	function index(){
		#节点管理
	 
        // $this->display();
	   
	}

	//扫描并填充全部节点;
	public function autoaddallnode(){		
			
			$appname= Genv_Config::get('Genv', 'appname');
			 
			$Dao = D("sysnode");
			//先清空表;
			$Dao->query("TRUNCATE TABLE ".gettable('sysnode')."");	
	 
			$not_auth = $this->no_add_node;		    
			$Dao = D("sysnode");			
			$data["name"] = $appname;
			$data["title"] = $data["remark"] = $appname."应用项目";
			$data["status"] = 1;
			$data["rootid"] = 0;
			$data["level"] = 1;
			$data["type"] = 0;
			

			$appid = $Dao->add($data);
			//dump($appid);
			echo "成功添加项目节点: ".$appname."<br>";
			
			//exit;
			//import("ORG.Aiqi.AppCodingSwitch");

			//$dir = Genv_Dir::exists(APPPATH."/App");
			//I('D.lib.Applib');
			 $actionFiles = Genv_App::searchdir(APPPATH."/App","FILES");
			 
			//$actionFiles=scandir($dir);
			foreach($actionFiles as $actionFile){				 
				
				$action = basename($actionFile,".php");		
				 
				 $this->checknode($action);
			}
			F();
			//$this->success('ok');
		}
		//检测节点信息;
       public function checknode($table){		
		 $appname= Genv_Config::get('Genv', 'appname');
		 $node=D("sysnode");
		//rootid=1为user_app;
		 $appitem=$node->where("rootid=0 and level=1")->findall();
		
		//如果没有项目节点则添加项目节点;
		if(!$appitem){		 	   
			$data["name"] = $appname;
			$data["title"] = $data["remark"] = $appname."应用项目";
			$data["status"] = 1;
			$data["rootid"] = 0;
			$data["level"] = 1;
			$data["type"] = 0;
			
			$rootid = $node->add($data);
			//echo 333;
			//echo $rootid;
		}else{
			$appnode=$node->where("name='$table' and rootid=1 and level=2")->findall();
			$rootid=$appnode[0]['id'];
		}		
	 
		
		 
		 $rootid=$this->AddFileNode($table);	
		 
	}
	//扫描文件进行节点管理
	function AddFileNode($action){
	 			$appname= Genv_Config::get('Genv', 'appname');
		        $Dao=D("sysnode");				
				$actionFile=$appname."/App/".ucfirst($action).".php" ;
				 dump( $actionFile);
				 
				$actionId = -1;
				if ( ! file_exists($actionFile)){
					//return -1;
				}
				$fp = fopen($actionFile,"r");
				$actionName = "";
				$funcName = "";				 
				do{
					$line = fgets($fp,2048);					
					if (preg_match('/class\\s+(.*?)Action\\s+extends/', $line, $regs)) {
						$actionName = $regs[1];
						$line = fgets($fp,2048); //再读一行就是Action文件的注释
						$actionInfo = strstr($line,"#");
						 echo $line;
						if(!$actionInfo) //没有找到注释
							continue;//$actionInfo = $actionName."模块";
						
						$data["name"] = trim($actionName);
						$data["title"] = $data["remark"] =trim( str_replace("#","",$actionInfo));
						$data["status"] = 1;
						$data["rootid"] = 1;
						$data["level"] = 2;
						$data["type"] = 0;
						$data["appname"] = trim(ucfirst($action));

						$data["md5key"] = md5($data["appname"]."_".$data["name"]);
						//dump( $actionName."__".$data["title"]);
						$actionId=$Dao->add($data);
						// $Dao->showsql();
						// dump($actionId);
						//$actionId =$Dao->insert_id;
						//echo $actionId;		
						 
					}
					//dump($actionId);
				
					if (preg_match('/function\\s+(\\S+)\\s*\\(/', $line, $regs) && $actionId>0) {
						$funcName = $regs[1];
						$line = fgets($fp,2048); //再读一行就是操作的注释
						//dump($line);
						$funcInfo = strstr($line,"#");
						if(!$funcInfo) //没有找到注释
							continue;//$funcInfo = $funcName."操作";

						// dump( $funcName); 
						$data["name"] = trim($funcName);
						$data["title"] = $data["remark"] = trim(str_replace("#","",$funcInfo));
						$data["status"] = 1;
						$data["rootid"] = $actionId;
						$data["level"] = 3;
						$data["type"] = 0;
						
						$data["appname"] =trim( ucfirst($action));
						$data["md5key"] = md5($data["appname"]."_".$data["name"]);
						$funcId = $Dao->add($data);	
						//$Dao->getsql();
					}
					
				}while(!feof($fp));
				fclose($fp);

				// dump($actionId);
			return $actionId;	
	}
	 
}	 
?>