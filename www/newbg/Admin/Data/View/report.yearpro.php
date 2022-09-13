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
	<a href="<? echo U('yearpro',array('cate'=>0))?>">报关公司1</a>&nbsp;&nbsp;
	<a href="<? echo U('yearpro',array('cate'=>1))?>">报关公司2</a>&nbsp;&nbsp;
	<a href="<? echo U('yearpro',array('cate'=>2))?>">托车公司</a>&nbsp;&nbsp;
	<a href="<? echo U('yearpro',array('cate'=>3))?>">核销单公司</a>&nbsp;&nbsp;
</div>


 <table    class=reporttable cellspacing="0" width=1000 align=center>
 <caption><?=$d1?>年应付(供应商) </caption>
 <tr><th width=90>供应商</th>
		<? for($i=1;$i<=12;$i++){?>
			<th style='width:60px;'><?=$i?>月</th>
		 <?}?>
		 <th>共计</th>
</tr>
 <? foreach((array)$dlist as $key => $v) {?>


		<tr>
		<td><?=$v['name']?></td>
		<? for($i=1;$i<=13;$i++){?>
			<td>
			<?=$v["c".$i]?>
			 </td>
		 <?}?>
		</tr>
 <?}?>
<tr class=spec><td>共计</td>
<? for($i=1;$i<=13;$i++){?>
			<td>
			<? echo $dt['count'.$i]?>
			 </td>
		 <?}?>
 </tr>
 </table>
 <? include $this->gettpl('formfoot');?>

