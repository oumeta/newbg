<? if(!defined('IN_GENV')) exit('Access Denied');?>
<? include $this->gettpl('pagegrid');?>
 
 <div id="aiqibar" style="display:none;position:absolute;z-ingdex:2020">
   <ul>
   <li data="lock">锁定</li>
   <li data="unlock">解锁</li>
   <li data="finclist">查看</li>
   
   </ul>
</div>
<div id='topbar' class="datetitle ">
	<ul  style="">	 
		<li  class="bt_s4"><a title="查询"  href="javascript:void(0);" onclick='gridsearch(0);'>查询</a></li>
		<li  class="bt_s4"><a title="导出"  href="javascript:void(0);" onclick='importdata(0);'>导出</a></li>
		 
		 	
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
	  <li>更新日期<input type=text name='d6'  class='date_input sbt'   readonly>
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
			    <li title='状态' id='status' width="40"formatter="statusFormatter"  sortable=true ></li>
				<li title='业务员' id='busname' width="60"  ></li>
				<li title='操作员' id='opratename' width="60"  ></li>
				<li title='客户' id='j_company' width="160" sortable=true></li>
				
				<li title='品名或抬头' id='b_product_name' width="80" sortable=true></li>
				<li title='柜号或申报美金数' id='b_tank_code' width="160" sortable=true></li>
				
 
				<li title='口岸' id='pc_port' width="60" ></li>
				<li title='单号' id='b_code' width="60" sortable=true></li>
				<li title='S/O或报关单号' id='b_so' width="120" sortable=true></li>
				<li title='报关过程' id='ischagui' width="60" sortable=true formatter="ischaguiFormatter"></li>

				
				<li title='应收' id='count_shu' width="80" sortable=true></li>
				<!--li title='实收' id='shi_shu' width="80" sortable=true></li-->
				<li title='应付' id='count_zhi' width="80" sortable=true></li>
				<!--li title='实付' id='shi_zhi' width="80" sortable=true></li-->
				<li title='利润' id='account' width="100" sortable=true></li>
				 
				<li title='下单日期' id='postdate' width="100" sortable=true></li>
				<li title='放行日期' id='finshdate' width="100" sortable=true></li>
				<li title='最后修改' id='editdate' width="100" sortable=true></li>
					<li title='核销' id='com1' width="120" ></li>
					<li title='报关1' id='com2' width="120" ></li>
					<li title='报关2' id='com3' width="120" ></li>
					<li title='托车' id='com4' width="120" ></li>

			</ul> 
			<textarea class="griddata" width=200><?=$listdata?></textarea>
			<textarea class="gridconfig" >var config={checkboxid:'sid',iscount:true}</textarea>
</div>
<div id='div_total'><?=$total?></div>

<div id=footbar ><div class=lf>显示条数<input type=text maxlength=3 size=3 onchange="Rsnum()" id="rsnum"></div><div class="pagesdiv">&nbsp;</div></div>
</div>
<div id='DivLinker' class='box2'></div>
 
<script type="text/javascript">
<!--
function $1(s){return document.getElementById(s);}

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
		var t=$(e.target).attr("data")
		if(t=='lock'){
			changestatus(row,billstatus['lock']);		
		}else if(t=='unlock'){
			changestatus(row,billstatus['unlock']);
		}else if(t=='finclist'){
			var id=data[row].id;
			//alert(PI.APP+'/finance/finclist/?billid='+id+"&mid="+PI.mid)
			var obj={
				title:'查看祥细',
				url:PI.APP+'/finance/edit/?id='+id+"&mid="+PI.mid,
				width:950,
				height:500,
				finshed:function(){
				$("#aiqibar").hide();
					PageGo(); 				 
				}
			}			 
			parent.appdialog(obj);			
		} 		
		 return false;
	})

	getcompany("com1_div",1);
	getcompany("com2_div",2)
	getcompany("com4_div",4)
 

})
function gridstop(){

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
					
					$("#aiqibar").hide();
					$("#background").hide();
					$("#progressBar").hide();
					str=$.parseJSON(e);
					if(str.status==0){
					  alert(str.info);
					  return 
					
					}
					PageGo();	
				}
			});

} 

function setgrid(){
 //alert(3)
			grid.onClick.subscribe( function (e, dt){

			 
				 getlinkers(data[dt.row].k_id)
						
				return true;

			 });

			 grid.onSort.subscribe(function(e, data) {
                var sortCol = data.sortCol;
                var sortAsc = data.sortAsc;
                sortdir = sortAsc ? 1 : -1;
                sortcol = sortCol.field;
				//alert(sortdir);alert(sortcol);


				$("#background").show();
				$("#progressBar").show();	
				var args="sort_by="+sortcol+"&sort_order="+sortdir+"";
				for (var i in gfilter) {
					if (typeof(gfilter[i]) != "function" &&i != "sort_order" && i != "sort_by" && !bisEmpty(gfilter[i])){
					  args += "&" + i + "=" + gfilter[i];
					}
				}
				$.ajax({ 
						url: PI.URL+"/query/",//rnd="+Math.floor(Math.random() * 2147483648).toString(36), 
					    data:args,
						type:'POST',
						//	context: document.body,
						//ifModified:true,
						success: function(e){				 
							str=$.parseJSON(e);		
							data=str.list;
							//dump(data)
							gfilter=str.filter;
							//dump(str.filter)
							grid.setData(str.list) 
								// grid.invalidateAllRows();
							grid.render();
							$("#footbar").html(str.listpage)
							$("#background").hide();
							$("#progressBar").hide();
						}
					});
 
            });
			
}
function setall(){
	 gfilter['status']=''
	 PageGo();
	
}

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
function importdata(){

window.location.href='<? echo U("import/fincebill")?>';

}
//-->
</script>
<? include $this->gettpl('page_foot');?>
 


  