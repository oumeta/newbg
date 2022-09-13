<? if(!defined('IN_GENV')) exit('Access Denied');?>
<? include $this->gettpl('formhead');?>
<div class="container  ">
<form id="form" action="<? echo U('save')?>" method=post>


<fieldset>
	<legend>订单信息及应收</legend>
	<div>
	  <div class="span-7 colborder">
		<div>
			<label for="f1">自编号:</label><br />
			<input name="b_code" type="text" id="b_code"   value="<?=$rs['b_code']?>" />
		</div>
        <div>
			<label for="pw">下单日期:</label><br />
			<input name="postdate" type="text" id="postdate" class="date_input" value="<?=$rs['postdate']?>" />
		</div>  
		<div>
			<label for="pw">放行日期:</label><br />
			<input name="finshdate" type="text" disabled id="finshdate" class="date_input" value="<?=$rs['finshdate']?>" />
		</div>  
		<div>
			<label for="f5">S/O:</label><br />
			<input type="text"   name='b_so' id='b_so' class=""  value="<?=$rs['b_so']?>" />
		</div>
		<div>
			<label for="f5">柜号:</label><br />
			<input type="text"   name='b_tank_code' id='b_tank_code' class=""  value="<?=$rs['b_tank_code']?>" />
			 </div>
		<div>
			<label for="f1">品名:</label><br />
			<input type="text"   name='b_product_name' id='b_product_name' class=""  value="<?=$rs['b_product_name']?>" />
		</div>
		<div>
			<label for="pw">口岸:</label><br />
			<input type="text"   name='pc_port' id='pc_port'  value="<?=$rs['pc_port']?>" />
		</div>
		<div>
			<label for="pw">柜数（个）:</label><br />
			<input type="text"   name='b_tank_num' id='b_tank_num' class=":digits" value="<?=$rs['b_tank_num']?>" />
		</div>
		 

	  </div>
	
	  <div class="span-7 colborder"> 
	  <div>
			<label for="f1">业务员:</label><br />
			 
			<input id="b_busid_hidden" type="hidden" name="b_busid" value="<?=$rs['b_busid']?>">
			<input id="b_busid_input" class="ffb-input watermark" value="<?=$rs['username']?>" readonly style="width: 260px;">
			 
		</div>
        <div>
			<label for="pw">客  户:</label><br />
			<div id="k_id"></div>
		</div> 
		<div>
			<label for="pw">联系人:</label><br />
			<div id="k_linker"></div>
			 
		</div>
		<div>
			<label for="f5">备注:</label><br />
			<textarea   name="cz_remark"   id="cz_remark"><?=$rs['cz_remark']?></textarea>
		</div>		
 	 </div>

	    <div class="span-7 last">
		<div>
	 
			<label for="f1">报关费：</label><br />
			<input type="text" name='b_bgf' id='b_bgf'  class=":number"  value="<?=$rs['b_bgf']?>" />
		</div>        
		<div>
			<label for="pw">港建费：</label><br />
			<input type="text"  name='b_gjf' id='b_gjf' class=":number"  value="<?=$rs['b_gjf']?>" />
		</div>
		<div>
			<label for="pw">单证费：</label><br />
			<input type="text"   name='b_dzf' id='b_dzf' class=":number" value="<?=$rs['b_dzf']?>" />
		</div>
		<div>
			<label for="pw">码头费：</label><br />
			<input type="text"   name='b_sxf' id='b_sxf' class=":number" value="<?=$rs['b_sxf']?>" />
		</div>
		 
		<div>
			<label for="pw">互认费/卫检费
:</label><br />
			<input type="text"   name='b_cgf' id='b_cgf' class=":number"  value="<?=$rs['b_cgf']?>" />
		</div>
		<div>
			<label for="pw">托车费:</label><br />
			<input type="text"   name='br_money' id='br_money'  class=":number" value="<?=$rs['br_money']?>" />
		</div>
		<div>
			<label for="pw">其它费：</label><br />
			<input type="text"   name='b_qtf' id='b_qtf'  class=":number" value="<?=$rs['b_qtf']?>" />
		</div>
		<div>
			<label for="pw">回扣：</label><br />
			<input type="text"   name='rebate' id='rebate' class=":number" value="<?=$rs['rebate']?>" />
		</div>
 	 </div>
	 <div style='width:100%;height:5px;' class='clear'></div>
	  
    <hr style='margin-top:15px;color:red;' />
	
		<div class=none>
	    <input type="text" id="id" name="id" class="input-w2 input-text" value="<?=$rs['id']?>"   />
		 <input type="text" id="doact" name="doact" class="input-w2 input-text" value="<?=$doact?>"   />	   
	   </div>
	<div class="span-18">

	  <button class="submit button positive" id='btsave' >
        <img src="<?=$_public?>images/tick.png" alt=""/> 保存
      </button>

      <a class="button" href="#" id='btreset'>
        <img src="<?=$_public?>images/cross.png" alt=""/>取消
      </a>		  
	  </div>
 </fieldset>
  
</div> 
</form>

<script type="text/javascript">
<!--

