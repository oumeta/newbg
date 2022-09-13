<? if(!defined('IN_GENV')) exit('Access Denied');?>
 <? include $this->gettpl('reporthead');?>
<div  >
   <form method='post' action="<? echo U()?>" >
    年份：<select name=d1>
	<option value="0">请选择年</option>
	<? for($i=2008;$i<2050;$i++){?>
		<option value="<?=$i?>"  <? if($d1==$i) { ?>selected<? } ?>><?=$i?></option>
     <?}?>
	 </select>
	月份:<select name=d2>
	<option value="0">请选择月</option>
	<? for($i=1;$i<=12;$i++){?>
		<option value="<?=$i?>"  <? if($d2==$i) { ?>selected<? } ?>><?=$i?></option>
     <?}?>
	 </select>
 	<input type=submit value='查询'>
	</form>
</div>

<div id=reportdiv>
 <table  class=reporttable cellspacing="0" width=1000 align=center  >

  <caption><?=$d1?>年<?=$d2?>月利润表 </caption>

 <tr><th width =80>部门</th><th>姓名</th>
		<? for($i=1;$i<=$days;$i++){?>
			<th style='width:20px;'><?=$i?>日</th>
		 <?}?>
</tr>
 <? foreach((array)$dlist as $key => $v) {?>

	    <? foreach((array)$v['data'] as $m => $n) {?>
		<tr><? if($m==0) { ?><td rowspan=<? echo count($v['data'])?> width =80><?=$v['name']?></td><? } ?><td><?=$n['real_name']?></td>
		<? for($i=1;$i<=$days;$i++){?>
			<td>&nbsp; <?=$n['data'][$i]?>

			 </td>
		 <?}?>
		</tr>

		 <?}?>
		<tr class=spec><td>小计</td><td></td>
		<? for($i=1;$i<=$days;$i++){?>
			<td>&nbsp; <?=$v['count'][$i]?></td>
		 <?}?>
		</tr>

 <?}?>
 <tr class=spec><td>共计</td><td></td>
<? for($i=1;$i<=$days;$i++){?>
			<td>&nbsp; <?=$dc[$i]?></td>
		 <?}?>
</tr>
 </table>
 <? include $this->gettpl('formfoot');?>
