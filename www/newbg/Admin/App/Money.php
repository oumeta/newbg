<?php
/*
银行对账

*/
 
class  MoneyAction extends GlobalAction{
    #银行对账  
    
   public function index(){
        #列表
		//$this->CheckPower();	 
		$list =$this->getlist('index');

		 
		$json=new Genv_Json;		 
	    $grid['listdata']=$json->encode($list);
        $grid['datasrc']=U('query');
		$grid['banklist']=$this->get_banklist();
		$grid['comlist']=
		$grid['total']=$list['countdiv'];		
		$this->assign($grid);
	 
   }
    public function query(){	
	   
		$list = $this->getlist('index');		
		$json=new Genv_Json;	
		die($json->encode($list));
	}
	function getlist($url=null){ 
	 // $result = $this->get_filter();
	 if(!$result){
			$filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'id' : trim($_REQUEST['sort_by']);
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
 
			$select->selectfrom(gettable('finc')." AS a " ,array('id','billid','money','cate','postdate','remark','comcate','bankid','comid','fyear','fmonth') );	
			
			 $select->leftJoin(gettable('company').' AS b ','a.comid = b.id','name as comname');	
			 $select->leftJoin(gettable('customer').' AS c ','a.comid = c.id','j_company as couname');	

				
			 
			$filter['d1'] = getgpc('d1');
			$filter['d2'] = getgpc('d2');
			$filter['d3'] = getgpc('d3');
			$filter['d4'] = getgpc('d4');
			$filter['cate'] = getgpc('cate');
			$filter['bankid']=getgpc('bankid');
			
			$filter['comcate']=getgpc('comcate');
			
			$filter['com_div']=getgpc('com_div');

			 

			if($filter['cate']===null){
				$filter['cate']=-1;
			}
			$where=' 1=1 ';	
			if($filter['d3']&&$filter['d3']!='null'){
				  
				$where .= " AND a.fyear =".$filter['d3']."";
			}else{
			
				//$where .= " AND a.fyear =".Date("Y")."";
			
			}
			if($filter['d4']&&$filter['d4']!='null'){
				  
				$where .= " AND a.fmonth =".$filter['d4']."";
			}	
				 

			if ($filter['d1']&&$filter['d1']!="null"){
				$d=local_strtotime($filter['d1']);
				$where .= " AND a.postdate >=".$d."";
			}
			if ($filter['d2']&&$filter['d2']!="null"){
				$d=local_strtotime($filter['d2']);
				$where .= " AND a.postdate <=".$d."";
			}
			if ($filter['d5']&&$filter['d5']!="null"){
				 $d=local_strtotime($filter['d5']);
				 $where .= ' and DATE_FORMAT(FROM_UNIXTIME(a.postdate),"%Y-%m")="'.$filter['d5'].'"';
			}
			 
			if ($filter['bankid']&&$filter['bankid']!="null"){				
				$where .= " AND a.bankid =" . $filter['bankid'] . "";
			}	
			 
			if ( $filter['cate']!=-1){				
				$where .= " AND a.cate=" . $filter['cate'] . "";
			}
		 
			if(in_array($filter['comcate'],array(0,1,2,4))&&is_numeric($filter['comcate']) ){
				 
				$where .= " and comcate=".$filter['comcate'];

				if(is_numeric($filter['com_div'])){
			 
					$where .= " AND a.comid=" . $filter['com_div'] . "";
			    }
			}

			 


			$select->where($where);	
			//$select->group(' cate,postdate,comid,bankid');	
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
  
		// exit;
	  $d=D(); 
	  // echo $sql;
	  // exit;
   //dump($filter);
	  $rs=$d->query($sql);

	 //s echo $sql;
	 // exit;
	  
 
	  $this->assign('sql',$sql);
	  //dump($rs);
	  //$status=$this->get_bill_status();
	  $banklist=$this->get_banklist();
	   $cc['money']=0;
		$cc['money1']=0; 
		 
	  if(is_array($rs)){
		  foreach($rs as $key=>$v){
		   
			 
			 $rs[$key]['bankname']=$banklist[$v['bankid']];
			 if($v['cate']==1){
				//$rs[$key]['comname']= $rs[$key]['comname']
				$cc['money']+=$v['money'];
			 }elseif($v['cate']==0){			 
				$rs[$key]['comname']= $rs[$key]['couname'];
				$cc['money1']+=$v['money'];
			 }elseif($v['cate']==2){			 
				$rs[$key]['comname']= $rs[$key]['couname'];
				$cc['money2']+=$v['money'];
			 }
			 $rs[$key]['postdate']=local_date('Y-m-d',$rs[$key]['postdate']);
			
			  
				/* $cc['count_zhi']+=$v['count_zhi'];
				$cc['account']+=$v['account'];
				$cc['shi_shu']+=$v['shi_shu'];
				$cc['shi_zhi']+=$v['shi_zhi'];*/
		  }
	  }
	 // exit;
	  $url=U($url);
	    
	   $multipage = $this->page($filter['count'], $filter['page_size'], $page, $url);
	 
		$countdiv=" 合计付款：{$cc['money']}  合计收款：{$cc['money1']} 合计回扣：{$cc['money2']}";

		$arr = array('list' => $rs, 'listpage' => $multipage, 'filter'=>$filter,'countdiv'=>$countdiv);
  
 
		return $arr;
	   exit;
	}
    function add(){
	    #添加;
		$this->CheckPower();
		$bank=$this->get_banklist();
		$this->assign('d3',date('Y'));
		$this->assign('d4',date('m'));		 
		$this->assign('bank',$bank);
		$this->assign('doact','insert');	
		$this->viefile("@form"); 
	
	}
	 function edit(){
		 #编辑

		$this->CheckPower();
        $id=getgpc('id');



		$rs=D('finc')->find($id);

 
		if($rs['comcate']==0){
		
		   $bb=D('customer')->find($rs['comid']);
		   $rs['comname']=$bb['j_company'];
		
		}else{
		
			 $bb=D('company')->find($rs['comid']);
			 
			 $rs['comname']=$bb['name'];
		
		}
 
		 

		$rs['postdate']=local_date('Y-m-d',$rs['postdate']);

		$this->assign('d3',$rs['fyear']);
		$this->assign('d4',$rs['fmonth']);	
		
		if(!$rs){
			$this->error('此记录不存在');		
		}


		 
		$bank=$this->get_banklist();
		$this->assign('bank',$bank);
	    $this->assign('rs',$rs);		 
		$this->assign('doact','update');	 
		$this->viefile("@form"); 
	
	}
	function save(){
	
		$db=D('finc');

		$data=$db->create();

		$doact=getgpc('doact');
		if(empty($data['postdate'])){		   
			$data['postdate']=date("Y-m-d");
		}
		$data['comid']=getgpc('com_div');

		if($data['comcate']==0){
			$data['cate']=getgpc('cate');
		}else{		
		    $data['cate']=1;		
		}		

		$data['postdate']=local_strtotime($data['postdate']);

		$data['fmonth']= str_pad($data['fmonth'], 2, "0", STR_PAD_LEFT);

 
		if($doact=='insert'){
		   
          $rs = $db->add($data);
          $dt['msg']='添加成功';
		}elseif($doact=="update"){			
		  
		  $rs = $db->save($data);
          $dt['msg']='更新成功';		
		}
       $this->logs(D()->logsql());
		$this->success('成功');
		
	
	}
	public function remove(){
       #删除
	   $this->CheckPower();
	   
	   $ids=getgpc('id');
	   $where=' id in('.$ids.') ';	
	      		
       $d=D('finc');
		//dump($ids);
		$rs=$d->where($where)->delete();
		//$d->showsql();	
		if($rs==0){
			$this->error('删除失败');
		}else{
			if($this->_request->isXhr()){
			  $this->query();
			  exit;		
			}

			$dt['msg']='删除成功';
			$dt['url']=U('index');
			$this->msg($dt);
		}

	}
}
