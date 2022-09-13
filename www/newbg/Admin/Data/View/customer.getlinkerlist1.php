<? if(!defined('IN_GENV')) exit('Access Denied');?>
 <table border=1 width=100%>
 <? foreach((array)$lister as $key => $v) {?>
 <tr><td><?=$v['name']?></td><td><?=$v['tel']?></td><td><?=$v['email']?></td></tr>
 <?}?>
 </table>