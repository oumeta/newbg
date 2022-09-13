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

function cccc(arr){


	var userCnt = arr.length;

    var comid="<?=$comid?>"
  $("#comid").get(0).options.length=0;
	for (var i = 0; i < userCnt; i++)
	{   if(arr[i].id==comid){
	      opt='selected';
	    }else{
		  opt='';

		}

		$("#comid").prepend("<option "+opt+" value='"+arr[i].id+"'>"+arr[i].j_company+"</option>");
	}



 }
//-->
</script>
<div  >
   <form method='post' id=write_post name=write_post action="<? echo U()?>" >

<select name=userid id=userid onChange=abcd(this.options[this.selectedIndex].value)>
	<option value="-1">请选择业务员</option>
	<? foreach((array)$userlist as $k => $v) {?>
		<option value="<?=$v['id']?>" <? if($userid==$v['id']) { ?>selected<? } ?>><?=$v['username']?></option>
     <?}?>
	 </select>
      <select name="comid" id="comid">

	  </select>


    年份：<select name=dt id=dt>

	<? for($i=2008;$i<2050;$i++){?>
		<option value="<?=$i?>" <? if($dt==$i) { ?>selected<? } ?>><?=$i?></option>
     <?}?>
	 </select>



 	<input type=submit value='查询'>
	</form>
</div>


 <table    class=reporttable cellspacing="0" width=1000 align=center>
 <caption>  </caption>
 <tr>
 <th style='width:60px;'   >业务员</th>
<th style='width:60px;'  >客户</th>
<th>月份</th>
 <th>应收</th>
 <th>已收</th>
 <th>未收</th>

</tr>

 <? foreach((array)$list as $key => $v) {?>


		<tr>
		<td>&nbsp;<?=$username?></td>
		<td>&nbsp;<?=$comname?></td>
		<td>&nbsp;<?=$key?></td>
		<td>&nbsp;<?=$v['ying_shu']?></td>
		<td>&nbsp;<?=$v['yi_shu']?></td>
		<td>&nbsp;<?=$v['wei_shu']?></td>


		</tr>
 <?}?>
   <tr><td> 合计</td>
		<td>&nbsp;</td><td>&nbsp;</td>

		<td><?=$countdata['ying_shu']?></td>
		<td><?=$countdata['yi_shu']?></td>
		<td><?=$countdata['wei_shu']?></td>



		</tr>
 </table>



 <script type="text/javascript">
 <!--
var userid="<?=$userid?>"

 $(function(){
   if(userid){

   abcd(userid);
   }


 })
	function abcd(i){


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
 //-->
 </script>
 <? include $this->gettpl('formfoot');?>

