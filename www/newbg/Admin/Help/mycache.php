<?php
//�����ɫ
 function cache_role(){
	$file='cache_role';
	 $dd=F($file);	 
	if(!$dd){
		$rs=D('sysrole')->order('id asc')->findall();
		$dd=array();
		foreach($rs as $key=>$v){
		  $dd[$v['id']]=$v['name'];
		
		}
		F($file,$dd);
	}	 
	return $dd;

 }
 //���沿��
 function cache_dep(){
	$file='cache_dep';
	 $dd=F($file);	 
	if(!$dd){
		$rs=D('sysdep')->order('id asc')->findall();
		$dd=array();
		foreach($rs as $key=>$v){
		  $dd[$v['id']]=$v['name'];
		
		}
		//D()->showsql();
		 
		 //����10����
		F($file,$dd);
	}	 
	return $dd;

 }
//վ�����û���
function system_config(){
   
	$data = FC('system_config');	 
    if ($data ==false){
         
        $res = D('sysoptions')->findall();
		$data=array();
        foreach ($res AS $row){
            $data[$row['code']] = $row['value'];
        }  
        FC('system_config', $data);
    } 	
	 
	G('C',(object)$data);
    return $data;
 }

 