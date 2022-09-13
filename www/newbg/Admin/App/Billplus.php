<?php
/*
应付消账;
*/
 
class  BillplusAction extends GlobalAction{
    #应付消账    
	#日报表	
   //获取所有业务员;

   public function index(){
    #应付消账 
	$this->CheckPower();
       $data['rmenu']=array(
		  
		   '报关行对账'=>U('bgcheckbill'),
		   '核销单对账'=>U('hxcheckbill'),
		   '托车行对账'=>U('tccheckbill'),
	   );
	   $this->assign($data);
   
   }
   
   public function getsalesmanlist(){ 
	   
	  $data=F("getsalesmanlist");
	  
	  if(empty($data)){
		$select=D(); 
		$select->selectfrom(gettable('sysmember')." AS a " ,array('id','username','real_name','dep_id') );
		$select->leftJoin(gettable('sysdep').' AS b','a.dep_id=b.id ','name');
		$select->where('isc=1');		 
		$select->order('a.dep_id, convert(a.username USING gbk) COLLATE gbk_chinese_ci');
		$sql=$select->fetch('sql');
		// echo $sql;
		$rs=$select->query($sql);	
		$data=array();
		foreach($rs as $key=>$v){
			$v['day_account']=0;
			$v['postdate']=0;
			$v['post_day']=0;
			$v['c0']=0;
			$v['c1']=0;
			$v["c2"]=0;
			$v["c3"]=0;
			$v["c4"]=0;
			$v["c5"]=0;
			$v["c6"]=0;
			$v["c7"]=0;
			$v["c8"]=0;
			$v["c9"]=0;
			$v["c10"]=0;
			$v["c11"]=0;
			$v["c12"]=0;
			$data[$v['id']]=$v;
		  
		}
		
		F("getsalesmanlist",$data);
	  } 
	  //dump($data);
	  return $data;		 
	}
	
