<?php 
class SysdepAction extends GlobalAction {
	//用户管理
	private $hold_field = array('id');
	private $tree;
	private $status;
	function _initialize(){
		LC('@.Lib.Json'); 
		$this->status=array(
			array("id"=>1,'title'=>'正常'),
			array("id"=>2,'title'=>'锁定'),
			array("id"=>3,'title'=>'离职')
		);	
		parent::_initialize();		
	}
	public function index(){
		//浏览
		$tabs=array(
			
			'0'=>array('title'=>'角色设置','href'=>__APP__."/Sysdep/rolelist"),				
			'1'=>array('title'=>'用户设置','href'=>__APP__."/Sysdep/userlist")
			//'2'=>array('title'=>'数据规则','href'=>__APP__."/Sysdep/datalimitlist"),
			//'0'=>array('title'=>'部门设置','href'=>__APP__."/Sysdep/deplist" ),
				 		
		);
		$this->assign("tabs",$tabs);
		$this->pageinfo();
		$this->display("pages.tabs");
	}   

	
	public function  rolelist(){	
		$sql = "SELECT *
					FROM " . gettable("sysrole")." ORDER BY id desc ";
					 

		$Appdata= D()->query($sql);
		$str='var DD=[];';
		foreach($Appdata as $k=>$v){	
			/*
			$Appdata[$k]['actlist'] ='&nbsp; &nbsp;
			<a href=javascript:; onclick=powerlist('.$v[id].')>权限</a>&nbsp;|&nbsp;
			<a href=javascript:; onclick=roledit('.$v[id].',"'.$v[name].'")>编辑</a>&nbsp;|&nbsp;<a href='.U("saverole").'?id='.$v[id].'&rd=2>删除</a>'; */
			$str.="DD.push({id:'".$v[id]."',name:'".$v[name]."'});";
		}
		 
		//$a=array();		 
		  	 
		$a['data']=$Appdata;
		 
		// dump($Appdata);
		//$sort_flag  = sort_flag($list['filter']);
	//	$a[$sort_flag['tag']]=$sort_flag['img'];
		//$this->assign($a);
		// $this->display("news.index");
		//$content=$this->view->getfetch('news.index');	
		 //$content = ob_get_contents();
       //ob_end_clean();
	    // dump($a);

		  $json=new Json();
		//$result['error']   = 0;	
		//$result['msg'] = $content;
		 
		// die($json->encode($a));
		//dump($a);
		 $bb=$json->encode($a);
		$this->assign("bb",$bb);
		$this->pageinfo();
		$this->display();	 
	} 
	//获取权限分组
	function getrollist(){

		$sql = "SELECT *
					FROM " . gettable("sysrole")." ORDER BY id desc ";
					 

		$Appdata= D()->query($sql);
		 
		foreach($Appdata as $k=>$v){	
			$Appdata[$k]['actlist'] ='&nbsp; &nbsp;
			<a href=javascript:; onclick=powerlist('.$v[id].')>权限</a>&nbsp;|&nbsp;
			<a href=javascript:; onclick=roledit('.$v[id].',"'.$v[name].'")>编辑</a>&nbsp;|&nbsp;<a href='.U("saverole").'?id='.$v[id].'&rd=2>删除</a>'; 
		}
		 
		$a=array();		 
		  	 
		$a['data']=$Appdata;
		 
		//dump($a);
		//$sort_flag  = sort_flag($list['filter']);
	//	$a[$sort_flag['tag']]=$sort_flag['img'];
		//$this->assign($a);
		// $this->display("news.index");
		//$content=$this->view->getfetch('news.index');	
		 //$content = ob_get_contents();
       //ob_end_clean();
	    // dump($a);

		$json=new Json();
		//$result['error']   = 0;	
		//$result['msg'] = $content;
		 
		 die($json->encode($a));
	} 

