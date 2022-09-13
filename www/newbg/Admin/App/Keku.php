<?php 
/*
客户月份收支表;
*/
class KekuAction extends GlobalAction {
	#月结付款
   

   public function index(){
        #对账首页
		 $this->CheckPower();

	 

		$dt=getgpc('dt');//年份; 
		$userid=getgpc('userid'); 
		 
	    $comid=getgpc('comid');


		 

       

		$sql="select sum(count_shu) as ying_shu,DATE_FORMAT(FROM_UNIXTIME(postdate), '%m') as fmonth from jp_bill where k_id=".$comid." and DATE_FORMAT(FROM_UNIXTIME(postdate), '%Y') =".$dt." group by DATE_FORMAT(FROM_UNIXTIME(postdate), '%m')";

		$rs=D()->query($sql);

 
		$sql="select sum(money) as yi_shu,fmonth from jp_finc where comid=".$comid." and cate=0 and  fyear=".$dt." group by fmonth";

		 

		$rs1=D()->query($sql);
		
		$rs2=array();
		foreach((array)$rs1 as $k=>$v){
		
		  $rs2[$v['fmonth']]['yi_shu']=$v['yi_shu'];
		
		}

		foreach((array)$rs as $k=>$v){
		
		 $rs2[$v['fmonth']]['ying_shu']=$v['ying_shu'];
		
		}
        //dump($rs2);
		foreach((array)$rs2 as $k=>$v){
		
		     $rs2[$k]['wei_shu']=floatval($v['ying_shu'])-floatval($v['yi_shu']);
		
		}
  //dump($rs2);

		$dd=array();
		if(is_array($rs2)){
			foreach($rs2 as $key=>$v){
			   
			  $dd['ying_shu']+=floatval($v['ying_shu']);
			  $dd['yi_shu']+=floatval($v['yi_shu']);
			  $dd['wei_shu']+=floatval($v['wei_shu']);
			  	  
			}
		}


 
		$db['countdata']=$dd; 
		$db['list']=$rs2;
		$db['dt']=$dt;
	    $db['userid']=$userid; 


	    if($userid){
			$db['username']=getuserbyid($userid);
        } 
            
		 
		$db['comid']=$comid;  
		 if($comid){
	    $db['comname']=getcombyid($comid);
		 }
		$db['userlist']=getsalesmanlist(); 
		$this->assign($db);
	
	}
	 
}

function getcompanylist($cate){
		 
		$rs=D('company')->select('id,name')->where("cate=$cate")->findall();		
		return $rs;
}
function getcustomerbyid(){
		 
		$rs=D('customer')->select('id,j_company as name')->order(' convert(j_company USING gbk) COLLATE gbk_chinese_ci')->findall();
		return $rs;
} 

function getcombyid($id){
		 
		$rs=D('customer')->select('id,j_company')->find($id);

		
		return $rs['j_company'];
} 


function getuserbyid($id){
		 
		$rs=D('sysmember')->select('id,username')->find($id);
		
		//D()->getsql();
		return $rs['username'];
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