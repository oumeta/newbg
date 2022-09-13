<?php
/*
框架核心;
*/
//框架路径;
define("SYSPATH", str_replace("\\", "/", dirname(__FILE__)));
 
define("Genv", "./Genv/");
require Genv.'Genv.php'; 

Genv::client("Admin");
Genv::start();
exceltrustbill();
function exceltrustbill(){
	header("Content-Type: application/x-msexcel; name=\"委托单.xls\"");
	header("Content-Disposition: inline; filename=\"委托单.xls\"");
	#委托单
	//$this->_response->setHeader("Content-Type: application/x-msexcel; name=\"bill.xls\"");
	$id=getgpc('id');
	$rs=D('bill')->find($id);
   //获取业务员姓名;
	$bus=D('sysmember')->find($rs['b_opeid']);		
	$rs['username']=$bus['username'];
	$rs['tel']=$bus['phone_tel'];
	//echo $rs['pc_id'];

	$rs["pc_company"]=getcompany($rs['pc_id']);
	$rs['postdate']=date("Y-m-d");
	$cc=D('trustbill')->where('bid='.$id)->find();
	//$this->assign("cc",$cc);
	$data['cc']=$cc;
	$data['rs']=$rs;
	//$this->assign("rs",$rs);
	// dump($data);
	 V($data)->display('actbill.trustbill1');
}
	  function getcompany($id){
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

Genv::stop();

