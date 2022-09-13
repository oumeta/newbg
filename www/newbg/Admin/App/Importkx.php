<?php 
/*
应收款对账
*/
class ImportkxAction extends GlobalAction {
	#导出对账单;
   

   public function index(){
        #对账首页
		
 

		$d3=getgpc('d3');//年份;
		$d4=getgpc('d4');//月份;

		$kid=getgpc('kid');
		$import_bill=getgpc('import_bill');

		if($d4&&$d4!='null'){
			if($d3=='0'){
			$d3=date("Y");}
			 
		} 


		
		$d5=$d3."-".sprintf("%02d",$d4);
		 
 
		 

		//$comid=getgpc('comid');
		$where='1=1 ';
	 
 
 
		if ($d5&&$d5!="null"){
				$d=local_strtotime($d5);
				$where .= ' and DATE_FORMAT(FROM_UNIXTIME(postdate),"%Y-%m")="'.$d5.'"';
				 
		}	

		

		

		$d=D();
        $sql="select * ,DATE_FORMAT(FROM_UNIXTIME(postdate),'%Y-%m-%d') as postdate, DATE_FORMAT(FROM_UNIXTIME(finshdate),'%Y-%m-%d') as finshdate from jp_bill where  ".$where." and k_id=".$kid." order by postdate asc"; 
 
 
 

		$rs=$d->query($sql);//findall();


 


		$dd=array();
		if(is_array($rs)){
			foreach($rs as $key=>$v){
			   
			  $dd['count_shu']+=floatval($v['count_shu']);
			  
			  	  
			}
		}
	 
		$db['countdata']=$dd;
		$db['list']=$rs;
		
	    $db['d3']=$d3;
	    $db['d4']=$d4;
		$db['kid']=$kid;	
		
		$banklist=$this->get_banklist1();

		$dd=array(); 
		foreach($banklist as $k=>$v){ 
			
		  if(!empty($v['bankcode'])){
 
		    $dd[]=$v;		  
		  }		
		}

		$db['banklist']=$dd;
	    if($import_bill==1){	   
	       $this->importexcl($db);
		   exit;
	   
	   }
		
		$this->assign($db);
	
	}

	function importexcl($data){


		$bankids=  $_POST['keyid'] ; 
		$banklist=$this->get_banklist1();
        $dd=array(); 
		foreach($banklist as $k=>$v){ 
			
		  if(in_array($v['id'],$bankids)&&!empty($v['bankcode'])){

 
		    $dd[]=$v;		  
		  }		
		}

	 
 
		$data['company']=getcustomerbyid($data['kid']);	
        $data['banklist']=$dd;

 
		$data['mycompany'] = FC('system_config');


		$time=time();
		S('bbbb_'.$time,$data);


		 
 
		$location= str_replace('index.php','',__APP__).'import.php?id='.$time;


 
		  header("Location: $location", true);

 

		
	
	}
	 
}

function getcompanylist($cate){
		 
		$rs=D('company')->select('id,name')->where("cate=$cate")->findall();		
		return $rs;
}
function getcustomerbyid($kid){
		 
		$rs=D('customer')->select('id,j_company as name')->find($kid);
		return $rs;
} 

//获取业务员;
 function getsalesmanlist(){	 
		
		
		$select=D(); 
		$select->selectfrom(gettable('sysmember')." AS a " ,array('id','username','real_name') );
		$select->leftJoin(gettable('sysdep').' AS b','a.dep_id=b.id ','isc');
		$select->where('isc=1'); 
		 
		$select->order(' convert(a.username USING gbk) COLLATE gbk_chinese_ci');
		$count=$select->countPages("b.id");			 	 
		$sql=$select->fetch('sql');			 
		$rs=$select->query($sql);
		
		 
		return $rs;
	}
?>