var billid="<?=$rs['id']?>",
	b_busid="<?=$rs['b_busid']?>",
	k_id="<?=$rs['k_id']?>",
	k_id="<?=$rs['k_id']?>",
	k_linker="<?=$rs['k_linker']?>"

var bill={
	getsalesmanlist:"<? echo U('api/getsalesmanlist')?>",
	getsalesmancustomerlist:"<? echo U('api/getmycustomerlist')?>",
	getlinkerlist:"<? echo U('api/getlinkerlist')?>",
	getcompanylist:"<? echo U('api/getcompanylist')?>",
	div:[],
	divs:[],
	init:function(){
	     
		 
		  /*bill.divs["pb_proid"]={id:"<?=$rs['pb_proid']?>",name:"<?=$rs['pb_proname']?>"};
		  bill.divs["pc_id"]={id:"<?=$rs['pc_id']?>",name:"<?=$rs['pc_id_name']?>"};
		  bill.divs["pc_id2"]={id:"<?=$rs['pc_id2']?>",name:"<?=$rs['pc_id2_name']?>"};
		  bill.divs["pr_id"]={id:"<?=$rs['pr_id']?>",name:"<?=$rs['pr_id_name']?>"};

		  bill.start();*/

		 
		  /*

		  bill.b_busid=$('#b_busid').flexbox(bill.getsalesmanlist, {  
			 displayValue:'username',
			 hiddenValue:'id',
			 allowInput: false,
			 
			 resultTemplate: '{username}',
			 
			 watermark: '选择业务员',
			 width: 260,
			  
			 onSelect: function() {
			       
			       bill.getcustomer($('#b_busid_hidden').val());
				//	alert( );
						 
			}					 
		 });*/		 
	
		 bill.edit();
	},
	getcustomer:function(id){
	 $('#k_id').html('');
	 var url=bill.getsalesmancustomerlist+"&userid="+id
		bill.k_id=$('#k_id').flexbox(url, {  
			 displayValue:'j_company',
			 hiddenValue:'id',
			 allowInput: false,
			 
			 resultTemplate: '{j_company}',
			 
			 watermark: '选择客户',
			 width: 260,
			 onSelect: function() {
				bill.getlinker($('#k_id_hidden').val());
					//alert( $('#b_busid_hidden').val());
						 
			}					 
		});	
	
	},
	getlinker:function(id){
	    $('#k_linker').html('');
	    var url=bill.getlinkerlist+"&cid="+id
		bill.k_linker=$('#k_linker').flexbox(url, {  
			 displayValue:'name',
			 hiddenValue:'id',
			 allowInput: false,
			 
			 resultTemplate: '{name}',
			 
			 watermark: '选择联系人',
			 width: 260,
			 onSelect: function() {

					// alert( $('#k_linker_hidden').val());
						 
			}					 
		});	
	
	},
	start:function(){
		//for(var i=0;i<4;i++){
		
		 bill.getcompany("pb_proid",4);		
		//}
		//报关公司选择;
		bill.getcompany("pc_id",1);
		bill.getcompany("pc_id2",1);	
		bill.getcompany("pr_id",2);
	},
	getcompany:function(div,cate){
			var url=bill.getcompanylist+"&cate="+cate
			bill.div[div]=$('#'+div).flexbox(url, {  
				 displayValue:'name',
				 hiddenValue:'id',
				 allowInput: false,
				 
				 resultTemplate: '{name}',
				 
				 watermark: '选择供应商',
				 width: 190,
				 onSelect: function() {	
							 
				}					 
			});	
			bill.div[div].setValue(bill.divs[div].id,bill.divs[div].name);
	 
	
	},
	edit:function(){
	   //更新的时候;
	   if(billid==0){
	    bill.getcustomer($('#b_busid_hidden').val());
	   return ;}
	   try{
	   // bill.b_busid.setValue('<?=$rs['b_busid']?>','<?=$rs['username']?>'); 
		 
		bill.getcustomer($('#b_busid_hidden').val());
		bill.k_id.setValue('<?=$rs['k_id']?>','<?=$rs['k_name']?>');
		bill.getlinker($('#k_id_hidden').val());
		bill.k_linker.setValue('<?=$rs['k_linker']?>','<?=$rs['k_linker_name']?>');

		$("#postdate").attr('disabled',true)
	  }catch(e){
	  }
	}


}

VanadiumForm.prototype.success=function(){

     var bid=$('#b_busid_hidden').val()
     if(Genv.isEmpty(bid)){
	 alert('请选择业务员')
       	 $('#b_busid_input').focus();
		 return false;
	 }
	   var bid=$('#k_id_hidden').val()
     if(Genv.isEmpty(bid)){
		 alert('请选择客户')
       	 $('#k_id_input').focus();
		 return false;
	 }
	 var j = $("#form").serializeArray();//序列化name/value
		 
	 $.ajax({
           url: "<? echo U('save')?>",           
		   type:'POST',
           data: j,           
           success: function(e) { //返回的json数据
              str=$.parseJSON(e);
			 if(str.status==0){
				alert(str.info)			 
			 }else{
               parent.aiqi();
			 }
           } 
     })

	  
	return false;
				
}

$(function(){
 bill.init();
 
 
})	
//-->
</script>
<? include $this->gettpl('formfoot');?>