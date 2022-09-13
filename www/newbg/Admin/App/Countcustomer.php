<?php 
/*
客户统计
*/
class CountcustomerAction extends GlobalAction {
	#客户统计
 
	public function index(){
	    #管理	
		$this->CheckPower();
		I('@.Lib.Form');
		$d1=getgpc('d1');//名称;
		$d2=getgpc('d2');//名称;
		$d3=getgpc('d3');//名称;
//dump($_POST);
		if($d2==""){
			$d2="1999-01-01";
		}
 		if($d3==""){
			$d3=date("Y-m-d");
		}
		$d2=local_strtotime($d2);
		$d3=local_strtotime($d3);
		 
		$d=D();

		  

		$d->select(array('count(a.k_id) as countid'))->from(gettable('bill')." AS a ");		
		$d->leftJoin(gettable('customer').' AS b','a.k_id=b.id','j_company  as kname');
        $where=" a.postdate>=".$d2." and a.postdate<=".$d3." ";
		if($d1!=""){
		 $where.=" and a.k_id=".$d1."";
		 }
		  
        $d->where($where);
		$d->group('a.k_id');
		$d->order('countid desc');
		//$sql=$d->fetch('sql');		
		 //echo $sql;
		//$rs=$d->query($sql);
		 
		 $rs=$d->findall();
		 
		$data['dlist']=$rs;
		$this->assign($data);
		 // $d->showsql();		 
		 //dump($rs);		
		 
		 
	} 
	 
	 
}
?>