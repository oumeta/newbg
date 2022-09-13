<?php 
/*
角色管理
*/
class RoleAction extends GlobalAction {
	#角色管理
	public function _postConstruct(){   
			parent::_postConstruct();
			//$this->CheckPower('index');
	}	
	public function index(){
	    #角色管理
		 
		$d=D('sysrole');	 		 
		$list['list']=$d->findall();
		$json=new Genv_Json;		 
	    $grid['listdata']=$json->encode($list);
		if($this->_request->isXhr()){
		  echo $grid['listdata'];
		
		}
        $grid['datasrc']=U('index');
		$this->assign($grid);
		$this->assign('datalist',$datalist); 
		 
	} 
	/*获取角色名称*/
	public function edit(){
	   #编辑角色;
	   $id=getgpc('id');
	   $rs=D('sysrole')->find($id);
	  // D()->showsql();
	   $json=new Genv_Json;		 
	   echo $json->encode($rs);
	
	}	
   //保存角色;
	public function save(){
	    $data['id']=getgpc("id");		 
		$data['name']=getgpc("name");
		$data['remark']=getgpc("remark");
		$d=D('sysrole');
		//dump($data);
		if($data['id']){
		
			$rs= $d->save($data);
		
		}else{
		
			$d->add($data);
		}
		//$d->showsql();
	 


		$this->index();
		exit;		
	}

	//删除角色;
	public function delete(){
		#删除角色
	    $data['id']=getgpc("id");		 
		//$data['name']=getgpcc("name");
		//$data['remark']=getgpc("remark");
		$d=D('sysrole');
		$d->delete($data['id']);
		//$d->showsql();
		$this->index();
		exit;		
	}

}
?>