	public function powerlist(){
		$id=intval(GetGP("roleid"));
		$roleid=intval(GetGP("roleid"));
	 
		$menulist=G('db')->query("SELECT *FROM " . gettable("sysmenu")." where status=1 ORDER BY id desc ");

	 
		
		$menuOnly=G('db')->query("SELECT *FROM " . gettable("sysmenu")." where status=1  GROUP BY appid  ");



//dump($menuOnly);
		$actlist=G('db')->query("SELECT *FROM " . gettable("sysnode")." where level=3 ORDER BY id desc ");  

		$menu=array();//一级菜单;
		$act=array();//动作分类;
		$menutwo=array();
		$ids=array();
		 
		foreach($menulist as $k){
			
			 if($k[rootid]==0){
				$ids[]=$k[id];
				$menu[$k[id]]=array("id"=>$k[id],'name'=>$k[menuname]);			 
			 }
			  
		}
		 
	
		//菜单对应的动作 ;
		foreach($actlist as $k){			
			$act[$k[appname]][$k[name]]=array("id"=>$k[id],'title'=>$k[title],'app'=>escape($k[appname]."||".$k[name]));			 
			 	
		}
		 
		
		//叛断是否可选;
		$powerlist=G('db')->query("SELECT *FROM " . gettable("sysrolepower")." where roleid=$roleid and actid!=0"); 
		
		//D("Sysrolepower")->query("select * from __TABLE__ WHERE roleid=$roleid and actid!=0");
        foreach($powerlist as $k=>$v){			 
		        $act[$v[appname]][$v[actname]][checked]=1;		
		}
 
		  
		foreach($menuOnly as $k){
			 if(in_array($k[rootid],$ids)){
				$menu[$k[rootid]][submenu][$k[id]]=array("id"=>$k[id],'name'=>$k[menuname],'actlist'=>$act[$k['appid']]);	 
			 }		
		}	 
		
		 

		$this->assign('roleid',$roleid);
		$this->assign('menulist',$menu);
		$this->pageinfo();
		$this->display();
		//	
	}
	//保存权限
	public function saverolepower(){

		$act=GetGP("keyid");// 权限id;
		$roleid=intval(GetGP("roleid"));//角色id;		 
		$groupid=array();
		foreach($act as $v){
			  $d=explode("_",$v);	
			  $e=explode("||",unescape($d[3]));
			  $groupid[$d[0]]=array('groupid'=>$d[0],'roleid'=>$roleid,'appname'=>$e[0],'actname'=>$e[1]);
			
			  $menuid[$d[1]]=array('menuid'=>$d[1],'roleid'=>$roleid,'appname'=>$e[0],'actname'=>$e[1]);
			  
			  $actid[$d[2]]=array('groupid'=>$d[0],'menuid'=>$d[1],'actid'=>$d[2],'roleid'=>$roleid,'appname'=>$e[0],'actname'=>$e[1]);
		}
	 
		//$app=D("Sysrolepower");		 
		//echo $app->getlastsql();		 
		//$app->where("")->delete();		
		// echo $app->getlastsql();
		// exit;
		G('db')->query("delete  from " . gettable("sysrolepower")." where roleid=$roleid");
		$a=array_values($groupid);
		 
		foreach($a as $k){
        // $app->add($k);
			 
			$sql = "INSERT INTO ".gettable('sysrolepower')."(groupid, roleid,  appname, actname) ".
					"VALUES ('$k[groupid]', '$k[roleid]', '$k[appname]','$k[actname]')";
			 
			G('db')->query($sql);
		  //echo $app->getlastsql();
		}
		 
		$a=array_values($menuid);
		foreach($a as $k){
			$sql = "INSERT INTO ".gettable('sysrolepower')."(menuid, roleid,  appname, actname) ".
					"VALUES ('$k[menuid]', '$k[roleid]', '$k[appname]','$k[actname]')";
			 
			G('db')->query($sql);
		// echo $app->getlastsql();
		}
		
		$a=array_values($actid);
		 
		foreach($a as $k){
			$sql = "INSERT INTO ".gettable('sysrolepower')."(groupid,menuid, actid,roleid,  appname, actname) ".
					"VALUES ('$k[groupid]','$k[menuid]', '$k[actid]','$k[roleid]', '$k[appname]','$k[actname]')";
			 
			G('db')->query($sql);
			 
        
		 
		}
		 
		 
		//$app->addAll(array_values($menuid));
		//$app->addAll(array_values($actid));
			
		//Mylib::clearCache();
		$this->show_message("编辑权限成功");
		//$this->success(L('_UPDATE_SUCCESS_'));	
	}

	public function saverole(){
	    $id=GetGP("id");
		$rd=GetGP("rd");
		$name=GetGP("name");
		$remark=GetGP("remark");
		$depid=GetGP("depid");
		//$App=D("Sysrole");
		$data=array();		
		$data[name]=$name;
		//$data[remark]=$remark;
		//$data[depid]=$depid;
		if($rd==0){
			 // $data[rootid]=-1;	
			 $sql = "INSERT INTO ".gettable('sysrole')." (name) ".
					"VALUES ('$name')";
			 
			 G('db')->query($sql);
			 $rs['msg']='添加成功';
		     $rs['type']=0;
		  
	         echo md_json_encode($rs);
		 
		     exit;
			 
			 // $App->add($data);
			  //$this->success(L('_INSERT_SUCCESS_'));
		}		
		if($rd==1){			
			  $sql = "update  ".gettable('sysrole')." set name='".$name."' WHERE id=$id";
			 
			 G('db')->query($sql);
			 $rs['msg']='修改成功';
		     $rs['type']=0;
		  
	         echo md_json_encode($rs);
		 
		     exit;

		}
		if($rd==2){
			//删除节点
			  $rs=G('db')->query("delete from ".gettable('sysrole')." WHERE id=$id");
			  //$this->tree->removeNode($id);
			  
			  if($rs){
				$this->success('操作成功');
			  }else{
				$this->error(L('_DELETE_FAIL_')); 
			  }
		}	
	}
	



