<? if(!defined('IN_GENV')) exit('Access Denied');?>
 <table border=1 width=500 align=center>
 <? foreach((array)$list as $key => $v) {?>
 <tr>
 <td><?=$v['bankname']?></td><td><?=$v['money']?></td><td><?=$v['postdate']?></td><td><?=$v['remark']?></td></tr>
 <?}?>
 </table>
 总金额<?=$countmoney?>
 
 