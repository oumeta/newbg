<? if(!defined('IN_GENV')) exit('Access Denied');?>
<? include $this->gettpl('pageform');?>
	 
 <body class="yui-skin-sam">
 
 <div style="z-index: 2; visibility: visible; left: 10px; top: 10px;" id="toolBoxHolder_c" class="yui-panel-container shadow">
 <div style="visibility: inherit; width: 750px;" class="yui-module yui-overlay yui-panel" id="toolBoxHolder">
    <div id="toolBoxHolder_h" style="cursor: move;" class="hd">添加</div>
    <div class="bd">
    <form id="form" action="<? echo U('save')?>" method=post>
        <fieldset>
            <legend>基本信息</legend>
            <div >
				<label>客户:
					 <input type="text" id="customers" name="customers" class="input-w2 input-text" value="<?=$rs['customers']?>"   />
				</label>				
			</div>
			 <div >
				<label>跟单人:
					 <input type="text" id="gdr" name="gdr" class="input-w2 input-text" value="<?=$rs['gdr']?>"   />
				</label>				
			</div>
			 <div >
				<label>核销单抬头:
					 <input type="text" id="taitou" name="taitou" class="input-w2 input-text" value="<?=$rs['taitou']?>"   />
				</label>				
			</div>
			 <div >
				<label>单号:
					 <input type="text" id="billcode" name="billcode" class="input-w2 input-text" value="<?=$rs['billcode']?>"   />
				</label>				
			</div>
			 <div >
				<label>公司:
					 <input type="text" id="company" name="company" class="input-w2 input-text" value="<?=$rs['company']?>"   />
				</label>				
			</div>
			 <div >
				<label>码头:
					 <input type="text" id="matou" name="matou" class="input-w2 input-text" value="<?=$rs['matou']?>"   />
				</label>				
			</div>
			 <div >
				<label>出黄单时间:
					 <input type="text" id="tui_date" name="tui_date" class="input-w2 input-text" value="<?=$rs['tui_date']?>"   />
				</label>				
			</div>
			 <div >
				<label>签收人:
					 <input type="text" id="qsr" name="qsr" class="input-w2 input-text" value="<?=$rs['qsr']?>"   />
				</label>				
			</div>
			 <div >
				<label>状态:
					 <input type="text" id="status" name="status" class="input-w2 input-text" value="<?=$rs['status']?>"   />
				</label>				
			</div>
			 <div >
				<label>备注:
					 <input type="text" id="remark" name="remark" class="input-w2 input-text" value="<?=$rs['remark']?>"   />
				</label>				
			</div>
			 <div >
				<label>添加时间:
					 <input type="text" id="add_data" name="add_data" class="input-w2 input-text" value="<?=$rs['add_data']?>"   />
				</label>				
			</div>
		 
        </fieldset>
       
       <div class=none>
	    <input type="text" id="id" name="id" class="input-w2 input-text" value="<?=$rs['id']?>"   />
		 <input type="text" id="doact" name="doact" class="input-w2 input-text" value="<?=$doact?>"   />
	   
	   </div>

		<div>
        	<span id="setHeader" class="yui-button yui-push-button"><span class="first-child"><button id="btsave" tabindex="0" type="button">保存信息</button></span></span>
		</div>
		 

    </form>
    </div>
</div>
<div class="underlay"></div>

</div>


 


</div>
<script type="text/javascript">
<!--
	$(function(){
	 
		$("#btsave").click(function(){
		 $("#form").submit();
		   
		
		})
	
	})
//-->
</script>