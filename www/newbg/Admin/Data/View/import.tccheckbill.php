<? if(!defined('IN_GENV')) exit('Access Denied');?>
 
 <table   border=1>
 <caption><?=$company?>(供应商) </caption>
 <tr> <th style='width:60px;'>序号</th>
		    <th style='width:60px;'>ID</th>
			<th style='width:60px;'>单号</th> 

			<th style='width:60px;'>柜号</th> 
			<th style='width:60px;'>S/O</th>
			<th style='width:60px;'>品名</th>
			
			<th style='width:60px;'>托车费</th> 
			<th style='width:60px;'>码头费</th> 
			 
			<th style='width:60px;'>其它费</th> 
		    <th style='width:60px;'>日期</th> 
		  
</tr>
 <? foreach((array)$list as $key => $v) {?>
 
	     
		<tr><td>&nbsp;<?=$key?></td>
		<td>&nbsp;<?=$v['id']?></td>
		<td>&nbsp;<?=$v['b_code']?></td>	<td>&nbsp;<?=$v['b_tank_code']?></td>	
		<td>&nbsp;<?=$v['b_so']?></td>	
		<td>&nbsp;<?=$v['b_product_name']?></td>
		<td>&nbsp;<?=$v['pr_money']?></td>
		<td>&nbsp;<?=$v['pr_portmoney']?></td>
		<td>&nbsp;<?=$v['pr_other']?></td>
		 
		<td>&nbsp;<?=$v['postdate']?></td>	
		</tr> 
 <?}?>
 <tr><td> 合计</td>
		<td>&nbsp;</td>	<td>&nbsp;</td><td>&nbsp;</td>	<td>&nbsp;</td><td>&nbsp;</td>
		<td><?=$countdata['pr_money']?></td>
		<td><?=$countdata['pr_portmoney']?></td>
		<td><?=$countdata['pr_other']?></td>
		 
		<td>&nbsp;</td>	
		</tr> 
 </table>  
   
 