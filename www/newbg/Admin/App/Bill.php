<?php
/*
订单管理;
*/
 
class  BillAction extends GlobalAction{
    #订单管理    
    
   public function index(){
        #列表
			 
		$list =$this->getlist('index');
		$json=new Genv_Json;		 
	    $grid['listdata']=$json->encode($list);
        $grid['datasrc']=U('query');
		$this->assign($grid);
	 
   }

   public function form1(){
        #列表
		 
	 
   }

   public function ff(){

	 //  dump($_GET);
    $rs=D('company')->select('id,name')->findall();
	 //dump($rs);
	$a['results']=$rs;

		$json=new Genv_Json;		 
	 echo $json->encode($a);


   }
   public function query(){	
	   
		$list = $this->getlist('index');		
		$json=new Genv_Json;	
		die($json->encode($list));
	}
	 
	public function add(){
		#添加
	    $select = D('bill');			 
		$select->where("`id`=0");
		$rs=$select->find();
		$rs['b_code']=G('C')->bill_pre.Genv_Cookie::get('uid').date("YmdHis");
		$rs['postdate']=date("Y-m-d");
		I('@.Lib.Form');		
	    $this->assign('rs',$rs);
		$this->assign('doact','insert');	
		$this->viefile("@form");
	}
	public function edit(){
		#编辑
        $id=getgpc('id');
		$db=D('bill');
		$rs=$db->find($id);
		I('@.Lib.Form');

		$rs['postdate']=local_date('Y-m-d',$rs['postdate']);


		//dump($rs);
		//获取业务员姓名;
		$bus=D('sysmember')->find($rs['b_busid']);		
		$rs['username']=$bus['username'];
		//获取客户名称;
		$bus=D('customer')->find($rs['k_id']);		
		$rs['k_name']=$bus['j_company'];
		//获取联系人名称;
		$bus=D('linker')->find($rs['k_linker']);		
		$rs['k_linker_name']=$bus['name'];
		for($i=0;$i<4;$i++){
			$rs["pb_proname_".$i]=$this->getcompany($rs['pb_proid_'.$i]);
		
		}
		$rs['pc_id_name']=$this->getcompany($rs['pc_id']);
		$rs['pc_id2_name']=$this->getcompany($rs['pc_id2']);
		$rs['pr_id_name']=$this->getcompany($rs['pr_id']);
		//D()->showsql();
		//dump($rs);
	    $this->assign('rs',$rs);
		$this->assign('doact','update');	 
		$this->viefile("@form"); 
	}
	public function getcompany($id){
	    $data=F("company_list");
	    if(empty($data)){
           $rs=D('company')->select('id,name')->findall();
           $data=array();
		   foreach($rs as $key=>$v){
		    $data[$v['id']]=$v['name'];		   
		   }
		   F('company_list',$data);
		}
		return $data[$id];
	
	
	}
	
	 public function save(){       
        $db=D('bill');
		$data=$db->create();
		  dump($data);
		$doact=getgpc('doact');
		if($doact=='insert'){

		  $data['postdate']=local_strtotime($data['postdate']);
		  $data['editdate']=local_strtotime(date("Y-m-d"));
          $rs = $db->add($data);
          $dt['msg']='添加成功';

		}elseif($doact=="update"){
			
		  $data['editdate']=local_strtotime(date("Y-m-d"));
		  $rs = $db->save($data);
          $dt['msg']='更新成功';
		
		}
		 $db->showsql();
		 exit;
		$dt['url']=U('index');
       $this->msg($dt);
		// dump($this->_response);

	 

	}
	function getlist($url=null){ 
	 // $result = $this->get_filter();
	 if(!$result){
			$filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'a.id' : trim($_REQUEST['sort_by']);
            if(in_array($filter['sort_by'],array('title'))){
				$sort_by=' convert('.$filter['sort_by'].' USING gbk) COLLATE gbk_chinese_ci ';
            }else{
			
			   $sort_by=$filter['sort_by'];
			}
			$filter['sort_order']=$sort_order=getgpc('sort_order');

			//dump($filter);
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
 
			$select->selectfrom(gettable('bill')." AS a " ,array('id','b_code','count_shu','count_zhi','account','postdate','finshdate','editdate','status') );	
			$select->leftJoin(gettable('customer').' AS b ','a.k_id=b.id','j_company');
			
			$where=' 1=1 ';
			$filter['b_code'] =  empty($_REQUEST['b_code']) ? '' : trim($_REQUEST['b_code']) ;
			$filter['d1'] = getgpc('d1');
			$filter['d2'] = getgpc('d2');
			$filter['d3'] = getgpc('d3');
			$filter['d4'] = getgpc('d4');			
			if ($filter['b_code']){				
				$where .= " AND b_code LIKE '%" . $filter['b_code'] . "%'";
			}
			 
			$select->where($where);	
			$select->order($sort_by.' '. $sort_order );
			$count=$select->countPages("a.id");

			$select->setpage($filter['page_size']);
			$page=$this->_request->get("page")?$this->_request->get("page"):1;
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
	  //dump($rs);
	  $status=$this->get_bill_status();
	  foreach($rs as $key=>$v){
	  
		 $rs[$key]['postdate']=local_date('Y-m-d',$rs[$key]['postdate']);
		 $rs[$key]['editdate']=local_date('Y-m-d',$rs[$key]['editdate']);
		 $rs[$key]['finshdate']=local_date('Y-m-d',$rs[$key]['finshdate']);
		 $rs[$key]['billstatus']=$status[$rs[$key]['status']];
	  }
	 // exit;
	  $url=U($url);
	//  dump($filter);
	   $multipage = $this->page($filter['count'], $filter['page_size'], $page, $url);
	 
		$arr = array('list' => $rs, 'listpage' => $multipage, 'filter'=>$filter);
 
		return $arr;
	   exit;
	}

	public function remove(){
       #删除
	    $ids=getgpc('id');	
		//dump($ids);
        $d=D('bill');
		//dump($ids);
		$d->delete($ids);
		//$d->showsql();
		if($this->_request->isXhr()){
		  $this->query();
		  exit;		
		}
		$dt['msg']='删除成功';
		$dt['url']=U('index');
        $this->msg($dt);

	}

}