	public function addsave(){
		$id=GetGP("id");
		$rd=GetGP("rd");
		$name=GetGP("name");
		$remark=GetGP("remark");
		$App=D("Sysdep");
		$data=array();
		
		$data[name]=$name;
		$data[remark]=$remark;
		if($rd==0){
			  $data[rootid]=-1;
			 // $data=array($name,$remark);
		      //$this->tree->addTopNode($data, "last");
			  $App->add($data);
			  $this->success(L('_UPDATE_SUCCESS_'));
		}
		if($rd==1){
		      $data[rootid]=$id;			
			  $App->add($data);
			  //echo $App->getlastsql();
			  $this->success(L('_UPDATE_SUCCESS_'));
		}
		if($rd==2){
			
			$App=D("Sysdep");
			$vo=$App->create();
			unset($vo[rd]);
			 
			if($App->save($vo)){//修改;		 
						//echo $App->getlastsql(); 
						 $this->success(L('_UPDATE_SUCCESS_'));
					}else{ 
						//echo $App->getlastsql();
						$this->error(L('_UPDATE_FAIL_')); 
			} 
			//echo $App->getlastsql();
		     //$this->tree->add($id,$data);

		}
		if($rd==3){
			//删除节点			
			  $this->tree->removeNode($id);
			  $this->success(L('_UPDATE_SUCCESS_'));
		}
		
		$this->assign('jumpUrl',urlfrom());
		$this->success(L('_INSERT_SUCCESS_'));      
	}	
	 
