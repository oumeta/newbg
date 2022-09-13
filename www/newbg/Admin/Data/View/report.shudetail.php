<? if(!defined('IN_GENV')) exit('Access Denied');?>
 <? include $this->gettpl('reporthead');?>
<div>
   <form method='post' action="<? echo U()?>" >
  <!-- 业务员：<select name=yw>
	<option value="0">请选择</option>
	<? foreach((array)$userlist as $k => $v) {?>
		<option value="<?=$v['id']?>" <? if($yw==$v['id']) { ?>selected<? } ?>><?=$v['username']?></option>
     <?}?>
	 </select-->
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

  <caption><?=$d1?>年<?=$d2?>月度应收明细表 </caption>

 <tr><th width =80>姓名</th><th width =80>客户</th><th width =80>应收</th>

</tr>

 <? foreach((array)$dlist as $key => $v) {?>

	    <? foreach((array)$v['data'] as $m => $n) {?>
		<tr><? if($m==0) { ?><td rowspan=<? echo count($v['data'])?> width =80><?=$v['username']?></td><? } ?><td><?=$n['j_company']?></td><td><?=$n['yingshu']?></td> </tr>
		 <?}?>
		<tr class=spec><td>小计</td><td></td><td><?=$v['yingshu']?></td></tr>

 <?}?>
<tr class=spec><td>小计</td><td></td><td><?=$dt['yingshu']?></td></tr>
</table>  </div>

 <? include $this->gettpl('formfoot');?>
