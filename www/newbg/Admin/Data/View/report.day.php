<? if(!defined('IN_GENV')) exit('Access Denied');?>
 <? include $this->gettpl('reporthead');?>
<div  >
   <form method='post' action="<? echo U()?>" >	
	日期:<input name="d1" type="text" id="d1" class="date_input" value="<?=$d1?>" />
 	<input type=submit value='查询'>
	</form>
</div>  

<div id=reportdiv>
 <table  class=reporttable cellspacing="0" width=1000 align=center  >

  <caption><?=$d1?>日利润表 </caption>

 <tr><th width =80>部门</th><th width =80>姓名</th><th width =80>利润</th>
		 
</tr>
 
 <? foreach((array)$dlist as $key => $v) {?>
 <? $count+=$v['count'];?>
	    <? foreach((array)$v['data'] as $m => $n) {?>
		<tr><? if($m==0) { ?><td rowspan=<? echo count($v['data'])?> width =80><?=$v['name']?></td><? } ?><td><?=$n['real_name']?></td><td><?=$n['day_account']?></td> </tr>
		
		 <?}?>
		<tr class=spec><td>小计</td><td></td><td><?=$v['count']?></td></tr>

 <?}?>
 <tr class=spec><td>共计</td><td></td><td><?=$count?></td></tr>
 </table>  </div>  

 <? include $this->gettpl('formfoot');?>