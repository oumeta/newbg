<?php 
/*
用户管理
*/
class UserAction extends GlobalAction {
	#用户管理	
	public function _postConstruct(){   
			parent::_postConstruct();
			
	}	
	public function index(){
	    #用户管理
		$this->CheckPower('index');
		I('@.Lib.Form');
		//$d=Genv::factory('Genv_Db_Query');
		///$d->from(gettable('sysmember')." AS a ",array(
			//'id,username,dep_id,real_name,role_id'
		//)) ;
		$d=D();
		$d->select(array('id','username','real_name','role_id','dep_id'))->from(gettable('sysmember')." AS a ");		
		$d->leftJoin(gettable('sysdep').' AS c','a.dep_id=c.id','name as depname');
		$d->leftJoin(gettable('sysrole').' AS r','a.role_id=r.id','name as rolename');
		//$d->fetch('all');
		$rs=$d->findall();
		 //$d->showsql();
		// exit;
		// echo $d->fetch('sql');
		 //exit;
		//dump($rs);
		
		//exit;
		$list['list']=$rs;
		$json=new Genv_Json;		 
	    $grid['listdata']=$json->encode($list);
		if($this->_request->isXhr()){
		  echo $grid['listdata'];
		
		}
        $grid['datasrc']=U('index');
		$grid['deplist']=cache_dep();
		$grid['rolelist']=cache_role();
	 
		$this->assign($grid);

		$this->assign('datalist',$datalist); 
		 
	} 
	 
	public function edit(){
	   #编辑用户
	   $this->CheckPower();
	   $id=getgpc('id');
	   $rs=D('sysmember')->select(array('id','username','real_name','dep_id','role_id','phone_tel'))->find($id);	   
	   $json=new Genv_Json;		 
	   echo $json->encode($rs);
	   exit;
	
	}
	
   
	public function save(){	    
		$data=$_POST;
		$data=D('sysmember')->create($data);
		if($data['password']){
		
			$data['password']=md5($data['password']);
		}else{
			unset($data['password']);
		}
		$d=D('sysmember');
		 
		if($data['id']){
			$rs= $d->save($data);
		
		}else{
		
			$d->add($data);
		}
		//$d->showsql();
		$this->index();
		exit;		
	}

 
	public function delete(){
		#删除用户
		$this->CheckPower();
	    $data['id']=getgpc("id");			 
		$d=D('sysmember');
		$d->delete($data['id']);		 
		$this->index();
		exit;		
	}

	public function changepwd(){

	}
	public function savepwd(){
	    $pwd=$_POST['password'];
		$username=Genv_Cookie::get("username");
		$d=D('sysmember');
		$rs=$d->where("username ='".$username."'")->find();	
		if($pwd[1]!=$pwd[2]){
			$this->error('两次输入不一致');		
		}

		if(md5($pwd[0])==$rs['password']){
		 
		  $rs['password']=md5($pwd[1]);
		  $d->save($rs);
		  $this->success('修改成功');
		}else{
		
			$this->error('原始密码不正确');
		
		}
		/*
$d->showsql();
		 dump($rs);


      $pwd=getgpc('password');
	  dump($pwd);*/
	}
}
?>