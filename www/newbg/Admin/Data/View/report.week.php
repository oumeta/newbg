<? if(!defined('IN_GENV')) exit('Access Denied');?>
 <? include $this->gettpl('reporthead');?>
<div  >
   <form method='post' action="<? echo U()?>" >
    年份：<select name=d1>
	<option value="0">请选择年</option>
	<? for($i=2008;$i<2050;$i++){?>
		<option value="<?=$i?>" <? if($d1==$i) { ?>selected<? } ?>><?=$i?></option>
     <?}?>
	 </select>
	月份:<select name=d2>
	<option value="0">请选择月</option>
	<? for($i=1;$i<=12;$i++){?>
		<option value="<?=$i?>" <? if($d2==$i) { ?>selected<? } ?>><?=$i?></option>
     <?}?>
	 </select>
 	<input type=submit value='查询'>
	</form>
</div>

<div id=reportdiv>
 <table  class=reporttable cellspacing="0" width=1000 align=center  >

  <caption><?=$d1?>年<?=$d2?>周利润表 </caption>

 <tr><th width =80>部门</th><th width =80>姓名</th>
		<? for($i=1;$i<=5;$i++){?>
			<th style='width:50px;'><?=$i?>周</th>
		 <?}?>
</tr>
 <? foreach((array)$dlist as $key => $v) {?>

	    <? foreach((array)$v['data'] as $m => $n) {?>
		<tr><? if($m==0) { ?><td rowspan=<? echo count($v['data'])?> width =80><?=$v['name']?></td><? } ?><td><?=$n['username']?></td>
		<? for($i=0;$i<=4;$i++){?>
			<td>
			<?=$n["c".$i]?>
			 </td>
		 <?}?>
		</tr>

		 <?}?>
		<tr class=spec><td>小计</td><td></td><td><?=$v['count0']?></td><td><?=$v['count1']?></td><td><?=$v['count2']?></td><td><?=$v['count3']?></td><td><?=$v['count4']?></td></tr>

 <?}?>
<tr class=spec><td>共计</td><td></td><td><?=$dt['count0']?></td><td><?=$dt['count1']?></td><td><?=$dt['count2']?></td><td><?=$dt['count3']?></td><td><?=$dt['count4']?></td></tr> </table>

</div>
 <? include $this->gettpl('formfoot');?>
