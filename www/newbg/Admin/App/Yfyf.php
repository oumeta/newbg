<?php 
/*

*/
class YfyfAction extends GlobalAction {
	#应付已付
   

   public function index(){
        #对账首页
		$this->CheckPower();

	
	    $d1=getgpc('d1');//日期; 		 
 		 
		$d2=getgpc('d2');//日期; 		 
 		 
		//$d1=local_strtotime($d1);
		//$d2=local_strtotime($d2);

		$d3=getgpc('d3');//年份;
		$d4=getgpc('d4');//月份;

		$comcate=getgpc('comcate');
		$comid=getgpc('com_div');		

		


		$db['comlist']=getcustomerbyid();


		$dcc=array();
		foreach((array)$db['comlist'] as $k=>$v){
		  $dcc[]=$v['id'];
		
		}
		$dcc=implode(',',$dcc);


		//$comid=getgpc('comid');
		$where='1=1 ';
		$where1=' ';
		$where2="1=1 ";
 
		if($d3!=="0"&&$d4==="0"){
		 
				 
				$where .= ' and DATE_FORMAT(FROM_UNIXTIME(a.postdate),"%Y")="'.$d3.'"';
				$where1 .= ' and fyear="'.$d3.'"';
				$where2 .= ' and DATE_FORMAT(FROM_UNIXTIME(postdate),"%Y")="'.$d3.'"';
				

		}
		if($d4&&$d4!='null'){
			if($d3=='0'){
			$d3=date("Y");}
			$d5=$d3."-".sprintf("%02d",$d4);	 
		    $where .= ' and DATE_FORMAT(FROM_UNIXTIME(a.postdate),"%Y-%m")="'.$d5.'"';
			$where1 .= ' and CONCAT(fyear,"-",fmonth)="'.$d5.'"';
			$where2 .= ' and DATE_FORMAT(FROM_UNIXTIME(postdate),"%Y-%m")="'.$d5.'"';

		}	

		
      if($comcate==1){

		$tab='
		(select pc_id, sum(ying_fu) as ying_fu,postdate from 
		(
			(
			SELECT	 
				pc_id,
		sum(pc_tankmoney+pc_other+pc_bgmoney+pc_portmoney) as ying_fu,
				 
				postdate
			FROM
				jp_bill
			WHERE
				  '.$where2.' 
				  GROUP BY pc_id
		)
		UNION ALL(
				SELECT					 
					pc_id2,
					sum(pc_tankmoney2+pc_other2+pc_bgmoney2) as ying_fu	,	
					postdate
				FROM
					jp_bill
				WHERE
				  '.$where2.'
				  GROUP BY pc_id2
		)) as sdf group by pc_id )';

			 

		 

		
	  $sql="select c.name as name,a.ying_fu,
			 tt.yi_fu,(a.ying_fu-tt.yi_fu) as mei_fu from ".$tab." as a
			left join 
			(
			select SUM(money) as yi_fu,comid from jp_finc 
			where cate=1 ".$where1."  
			group by comid
			) as tt on a.pc_id=tt.comid

			left join jp_company as c on c.id=a.pc_id
			 
			where    ".$where." and a.pc_id!=0 group by a.pc_id order by convert(c.name USING gbk) COLLATE gbk_chinese_ci  ";

			 
	  
	  }
	  //托车
	  if($comcate==2){
	  $ccc="a.pr_money+a.pr_portmoney+a.pr_other";
	  $sql="select c.name as name,SUM(".$ccc.") as ying_fu,
			 tt.yi_fu,(SUM(".$ccc.")-tt.yi_fu) as mei_fu from jp_bill as a
			left join 
			(
			select SUM(money) as yi_fu,comid from jp_finc 
			where cate=1 ".$where1."  
			group by comid
			) as tt on a.pr_id=tt.comid

			left join jp_company as c on c.id=a.pr_id
			 
			where    ".$where." and a.pr_id!=0 group by a.pr_id order by convert(c.name USING gbk) COLLATE gbk_chinese_ci  ";
	  }
	  //
	   if($comcate==4){
	  
		  $sql="select c.name as name,SUM(a.pb_dzf) as ying_fu,
			 tt.yi_fu,(SUM(a.pb_dzf)-tt.yi_fu) as mei_fu from jp_bill as a
			left join 
			(
			select SUM(money) as yi_fu,comid from jp_finc 
			where cate=1 ".$where1."  
			group by comid
			) as tt on a.pb_proid=tt.comid

			left join jp_company as c on c.id=a.pb_proid
			 
			where    ".$where." and a.pb_proid!=0 group by a.pb_proid order by convert(c.name USING gbk) COLLATE gbk_chinese_ci  ";
	  }
		

		$d=D();
	
 
 //echo $sql;
		$rs=$d->query($sql);//findall();





		$dd=array();
		if(is_array($rs)){
			foreach($rs as $key=>$v){
			   
			  $dd['ying_fu']+=floatval($v['ying_fu']);
			  $dd['yi_fu']+=floatval($v['yi_fu']);
			  $dd['mei_fu']+=floatval($v['mei_fu']);
			  	  
			}
		}
		$db['countdata']=$dd;
		$db['list']=$rs;
		$db['d1']=$d1;
	    $db['d2']=$d2;
	    $db['d3']=$d3;
	    $db['d4']=$d4;
		$db['comcate']=$comcate;

		 

		//$db['userlist']=getsalesmanlist();

		
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