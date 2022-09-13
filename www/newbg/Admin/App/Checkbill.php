<?php
/*
对账管理;
*/ 
class  CheckbillAction extends GlobalAction{
    #对账管理      
   public function khcheckbill(){
        #客户对账
		$this->CheckPower();	 
		 
		$list =$this->getlist('index');
		$json=new Genv_Json;		 
	    $grid['listdata']=$json->encode($list);
        $grid['datasrc']=U('query');
		$bank=$this->get_banklist();
		$grid['bank']=$bank;
		$grid['total']=$list['countdiv'];
		//订单状态;
		$grid['status']=$this->get_bill_status();
		$this->assign($grid);	 
   }

   //添加一项收款记录;
   public function addshu($data){
	   
		$db=D('finc');		
		if(empty($data['postdate'])){		   
			$data['postdate']=local_strtotime(date("Y-m-d"));
		}		
		//$data['postdate']=local_strtotime($data['postdate']);
		$db->add($data);
		// $db->getsql();
		 //exit;
		$billid=$data['billid'];
		$d=D('finc');	 		 
		$rs=$d->where('billid='.$billid.'')->order('postdate desc')->findall();
		$counts=0;		
		if(is_array($rs)){ 
			foreach($rs as $key=>$v){				 
			  if($v['cate']==0){
				$counts+=$v['money'];
			  }
			}
		}
		$data1['id']=$billid;
		$data1['shi_shu']=$counts;	
		//unset($data['postdate']);
		//unset($data['remark']);
		// dump($data);
		D('bill')->save($data1);
	// D()->showsql();
   
   
   }
   public function  batchshu(){ 
	 #月结收款


	 
	  $ids=getgpc('ids');
	  
	  $rs=D('bill')->findall($ids);	
      $comid=$rs[0]['k_id'];

	  $g_pmoney=getgpc('money');
	 
	  if(empty($g_pmoney)){
		 $this->error(' 金额不能为空 ');	  
	  }
	  if(!is_numeric($g_pmoney)){
		 $this->error('录入正确金额');	  
	  }
	  $bankid=getgpc('bankid');
	  if(empty($bankid)){
		 $this->error('请选择银行');	  
	  }

	  $postdate=getgpc('postdate');
	  if(empty($postdate)){
		 $postdate=date('Y-m-d');
	  
	  }
	  $postdate=local_strtotime($postdate);
	   

	  

		$data['cate']=0;
		$data['postdate']=$postdate;
		$data['bankid']=$bankid;
		$data['remark']=getgpc('remark');
		$data['money']=$g_pmoney;
		$data['comid']=$comid;
		$data['comcate']=0;			
		
	    $db=D('finc');	
		
		$rs=$db->add($data);
		
        if($rs){
		  $this->success('添加成功');
		}else{
		   $this->error('添加失败'); 
		   
		}
	  
	  foreach($rs as $key=>$v){
		 
		//如果还没有收齐款项，则操作;
		if($v['count_shu']>$v['shi_shu']){
			if($v['count_shu']>=($v['shi_shu']+floatval($g_pmoney)) ){
				$data['billid']=$v['id'];
				$data['cate']=0;
				$data['postdate']=$postdate;
				$data['bankid']=$bankid;
				$data['comid']=$comid;
				$data['remark']=getgpc('remark');
				$data['money']=$g_pmoney;
				$this->addshu($data);			
				break;
			}else{				
				
				$data['billid']=$v['id'];
				$data['cate']=0;
				$data['postdate']=$postdate;
				$data['bankid']=$bankid;
				$data['comid']=$comid;
				$data['remark']=getgpc('remark');
				$data['money']=$v['count_shu']-$v['shi_shu'];//getgpc('g_pmoney');
				$this->addshu($data);
				$g_pmoney=$g_pmoney-($v['count_shu']-$v['shi_shu']);				 	
			}
		
		}	  
	  }
	  $this->success('月结收款成功');

	 // D()->showsql();
	  
	 
   }
   public function gyscheckbill(){
	   //nljlk
	   #供应商对账
   
   
   }
  
