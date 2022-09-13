<?php
/*
客户管理;
*/
 
class  CustomerAction extends GlobalAction{
    #客户管理    
    
   public function index(){
        #列表	
		 
		$this->CheckPower();		 
		$list =$this->getlist('index');		 
		$json=new Genv_Json;		 
	    $grid['listdata']=$json->encode($list);
        $grid['datasrc']=U('query');
		$grid['cate']=getgpc('cate');
		$grid['mid']=getgpc('mid');		 
		$this->assign($grid);		
   }
  
   public function query(){			 
		$list = $this->getlist('index');
		//$json=new Json();	
		$json=new Genv_Json;
	  //  $json->encode($list);
		die($json->encode($list));
	}
	 
	public function add(){
		#添加
		$this->CheckPower();
	    $select = D('customer');			 
		$select->where("`id`=0");
		$rs=$select->find();
		I('@.Lib.Form');
		$this->assign('mid',getgpc('mid'));
	    $this->assign('rs',$rs);		 
	   
		$this->assign('linkerlist',$this->getlinkerlist(0));
	      G('AUTODISPLAY',true);	
		$this->assign('doact','insert');	 
	 	 $this->viefile("customer.add");//赋值常用的一些变量
	}
	public function edit(){
		#编辑
		$this->CheckPower();
        $id=getgpc('id');
		$db=D('customer');
		$rs=$db->find($id);
		//如果仅能编辑自己的
		if($this->CPK('onlyeidtmy')==true&&$this->CPK('alleidtmy')!=true){
		   if(Genv_Cookie::get("uid")!=$rs['userid']){
			 $this->error('无权操作');
		   }
		
		};
		 
		
		$user=D('sysmember')->find($rs['userid']);
		$rs['username']=$user['username'];
		 
		 
		I('@.Lib.Form');
	    $this->assign('rs',$rs);
		$this->assign('doact','update');
		 
		$this->assign('linkerlist',$this->getlinkerlist($rs['id']));
	    G('AUTODISPLAY',true);
		 
		$this->viefile("@add");//赋值常用的一些变量
	}
	
	
	 public function save(){
		 
        $doact=getgpc('doact');			
        $db=D('customer');	 
		$data=$db->create(); 
  
		if($doact=='insert'){
          $rs = $db->add($data);
          $dt['text']='添加成功';
		}elseif($doact=="update"){
		  $rs = $db->save($data);
          $dt['text']='更新成功';		
		}
		$uid=Genv_Cookie::get('uid');
		
		$where='uid='.$uid ." and cid=0 ";
	 
		if($doact=='insert'){
		    $data1['cid']=$rs;
			$dbc['where']=$where;
			D('linker')->save($data1,$dbc);
		}
		 
		$this->assign('jumpUrl',U('index'));
		$this->success($dt);
	}

