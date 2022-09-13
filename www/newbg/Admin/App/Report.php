<?php 
/*
报表
*/
class ReportAction extends GlobalAction {
	#日报表	
   //获取所有业务员;

   public function index(){
    #报表首页
	$this->CheckPower();
       $data['rmenu']=array(
		   '日利润表'=>U('day'),
		   '周利润表'=>U('week'),
		   '月利润表'=>U('month'),
		   '季利润表'=>U('quarter'),
		   '年利润表'=>U('year'),
		   '自定义表'=>U('free'),
		   '应收明细'=>U('shudetail'),
		   //'月未收客户'=>U('yearcus'),
		   '月应付供应商'=>U('yearpro'),
		  // '报关行对账'=>U('bgcheckbill'),
		   //'核销单对账'=>U('hxcheckbill'),
		   //'托车行对账'=>U('tccheckbill'),
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
	public function day(){
	    #日报表	
		$this->CheckPower();
		I('@.Lib.Form');

		$d1=getgpc('d1');//日期; 		 
 		if($d1==""){
			$d1=date("Y-m-d");
		}	
		 
		$d1=local_strtotime($d1);		 
		$d=D();
		$d->select(array('b_busid','sum(account) as day_account','postdate'))->from(gettable('bill'));		
		$d->where('postdate='.$d1.'');
		$d->group('b_busid,postdate');
		$d->order('b_busid desc');
		$rs=$d->findall();
		 //$d->showsql();
		$rlist=$this->getsalesmanlist();

		
	    if(is_array($rs)){
			foreach($rs as $key=>$v){
			   if($rlist[$v['b_busid']]){
				$rlist[$v['b_busid']]['day_account']=$v['day_account'];
				$rlist[$v['b_busid']]['postdate']=$v['postdate'];	
			   }
			}
		}
		 
        $dd=array();
		if(is_array($rlist)){
			foreach($rlist as $key=>$v){
			  $dd[$v['dep_id']]['name']=$v['name'];
			  $dd[$v['dep_id']]['count']+=floatval($v['day_account']);
			  $dd[$v['dep_id']]['data'][]=$v;		  
			}
		}
		$db['dlist']=$dd;
		$db['d1']=local_date("Y-m-d",$d1);
		$this->assign($db);
        

		  //$d->showsql();
		 //dump($dd);	
		 
	} 
	public function week(){
	    #周报表		
		$this->CheckPower();
		I('@.Lib.Form');
		$d1=getgpc('d1');//年份;
		$d2=getgpc('d2');//月份;
 		$d3=$d1."-".sprintf("%02d",$d2);
	    if($d3=="-00"){
		 $d3=date("Y-m");
		 $d1=date("Y");
		 $d2=date("m");
		
		}
		//$d1=local_strtotime($d1);		 
		$d=D();
		$d->select(array('b_busid',
			"SUM( IF(  DATE_FORMAT(FROM_UNIXTIME(postdate) ,'%e') div 7=0 ,account,0) ) As 'c0'",
			"SUM( IF(  DATE_FORMAT(FROM_UNIXTIME(postdate) ,'%e') div 7=1 ,account,0) ) As 'c1'",
			"SUM( IF(  DATE_FORMAT(FROM_UNIXTIME(postdate) ,'%e') div 7=2 ,account,0) ) As 'c2'",
			"SUM( IF(  DATE_FORMAT(FROM_UNIXTIME(postdate) ,'%e') div 7=3 ,account,0) ) As 'c3'",
			"SUM( IF(  DATE_FORMAT(FROM_UNIXTIME(postdate) ,'%e') div 7=4 ,account,0) ) As 'c4'",
		 ));
		$d->from(gettable('bill'));		
		$d->where('DATE_FORMAT(FROM_UNIXTIME(postdate),"%Y-%m")="'.$d3.'"');
		$d->group('b_busid');
		$d->order('b_busid desc');
		$rs=$d->findall();
		
		$rlist=$this->getsalesmanlist();
 
	    if(is_array($rs)){
			foreach($rs as $key=>$v){
			      if($rlist[$v['b_busid']]){
				 
					 $rlist[$v['b_busid']]['c0']=$v['c0'];
					 $rlist[$v['b_busid']]['c1']=$v['c1'];
					 $rlist[$v['b_busid']]['c2']=$v['c2'];
					 $rlist[$v['b_busid']]['c3']=$v['c3'];
					 $rlist[$v['b_busid']]['c4']=$v['c4'];
				  }
				// $rlist[$v['b_busid']]['post_day']=$v['post_day'];
			}
	    }
		 
        $dd=array();
		$db['dt']=array();
		if(is_array($rlist)){
			foreach($rlist as $key=>$v){
			  $dd[$v['dep_id']]['name']=$v['name'];
			  $dd[$v['dep_id']]['count0']+=$v['c0'];
			  $dd[$v['dep_id']]['count1']+=$v['c1'];
			  $dd[$v['dep_id']]['count2']+=$v['c2'];
			  $dd[$v['dep_id']]['count3']+=$v['c3'];
			  $dd[$v['dep_id']]['count4']+=$v['c4'];
			  $dd[$v['dep_id']]['data'][]=$v;
			  $db['dt']['count0']+=$v['c0'];
			  $db['dt']['count1']+=$v['c1'];
			  $db['dt']['count2']+=$v['c2'];
			  $db['dt']['count3']+=$v['c3'];
			  $db['dt']['count4']+=$v['c4'];
			}
		}

		// dump($dd);
		$db['dlist']=$dd;
		$db['d1']=$d1;
		$db['d2']=$d2;
		 
		 //exit;
		$this->assign($db);
		 
	} 
 

	 

	public function month(){
	    #月报表	
		 $this->CheckPower();
		I('@.Lib.Form');

		$d1=getgpc('d1');//年份;
		$d2=getgpc('d2');//月份;
 		$d3=$d1."-".sprintf("%02d",$d2);
	    if($d3=="-00"){
		 $d3=date("Y-m");
		 $d1=date("Y");
		 $d2=date("m");
		}
		//$d1=local_strtotime($d1);		 
		$d=D();
		$d->select(array('b_busid','sum(account) as day_account','DATE_FORMAT(FROM_UNIXTIME(postdate),"%e") as post_day' ,'postdate'))->from(gettable('bill'));		
		$d->where('DATE_FORMAT(FROM_UNIXTIME(postdate),"%Y-%m")="'.$d3.'"');
		$d->group('b_busid,postdate');
		$d->order('b_busid desc');
		$rs=$d->findall();
		 //$d->getsql();
		  
		$rlist=$this->getsalesmanlist();
		 // dump($rlist);
		  
	    $dd=array();
		//$db['dt']=array();
		
		foreach($rs as $key=>$v){
			if(isset($rlist[$v['b_busid']])){ 	  
			   $rlist[$v['b_busid']]['data'][$v['post_day']]=$v['day_account'];
			   $db['dc'][$v['post_day']]+=$v['day_account'];
			}
		}
		
        //dump($rlist);
		// dump($db);
		// exit;
		foreach($rlist as $key=>$v){
		  $dd[$v['dep_id']]['name']=$v['name'];		 
		  if(is_array($v['data'])){
			  foreach($v['data'] as $m=>$n){
				 $dd[$v['dep_id']]['count'][$m]+=$n;
			  }
		  }
		  $dd[$v['dep_id']]['data'][]= $v;	
		  //$db['dt'][$v['dep_id']][$v['post_day']]+=$v['day_account'];
		}
 
	 
		$db['dlist']=$dd;
		$db['d1']=$d1;
		$db['d2']=$d2;
		$db['days']=cal_days_in_month(CAL_GREGORIAN, $d2, $d1);

		// dump($db);
		// exit;
		$this->assign($db);
		 
	} 

	public function quarter(){
	    #季报表
		$this->CheckPower();
		I('@.Lib.Form');
		$d1=getgpc('d1');//年份;
		 
		//$d2=getgpc('d2');//月份;
 		//$d3=$d1."-".sprintf("%02d",$d2);
	    if($d1=="0"||is_null($d1)){
		  $d3=date("Y");
		  $d1=date("Y");	
		}
		 
		//$d1=local_strtotime($d1);		 
		$d=D();
		$d->select(array('b_busid',
			"SUM( IF(  QUARTER(FROM_UNIXTIME(postdate)) =1 ,account,0) ) As 'c0'",
			"SUM( IF(  QUARTER(FROM_UNIXTIME(postdate)) =2 ,account,0) ) As 'c1'",
			"SUM( IF(  QUARTER(FROM_UNIXTIME(postdate)) =3 ,account,0) ) As 'c2'",
			"SUM( IF(  QUARTER(FROM_UNIXTIME(postdate)) =4 ,account,0) ) As 'c3'"
		 ));
		$d->from(gettable('bill'));		
		$d->where('DATE_FORMAT(FROM_UNIXTIME(postdate),"%Y")="'.$d1.'"');
		$d->group('b_busid');
		$d->order('b_busid desc');
		$rs=$d->findall();
		//$d->getsql();
		// exit;
		$rlist=$this->getsalesmanlist();
 
	    if(is_array($rs)){
			foreach($rs as $key=>$v){
			      if($rlist[$v['b_busid']]){
				 
					 $rlist[$v['b_busid']]['c0']=$v['c0'];
					 $rlist[$v['b_busid']]['c1']=$v['c1'];
					 $rlist[$v['b_busid']]['c2']=$v['c2'];
					 $rlist[$v['b_busid']]['c3']=$v['c3'];
					 $rlist[$v['b_busid']]['c4']=$v['c4'];
				  }
				// $rlist[$v['b_busid']]['post_day']=$v['post_day'];
			}
	    }
		 
        $dd=array();
		$db['dt']=array();
		if(is_array($rlist)){
			foreach($rlist as $key=>$v){
			  $dd[$v['dep_id']]['name']=$v['name'];
			  $dd[$v['dep_id']]['count0']+=$v['c0'];
			  $dd[$v['dep_id']]['count1']+=$v['c1'];
			  $dd[$v['dep_id']]['count2']+=$v['c2'];
			  $dd[$v['dep_id']]['count3']+=$v['c3'];
			 
			  $dd[$v['dep_id']]['data'][]=$v;
			  $db['dt']['count0']+=$v['c0'];
			  $db['dt']['count1']+=$v['c1'];
			  $db['dt']['count2']+=$v['c2'];
			  $db['dt']['count3']+=$v['c3'];
			 
			}
		}

		  //dump($dd);
		  //exit;
		$db['dlist']=$dd;
		$db['d1']=$d1;
		$db['d2']=$d2;
		 
		 //exit;
		$this->assign($db);
		 
	} 
	public function year(){
	    #年报表		
		$this->CheckPower();
		I('@.Lib.Form');

		//dump($_POST);
		$d1=getgpc('d1');//年份;
		 
		//$d2=getgpc('d2');//月份;
 		//$d3=$d1."-".sprintf("%02d",$d2);
		//dump($d1);
	    if($d1=="0"||is_null($d1)){
		  $d3=date("Y");
		  $d1=date("Y");	
		}else{
		  $d3=$d1;
		
		}
		 
		// exit;
		//$d1=local_strtotime($d1);		 
		$d=D();
		$d->select(array('b_busid',
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(postdate), '%m') =1 ,account,0) ) As 'c1'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(postdate), '%m') =2 ,account,0) ) As 'c2'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(postdate), '%m') =3 ,account,0) ) As 'c3'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(postdate), '%m') =4 ,account,0) ) As 'c4'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(postdate), '%m') =5 ,account,0) ) As 'c5'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(postdate), '%m') =6 ,account,0) ) As 'c6'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(postdate), '%m') =7 ,account,0) ) As 'c7'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(postdate), '%m') =8 ,account,0) ) As 'c8'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(postdate), '%m') =9 ,account,0) ) As 'c9'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(postdate), '%m') =10 ,account,0) ) As 'c10'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(postdate), '%m') =11 ,account,0) ) As 'c11'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(postdate), '%m') =12 ,account,0) ) As 'c12'",
			"sum(account) as 'cc' ",
		 ));
		$d->from(gettable('bill'));		
		$d->where('DATE_FORMAT(FROM_UNIXTIME(postdate),"%Y")="'.$d1.'"');
		$d->group('b_busid');
		$d->order('b_busid desc');
		$rs=$d->findall();
		 // $d->getsql();
		 
		 for($i=1;$i<13;$i++){
		// echo '$v["c'.$i.'"]=0;'."<br>";
			//echo   '$db["dt"]["count'.$i.'"]+=$v["c'.$i.'"];'."<br>";
			// ' $dd[$v["dep_id"]]["count'.$i.'"]+=$v["c'.$i.'"];'."<br>";
			 
				 
		 }
		 //exit;
		$rlist=$this->getsalesmanlist();
 
	    if(is_array($rs)){
			foreach($rs as $key=>$v){
			   if($rlist[$v['b_busid']]){
				 
				$rlist[$v["b_busid"]]["c1"]=$v["c1"];
				$rlist[$v["b_busid"]]["c2"]=$v["c2"];
				$rlist[$v["b_busid"]]["c3"]=$v["c3"];
				$rlist[$v["b_busid"]]["c4"]=$v["c4"];
				$rlist[$v["b_busid"]]["c5"]=$v["c5"];
				$rlist[$v["b_busid"]]["c6"]=$v["c6"];
				$rlist[$v["b_busid"]]["c7"]=$v["c7"];
				$rlist[$v["b_busid"]]["c8"]=$v["c8"];
				$rlist[$v["b_busid"]]["c9"]=$v["c9"];
				$rlist[$v["b_busid"]]["c10"]=$v["c10"];
				$rlist[$v["b_busid"]]["c11"]=$v["c11"];
				$rlist[$v["b_busid"]]["c12"]=$v["c12"];
				$rlist[$v["b_busid"]]["cc"]=$v["cc"];
			   }
				// $rlist[$v['b_busid']]['post_day']=$v['post_day'];
			}
	    }
		 
        $dd=array();
		$db['dt']=array();
		if(is_array($rlist)){
			foreach($rlist as $key=>$v){
			    $dd[$v['dep_id']]['name']=$v['name'];
			    $dd[$v["dep_id"]]["count1"]+=$v["c1"];
				$dd[$v["dep_id"]]["count2"]+=$v["c2"];
				$dd[$v["dep_id"]]["count3"]+=$v["c3"];
				$dd[$v["dep_id"]]["count4"]+=$v["c4"];
				$dd[$v["dep_id"]]["count5"]+=$v["c5"];
				$dd[$v["dep_id"]]["count6"]+=$v["c6"];
				$dd[$v["dep_id"]]["count7"]+=$v["c7"];
				$dd[$v["dep_id"]]["count8"]+=$v["c8"];
				$dd[$v["dep_id"]]["count9"]+=$v["c9"];
				$dd[$v["dep_id"]]["count10"]+=$v["c10"];
				$dd[$v["dep_id"]]["count11"]+=$v["c11"];
				$dd[$v["dep_id"]]["count12"]+=$v["c12"];
				$dd[$v["dep_id"]]["count13"]+=$v["cc"];

			 
			    $dd[$v['dep_id']]['data'][]=$v;
			    $db["dt"]["count1"]+=$v["c1"];
				$db["dt"]["count2"]+=$v["c2"];
				$db["dt"]["count3"]+=$v["c3"];
				$db["dt"]["count4"]+=$v["c4"];
				$db["dt"]["count5"]+=$v["c5"];
				$db["dt"]["count6"]+=$v["c6"];
				$db["dt"]["count7"]+=$v["c7"];
				$db["dt"]["count8"]+=$v["c8"];
				$db["dt"]["count9"]+=$v["c9"];
				$db["dt"]["count10"]+=$v["c10"];
				$db["dt"]["count11"]+=$v["c11"];
				$db["dt"]["count12"]+=$v["c12"];
				$db["dt"]["count13"]+=$v["cc"];
			 
			}
		}

		 
		$db['dlist']=$dd;
		$db['d1']=$d1;
		$db['d2']=$d2;		 
		$this->assign($db);
		 
	} 
    //自定义时间段
	public function free(){
	    #自定义报表	
		$this->CheckPower();
		I('@.Lib.Form');
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
		$d=D();
		$d->select(array('b_busid','sum(account) as day_account','postdate'))->from(gettable('bill'));		
		$d->where('postdate<='.$d2.' and postdate>='.$d1.'');
		$d->group('b_busid');
		$d->order('b_busid desc');
		$rs=$d->findall();
		 //$d->showsql();
		// exit;
		$rlist=$this->getsalesmanlist();
	    if(is_array($rs)){
			foreach($rs as $key=>$v){
				 if($rlist[$v['b_busid']]){
					$rlist[$v['b_busid']]['day_account']=$v['day_account'];
					$rlist[$v['b_busid']]['postdate']=$v['postdate'];	
				  }
			    		
			}
		}
        $dd=array();
		if(is_array($rlist)){
			foreach($rlist as $key=>$v){
			  $dd[$v['dep_id']]['name']=$v['name'];
			  $dd[$v['dep_id']]['count']+=floatval($v['day_account']);
			  $dd[$v['dep_id']]['data'][]=$v;		  
			}
		}
		$db['dlist']=$dd;
		$db['d1']=local_date("Y-m-d",$d1);
		$db['d2']=local_date("Y-m-d",$d2);
		$this->assign($db);
        

		  //$d->showsql();
		 //dump($dd);	
		 
	} 
	//月度应收明细
	public function shudetail(){
	    #年度应收明细 	
		$this->CheckPower();
		I('@.Lib.Form');
		$yw=getgpc('yw');//年份;
		$d1=getgpc('d1');//年份;
		$d2=getgpc('d2');//月份;
 		$d3=$d1."-".sprintf("%02d",$d2);
	    if($d3=="-00"){
		 $d3=date("Y-m");
		 $d1=date("Y");
		 $d2=date("m");
		}
		//$d1=local_strtotime($d1);		 
		$d=D();
		$d->selectfrom(gettable('bill')." AS a",array('b_busid','k_id','sum(count_shu) as yingshu'));		
		$d->leftJoin(gettable('customer').' AS b ','a.k_id=b.id','j_company');		

		$d->where('DATE_FORMAT(FROM_UNIXTIME(a.postdate),"%Y-%m")="'.$d3.'"');
		$d->group('a.b_busid,a.k_id');
		$d->order('a.b_busid desc');
		$sql=$d->fetch('sql');
		 //echo $sql;
		//exit;
		$rs=$d->query($sql);
		 
		$rlist=$this->getsalesmanlist();
		$dd['userlist']=$rlist;
		// dump($rlist);

		 $userlist=$this->getuserlist();
		$dbb=array();
		$db['dt']=array();
	    if(is_array($rs)){
			foreach($rs as $key=>$v){
                $cc=$v;				 
				$dbb[$cc['b_busid']]['username']=$userlist[$v['b_busid']];
				$dbb[$cc['b_busid']]['data'][]=$cc;
				
				$dbb[$cc['b_busid']]['yingshu']+=$v['yingshu'];
				 
				 
				$db['dt']['yingshu']+=$v['yingshu'];
				 
					
			}
		}
		 
		// dump($dbb);
		$db['dlist']=$dbb;
		$db['d1']=$d1;
		$db['d2']=$d2;//local_date("Y-m-d",$d2);
		$db['yw']=$yw;
		$this->assign($db);
        

		  //$d->showsql();
		 //dump($dd);	
		 
	}  
    //年度客户未付款
	public function yearcus(){
	    #年度客户未付款 	
		$this->CheckPower();
		I('@.Lib.Form');
		$d1=getgpc('d1');		
	    if($d1=="0"||is_null($d1)){
		  
		  $d1=date("Y");
		}
		 
		// exit;
		//$d1=local_strtotime($d1);		 
		$d=D();
      
		$d->selectfrom(gettable('bill')." AS a",array('b_busid','k_id',
            "SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =1 ,a.count_shu-a.shi_shu,0) ) As 'c1'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =2 ,a.count_shu-a.shi_shu,0) ) As 'c2'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =3 ,a.count_shu-a.shi_shu,0) ) As 'c3'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =4 ,a.count_shu-a.shi_shu,0) ) As 'c4'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =5 ,a.count_shu-a.shi_shu,0) ) As 'c5'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =6 ,a.count_shu-a.shi_shu,0) ) As 'c6'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =7 ,a.count_shu-a.shi_shu,0) ) As 'c7'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =8 ,a.count_shu-a.shi_shu,0) ) As 'c8'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =9 ,a.count_shu-a.shi_shu,0) ) As 'c9'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =10 ,a.count_shu-a.shi_shu,0) ) As 'c10'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =11 ,a.count_shu-a.shi_shu,0) ) As 'c11'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%m') =12 ,a.count_shu-a.shi_shu,0) ) As 'c12'",
			"SUM( IF( DATE_FORMAT(FROM_UNIXTIME(a.postdate), '%Y') =".$d1." ,a.count_shu-a.shi_shu,0) ) As 'c13'"
		
		));		
		$d->leftJoin(gettable('customer').' AS b ','a.k_id=b.id','j_company');		

		$d->where('DATE_FORMAT(FROM_UNIXTIME(a.postdate),"%Y")="'.$d1.'"');
		$d->group('a.b_busid,a.k_id');
		$d->order('a.b_busid desc');
		$sql=$d->fetch('sql');
		// echo $sql;
		$rs=$d->query($sql);
		// $d->getsql();
		 // dump($rs);
		// exit;
		 for($i=1;$i<13;$i++){
		// echo '$v["c'.$i.'"]=0;'."<br>";
			//echo   '$db["dt"]["count'.$i.'"]+=$v["c'.$i.'"];'."<br>";
			// ' $dd[$v["dep_id"]]["count'.$i.'"]+=$v["c'.$i.'"];'."<br>";
			 
				 
		 }
		$rlist=$this->getsalesmanlist();
		$dd['userlist']=$rlist;

		$userlist=$this->getuserlist();
		$dbb=array();
		$db['dt']=array();
	    if(is_array($rs)){
			foreach($rs as $key=>$v){
                $cc=$v;				 
				$dbb[$cc['b_busid']]['username']=$userlist[$v['b_busid']];
				$dbb[$cc['b_busid']]['data'][]=$cc;
				 
				for($i=1;$i<=13;$i++){
					$dbb[$cc['b_busid']]['count'.$i]+=$v['c'.$i];
					$db["dt"]["count".$i]+=$v["c".$i];
				}  
					
			}
		}
 
	   

		 
		$db['dlist']=$dbb;
		$db['d1']=$d1;
		$db['d2']=$d2;		 
		$this->assign($db);
		 
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