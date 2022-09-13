<? if(!defined('IN_GENV')) exit('Access Denied');?>
<? include $this->gettpl('reporthead');?>

<style type="text/css">
	input[type="text"] {
		background: url("../images/input-bg.png") repeat-x scroll 0 0 #FFFFFF;
		height: 20px;
		width: 100px;
	}

	ul {
    list-style: none outside none;
	width:1000px;

	margin:auto;
}
ul li{float:left;
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


function searchAct(a){
   var url=''
   if(a==0){
	 url="<? echo U('api/getcustomerlist')?>"
	 var cc='j_company'
   }else{
     url="<? echo U('api/getcompanylist')?>"+"&cate="+a
	 var cc='name'
   }
   $("#com_div").html('')
	var oo=$('#com_div').flexbox(url,{
			 displayValue:cc,
			 hiddenValue:'id',
			  showArrow: true,

			 
			 resultTemplate: '{'+cc+'}',
			 
			 watermark: '请选择',
			 width: 320
	 });
}

//-->
</script>
<div  >
   <form method='post' id=write_post name=write_post action="<? echo U()?>" >
	 <ul >
	 <li style='width:100px' >
			<select name=comcate id=comcate  >
						<option value=''></option>

						 <option value=1 <? if($comcate==1) { ?>selected<? } ?>>报关</option>
						 <option value=2 <? if($comcate==2) { ?>selected<? } ?>>托车</option>
						 <option value=4 <? if($comcate==4) { ?>selected<? } ?>>核销单</option>



					</select>
	 </li>
    <!--li style='width:410px ;text-align:left;'>
			 公司:<span id='com_div'  ></span>
	</li-->
	<li>

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
	</li>
	</ul>
	</form>
</div>
<br>
<div style='padding-top:20px'>
 <table    class=reporttable cellspacing="0" width=1000 align=center>
 <caption>  </caption>
 <tr>
		    <th style='width:60px;'>ID</th>

			<th style='width:60px;'>公司</th>

			<th style='width:60px;'>应付</th>
			<th style='width:60px;'>已付</th>

		    <th style='width:60px;'>没付</th>


</tr>

 <? foreach((array)$list as $key => $v) {?>


		<tr>
		<td>&nbsp;<?=$key?></td>

		<td>&nbsp;<?=$v['name']?></td>
		<td>&nbsp;<?=$v['ying_fu']?></td>
		<td>&nbsp;<?=$v['yi_fu']?></td>
		<td>&nbsp;<?=$v['mei_fu']?></td>

		</tr>
 <?}?>
 <tr><td> 合计</td>
		<td>&nbsp;</td>


		<td><?=$countdata['ying_fu']?></td>
		<td><?=$countdata['yi_fu']?></td>
		<td><?=$countdata['mei_fu']?></td>



		</tr>
 </table>

 </div>
 <? include $this->gettpl('formfoot');?>

