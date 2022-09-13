<?php 
/*
Api接口管理
*/
 
class ApiAction extends GlobalAction {		
	public function index(){	   
		 
	} 
	//获取全部用户列表;
	public function getuserlist(){	    
		$ps=getgpc('s');
		$select=D('sysmember')->select('id,username,real_name');
		$select->setpage($ps);
		$page=$this->_request->get("p")?$this->_request->get("p"):1;
		$select->page($page);	
		$rs=$select->findall();
		$sql=$select->fetch('sql');
		echo  $sql;
		$a['results']=$rs;
		$json=new Genv_Json;		 
		echo $json->encode($a);	
	}
	//获取操作员;
	public function getoperatelist(){	 
		$ps=getgpc('s');
		
		$select=D(); 
		$select->selectfrom(gettable('sysmember')." AS a " ,array('id','username','real_name') );
		$select->leftJoin(gettable('sysdep').' AS b','a.dep_id=b.id ','isc');
		$select->where('isact=1'); 
		 
		$select->order(' convert(a.username USING gbk) COLLATE gbk_chinese_ci');
		/*$count=$select->countPages("b.id");
		
		$select->setpage($ps);
		$page=$this->_request->get("p")?$this->_request->get("p"):1;	
		$select->page($page);		 
		*/
		$sql=$select->fetch('sql');		
		 
		$rs=$select->query($sql);

		//$select->showsql();
		//$a['total']=$count['count'];;
		$a['results']=$rs;
		$json=new Genv_Json;		 
		echo $json->encode($a);	
	}
	//获取业务员;
	public function getsalesmanlist(){	 
		//$ps=getgpc('s');
		
		$select=D(); 
		$select->selectfrom(gettable('sysmember')." AS a " ,array('id','username','real_name') );
		$select->leftJoin(gettable('sysdep').' AS b','a.dep_id=b.id ','isc');
		$select->where('isc=1'); 
		 
		$select->order(' convert(a.username USING gbk) COLLATE gbk_chinese_ci');
		$count=$select->countPages("b.id");
		
		//$select->setpage($ps);
		//$page=$this->_request->get("p")?$this->_request->get("p"):1;	
		//$select->page($page);		 
		$sql=$select->fetch('sql');		
		 
		$rs=$select->query($sql);

		//$select->showsql();
		//$a['total']=$count['count'];;
		$a['results']=$rs;
		$json=new Genv_Json;		 
		echo $json->encode($a);	
	}
	//获取全部客户列表;
	public function getcustomerlist(){	
		
		$ps=getgpc('s');
		$jq=getgpc('q');
		
		$select=D(); 
		$select->selectfrom(gettable('customer')."  " ,array('id','userid','j_company') );
		//$select('id,userid,j_company')->findall();
		//$select->selectfrom(gettable('sysmember')." AS a " ,array('id','username','real_name') );
		//$select->leftJoin(gettable('sysdep').' AS b','a.dep_id=b.id ','isc');
		if($jq){
		$select->where('j_company like "%'.$jq.'%"'); 
		}
		$select->order(' convert(j_company USING gbk) COLLATE gbk_chinese_ci');
		$count=$select->countPages("id");
		
		$select->setpage($ps);
		$page=$this->_request->get("p")?$this->_request->get("p"):1;	
		$select->page($page);			
		$sql=$select->fetch('sql');		
		// echo $sql;
		$rs=$select->query($sql);

		
		$a['total']=$count['count'];;
		$a['results']=$rs;
		$json=new Genv_Json;		 
		echo $json->encode($a);	

		 
	}
    /*
	获取指定业务员的客户
	*/
	public function getmycustomerlist(){
		$userid=getgpc('userid');
		$jq=getgpc('q');
		$where="userid=$userid";
		if($jq){
			$where.=' and j_company like "%'.$jq.'%"'; 
		}
		$rs=D('customer')->select('id,userid,j_company')->where($where)->order('convert(j_company USING gbk) COLLATE gbk_chinese_ci')->findall();		
		$a['results']=$rs;
		 //D()->showsql();
		$json=new Genv_Json;		 
		echo $json->encode($a);	
	}

	  /*
	获取指定公司的联系人;
	*/
	public function getlinkerlist(){
		$cid=getgpc('cid');
		$rs=D('linker')->select('id,name')->where("cid=$cid")->findall();		
		$a['results']=$rs;
		//D()->showsql();
		$json=new Genv_Json;		 
		echo $json->encode($a);	
	}


	//获取全部公司列表;
	/*
			company 
1 报关公司
2 托车公司
3 商检公司
4 核销单供应商
	
	*/
	public function getcompanylist(){
		$cate=getgpc('cate');
		$rs=D('company')->select('id,name')->where("cate=$cate")->order('rank desc')->findall();		
		$a['results']=$rs;
		$json=new Genv_Json;		 
		echo $json->encode($a);	
	}
    //获取联系全部信息;
	function billcomlinker(){
		 
	   $cid=getgpc('cid');

	    
	   $d=D("linker");	   
	   $data['lister']=$d->where("cid=$cid")->findall();
	   // $d->showsql();
	   //exit;
	   
	   $this->assign($data);	  
	  
	   $this->viefile("customer.getlinkerlist1");
	  
	   echo  $this->getview();   
	
	}
	 
}
?>