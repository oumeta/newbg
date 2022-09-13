<?php 
/*
月结付款
*/
class MonthmoneyAction extends GlobalAction {
	#月结付款
   

   public function index(){
    #月结付款
	//$this->CheckPower();
       $data['rmenu']=array(	
		  
		   '报关行对账'=>U('bgcheckbill'),
		   '核销单对账'=>U('hxcheckbill'),
		   '托车行对账'=>U('tccheckbill'),
	   );
	   $this->assign($data);
   
   }
   
 
	
	 
    //报关公司对账；
	public function bgcheckbill(){	
	//$this->CheckPower();
	   
		$data['comlist']=getcompanylist(1);		
		$comid=getgpc('comid');	
		

		$d1=getgpc('d1');//起始日期;
		$d2=getgpc('d2');//结束日期;
		$d3=getgpc('d3');//年份;
		$d4=getgpc('d4');//月份;

		if($d4&&$d4!='null'){
			if($d3=='0'){$d3=date("Y");}
			$d5=$d3."-".sprintf("%02d",$d4);	 
		
		}

		$data['comid']=$comid;

		$data['company']=$this->getcompany($comid);
        $where=' ';
		if ($d1&&$d1!="null"){
				$d=local_strtotime($d1);
				$where .= " AND postdate >=".$d."";
		}
		if ($d2&&$d2!="null"){
				$d=local_strtotime($d2);
				$where .= " AND postdate <=".$d."";
		}
		if ($d5&&$d5!="null"){
				$d=local_strtotime($d5);
				$where .= ' and DATE_FORMAT(FROM_UNIXTIME(postdate),"%Y-%m")="'.$d5.'"';
		}	
		$sql="(select id,b_code,b_tank_code,b_so,b_product_name,pc_id,pc_tankmoney,pc_other,pc_bgmoney,pc_portmoney ,postdate,1 as cc from jp_bill where pc_id=".$comid.$where." )
		union all(select id,b_code,b_tank_code,b_so,b_product_name ,pc_id2,pc_tankmoney2,pc_other2,pc_bgmoney2 ,0 as pc_portmoney ,postdate,2 as cc from jp_bill where pc_id2=".$comid.$where.") 
		ORDER BY  postdate desc";
	    $d=D(); 

		// echo $sql;
	    $rs=$d->query($sql);
	    //$sql2=$select->fetch('sql');
	    
	  
	  $ids=array(); 
	  $idd=array();
	  if(is_array($rs)){
		  foreach($rs as $key=>$v){
			 
		    
			 $rs[$key]['postdate']=local_date('Y-m-d',$rs[$key]['postdate']);			 
			 $cc['pc_bgmoney']+=$v['pc_bgmoney'];
			 $cc['pc_portmoney']+=$v['pc_portmoney'];
			 $cc['pc_tankmoney']+=$v['pc_tankmoney'];
			 $cc['pc_other']+=$v['pc_other'];
			/* $mm['id']=$v['id'];
			 $mm['cc']=$v['cc'];
			 $mm['money']=$v['pc_bgmoney']+$v['pc_portmoney']+$v['pc_tankmoney']+$v['pc_other'];
			 $ids[]=$mm;
			 $idd[]=$v['id'];
            */
		  }
	   }
	   if($comid){		   
		   $data['alreadyfu']=$this->getyifu($comid);	
		   G('AUTODISPLAY',true);
	   }

	   $json=new Genv_Json;		   
	   $data['ids']=$json->encode($ids);
	   $data['comcate']=1;
	   $data['comid']=$comid;
	   $data['list']=$rs;
	   $data['d1']=$d1;
	   $data['d2']=$d2;
	   $data['d3']=$d3;
	   $data['d4']=$d4;
	   $data['countdata']=$cc;
	   $data['bank']=$this->get_banklist();
	   $uid=Genv_Cookie::get("uid");
		$guid=guid();
	   S($guid,$data);
		$data['guid']=$guid;
	   $this->assign($data);
	   $this->viefile("@bgcheckbill");//赋值常用的一些变量
	//exit;
	//$where .= " AND (pc_id=" . $filter['com1_div'] . " or pc_id2= " . $filter['com1_div'] . " )";
	}


