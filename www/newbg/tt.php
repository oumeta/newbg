<?php
/*
¿ò¼ÜºËÐÄ;
*/
//¿ò¼ÜÂ·¾¶;
define("SYSPATH", str_replace("\\", "/", dirname(__FILE__)));
 
define("Genv", "./Genv/");
require Genv.'Genv.php'; 

Genv::client("Admin");
Genv::start();

 $sql="select * from jp_bill limit 10";
	$infolist=D()->query($sql);

	dump($infolist);
Genv::stop();

