<?php 
/*
用户组及权限管理
*/
class PowerAction extends GlobalAction {
    #权限管理

	public function index(){
		#设置权限
		 
		$id=intval(getgpc("roleid"));
		$roleid=intval(getgpc("roleid"));
	    $d=D('sysmenu');
		$menulist=$d->where('status=1')->order('id desc')->findall();

		//
		
		$menuOnly=$d->where('status=1')->group('menu')->findall();

 



		$actlist=$d->from('table.sysnode')->where("level=3")->order('id desc')->findall();

		$menu=array();//一级菜单;
		$act=array();//动作分类;
		$menutwo=array();
		$ids=array();
		 //dump($menulist);
		 foreach($menulist as $k){			
			 if($k['rootid']==0){				
				$ids[]=$k['id'];
				$menu[$k['id']]=array("id"=>$k['id'],'name'=>$k['menuname']);			 
			 }
		} 	
		
 
	
		//菜单对应的动作 ;
		foreach($actlist as $k){			
			$act[$k['appname']][$k['name']]=array('title'=>$k['title'],'app'=>($k['appname']."||".$k['name']),'md5key'=>$k['md5key']);			 
			 	
		}
		 
		/*
		//叛断是否可选;
		*/
		


 
		foreach($menuOnly as $k){
			 if(in_array($k['rootid'],$ids)){
				$menu[$k['rootid']]['submenu'][$k['id']]=array("id"=>$k['id'],'name'=>$k['menuname'],'actlist'=>$act[$k['appid']]);	 
			 }		
		}
		
		
	 
		$select=D();
		$select->select(array('roleid','groupid','menuid','actid'))->from(gettable('syspower')." AS a ");	
		$select->leftJoin(gettable('sysnode').' AS b ','a.actid=b.md5key','name');
		$select->where(" a.roleid=$roleid and a.actid!='0' and actid!='' ");
		$powerlist=$select->findall();
		 
 

	 
 		 if(is_array($powerlist)){
			foreach($powerlist as $k=>$v){			 
				 
		        $menu[$v['groupid']]['submenu'][$v['menuid']]['actlist'][$v['name']]['checked']=1;//[$v['menu'][$v['actid']['checked']=1;		
			}
		} 
	 
 
		$this->assign('roleid',$roleid);
		 
		$this->assign('menulist',$menu);

		 
		//$this->pageinfo();
		$this->display();
		//	
	}
	//保存权限
	public function save(){
     
		$act=getgpc("keyid");// 权限id;
		 
		 

		 
		$roleid=intval(getgpc("roleid"));//角色id;		 
		$groupid=array();
		foreach($act as $v){
			  $d=explode("_",$v);	
			  
			 
			  $groupid[$d[0]]=array('groupid'=>$d[0],'roleid'=>$roleid);
			
			  $menuid[$d[1]]=array('menuid'=>$d[1],'roleid'=>$roleid);
			 // dump()
			  $actid[$d[1]."-".$d[2]]=array('groupid'=>$d[0],'menuid'=>$d[1],'actid'=>$d[2],'roleid'=>$roleid);
		}
	 
		 
		$d=D('syspower');
		$d->where("roleid=$roleid")->delete();
		 
		//G('db')->query("delete  from " . gettable("sysrolepower")." where roleid=$roleid");
		$a=array_values($groupid);
		
		
		$d->addall($a);

		 
		
		
		
		 
		$a=array_values($menuid);
		$d->addall($a);

		// $d->showsql();
		//exit;
		/*foreach($a as $k){
			dump($k);
			$sql = "INSERT INTO ".gettable('sysrolepower')."(menuid, roleid,  menu, actname) ".
					"VALUES ('$k[menuid]', '$k[roleid]', '$k[menu]','$k[actname]')";
			 
			//G('db')->query($sql);
		// echo $app->getlastsql();
		}*/
		//$d->showsql();
		//exit;
		//exit;
		//  dump($actid);
		$a=array_values($actid);
		
		  
		 $d->addall($a);


		  
		/*foreach($a as $k){
			dump($k);
			$sql = "INSERT INTO ".gettable('sysrolepower')."(groupid,menuid, actid,roleid,  menu, actname) ".
					"VALUES ('$k[groupid]','$k[menuid]', '$k[actid]','$k[roleid]', '$k[menu]','$k[actname]')";
			 
			///G('db')->query($sql);
			 
        
		 
		}*/
		F();
		// powerlist/?roleid=1
		   U('role/index','',true);
	 
		 
		 
		//$app->addAll(array_values($menuid));
		//$app->addAll(array_values($actid));
			
		//Mylib::clearCache();
		//$this->show_message("编辑权限成功");
		//$this->success(L('_UPDATE_SUCCESS_'));	
	}

	function billpower(){
	 #订单权限
       $roleid=getgpc('roleid');
	   $rs=D('sysrole ')->find($roleid);
	   
	   $json=new Genv_Json;	    
	   $billpower=$json->decode(stripslashes($rs['billpower']),true);	
	   
	   $cc=array();
       if(is_array($billpower['status'])){
		   foreach($billpower['status'] as $k=>$v){
			  $cc['status'][$v]=1;	   
		   }
	   }
	   if(is_array($billpower['limitd'])){
		   foreach($billpower['limitd'] as $k=>$v){
			  $cc['limitd'][$v]=1;	   
		   }
	   }
	   if(is_array($billpower['limitd1'])){
		   foreach($billpower['limitd1'] as $k=>$v){
			  $cc['limitd1'][$v]=1;	   
		   }
	   }
	  
	  
	


	   $data['actlist']=array('view'=>'查看','edit'=>'修改','del'=>'删除','pai'=>'派单','status'=>'改变状态');//$this->get_bill_status();
       $data['billstatus']=$this->get_bill_status();	   
	   $data['deplist']=$this->getdeplist();
	   $data['rows']=count($data['deplist'])*count($data['billstatus']);
	   $data['roleid']=$roleid;
	   $data['billpower']=$cc;
       $this->assign($data);
	
	
	}




	//保存权限
	public function billsave(){
     
		$status=getgpc("status");// 权限id;
		$limitd=getgpc("limitd");// 权限id;	
		$limitd1=getgpc("limitd1");// 权限id;	
		$roleid=intval(getgpc("roleid"));//角色id;	
		
		$groupid['limitd']=$limitd;
		$groupid['limitd1']=$limitd1;
		$groupid['status']=$status;
        /*
		foreach($status as $v){
			  $d=explode("_",$v);		  
			 
			  $groupid[$d[0]]['status'][]=$d[1];
			 
		}
		foreach($limitd as $v){
			  $d=explode("_",$v);			 
			  $groupid[$d[0]]['limitd'][]=$d[1];			 
		}*/

		$data['id']=$roleid;
		$json=new Genv_Json;	    
		$data['billpower']=addslashes($json->encode($groupid));		 
		$d=D('sysrole');
		$d->save($data);		
		F();		
		U('role/index','',true);
		
	}
	public function getdeplist($id=0){
	    $data=F("getdeplist");
	    if(empty($data)){
           $rs=D('sysdep')->select('id,name')->findall();
           $data=array();
		   $data[0]="自己的订单";
		   foreach($rs as $key=>$v){
		    $data[$v['id']]=$v['name'];		   
		   }
		   F('getdeplist',$data);
		}
		if($id!=0){
		
		return $data[$id];
		}elseif($id==0){
		return $data;
		}	
	}
   
}

?>