 //核销公司对账；
	public function hxcheckbill(){	
	//$this->CheckPower();
	   
		$data['comlist']=getcompanylist(4);		
		$comid=getgpc('comid');	
		

		$d1=getgpc('d1');//起始日期;
		$d2=getgpc('d2');//结束日期;
		$d3=getgpc('d3');//年份;
		$d4=getgpc('d4');//月份;

		 if($d4&&$d4!='null'){
			if($d3=='0'){$d3=date("Y");}
			$d5=$d3."-".sprintf("%02d",$d4);	 
		
		}
		$data['comcate']=3;
		$data['comid']=$comid;

		$data['company']=$this->getcompany($comid);
        $where=' ';
		if ($d1&&$d1!="null"){
				$d=local_strtotime($d1);
				$where .= " AND postdate >=".$d."";
		}
		if ($d2&&$d2!="null"){
				$d=local_strtotime($d2);
				$where .= " AND postdate <=".$d."";
		}
		if ($d5&&$d5!="null"){
				$d=local_strtotime($d5);
				$where .= ' and DATE_FORMAT(FROM_UNIXTIME(postdate),"%Y-%m")="'.$d5.'"';
		}	
		$sql="select id,b_code,b_tank_code,b_so,b_product_name ,pb_dzf,pb_proid ,postdate,3 as cc from jp_bill where pb_proid=".$comid.$where." 
		ORDER BY  postdate desc";
	    $d=D(); 

		 //echo $sql;
	    $rs=$d->query($sql);
	  
	  
	  $ids=array(); 
	  $idd=array(); 
	  if(is_array($rs)){
		  foreach($rs as $key=>$v){
		    
			 $rs[$key]['postdate']=local_date('Y-m-d',$rs[$key]['postdate']);			 
			 
			 $cc['pb_dzf']+=$v['pb_dzf'];
			 
			 $mm['id']=$v['id'];
			 $mm['cc']=$v['cc'];
			 $mm['money']=$v['pb_dzf'];
			 $ids[]=$mm;
			 $idd[]=$v['id'];
		  }
	  }
	  if($comid){		   
		   $data['alreadyfu']=$this->getyifu($comid);	
		   G('AUTODISPLAY',true);
	   }

	  //dump($rs);
	   $data['list']=$rs;
	   $data['d1']=$d1;
	   $data['d2']=$d2;
	   $data['d3']=$d3;
	   $data['d4']=$d4;
	   $data['countdata']=$cc;
	  $data['bank']=$this->get_banklist();
	   $uid=Genv_Cookie::get("uid");
		//F("monthmoney_".$uid,$sql);
	  

		$guid=guid();
		S($guid,$data);
		$data['guid']=$guid;
		$this->assign($data);
	$this->viefile("@hxcheckbill");
	//$where .= " AND (pc_id=" . $filter['com1_div'] . " or pc_id2= " . $filter['com1_div'] . " )";
	}
	
	//托车公司对账；
	public function tccheckbill(){	
	//$this->CheckPower();
	  
		$data['comlist']=getcompanylist(2);		
		$comid=getgpc('comid');	
		

		$d1=getgpc('d1');//起始日期;
		$d2=getgpc('d2');//结束日期;
		$d3=getgpc('d3');//年份;
		$d4=getgpc('d4');//月份;

		if($d4&&$d4!='null'){
			if($d3=='0'){$d3=date("Y");}
			$d5=$d3."-".sprintf("%02d",$d4);	 
		
		}
		$data['comcate']=2;
		$data['comid']=$comid;

		$data['company']=$this->getcompany($comid);
        $where=' ';
		if ($d1&&$d1!="null"){
				$d=local_strtotime($d1);
				$where .= " AND postdate >=".$d."";
		}
		if ($d2&&$d2!="null"){
				$d=local_strtotime($d2);
				$where .= " AND postdate <=".$d."";
		}
		if ($d5&&$d5!="null"){
				$d=local_strtotime($d5);
				$where .= ' and DATE_FORMAT(FROM_UNIXTIME(postdate),"%Y-%m")="'.$d5.'"';
		}	
		$sql="select id,b_code ,b_tank_code,b_so,b_product_name,pr_id,pr_money,pr_portmoney,pr_other ,postdate,4 as cc from jp_bill where pr_id=".$comid.$where." 
		ORDER BY  postdate desc";
	    $d=D(); 

		//  echo $sql;
	    $rs=$d->query($sql);
	  
	  
	    $ids=array(); 
	  $idd=array();
	  if(is_array($rs)){
		  foreach($rs as $key=>$v){
		    
			 $rs[$key]['postdate']=local_date('Y-m-d',$rs[$key]['postdate']);			 
			 $cc['pr_money']+=$v['pr_money'];	
			 $cc['pr_portmoney']+=$v['pr_portmoney'];
			 $cc['pr_other']+=$v['pr_other'];
			 $mm['id']=$v['id'];
			 $mm['cc']=$v['cc'];
			 $mm['money']=$v['pr_money']+$v['pr_portmoney']+$v['pr_other'];
			 $ids[]=$mm;
			 $idd[]=$v['id'];  
		  }
	  }
	   if($comid){		   
		   $data['alreadyfu']=$this->getyifu($comid);	
		   G('AUTODISPLAY',true);
	   }

	  //dump($rs);
	   $data['list']=$rs;
	   $data['d1']=$d1;
	   $data['d2']=$d2;
	   $data['d3']=$d3;
	   $data['d4']=$d4;
	   $data['countdata']=$cc;
	   $data['bank']=$this->get_banklist();

		$guid=guid();
		S($guid,$data);

		$data['guid']=$guid;
	   $this->assign($data);

	    $uid=Genv_Cookie::get("uid");
		//F("monthmoney_".$uid,$sql);
	   //S("tccheckbill".$uid,$data);

	   $this->viefile("@tccheckbill");
	//$where .= " AND (pc_id=" . $filter['com1_div'] . " or pc_id2= " . $filter['com1_div'] . " )";
	}
    //月结付款;
	public function batchfu(){
	
	  
 
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
	  $cate=getgpc('cate');
	  $comid=getgpc('comid');
	  if(empty($comid)){
		 $this->error('请选择公司');	  
	  }

	  

		$data['cate']=1;
		$data['postdate']=$postdate;
		$data['bankid']=$bankid;
		$data['remark']=getgpc('remark');
		$data['money']=$g_pmoney;
		$data['comid']=$comid;
		$data['comcate']=getgpc('comcate');			
		
	    $db=D('finc');	
		
		$rs=$db->add($data);
		$return=$this->getyifu($comid);
        if($rs){
		  $this->success($return);
		}else{
		   $this->error('添加失败'); 
		   
		}	
	}
   

 
   function getyifu($comid=0){
	     
	   $rs=D('finc')->query('select * from '.gettable('finc').' where comid='.$comid.' and cate=1');
       $banklist=$this->get_banklist();
	   $cc['money']=0;
	   if(is_array($rs)){
		  foreach($rs as $key=>$v){		   
			 
			 $rs[$key]['bankname']=$banklist[$v['bankid']];
			 $rs[$key]['postdate']=local_date('Y-m-d',$rs[$key]['postdate']);			
			 $cc['money']+=$v['money'];			
		  }
	  }

		$data['list']=$rs;
		$data['countmoney']=$cc['money'];
	   $this->assign($data);	  
	  
	   $this->viefile("@getyifu");
	  
	   return $this->getview();   
	
	}