	//供应商应付
	function yearpro(){
	   #应付给供应商
	   $this->CheckPower();
	   $cate=1;
	   $cate=getgpc('cate');
	   I('@.Lib.Form');
	  
	   switch($cate){
		  case 0:
			   $this->getbgreport1();
				break;
	       case 1:
			   $this->getbgreport2();
				break;
		   case 2:
			   $this->gettuoreport();
				 break;
		   case 3:
			   $this->gethereport();
				break;
		   default:
			   $this->getbgreport1();
				break;
	   }
	   //echo $this->reportsql;
	   $rs=D()->query(	$this->reportsql);
		
		
		$dbb=array();
		$db['dt']=array();
	    if(is_array($rs)){
			foreach($rs as $key=>$v){
                
				for($i=1;$i<=13;$i++){
					 
					$db["dt"]["count".$i]+=$v["c".$i];
				}  
					
			}
		}		 
		$db['dlist']=$rs;
		$db['d1']=$d1;
		$db['d2']=$d2;		 
		$this->assign($db);
	}
	//报关公司１
	public function getbgreport1(){	
		$d1=getgpc('d1');		
	    if($d1=="0"||is_null($d1)){		  
		  $d1=date("Y");
	    }  	 
		$d=D();
        $cccc="a.pc_bgmoney+a.pc_portmoney+a.pc_tankmoney+a.pc_other";
		$d->selectfrom(gettable('bill')." AS a",array('pc_id as cid',
            "SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =1 ,".$cccc.",0) ) As 'c1'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =2 ,".$cccc.",0) ) As 'c2'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =3 ,".$cccc.",0) ) As 'c3'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =4 ,".$cccc.",0) ) As 'c4'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =5 ,".$cccc.",0) ) As 'c5'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =6 ,".$cccc.",0) ) As 'c6'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =7 ,".$cccc.",0) ) As 'c7'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =8 ,".$cccc.",0) ) As 'c8'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =9 ,".$cccc.",0) ) As 'c9'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =10 ,".$cccc.",0) ) As 'c10'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =11 ,".$cccc.",0) ) As 'c11'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =12 ,".$cccc.",0) ) As 'c12'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%Y') =".$d1." ,".$cccc.",0) ) As 'c13'"

		
		));		
		$d->leftJoin(gettable('company').' AS b ','a.pc_id=b.id','name');		

		$d->where('a.pc_id<>"" and DATE_FORMAT(FROM_UNIXTIME(a.postdate),"%Y")="'.$d1.'"');
		$d->group('a.pc_id');
		$d->order('a.pc_id desc');
		$this->reportsql=$d->fetch('sql');
	}
    //报关公司2
	public function getbgreport2(){	
		 $d1=getgpc('d1');		
	   if($d1=="0"||is_null($d1)){		  
		  $d1=date("Y");
	   }
		$d=D();
        $cccc="a.pc_bgmoney2+a.pc_tankmoney2+a.pc_other2";
		$d->selectfrom(gettable('bill')." AS a",array('pc_id2 as cid',
            "SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =1 ,".$cccc.",0) ) As 'c1'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =2 ,".$cccc.",0) ) As 'c2'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =3 ,".$cccc.",0) ) As 'c3'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =4 ,".$cccc.",0) ) As 'c4'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =5 ,".$cccc.",0) ) As 'c5'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =6 ,".$cccc.",0) ) As 'c6'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =7 ,".$cccc.",0) ) As 'c7'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =8 ,".$cccc.",0) ) As 'c8'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =9 ,".$cccc.",0) ) As 'c9'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =10 ,".$cccc.",0) ) As 'c10'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =11 ,".$cccc.",0) ) As 'c11'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =12 ,".$cccc.",0) ) As 'c12'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%Y') =".$d1." ,".$cccc.",0) ) As 'c13'"

		
		));		
		$d->leftJoin(gettable('company').' AS b ','a.pc_id2=b.id','name');		

		$d->where('a.pc_id2<>"" and DATE_FORMAT(FROM_UNIXTIME(a.postdate),"%Y")="'.$d1.'"');
		$d->group('a.pc_id2');
		$d->order('a.pc_id2 desc');
		$this->reportsql=$d->fetch('sql');		 
	} 

	
	//托车行;
	public function gettuoreport(){
		 $d1=getgpc('d1');		
	   if($d1=="0"||is_null($d1)){		  
		  $d1=date("Y");
	   }
	  	 
		$d=D();
        $cccc="a.pr_money+a.pr_portmoney+a.pr_other";
		$d->selectfrom(gettable('bill')." AS a",array('pr_id as cid',
            "SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =1 ,".$cccc.",0) ) As 'c1'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =2 ,".$cccc.",0) ) As 'c2'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =3 ,".$cccc.",0) ) As 'c3'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =4 ,".$cccc.",0) ) As 'c4'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =5 ,".$cccc.",0) ) As 'c5'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =6 ,".$cccc.",0) ) As 'c6'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =7 ,".$cccc.",0) ) As 'c7'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =8 ,".$cccc.",0) ) As 'c8'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =9 ,".$cccc.",0) ) As 'c9'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =10 ,".$cccc.",0) ) As 'c10'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =11 ,".$cccc.",0) ) As 'c11'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =12 ,".$cccc.",0) ) As 'c12'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%Y') =".$d1." ,".$cccc.",0) ) As 'c13'"

		
		));		
		$d->leftJoin(gettable('company').' AS b ','a.pr_id=b.id','name');		

		$d->where('a.pr_id<>"" and DATE_FORMAT(FROM_UNIXTIME(a.postdate),"%Y")="'.$d1.'"');
		$d->group('a.pr_id');
		$d->order('a.pr_id desc');
		$this->reportsql=$d->fetch('sql');
	}
	 //核销单;
	public function gethereport(){
		 $d1=getgpc('d1');		
	   if($d1=="0"||is_null($d1)){		  
		  $d1=date("Y");
	   }
		$d=D();
        $cccc="a.pb_dzf";
		$d->selectfrom(gettable('bill')." AS a",array('pb_proid as cid',
            "SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =1 ,".$cccc.",0) ) As 'c1'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =2 ,".$cccc.",0) ) As 'c2'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =3 ,".$cccc.",0) ) As 'c3'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =4 ,".$cccc.",0) ) As 'c4'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =5 ,".$cccc.",0) ) As 'c5'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =6 ,".$cccc.",0) ) As 'c6'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =7 ,".$cccc.",0) ) As 'c7'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =8 ,".$cccc.",0) ) As 'c8'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =9 ,".$cccc.",0) ) As 'c9'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =10 ,".$cccc.",0) ) As 'c10'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =11 ,".$cccc.",0) ) As 'c11'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =12 ,".$cccc.",0) ) As 'c12'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%Y') =".$d1." ,".$cccc.",0) ) As 'c13'"

		
		));		
		$d->leftJoin(gettable('company').' AS b ','a.pb_proid=b.id','name');		

		$d->where('a.pb_proid<>"" and DATE_FORMAT(FROM_UNIXTIME(a.postdate),"%Y")="'.$d1.'"');
		$d->group('a.pb_proid');
		$d->order('a.pb_proid desc');
		$this->reportsql=$d->fetch('sql');
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
		$sql="(select id,b_code,b_tank_code,b_so,b_product_name,pc_id,pc_tankmoney,pc_other,pc_bgmoney,pc_portmoney ,postdate from jp_bill where pc_id=".$comid.$where." )
		union all(select id,b_code,b_tank_code,b_so,b_product_name ,pc_id2,pc_tankmoney2,pc_other2,pc_bgmoney2 ,0 as pc_portmoney ,postdate from jp_bill where pc_id2=".$comid.$where.") 
		ORDER BY  postdate desc";
	    $d=D(); 

		// echo $sql;
	    $rs=$d->query($sql);
	  
	  
	   
	  if(is_array($rs)){
		  foreach($rs as $key=>$v){
		    
			 $rs[$key]['postdate']=local_date('Y-m-d',$rs[$key]['postdate']);			 
			 $cc['pc_bgmoney']+=$v['pc_bgmoney'];
			 $cc['pc_portmoney']+=$v['pc_portmoney'];
			 $cc['pc_tankmoney']+=$v['pc_tankmoney'];
			 $cc['pc_other']+=$v['pc_other'];			 
		  }
	  }

	  //dump($rs);
	   $data['list']=$rs;
	   $data['d1']=$d1;
	   $data['d2']=$d2;
	   $data['d3']=$d3;
	   $data['d4']=$d4;
	   $data['countdata']=$cc;
	 
		$this->assign($data);
	
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
		$sql="select id,b_code,b_tank_code,b_so,b_product_name ,pb_dzf,pb_proid ,postdate from jp_bill where pb_proid=".$comid.$where." 
		ORDER BY  postdate desc";
	    $d=D(); 

		 //echo $sql;
	    $rs=$d->query($sql);
	  
	  
	   
	  if(is_array($rs)){
		  foreach($rs as $key=>$v){
		    
			 $rs[$key]['postdate']=local_date('Y-m-d',$rs[$key]['postdate']);			 
			 
			 $cc['pb_dzf']+=$v['pb_dzf'];			 
		  }
	  }

	  //dump($rs);
	   $data['list']=$rs;
	   $data['d1']=$d1;
	   $data['d2']=$d2;
	   $data['d3']=$d3;
	   $data['d4']=$d4;
	   $data['countdata']=$cc;
	 
		$this->assign($data);
	
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
		$sql="select id,b_code ,b_tank_code,b_so,b_product_name,pr_id,pr_money,pr_portmoney,pr_other ,postdate from jp_bill where pr_id=".$comid.$where." 
		ORDER BY  postdate desc";
	    $d=D(); 

		//  echo $sql;
	    $rs=$d->query($sql);
	  
	  
	   
	  if(is_array($rs)){
		  foreach($rs as $key=>$v){
		    
			 $rs[$key]['postdate']=local_date('Y-m-d',$rs[$key]['postdate']);			 
			 $cc['pr_money']+=$v['pr_money'];	
			 $cc['pr_portmoney']+=$v['pr_portmoney'];
			 $cc['pr_other']+=$v['pr_other'];
			    
		  }
	  }

	  //dump($rs);
	   $data['list']=$rs;
	   $data['d1']=$d1;
	   $data['d2']=$d2;
	   $data['d3']=$d3;
	   $data['d4']=$d4;
	   $data['countdata']=$cc;
	 
		$this->assign($data);
	
	//$where .= " AND (pc_id=" . $filter['com1_div'] . " or pc_id2= " . $filter['com1_div'] . " )";
	}
}


function getcompanylist($cate){
		 
		$rs=D('company')->select('id,name')->where("cate=$cate")->findall();		
		return $rs;
}
 
?>