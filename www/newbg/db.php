<?php
/*
¿ò¼ÜºËĞÄ;
*/
//¿ò¼ÜÂ·¾¶;
define("SYSPATH", str_replace("\\", "/", dirname(__FILE__)));
 
define("Genv", "./Genv/");
require Genv.'Genv.php'; 

Genv::client("Admin");
Genv::start();
$rs=D('customer','test')->findall();
 
foreach($rs as $key=>$v){
   $data=array();
   $data['id']=$v['id'];
   $data['username']=$v['username'];
   $data['password']=$v['password'];
   $data['real_name']=$v['user'];
 
   D('sysmember')->add($data);

}
Genv::stop();

