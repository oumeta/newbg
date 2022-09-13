<? if(!defined('IN_GENV')) exit('Access Denied');?>
<? include $this->gettpl('reporthead');?>

<style type="text/css">
	input[type="text"] {
		background: url("../images/input-bg.png") repeat-x scroll 0 0 #FFFFFF;
		height: 20px;
		width: 100px;
	}

</style>
<script type="text/javascript">
<!--
	function clear1(){

	 $("#d1").val('');
	 $("#d2").val('');
	 $("#d3").val(0);
	 $("#d4").val(0);
	 $("#comid").val(-1);
	}



function searchAct1(i){

    $.ajax({
           url: "<? echo U('api/getmycustomerlist')?>&userid="+i,
           //dataType: 'jsonp',
		   type:'POST',

           //jsonp: 'callback',
           success: function(e) { //返回的json数据
             str=$.parseJSON(e);

			 cccc(str.results)
           }
     })


}

function cccc(result){

var eles = document.forms['write_post'].elements;
	/* 清除列表 */
	var selLen = eles['comid'].options.length;
	for (var i = selLen - 1; i >= 0; i--)
	{
		eles['comid'].options[i] = null;
	}
	var arr = result;


	var userCnt = arr.length;
	var opt = document.createElement('OPTION');
		opt.value = -1;
		opt.text = '选择客户';


		eles['comid'].options.add(opt);


	for (var i = 0; i < userCnt; i++)
	{
		var opt = document.createElement('OPTION');
		opt.value = arr[i].id;
		opt.text = arr[i].j_company;


		eles['comid'].options.add(opt);
	}

}
//-->
</script>
<div  >
   <form method='post' id=write_post name=write_post action="<? echo U()?>" >

<select name=userid id=userid onChange=searchAct1(this.options[this.selectedIndex].value)>
	<option value="-1">请选择业务员</option>
	<? foreach((array)$userlist as $k => $v) {?>
		<option value="<?=$v['id']?>" <? if($userid==$v['id']) { ?>selected<? } ?>><?=$v['username']?></option>
     <?}?>
	 </select>



    年份：<select name=d3 id=d3>
	<option value="0">请选择年</option>
	<? for($i=2008;$i<2050;$i++){?>
		<option value="<?=$i?>" <? if($d3==$i) { ?>selected<? } ?>><?=$i?></option>
     <?}?>
	 </select>
	月份:<select name=d4 id=d4>
	<option value="0">请选择月</option>
	<? for($i=1;$i<=12;$i++){?>
		<option value="<?=$i?>" <? if($d4==$i) { ?>selected<? } ?>><?=$i?></option>
     <?}?>
	 </select>


 	<input type=submit value='查询'>&nbsp;&nbsp;<input type=button onclick=clear1() value='取消查询' >
	</form>
</div>


 <table    class=reporttable cellspacing="0" width=1000 align=center>
 <caption>  </caption>
 <tr>
		    <th style='width:60px;'>ID</th>
			 <th style='width:60px;'>业务员</th>
			<th style='width:60px;'>客户</th>

			<th style='width:60px;'>应收</th>
			<th style='width:60px;'>实收</th>

		    <th style='width:60px;'>没收</th>
			 <th style='width:60px;'>导出对账单</th>

</tr>

 <? foreach((array)$list as $key => $v) {?>


		<tr>
		<td>&nbsp;<?=$key?></td>
		<td>&nbsp;<?=$v['username']?></td>
		<td>&nbsp;<?=$v['name']?></td>
		<td>&nbsp;<?=$v['ying_shu']?></td>
		<td>&nbsp;<?=$v['yi_shu']?></td>
		<td>&nbsp;<?=$v['ying_fu']?></td>
		<td><a href="<? echo U('importkx/index')?>&kid=<?=$v['k_id']?>&d3=<?=$d3?>&d4=<?=$d4?>">导出对账单</a></td>
		</tr>
 <?}?>
 <tr><td> 合计</td>
		<td>&nbsp;</td>

		<td>&nbsp;</td>
		<td><?=$countdata['ying_shu']?></td>
		<td><?=$countdata['yi_shu']?></td>
		<td><?=$countdata['ying_fu']?></td>
		<td></td>


		</tr>
 </table>
 <? include $this->gettpl('formfoot');?>

