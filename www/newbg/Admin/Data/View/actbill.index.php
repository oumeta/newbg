<? if(!defined('IN_GENV')) exit('Access Denied');?>
<? include $this->gettpl('pagegrid');?>
<div id="aiqibar" style="display:none;position:absolute;z-ingdex:2020">
   <ul>
   <li data="addbill">下单</li>
   <li data="action">操作</li>
   <li data="chektank">查柜</li>
   <li data="fangxing">放行</li> 
   <li data="trustbill">资料管理</li>  
   </ul>
</div>
 
<div id='topbar' class="datetitle ">
	<ul  style="">	 
		<li  class="bt_s4"><a title="添加"  href="javascript:void(0)" onclick=gridadd("<? echo U('add')?>")>添加</a></li>
		<li  class="bt_s4"><a title="删除"  href="javascript:void(0);" id='deleteselect'>删除</a></li>
		<li  class="bt_s4"><a title="查询"  href="javascript:void(0);" onclick='gridsearch(0);'>查询</a></li>
		<li  class="bt_s4" id='pd'><a title="派单"   href="javascript:void(0);" onclick='gridsearch(1)'>派单</a></li>
		 	
	 </ul> 
	
</div> 

<div id='searchbar' class="searchbar none">
<div id=divsearch class=" none  ">
<form   class="gridform" target='DataGrid' id="myform" onsubmit='return false;'>
  <ul>
     <li>报关过程
	    <select name='ischagui'>
			<option value=''>所有</option>
			<option value=0>未查过</option>
			<option value=1>查过</option>
		</select>
	 
	 </li>
	  <li>S/O<input type=text name='b_so' class=sbt></li>
	  <li>品名<input type=text name='b_product_name' class=sbt></li>
	  <li>柜号<input type=text name='b_tank_code' class=sbt></li>
	  <li>业务员<span id='bus_div'></span> </li>
	  <li>操作员<span id='ope_div'></span></li>
	  <li style='width:340px'>客户<span id='cus_div'></span> </li>
	  <li>下单日期<input type=text name='d1'  class='date_input sbt'   readonly>-
	  </li><li><input type=text name='d2'class='date_input sbt' readonly></li>
	  <li>放行日期<input type=text name='d3' class='date_input sbt' readonly>-</li><li><input type=text name='d4' class='date_input sbt' readonly></li>
	 
	  <li>状态<select id=status name=status>
	  <option value=''>全部</option>
	  <? foreach((array)$status as $k => $v) {?>
	  <option value='<?=$k?>'><?=$v?></option>
	  <?}?>
	  </select>
	  </li>

	  <li>核销单<span id='com4_div'></span> </li>
	  <li>报关<span id='com1_div'></span> </li>
	  <li>托车<span id='com2_div'></span> </li>
	  <li><input type=submit class=bt_s2 id=gsearch value='查询' />&nbsp;<input type=reset class=bt_s2 id=gsearchrest value='取消查询' />
	  <!--input type=hidden id=rsnum onchange=Rsnum()--></li>
 </ul>
</form>
</div>
<div id='b_opeiddiv' class=" none ">
 
</div> 
</div> 

<div id="myGrid" style='width:100%;height:500px;'></div>
 
<div id="DataGrid" class=DataGrid style='align:left;display:none' url='<?=$datasrc?>'>
		    <ul>
			    <li title='状态' id='status' width="40" sortable=true formatter="statusFormatter"></li>
				<li title='业务员' id='busname' width="60" ></li>
				
				<li title='操作员' id='opratename' width="60" ></li>
				<li title='客户' id='j_company' width="160" sortable=true></li>
				<li title='品名或抬头' id='b_product_name' width="80" sortable=true></li>
				<li title='柜号或申报美金数' id='b_tank_code' width="160" sortable=true></li>
				<li title='S/O或报关单号' id='b_so' width="120" sortable=true></li>
				<li title='报关过程' id='ischagui' width="60" sortable=true formatter="ischaguiFormatter"></li>

				
				<li title='口岸' id='pc_port' width="60" ></li>
				<li title='应收' id='count_shu' width="80" sortable=true></li>
				<li title='应付' id='count_zhi' width="80" sortable=true></li>
				<li title='利润' id='account' width="100" sortable=true></li>
				<li title='下单日期' id='postdate' width="100" sortable=true></li>
				<li title='放行日期' id='finshdate' width="100" sortable=true></li>
				<li title='最后修改' id='editdate' width="100" sortable=true></li>
				<li title='核销' id='com1' width="120" ></li>
					<li title='报关1' id='com2' width="120" ></li>
					<li title='报关2' id='com3' width="120" ></li>
					<li title='托车' id='com4' width="120" ></li>
				<li title='单号' id='b_code' width="60" sortable=true></li>
				
			</ul> 
			<textarea class="griddata" width=200><?=$listdata?></textarea>
			<textarea class="gridconfig" >var config={checkboxid:'sid',iscount:true}</textarea>
</div>
<div id='div_total'><?=$total?></div>
<div id=footbar ><div class=lf>显示条数<input type=text maxlength=3 size=3 onchange="Rsnum()" id="rsnum"></div><div class="pagesdiv">&nbsp;</div></div>
</div>
<div id='DivLinker' class='box2'></div>

 </body>
</html>
<script type="text/javascript">
<!--