   public function query(){	
	   
		$list = $this->getlist('index');		
		$json=new Genv_Json;	
		die($json->encode($list));
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
			$sort_order=getgpc('sort_order');
			if(empty($sort_order)){
			$sort_order=-1;
			}
			$filter['sort_order']=$sort_order;

			// dump($_POST);
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
 
			$select->selectfrom(gettable('bill')." AS a " ,array('id','b_code','count_shu','shi_shu','count_zhi','shi_zhi','account','postdate','finshdate','editdate','status','b_opeid','b_busid','b_product_name','b_tank_code') );	
			$select->leftJoin(gettable('customer').' AS b ','a.k_id=b.id','j_company');
			 
			
			$where=' 1=1 ';
			$filter['status'] =getgpc('status');
			$filter['d1'] = getgpc('d1');
			$filter['d2'] = getgpc('d2');
			$filter['d3'] = getgpc('d3');
			$filter['d4'] = getgpc('d4');
			$filter['b_so']=getgpc('b_so');
			$filter['b_product_name']=getgpc('b_product_name');
			$filter['b_tank_code']=getgpc('b_tank_code');
			$filter['bus_div']=getgpc('bus_div');//客户名
			$filter['ope_div']=getgpc('ope_div');
			$filter['cus_div']=getgpc('cus_div');
			if ($filter['d1']&&$filter['d1']!="null"){
				$d=local_strtotime($filter['d1']);
				$where .= " AND a.postdate >=".$d."";
			}
			if ($filter['d2']&&$filter['d2']!="null"){
				$d=local_strtotime($filter['d2']);
				$where .= " AND a.postdate <=".$d."";
			}
			if ($filter['d3']&&$filter['d3']!="null"){
				$d=local_strtotime($filter['d3']);
				$where .= " AND a.finshdate >=".$d."";
			}
			if ($filter['d4']&&$filter['d4']!="null"){
				$d=local_strtotime($filter['d4']);
				$where .= " AND a.finshdate <=".$d."";
			}
			if ($filter['b_so']&&$filter['b_so']!="null"){				
				$where .= " AND b_so LIKE '%" . $filter['b_so'] . "%'";
			}
			if ($filter['b_product_name']&&$filter['b_product_name']!="null"){				
				$where .= " AND b_product_name LIKE '%" . $filter['b_product_name'] . "%'";
			}
			if ($filter['b_tank_code']&&$filter['b_tank_code']!="null"){				
				$where .= " AND b_tank_code LIKE '%" . $filter['b_tank_code'] . "%'";
			}
			if ($filter['cus_div']&&$filter['cus_div']!="null"){				
				$where .= " AND (b.j_company LIKE '%" . $filter['cus_div'] . "%' or a.k_id=". $filter['cus_div'] .")";
			}
			if ($filter['ope_div']&&$filter['ope_div']!="null"){				
				$where .= " AND b_opeid=". $filter['ope_div'] . "";
			}
			if ($filter['bus_div']&&$filter['bus_div']!="null"){				
				$where .= " AND b_busid=". $filter['bus_div'] . "";
			}
			if ($filter['status']!=''&&$filter['status']!="null"){				
				$where .= " AND status=" . $filter['status'] . "";
			}
			 
			$select->where($where);	
			$select->order($sort_by.' '. $sort_order );
			$count=$select->countPages("a.id");
//dump($filter);
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
		// exit;
	  $d=D(); 
	  $rs=$d->query($sql);
	  $this->assign('sql',$sql);
	  //dump($rs);
	  $status=$this->get_bill_status();
	  $userlist=$this->getuserlist();

		$cc['count_shu']=0;
		$cc['count_zhi']=0;
		$cc['account']=0;
		$cc['shi_shu']=0;
		$cc['shi_zhi']=0;

	  if(is_array($rs)){
		  foreach($rs as $key=>$v){
		  //dump($v);
			 $rs[$key]['postdate']=local_date('Y-m-d',$rs[$key]['postdate']);
			 $rs[$key]['editdate']=local_date('Y-m-d',$rs[$key]['editdate']);
			 $rs[$key]['finshdate']=local_date('Y-m-d',$rs[$key]['finshdate']);
			 $rs[$key]['billstatus']=$status[$rs[$key]['status']];
			 $rs[$key]['busname']=$userlist[$v['b_busid']];
			 $rs[$key]['opratename']=$userlist[$v['b_opeid']];

			    $cc['count_shu']+=$v['count_shu'];
			    $cc['count_zhi']+=$v['count_zhi'];
				$cc['account']+=$v['account'];
				$cc['shi_shu']+=$v['shi_shu'];
				$cc['shi_zhi']+=$v['shi_zhi'];
		  }
	  }
	 // exit;
	  $url=U($url);
	//  dump($filter);
	   $multipage = $this->page($filter['count'], $filter['page_size'], $page, $url);
	    $countdiv=" 应收：{$cc['count_shu']}&nbsp;&nbsp;实收：{$cc['shi_shu']}&nbsp;&nbsp;利润：{$cc['account']}&nbsp;&nbsp;应付：{$cc['count_zhi']}&nbsp;&nbsp;实付：{$cc['shi_zhi']}";

		$arr = array('list' => $rs, 'listpage' => $multipage, 'filter'=>$filter,'countdiv'=>$countdiv);

		//$arr = array('list' => $rs, 'listpage' => $multipage, 'filter'=>$filter);
 
		return $arr;
	   exit;
	}

	
}
 