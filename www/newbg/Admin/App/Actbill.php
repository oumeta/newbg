<?php
/*
订单管理;
操作部门;
已锁定的单，不能编辑；

*/
 
class  ActbillAction extends GlobalAction{
    #订单管理    
    
   public function index(){
        #列表
		$this->CheckPower();	 
		$list =$this->getlist('index');
		$json=new Genv_Json;		 
	    $grid['listdata']=$json->encode($list);
        $grid['datasrc']=U('query');
		$grid['total']=$list['countdiv'];
		//订单状态;
		$grid['status']=$this->get_bill_status();
		$this->assign($grid);
	 
   }
  
   public function query(){	
	   
		$list = $this->getlist('index');		
		$json=new Genv_Json;	
		die($json->encode($list));
	}
	 
	public function add(){
		#添加
		$this->CheckPower();
	    $select = D('bill');			 
		$select->where("`id`=0");
		$rs=$select->find();
		//$rs['b_code']=G('C')->bill_pre.Genv_Cookie::get('uid').date("YmdHis");
		$rs['postdate']=date("Y-m-d");
		I('@.Lib.Form');		
	    $this->assign('rs',$rs);
		$this->assign('doact','insert');	
		$this->viefile("@form");
	}
	public function edit(){
		#编辑
		$this->CheckPower();
        $id=getgpc('id');
		$db=D('bill');
		$upower=$this->getbillpower('edit');

		$where='id='.$id.' and status in( '.$upower['status'].' ) and (b_busid in('.$upower['limitd'].') or b_opeid in('.$upower['limitd1'].') )';		 	
		$rs=$db->where($where)->find();
		if(!$rs){
			$this->error('无权操作,或此定单不存在');		
		}

		I('@.Lib.Form');

		$rs['postdate']=local_date('Y-m-d',$rs['postdate']);
		$rs['editdate']=local_date('Y-m-d',$rs['editdate']);
		$rs['finshdate']=local_date('Y-m-d',$rs['finshdate']);

		//dump($rs);
		//获取业务员姓名;
		$bus=D('sysmember')->select("*")->find($rs['b_busid']);
		//D()->getsql();
		//dump($bus);
		$rs['username']=$bus['username'];
		//获取客户名称;
		$bus=D('customer')->find($rs['k_id']);		
		$rs['k_name']=$bus['j_company'];
		//获取联系人名称;
		$bus=D('linker')->find($rs['k_linker']);		
		$rs['k_linker_name']=$bus['name'];
		
		$rs["pb_proname"]=$this->getcompany($rs['pb_proid']);
		
		
		$rs['pc_id_name']=$this->getcompany($rs['pc_id']);
		$rs['pc_id2_name']=$this->getcompany($rs['pc_id2']);
		$rs['pr_id_name']=$this->getcompany($rs['pr_id']);		
	    $this->assign('rs',$rs);
		//dump($rs);
		//exit;
		$this->assign('doact','update');	 
		$this->viefile("@form"); 
	}
	
