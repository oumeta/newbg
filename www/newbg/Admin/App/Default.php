<?php

/*
默认首页;
*/
 
class  DefaultAction extends GlobalAction{
    
    
   public function index(){
   
        $array=array();
		  
		$array['menus_list']=$this->getmenus();
		$array['username']=  Genv_Cookie::get("username");
 	    $array['groupname']=  Genv_Cookie::get("groupname");
		
		$this->viefile("@frame");//赋值常用的一些变量
        $this->assign($array);
		//exit;
   }

   	public function getmenus(){
           //dump($this->visitor->get('role_id'));
          // $roleid=$this->visitor->get('role_id');
		   $roleid=Genv_Cookie::get("role_id");//31;
		   //dump($roleid);
		   $cid=str_replace(",","_",$roleid);
		  $group=F("menus_$cid");
		 // dump($group);
	  
		   if(empty($group)){
			   
			    $Menu=D('syspower');	
				 //dump($Menu);
			     $tree=$Menu->where("roleid in($roleid) and actid=0")->findall();
				// dump($tree);
				 //$Menu->showsql();
				// exit;
				 //$Menu->query("upate jp_sysrolepower set r")
				 //$Menu->db->vardump();
				 //$Menu->showsql();
				 // dump($tree);
				 // exit;
			 
			   $mid=array();
			   $actid=array();
			   foreach($tree as $k=>$v){
				   
					  if($v['menuid']==0){
						 $mid[$v['groupid']]=$v['groupid'];
					  }
					  if($v['groupid']==0){
						 $mid[$v['menuid']]=$v['menuid'];
					  }
					  if($v['actid']!=0){				  
						$actid[]=$v['actid'];
					  }
			    }					
				 //echo $Menu->getlastsql();
				  //dump($tree);
				$ids=array_values($mid);
				$ids=implode(',',$ids);
				 
				$db=D(); 
				$re=$db->_prefix;
				$sql="select m.id,m.appid,m.menuname,m.status,m.rootid,m.act,m.url,m.args,m.menu,m.taxis,m.remark ".
					"from ".$re."sysmenu as m ".
					"left join ".$re."sysapp as a on a.id=m.appid ".
					"where m.id in(".$ids.") and m.status=1 ".
					'order by m.taxis asc ';
				 
				$tree=$db->query($sql);
				//$db->showsql();
				  //dump($tree);
				//$tree=$Menu->where("Sysmenu.id in ($ids) and Sysmenu.status=1")->order("taxis asc")->findAll(); 		 
				$group=array();
				$menuid=array();
				
				foreach($tree as $k=>$v){
						// $v['ename']=ucfirst($v['ename']);
						 $v['href']=$v['url']==null?U($v['appid']."/".$v['act'],array('mid'=>$v['id']))."&".$v['args']:$v['url'];//初始化链接
 
						  if($v['rootid']==0){				    
							$group[$v['id']]=$v;					 
						  }else{ 				  
							$menuid[$v['rootid']][]=$v;						 
						  }				  	   
				}

				$group=array_values($group);
				foreach($group as $k=>$v){		
					

					//dump($menuid[$v['id']]);
				 $group[$k]['menu']=array_values($menuid[$v['id']]);		
				}
				 //dump($group);
				 F("menus_$cid",$group);
		} 
		 
		return $group;
	
	}
	function main(){
	
	
	}
   
	
}