	function yingshufu(){
	
	   $d1=getgpc('d1');//日期; 		 
 		if($d1==""){
			$d1=date("Y-m-d");
		}
		$d2=getgpc('d2');//日期; 		 
 		if($d2==""){
			$d2=date("Y-m-d");
		}
		 
		$d1=local_strtotime($d1);
		$d2=local_strtotime($d2);
		$db['comlist']=getcustomerbyid();
		$dcc=array();
		foreach((array)$db['comlist'] as $k=>$v){
		  $dcc[]=$v['id'];
		
		}
		$dcc=implode(',',$dcc);


		$comid=getgpc('comid');
		if($comid!=-1){
          $where=" and a.k_id=".$comid;
		  $where1=" and comid=".$comid;
		}else{
		  $where=" and a.k_id in(".$dcc.") ";
		   $where1=" and comid in(".$dcc.")";
		}

		

		

		$d=D();
		$sql="select c.j_company as name,SUM(a.count_shu) as ying_shu,a.k_id,tt.yi_shu,(SUM(a.count_shu)-tt.yi_shu) as ying_fu from jp_bill as a
left join 
(
select SUM(money) as yi_shu,comid from jp_finc 
where cate=0 ".$where1." and postdate<=".$d2." and postdate>=".$d1." 
group by comid
) as tt on a.k_id=tt.comid
left join jp_customer as c on c.id=a.k_id

where  a.postdate<=".$d2." and a.postdate>=".$d1." ".$where."
group by a.k_id";


	  
		$rs=$d->query($sql);//findall();
		$dd=array();
		if(is_array($rs)){
			foreach($rs as $key=>$v){
			   
			  $dd['ying_shu']+=floatval($v['ying_shu']);
			  $dd['yi_shu']+=floatval($v['yi_shu']);
			  $dd['ying_fu']+=floatval($v['ying_fu']);
			  	  
			}
		}
		$db['countdata']=$dd;
		$db['list']=$rs;
		$db['d1']=local_date("Y-m-d",$d1);
		$db['d2']=local_date("Y-m-d",$d2);

		
		$this->assign($db);
	
	}
	 
}

function getcompanylist($cate){
		 
		$rs=D('company')->select('id,name')->where("cate=$cate")->order('rank desc')->findall();
		return $rs;
}
function getcustomerbyid(){
		 
		$rs=D('customer')->select('id,j_company as name')->findall();
		return $rs;
} 
?>