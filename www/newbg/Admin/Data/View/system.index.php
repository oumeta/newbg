<? if(!defined('IN_GENV')) exit('Access Denied');?>
<? include $this->gettpl('menu');?>
<style type="text/css">
	.tb td{
	text-align:left;
	}
	.tb .tl{
	text-align:right;
	color:black;
	margin:2px;
	padding:5px;
	
	}
</style>
<form method="post" action="<? echo U('save')?> ">

<div id="Tabs1" style="display:">
		<div class="tt">基本设置</div>
		<table cellpadding="2" cellspacing="1" class="tb">
		<tr>
			<td class="tl">公司名称</td>
			<td><input name="rs[j_name]" type="text" value="<?=$j_name?>" size="40"/>
			 </td>
		</tr>
		<tr>
			<td class="tl">公司地址</td>
			<td><input name="rs[j_address]" type="text" value="<?=$j_address?>" size="40"/>
			 </td>
		</tr>
		<tr>
			<td class="tl">电话：</td>
			<td><input name="rs[j_tel]" type="text" value="<?=$j_tel?>" size="40"/>
			 </td>
		</tr>
		<tr>
			<td class="tl">传真：</td>
			<td><input name="rs[j_fax]" type="text" value="<?=$j_fax?>" size="40"/>
			 </td>
		</tr>
		<tr>
			<td class="tl">联系人：</td>
			<td><input name="rs[j_linker]" type="text" value="<?=$j_linker?>" size="40"/>
			 </td>
		</tr>
		<tr>
			<td class="tl">邮箱：</td>
			<td><input name="rs[j_mail]" type="text" value="<?=$j_mail?>" size="40"/>
			 </td>
		</tr>
		<tr>
			<td class="tl">备注：</td>
			<td>  <textarea id="j_bank" name="rs[j_bank]"   ><?=$j_bank?></textarea></td>
		</tr>

		 
		</tbody>
		</table>
</div>
<div id="Tabs2" style="display:none">
		<div class="tt">系统设置</div>
		<table cellpadding="2" cellspacing="1" class="tb">
		<tr>
			<td class="tl">订单前缀</td>
			<td><input name="rs[bill_pre]" type="text" value="<?=$bill_pre?>" size="40"/></td>
		</tr>
		 <tr>
			<td class="tl">时区</td>
			<td>
			 <select name="rs[timezone]" id="rs[timezone]">
					   <option value="8" selected>(GMT +08:00) Beijing, Hong Kong, Perth, Singapore, Taipei</option> 
			</select>

			 </td>
		</tr>
		<tr>
			<td class="tl">货币格式</td>
			<td><input name="rs[currency_format]" type="text" value="<?=$currency_format?>" size="40"/></td>
		</tr>
		
		</tbody>
		</table>
</div>
 

<div class="sbt"><input type="submit" name="submit" value=" 确 定 " class="btn">&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value=" 重 置 " class="btn"></div>
</form>
 