	public function  userlist(){
		//用户管理
		$id=intval(GetGP("id"));	 
		if (!$id) $id=0;// $this->error(L('_SELECT_NOT_EXIST_'));
		$fields=array(
			//'0'=>array('width'=>'20','fieldname'=>'ID','fieldid'=>'id'),
			'1'=>array('width'=>'80','fieldname'=>'用户名','fieldid'=>'realname'),
			'2'=>array('width'=>'80','fieldname'=>'真实姓名','fieldid'=>'username'),			
			'4'=>array('width'=>'180','fieldname'=>'入职日期','fieldid'=>'jobfromdate'),
			'5'=>array('width'=>'80','fieldname'=>'添加日期','fieldid'=>'postdate')	,
			'6'=>array('width'=>'80','fieldname'=>'密码','fieldid'=>'password')	,
			'7'=>array('width'=>'80','fieldname'=>'状态','fieldid'=>'statusgif'),
			'8'=>array('width'=>'150','fieldname'=>'动作','fieldid'=>'actlist'),		
		); 
		//$fields=  $list=multi_sort($list,'taxis');	($fields);
		$order= GetGP("order");
		$orderby= GetGP("orderby");	
		if (empty($order)){
            $order= "taxis";
        } 		 
		if (empty($orderby)){
            $orderby= "DESC";
        } 		
		!$orderby && $orderby = "DESC";
		$orderby1=($orderby=="DESC"?"ASC":"DESC");		
        $orders=$order." ".$orderby1;	
        $data=array();	
		 	 
		$Appdata=D('Sysmember')->order("username asc")->findall();
 
		foreach($Appdata as $k=>$v){	
			$Appdata[$k]['statusgif'] =$this->getstatus($Appdata[$k]['status']);
			$Appdata[$k]['actlist'] ='		
			 &nbsp;|&nbsp;<a href='.U("deluser").'?id='.$v[id].'&rd=2>删除</a>'; 
		}

		$fwidth="[";
		foreach($fields as $k=>$v){
			$fwidth.=$v[width].",";
		}	
		$fwidth.="]";
		$url=Mylib::geturl($order,$orderby);		
	    $maindata=Mylib::getDatalist($fields,$Appdata,$url ,$orderby1); 
		 
		$a["fwidth"]=$fwidth;		
		$a["maindata"]=$maindata;
		$a["roles"]=$this->getdeprole();	
		 
		$a["status"]=$this->status;
		$a["id"]=$id;			
		$this->assign($a);
		$this->pageinfo();
		$this->display();	 
	} 
   //保存用户
	public function saveuser(){
	    $id=GetGP("id");
		$App=D("Sysmember");		 
		$vo=$App->create();
		$pwd=$vo[password2];
		unset($vo[password2]);
		if(empty($pwd)){
		
		}else{
			 $vo[password]=md5($pwd);		
		}
		 
		$App->getError();
		if(!$vo) {
					$this->error($App->getError());
		}
		if(!empty($id)){
			
			if($App->save($vo)){//修改;
						 $this->assign('jumpUrl',urlfrom());						
						 $this->success(L('_UPDATE_SUCCESS_'));
					}else{ 
						$this->error($App->getError()); 
					} 
		}else{				
				 //添加				
				if($App->add($vo)){ 
					// echo $App->postdate;
					 $this->success('添加成功');
				}else{ 
					$this->error($App->getError()); 
				} 
			 }     
	}
	//删除用户
	function deluser(){
	
		 
		$Dao = D("Sysmember");
		$id =GetGP("id");
		$keyid ="id";
		if(isset($id))
		{
			$result = $Dao->query("select id FROM __TABLE__ where `$keyid` IN ('$id')");
			//echo $Dao->getlastsql();
			if(!$result)
				$this->error("Non-existed record!");

			if($Dao->execute("DELETE FROM __TABLE__ where `$keyid` IN ($id)"))
			{
				//$this->assign("jumpUrl",__URL__);
				$this->success("删除数据成功id=$id");
			}
			else
				$this->error("删除数据失败");
		}
		else
			$this->error("非法操作");	
	
	}
	//编辑规则对应的角色
	public function editrule(){				
		 
		$id=intval(GetGP("id"));	 
		
		$fields=array(			
			//'1'=>array('width'=>'80','fieldname'=>'动作名称','fieldid'=>'action'),
			'1'=>array('width'=>'180','fieldname'=>'描述','fieldid'=>'remark'),
			'2'=>array('width'=>'80','fieldname'=>'字段','fieldid'=>'fields'),
			//'3'=>array('width'=>'80','fieldname'=>'所属应用','fieldid'=>'ename'),
			'4'=>array('width'=>'40','fieldname'=>'规则','fieldid'=>'op'),
			'5'=>array('width'=>'140','fieldname'=>'限制值','fieldid'=>'keyvalue'),			
			//'7'=>array('width'=>'180','fieldname'=>'说明','fieldid'=>'remark')
			//'6'=>array('width'=>'80','fieldname'=>'锁定','fieldid'=>'lockstatus')
			//'7'=>array('width'=>'40','fieldname'=>'锁定','fieldid'=>'issystems'),
		); 

		$order= GetGP("order");
		$orderby= GetGP("orderby");	
		if (empty($order)){
            $order= "taxis";
        } 		 
		if (empty($orderby)){
            $orderby= "DESC";
        } 		
		!$orderby && $orderby = "DESC";
		$orderby1=($orderby=="DESC"?"ASC":"DESC");		
        $orders=$order." ".$orderby1;	
		
		$Appdata=D('Sysdatalimit')->order("id asc")->findall();	
		

		$fwidth="[";
		foreach($fields as $k=>$v){
			$fwidth.=$v[width].",";
		}	
		$fwidth.="]";
		$url=$this->geturl($order,$orderby);		
	    $maindata=$this->getDatalist($fields,$Appdata,$url ,$orderby1); 
		
		$a = array();		
		$a["fwidth"]=$fwidth;		
		$a["maindata"]=$maindata;
		$rolelist=D("SysroleView")->order("Sysdep.id asc,Sysrole.id asc")->findAll();
		$a["roles"]=$rolelist;	
		$this->pageinfo();
		$this->assign($a);		 			 
		$this->display();
	}
    //保存权限规则
	public function savemenurule(){		
  			 
			$App=D("Sysdatalimit");
			$vo=$App->create();	
			 
			$type=GetGP("action");
			 
			if(false === $vo){
				 
	 			$this->error($Dao->getError());
	        }
			 
			
              //dump($vo);
				$rs = $App->save($vo);
				// echo $App->getlastsql();
				$success = "修改数据成功!";
				$error = "修改数据失败";
			
		 
			if($rs){
				//$this->assign("jumpUrl",urlfrom());
				$this->success($success);
			}else{
				$this->error($error);
			}
			 
			
		}
        
	/*获取部门的角色*/
	function getdeprole(){		 	
		$App=D("Sysrole");
		
		$list=$App->findAll();	
		return $list;
	}

	/*获取角色名称*/
	function getrole($id){		 	
		$App=D("Sysrole");
		$data['id']=$id;
		$list=$App->where($data)->find();	
		return $list;
	}
	function getstatus($id){
	
		$d=$this->status;
			foreach($d as $k=>$v){
				if($v[id]==$id){
				return $v[title];
			   }
		   }
	}

}

?>