	 public function save(){       
        $db=D('bill');
		$data=$db->create();
		$dbc=$this->getbillcount($data);
		$data['count_zhi']=$dbc['count_zhi'];
		$data['count_shu']=$dbc['count_shu'];
		$data['account']=$dbc['account'];		 
		$doact=getgpc('doact');

		if(empty($data['b_busid'])){		 
		 $this->error('业务员不能为空');
		}
		if(empty($data['k_id'])){		 
		 $this->error('客户不能为空');
		}

		  
		if($doact=='insert'){
		  $data['postdate']=local_strtotime($data['postdate']);
		  $data['editdate']=local_strtotime(date("Y-m-d"));
          $rs = $db->add($data);
          $dt['msg']='添加成功';
		}elseif($doact=="update"){			
		  $data['editdate']=local_strtotime(date("Y-m-d"));
		  $data['finshdate']=local_strtotime($data['finshdate']);
		   $data['postdate']=local_strtotime($data['postdate']);
		  $rs = $db->save($data);
          $dt['msg']='更新成功';		
		}
		  //$db->showsql();
		 // exit;
		$this->assign('jumpUrl',U('index'));		 
        $this->success($dt['msg']);
		//$dt['url']=U('index');
      // $this->msg($dt);
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
 
			$select->selectfrom(gettable('bill')." AS a " ,array('id','b_code','count_shu','count_zhi','account','postdate','finshdate','editdate','status','b_opeid','b_busid','b_product_name','b_tank_code','shi_shu','shi_zhi','b_so','pc_port','pb_proid','pc_id','pc_id2','pr_id','k_id','ischagui') );	
			$select->leftJoin(gettable('customer').' AS b ','a.k_id=b.id','j_company');
			 
			$upower=$this->getbillpower('view');
			$where=' status in( '.$upower['status'].' ) and (b_busid in('.$upower['limitd'].') or b_opeid in('.$upower['limitd1'].') )';		
			 
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
			$filter['com1_div']=getgpc('com1_div');
			$filter['com2_div']=getgpc('com2_div');
			$filter['com4_div']=getgpc('com4_div');
			$filter['ischagui']=getgpc('ischagui');

 
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
			 
			if ($filter['com1_div']!=''&&$filter['com1_div']!="null"){				
				$where .= " AND (pc_id=" . $filter['com1_div'] . " or pc_id2= " . $filter['com1_div'] . " )";
			}
			if ($filter['com2_div']!=''&&$filter['com2_div']!="null"){				
				$where .= " AND pr_id=" . $filter['com2_div'] . "";
			}
			if ($filter['com4_div']!=''&&$filter['com4_div']!="null"){				
				$where .= " AND pb_proid=" . $filter['com4_div'] . "";
			}
			if ($filter['ischagui']!=""&&$filter['com4_div']!="null"){				
				$where .= " AND ischagui =" . $filter['ischagui'] . "";
			}
			$select->where($where);	
			$select->order($sort_by.' '. $sort_order );
			$count=$select->countPages("a.id");
//dump($filter);
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
			 $rs[$key]['com1']=$this->getcompany($v['pb_proid']);
			 $rs[$key]['com2']=$this->getcompany($v['pc_id']);
			 $rs[$key]['com3']=$this->getcompany($v['pc_id2']);
			 $rs[$key]['com4']=$this->getcompany($v['pr_id']);
			   $cc['count_shu']+=$v['count_shu'];
				$cc['count_zhi']+=$v['count_zhi'];
				$cc['account']+=$v['account'];
				 
		  }
	  }
	 // exit;
	  $url=U($url);
	//  dump($filter);
	   $multipage = $this->page($filter['count'], $filter['page_size'], $page, $url);
	 
 $countdiv=" 应收：{$cc['count_shu']}&nbsp;&nbsp;  利润：{$cc['account']}&nbsp;&nbsp;应付：{$cc['count_zhi']} ";

		$arr = array('list' => $rs, 'listpage' => $multipage, 'filter'=>$filter,'countdiv'=>$countdiv);
  
 
		return $arr;
	   exit;
	}

	public function remove(){
       #删除
	   $this->CheckPower();
	   $upower=$this->getbillpower('del');
	   $ids=getgpc('id');
	   $where=' id in('.$ids.') and status in( '.$upower['status'].' ) and (b_busid in('.$upower['limitd'].') or b_opeid in('.$upower['limitd1'].') )';	
	      		
       $d=D('bill');
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
	public function setoprate(){
		#派单
		$this->CheckPower();
	    $ids=getgpc('ids');	
		$oid=getgpc('oid');		 
        $d=D('bill');		
		$upower=$this->getbillpower('pai');
		$where=' id in('.$ids.') and status in( '.$upower['status'].' ) and (b_busid in('.$upower['limitd'].') or b_opeid in('.$upower['limitd1'].') ) ';	
		
		$d->query('update jp_bill set b_opeid='.$oid.',status=1 where '.$where);		
		if($this->_request->isXhr()){
		  $this->query();
		  exit;		
		}
	} 
	public function apiedit(){
		#改变订单状态
	  $this->CheckPower();
	  $upower=$this->getbillpower('status');

	  $where=' id ='.getgpc('id').' and status in( '.$upower['status'].' ) and (b_busid in('.$upower['limitd'].') or b_opeid in('.$upower['limitd1'].') ) ';
	   
	  $rs=D('bill')->where($where)->find();
	
	  if(!$rs){
		$this->error('无权操作,或此定单不存在');		
	  }
	  $data=D('bill')->create();
	   
	  if($data['status']==3){
		 $data['finshdate']=local_strtotime(date("Y-m-d"));	  
	  }
	  if($data['status']==2){
		 $data['ischagui']=1;	  
	  }
	  $data['editdate']=local_strtotime(date("Y-m-d"));	 
	  $rs=D('bill')->save($data);
	 //D()->getsql();
	// dump($data);
	  if($rs==1){
	    $this->success('修改成功');	  
	  }
	  if($rs==0){
	    $this->error('修改失败');	  
	  }
	  
	}
	public function  trustbill(){
	    #委托单
		$this->CheckPower();
        $id=getgpc('id');
	    $rs=D('bill')->find($id);
	   //获取业务员姓名;
		$bus=D('sysmember')->find($rs['b_opeid']);		
		$rs['username']=$bus['username'];
		$rs['tel']=$bus['phone_tel'];
		//echo $rs['pc_id'];

		$rs["pc_company"]=$this->getcompany($rs['pc_id']);
		$rs['postdate']=date("Y-m-d");
		$cc=D('trustbill')->where('bid='.$id)->find();
        $this->assign("cc",$cc);
	    $this->assign("rs",$rs);
	 
	}
	//保存委托单
	public function  savetrustbill(){
	    $db=D('trustbill');
		$cid=getgpc('id');
		 
		if(empty($cid)||$cid==''){
			$doact='insert';
			 
		
		}else{
			$doact='update';
		}
 
		$data=$db->create();
		 
		
		if($doact=='insert'){
		  $data['postdate']=local_strtotime($data['postdate']);
		  //$data['editdate']=local_strtotime(date("Y-m-d"));
          $rs = $db->add($data);
          $dt['msg']='添加成功';
		}elseif($doact=="update"){			
		  $data['editdate']=local_strtotime(date("Y-m-d"));
		  $data['finshdate']=local_strtotime($data['finshdate']);
		  //dump($data);
		  $rs = $db->save($data);
          $dt['msg']='更新成功';		
		}
		 //D()->getsql();
		 //exit;
		$this->assign('jumpUrl',U('index'));		 
        $this->success($dt['msg']);
	 
	}
	function exceltrustbill(){
		header("Content-Type: application/x-msexcel; name=\"bill.xls\"");
		header("Content-Disposition: inline; filename=\"bill.xls\"");
		#委托单
		//$this->_response->setHeader("Content-Type: application/x-msexcel; name=\"bill.xls\"");
        $id=getgpc('id');
	    $rs=D('bill')->find($id);
	   //获取业务员姓名;
		$bus=D('sysmember')->find($rs['b_opeid']);		
		$rs['username']=$bus['username'];
		$rs['tel']=$bus['phone_tel'];
		//echo $rs['pc_id'];

		$rs["pc_company"]=$this->getcompany($rs['pc_id']);
		$rs['postdate']=date("Y-m-d");
		$cc=D('trustbill')->where('bid='.$id)->find();
        $this->assign("cc",$cc);
	    $this->assign("rs",$rs);
		$this->viefile("@trustbill");
		 
	}
	public function  download(){
	    #订单资料上传管理
		//$this->CheckPower();
        $id=getgpc('bid');
	    $rs=D('sysupload')->where('bid='.$id)->findall();

	 
	    $this->assign("bid",$id);
	    $this->assign("rs",$rs);
		 
	 
	}
	 
}