	function getlist($url=null){ 
		 
	 //$result = $this->get_filter();
	 if(!$result){
		   $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'a.id' : trim($_REQUEST['sort_by']);
            if(in_array($filter['sort_by'],array('title'))){
				$sort_by=' convert('.$filter['sort_by'].' USING gbk) COLLATE gbk_chinese_ci ';
            }else{
			
			   $sort_by=$filter['sort_by'];
			}
			$sort_order=getgpc('sort_order');
			if(empty($sort_order)){
			$sort_order=-1;
			}
			$filter['sort_order']=$sort_order;			   
			if(empty($sort_order)){
				$sort_order="DESC";			
			}else{
			
				$sort_order=$sort_order==-1?"DESC":'asc';
			}
		 	 
			if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0)
			{
				$filter['page_size'] = intval($_REQUEST['page_size']);
			}
			elseif (isset($_COOKIE['MDL']['page_size']) && intval($_COOKIE['MDL']['page_size']) > 0)
			{
				$filter['page_size'] = intval($_COOKIE['MDL']['page_size']);
			}
			else
			{
				$filter['page_size'] = 10;
			}
			$select=D();
 
			$select->selectfrom(gettable('customer')." AS a " ,array('userid','j_company','j_address','updatedate','id') );	
			$select->leftJoin(gettable('sysmember').' AS b ','a.userid=b.id','real_name');
			 
		 
			if($this->CPK('onlyviewmy')){
				 
				$where=' userid='.Genv_Cookie::get("uid").'';
			}
			if($this->CPK('allviewmy')){
				 
				$where=' 1=1 ';
			}
			 
              

			$filter['j_company'] =  empty($_REQUEST['j_company']) ? '' : trim($_REQUEST['j_company']) ;
			 			
			if ($filter['j_company']){	
			 
				$where .= " AND j_company LIKE '%" . $filter['j_company'] . "%'";
			}

			$filter['bus_div']=getgpc('bus_div');//客户名
			if ($filter['bus_div']&&$filter['bus_div']!="null"){				
				$where .= " AND userid=". $filter['bus_div'] . "";
			}

			//dump($filter);
			 
			$select->where($where);	
			$select->order($sort_by.' '. $sort_order );
			$count=$select->countPages("a.id");

			$select->setpage($filter['page_size']);
			$page=getgpc("page")?getgpc('page'):1;
			$select->page($page);	
			 	
			$sql=$select->fetch('sql');
			 
			$filter['count']=$count['count'];
			 
			$this->set_filter($filter,$sql);
			 
		}
		else
		{
			 
			$sql = $result['sql'];
			 
			$count = $result['count'];
			$filter = $result['filter'];
		}

	 	  //echo $sql; 
	  $d=D(); 
	  $rs=$d->query($sql); 
	   $this->assign('sql',$sql);
	  $url=U($url);
	//  dump($filter);
	   $multipage = $this->page($filter['count'], $filter['page_size'], $page, $url);
	 
		$arr = array('list' => $rs, 'listpage' => $multipage, 'filter'=>$filter);
 
		return $arr;
	   exit;
	}
	public function remove(){
       #删除
	   $this->CheckPower();
	   
	    $ids=getgpc('id');	
		 
        $d=D('customer');
		//dump($ids);
		$d->delete($ids);
		 
		if($this->_request->isXhr()){
		 $this->query();
		  exit;		
		}
		 
	}
    //保存联系人;
	function savelinker(){
	   $d=D("linker");
	   $data=$d->create();
	   $data['uid']=Genv_Cookie::get('uid');
	   
	   if($data['id']){
	   
		$d->save($data);
	   
	   }else{
		    unset($data['id']);
		    $d->add($data);	   
	   }
	   if(empty($data['cid'])){
	   
	   $data['cid']=0;
	   }
		 
	   echo $this->getlinkerlist($data['cid']);
	}
	function getlinkerlist($cid=0){
	   $d=D("linker");	   
	   $data['lister']=$d->where("cid=$cid")->findall();
	   // $d->showsql();
	   //exit;
	   $this->assign($data);	  
	  
	   $this->viefile("@getlinkerlist");
	  
	   return $this->getview();   
	
	}
	//编辑联系人;
	function editlinker(){	    
	   $id=getgpc('id');
	   $rs=D('linker')->find($id);	   
	   $json=new Genv_Json;		 
	   echo $json->encode($rs);
	}
//编辑联系人;
	function dellinker(){
		$cid=getgpc('cid');
		$data['id']=getgpc("id");			 
		$d=D('linker');
		$d->delete($data['id']);		 
		echo $this->getlinkerlist($cid);
	}
	 public function onlyviewmy(){
		#仅查看自己
   
	  }
	  public function onlyeidtmy(){
			#仅编辑自己
	   
	   } 
		public function allviewmy(){
			#查看所有
	   
	   } 
		public function alleidtmy(){
			#编辑所有
	   
	   } 

	
}
