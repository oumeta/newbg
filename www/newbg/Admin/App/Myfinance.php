<?php 
/*
业务之我的应收
*/
class MyfinanceAction extends GlobalAction {
	#日报表	
   //获取所有业务员;
 

   public function index(){
        #查看我的应收
		$this->CheckPower();

	
	    $d1=getgpc('d1');//日期; 		 
 		 
		$d2=getgpc('d2');//日期; 		 
 		 
		//$d1=local_strtotime($d1);
		//$d2=local_strtotime($d2);

		$d3=getgpc('d3');//年份;
		$d4=getgpc('d4');//月份;

		$userid=getgpc('userid');

		if($d4&&$d4!='null'){
			if($d3=='0'){
			$d3=date("Y");}
			$d5=$d3."-".sprintf("%02d",$d4);	 
		
		}


		$db['comlist']=getcustomerbyid();
		$dcc=array();
		foreach((array)$db['comlist'] as $k=>$v){
		  $dcc[]=$v['id'];
		
		}
		$dcc=implode(',',$dcc);


		//$comid=getgpc('comid');
		$where='1=1 ';
		 
        $userid=Genv_Cookie::get("uid");
		 
	    $where.=" and a.b_busid=".$userid;
		
		 
/*
		if($comid!=-1){
          $where.=" and a.k_id=".$comid;
		  $where1.=" and comid=".$comid;
		}else{
		  $where.=" and a.k_id in(".$dcc.") ";
		   $where1.=" and comid in(".$dcc.")";
		}



		if ($d1&&$d1!="null"){
				$d=local_strtotime($d1);
				$where .= " AND a.postdate >=".$d."";
				$where1 .= " AND postdate >=".$d."";
		}
		if ($d2&&$d2!="null"){
				$d=local_strtotime($d2);
				$where .= " AND a.postdate <=".$d."";
				$where1 .= " AND postdate <=".$d."";
		}*/
		if ($d5&&$d5!="null"){
				$d=local_strtotime($d5);
				$where .= ' and DATE_FORMAT(FROM_UNIXTIME(a.postdate),"%Y-%m")="'.$d5.'"';
				$where1 .= ' and CONCAT(fyear,"-",fmonth)="'.$d5.'"';
		}	

		

		

		$d=D();
	$sql="select u.username,c.j_company as name,SUM(a.count_shu) as ying_shu,a.k_id,tt.yi_shu,(SUM(a.count_shu)-tt.yi_shu) as ying_fu from jp_bill as a
left join 
(
select SUM(money) as yi_shu,comid from jp_finc 
where cate=0 ".$where1."  
group by comid
) as tt on a.k_id=tt.comid
left join jp_customer as c on c.id=a.k_id
left join jp_sysmember as u on u.id=a.b_busid

where    ".$where." group by a.k_id order by convert(u.username USING gbk) COLLATE gbk_chinese_ci  ";

 
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
		$db['d1']=$d1;
	    $db['d2']=$d2;
	    $db['d3']=$d3;
	    $db['d4']=$d4;
		$db['userid']=getgpc('userid');

		 

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