var a=[false,false];
function gridsearch(bb){
    if(!a[bb]){
		if(bb==0){	
		  $('#divsearch').show();
		  $('#b_opeiddiv').hide();
		}
		if(bb==1){	
		  $('#divsearch').hide();
		  $('#b_opeiddiv').show();
		  gfilter['status']='';
		  gfilter['sort_by']='status';
		  gfilter['sort_order']='asc';
		  PageGo();
		}   
	}
	if(!a[bb]){
		$("#searchbar").show(function(){   
			  
			  AppPage.H=AppPage.H+$("#searchbar").height()
			  Global.exec("winresize");
		});
	
	}
	if(a[bb]){
	 
		$("#searchbar").hide(function(){       
			   AppPage.H=AppPage.H-$("#searchbar").height()	
			  Global.exec("winresize");
		});
	
	}
	a[bb]=!a[bb]
	
 }
 
 $(function(){     
	 var url="<? echo U('api/getoperatelist')?>"
	 var oo=$('#b_opeiddiv').flexbox(url,{  
			 displayValue:'username',
			 hiddenValue:'id',
			 
			 allowInput: false,
			 
			 resultTemplate: '{username}',
			 
			 watermark: '选择操作员',
			 width: 120,
			  
			 onSelect: function() {
			     
			      setoprate();
						 
			}					 
	 });
	  var url="<? echo U('api/getsalesmanlist')?>"
	 var oo=$('#bus_div').flexbox(url,{  
			 displayValue:'username',
			 hiddenValue:'id',
			 allowInput: false,
			 
			 
			 resultTemplate: '{username}',
			 
			 watermark: '选择业务员',
			 width: 120,
			  
			 onSelect: function() {
			     
			     // setoprate();
						 
			}					 
	 });
	  var url="<? echo U('api/getoperatelist')?>"
	  var oo=$('#ope_div').flexbox(url,{  
			 displayValue:'username',
			 hiddenValue:'id',
			 allowInput: false,
			 
			 resultTemplate: '{username}',
			 
			 watermark: '选择操作员',
			 width: 120,
			  
			 onSelect: function() {
			     
			      //setoprate();
						 
			}					 
	 });
	 var url="<? echo U('api/getcustomerlist')?>"
	  var oo=$('#cus_div').flexbox(url,{  
			 displayValue:'j_company',
			 hiddenValue:'id',
			 showArrow: true,
			 
			 //allowInput: false,
			 
			 resultTemplate: '{j_company}',
			 
			 watermark: '选择客户',
			 width: 290,
			  
			 onSelect: function() {
			     
			      //setoprate();
						 
			}					 
	 });

  

$("#aiqibar").click(function(e) {
		 
		if (!$(e.target).is("li"))
			return;

		var row = $(this).data("row");
		var t=$(e.target).attr("data");	
		
		if(t=='trustbill'){
		
				var id=data[row].id;
				 var url=PI.URL+"/download/?bid="+id+"&mid="+PI.mid
				 var obj={
					title:'委托单',
					url:url,					
					finshed:function(){
					    $("#aiqibar").hide();
						PageGo(); 				 
					}
				}			 
				parent.appdialog(obj);	
				return ;
		}else{
		changestatus(row,billstatus[t]);		 
		}
		return false;
	})
	getcompany("com1_div",1);
	getcompany("com2_div",2)
	getcompany("com4_div",4)
})



function getcompany(div,cate){
			var url="<? echo U('api/getcompanylist')?>"+"&cate="+cate
			$('#'+div).flexbox(url, {  
				 displayValue:'name',
				 hiddenValue:'id',
				 allowInput: false,
				 
				 resultTemplate: '{name}',
				 
				 watermark: '选择供应商',
				 width: 100,
				 onSelect: function() {	
							 
				}					 
			});	
			//bill.div[div].setValue(bill.divs[div].id,bill.divs[div].name);
	 
	
}

function gridstop(){
	grid.onClick.subscribe( function (e, dt){

			 
				 getlinkers(data[dt.row].k_id)
						
				return true;

			 });
	grid.onContextMenu.subscribe(function (e){
			e.preventDefault();			
			var cell = grid.getCellFromEvent(e);
			 
			$("#aiqibar")
					.data("row", cell.row)
					.css("top", e.pageY)
					.css("left", e.pageX)
					.show();
			
			$("#aiqibar").blur(function(){
				$("#aiqibar").hide();
			
			})
			$("body").one("click", function() {
                    $("#aiqibar").hide();
            });
			 
   });
}
//改变状态;
function changestatus(row,status){ 
	var id=data[row].id;
		$("#background").show();
		$("#progressBar").show();		 
		$.ajax({ 
				url: PI.URL+"/apiedit/", 
				 
				data: "id="+id+"&status="+status+"&mid="+PI.mid,
				type:'POST',
				success: function(e){
				    str=$.parseJSON(e);
					$("#aiqibar").hide();
										
					$("#background").hide();
					$("#progressBar").hide();	
					if(str.status==0){
							alert(str.info);
							return ;
					}
					PageGo();	
				}
			});

} 
function setoprate(){ 
	var b=[];	   
	  $.each(grid.getSelectedRows(),function(i,n){				 
		b.push(data[n].id);			 
	})
	if(b.length==0){
	alert('请选择单据');
	return false;
	}
	var uid=$("#b_opeiddiv_hidden").val();
	 
	$.ajax({ 
		url: PI.URL+"/setoprate", 
		type:'POST',
		data:'ids='+b+"&oid="+uid+"&mid="+PI.mid,
		before:function(){
			$("#background").show();
			$("#progressBar").show();
		},
		success: function(e){
		    PageGo();
			/*
			str=$.parseJSON(e);
			data=str.list;
			grid.setData(str.list,0) ;
			try{	 
			 
				grid.render();
			}catch(e){} 			 
			$("#background").hide();
			$("#progressBar").hide();	
			*/
		}
	});
}
function setall(){
	 gfilter['status']=''
	 PageGo();
	
}
//-->
</script>
<? include $this->gettpl('page_foot');?>
