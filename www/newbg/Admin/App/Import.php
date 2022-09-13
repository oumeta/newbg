<?php 
/*
导出
*/
class ImportAction extends GlobalAction {
  #导出;
   

   public function fincebill(){
        #费用确认
		
 

		 

		$d=D();
		$uid=Genv_Cookie::get("uid");
        $sql=F("fincbill_".$uid);

		 
		if(!$sql){ 	 
		
			return false;
		}
 
 
 

		$rs=$d->query($sql);//findall();

  
	  $status=$this->get_bill_status();
	  $userlist=$this->getuserlist();
	   $cc['count_shu']=0;
		$cc['count_zhi']=0;
		$cc['account']=0;
		$cc['shi_shu']=0;
		$cc['shi_zhi']=0;

		$status=array('下单','操作','查柜','放行','锁定','解锁');
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
			 $rs[$key]['status']=$status[ $rs[$key]['status']];

			 $cc['count_shu']+=$v['count_shu'];
			 $cc['count_zhi']+=$v['count_zhi'];
			 $cc['account']+=$v['account'];
				
		  }
	  }	 
	 
	 $countdiv=" 应收：{$cc['count_shu']}&nbsp;&nbsp;利润：{$cc['account']}&nbsp;&nbsp;应付：{$cc['count_zhi']}&nbsp;&nbsp;";

	 $arr = array('list' => $rs, 'countdiv'=>$countdiv);
 	 S("fincbill_data".$uid,$arr);


	 $a= S("fincbill_data".$uid);
 
     $data['url']= str_replace('index.php','',__APP__).'import_finc.php';
     //redirect($location,'',true);
	 V($data)->display('jump');

	 
	 //header("Location: $location", true);
		
	
	}

	function bgcheckbill(){

		$guid=getgpc("guid");
		$data=S($guid);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$guid.'.xls"');
		header('Cache-Control: max-age=0');

		 
	    $this->assign($data);
	    $this->viefile("@bgcheckbill");//赋值常用的一些变量 
	
	}
	function hxcheckbill(){
		$guid=getgpc("guid");
		$data=S($guid);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$guid.'.xls"');
		header('Cache-Control: max-age=0');



	    $this->assign($data);
	    $this->viefile("@hxcheckbill");//赋值常用的一些变量 
	
	}
	function tccheckbill(){
		$guid=getgpc("guid");
		$data=S($guid);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$guid.'.xls"');
		header('Cache-Control: max-age=0');



	    $this->assign($data);
	    $this->viefile("@tccheckbill");//赋值常用的一些变量 
	
	}

	
	 
}

 ?>