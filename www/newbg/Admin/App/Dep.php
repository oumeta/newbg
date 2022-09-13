<?php 
/*
部门管理
*/
class DepAction extends GlobalAction {
	#部门管理	
	public function _postConstruct(){   
			parent::_postConstruct();
			$this->CheckPower('index');
	}	
	public function index(){
	    #部门管理		 
		$d=D('sysdep');	 		 
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
	 
	public function edit(){
	   #编辑部门
	   $id=getgpc('id');
	   $rs=D('sysdep')->find($id);	   
	   $json=new Genv_Json;		 
	   echo $json->encode($rs);
	
	}
	
   
	public function save(){
	    $data['id']=getgpc("id");		 
		$data['name']=getgpc("name");
		$data['remark']=getgpc("remark");
		$data['isc']=getgpc("isc");
		if($data['isc']=="on"){
		
		$data['isc']=1;
		}else{
		$data['isc']=0;
		}

		$data['isact']=getgpc("isact");

		if($data['isact']=="on"){
		
		$data['isact']=1;
		}else{
		$data['isact']=0;
		}

		//dump($data);
		 
		$d=D('sysdep');
		 
		if($data['id']){
			$rs= $d->save($data);
		
		}else{
		
			$d->add($data);
		}
		F();
		
		$this->index();
		exit;		
	}

 
	public function delete(){
		#删除部门
	    $data['id']=getgpc("id");			 
		$d=D('sysdep');
		$d->delete($data['id']);
		F();
		$this->index();
		exit;		
	}
	public function apiedit(){
	  //#编辑字段
	  $db=D('sysdep');
	  $data=$db->create();
	  

	  
	  $data['isc']=($data['isc']=='true'||$data['isc']==1)?1:0;
	  $data['isact']=($data['isact']=='true'||$data['isact']==1)?1:0;
	  F();
	  $db->save($data);
	  // $db->showsql();
	 //  exit;
	
	
	}
	
	



}

?>