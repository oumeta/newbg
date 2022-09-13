<? if(!defined('IN_GENV')) exit('Access Denied');?>
 <table border=1 width=100%>
 <? foreach((array)$lister as $key => $v) {?>
 <tr><td><?=$v['name']?></td><td><?=$v['tel']?></td><td><?=$v['email']?></td><td><a href='javascript:void(0);' onclick='mode(<?=$v['id']?>)'>修改</a>&nbsp;&nbsp;<a href='javascript:void(0);' onclick='del(<?=$v['id']?>)'>删除 </a></td></tr>
 <?}?>
 </table>