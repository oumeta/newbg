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

 	<input type=submit value='查询'>
	</form>
</div>


 <table    class=reporttable cellspacing="0" width=1000 align=center>
 <caption><?=$d1?>年利润表 </caption>
 <tr><th width =80>部门</th><th width=90>姓名</th>
		<? for($i=1;$i<=12;$i++){?>
			<th style='width:60px;'><?=$i?>月</th>
		 <?}?>
	<th width=80>合计</th>
</tr>
 <? foreach((array)$dlist as $key => $v) {?>

	    <? foreach((array)$v['data'] as $m => $n) {?>
		<tr><? if($m==0) { ?><td rowspan=<? echo count($v['data'])?> width =80><?=$v['name']?></td><? } ?>
		<td><?=$n['username']?></td>
		<? for($i=1;$i<=12;$i++){?>
			<td>
			<?=$n["c".$i]?>
			 </td>
		 <?}?>
		 <td width=80><?=$n["cc"]?></td>
		</tr>
		 <?}?>
		<tr class=spec><td>小计</td><td></td>

		<? for($i=1;$i<=13;$i++){?>
			<td>
			<? echo $v['count'.$i]?>
			 </td>
		 <?}?>
		 </tr>

 <?}?>
<tr class=spec><td>共计</td><td></td>
<? for($i=1;$i<=13;$i++){?>
			<td>
			<? echo $dt['count'.$i]?>
			 </td>
		 <?}?>
 </tr>
 </table>
 <? include $this->gettpl('formfoot');?>
