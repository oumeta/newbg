<? if(!defined('IN_GENV')) exit('Access Denied');?>
<? include $this->gettpl('pagegrid');?>
<style type="text/css" media="screen">		 
		
@import '<?=$_public?>css/yuiskin.css';
@import '<?=$_public?>css/form.css';
.none{display:none;}
</style>
<script type="text/javascript">
<!--
	var apiediturl="<? echo U('apiedit')?>";
//-->
</script>
<body class="yui-skin-sam">
<ul id="contextMenus" style="display:none;position:absolute;z-ingdex:2020">			 
	<li data="edit">修改</li>
	<li data="delete">删除</li>
	 
</ul>
<div id='topbar' class="datetitle">
	<ul  style="">	 
		<li  class="bt_s4"><a title="添加"  href="javascript:void(0)" id='btadd'>添加</a></li>	
		<li  class="bt_s4"><a title="删除"  href="javascript:void(0);" id='deleteselect'>删除</a></li>
	 </ul> 
</div> 
<div id='searchbar' class="searchbar none">
	 标题<input type=text name='title'><button class=bt_s2>查询</button>
</div> 
<div id="myGrid" style="width:500px;height:500px;"></div>
 
<div id="DataGrid" class=DataGrid style='align:left;display:none' url='<?=$datasrc?>'>
	<ul>
		<li title='id' id='id' width="50"  ></li>
		<li title='角色名' id='name' width="150" ></li>
		<li title='是否统计' id='isc' width="50" formatter=BoolCellFormatter editor=YesNoCheckboxCellEditor></li>
		<li title='是否操作' id='isact' formatter=BoolCellFormatter editor=YesNoCheckboxCellEditor width="50"></li>
		<li title='状态' id='status' width="50"></li>
		<li title='备注' id='remark' width="150" ></li>
	</ul> 
	<textarea class="griddata" ><?=$listdata?></textarea>
	<textarea class="gridconfig" >var config={checkboxid:'sid',iscount:true}</textarea>
</div>



<div id=footbar class="pages "></div>
</div>

<div style="z-index: 2; visibility: visible; left: 550px; top: 40px;width:400px;" class="yui-module yui-overlay " id="classPath">
 <div style="z-index: 2; visibility: visible; left: 0px; top: 0px;" id="toolBoxHolder_c" class="yui-panel-container shadow">
 <div style="visibility: inherit; width: 400px;" class="yui-module yui-overlay yui-panel" id="toolBoxHolder">
    <div id="toolBoxHolder_h"  class="hd">其它信息</div>
    <div class="bd">
    <form id="myform">
        <fieldset>
            <legend>信息</legend>
            <div >
				<label>部门名称:
					 <input type="text" size="20" class="input-w2 input-text" name='name' id='name'    />					 
				</label>				
			</div>
			<!--div >
				<label>部门经理:
					 <input type="text" size="20" class="input-w2 input-text" name='name1' id='name1'    />					 
				</label>				
			</div-->

			<div >
				<label>是否统计:
					 <input type="checkbox" size="20" class="" name='isc' id='isc'  />
					 <br>
					 选中则会此部门属于业务部，会在统计里显示此部门；
				</label>				
			</div>
			<div >
				<label>是否操作:
					 <input type="checkbox" size="20" class="" name='isact' id='isact'  />
					 <br>
					 选中则可以给该部门员工派单；
				</label>				
			</div>
			  
			 <div >
				<label>描述:
					 <input type="text" size="20" class="input-w2 input-text" name='remark' id='remark'   />					 
				</label>				
			</div>
			 <div >
				<label>
				 <input type="hidden" size="20"  name='id' id='id'    />
				<span id="setHeader" class="yui-button yui-push-button">
					<span class="first-child">
						<button id="btsave" tabindex="0" type="button">保存信息</button>
					</span>
					</span>					 					 
				</label>				
			</div>
        </fieldset>		 

    </form>
    </div>
</div>

<script type="text/javascript">
<!--

 
//配置表格;
function $1(s){return document.getElementById(s);}

function setgrid(){
 
	$("#btsave").click(function(){
	
	 var j = $("#myform").serializeArray();//序列化name/value
	   
       $.ajax({
           url: "<? echo U('save')?>",
           //dataType: 'jsonp',
		   type:'POST',
           data: j,
           //jsonp: 'callback',
           success: function(e) { //返回的json数据
             okaction(e)
           } 
     })
	
	
	
	})
	$('#deleteselect').click(function(e){
		  var b=[];
		  if(typeof grid=='undefined'){
		  return false;
		  }
		  if(grid.getSelectedRows()==''){
			 alert('请选中要操作的项')
			return false;
		  }
		  try{
		 
		  $.each(grid.getSelectedRows(),function(i,n){				 
			b.push(data[n].id);	
			 
		 })
		 }catch(e){}
		if(G.isEmpty(b)){
			return false;
		}
		 if (confirm('你确定要删除吗？')==false){
		 return false;
		 }
		$.ajax({ 
			url: PI.URL+"/delete", 
			type:'POST',
			data:'id='+b+"&mid="+PI.mid,
			before:function(){
				$("#background").show();
				$("#progressBar").show();
			},
			success: function(e){			 
				 okaction(e)
				
			}
		});
		 return ;
	 })

	 
	grid.onDblClick.subscribe( function (e, dt){	
				 
				  editData(dt.row); 
				return true;

			 });

	 

	/*grid.onDblClick = function (e, row, cell){	
		 
			editData(row);

		

	}*/
	$("#btadd").click(function(){
	
		$1("myform").reset();
	  
     })
	  grid.onContextMenu.subscribe(function (e){
                e.preventDefault();
				//alert(3)
                var cell = grid.getCellFromEvent(e);
                $("#contextMenus")
                        .data("row", cell.row)
                        .css("top", e.pageY)
                        .css("left", e.pageX)
                        .show();

                $("body").one("click", function() {
                    $("#contextMenus").hide();
                });
	 });
	   

	$("#contextMenus").click(function(e) {
		 
			if (!$(e.target).is("li"))
				return;

			var row = $(this).data("row");
			var t=$(e.target).attr("data")
			if(t=='edit'){
				editData(row);
			
			}else if(t=='delete'){
			    if (confirm('你确定要删除吗？')){
					 $.ajax({
						   url: "<? echo U('delete')?>&id="+data[row].id+"&mid="+PI.mid,						  
						   success: function(e) { //返回的json数据
							 
									str=$.parseJSON(e);		
									data=str.list;
									if(!grid){									
										grid = new Slick.Grid($("#myGrid"), str.list, columns, options);
									}
									grid.setData(str.list,0); 
									grid.render();
								
						   } 
					 })
			  }

			
			} 
			return false;
			 
			 
  
	})
}

function editData(row){
 
 
	var id=data[row].id;
		$("#background").show();
		$("#progressBar").show();		 
		$.ajax({ 
				url: PI.URL+"/edit/?id="+id+"&mid="+PI.mid+"&r="+Math.floor(Math.random() * 2147483648).toString(36), 
			//	context: document.body,
				
				ifModified:true,
				success: function(e){				 
					str=$.parseJSON(e);	
					try{
					 
						G.each(str,function(i,n){	
						 
						    if(i=="isc"||i=='isact'){
						 
							   if(n==1){
								 $("#myform  #"+i).attr('checked',true);
							   }else{
								 $("#myform  #"+i).attr('checked',false);
							   }
						 
							}else{
							 
								$("#myform  #"+i).val(n);						
							}
						})	
					}catch(e){
					 
					
					}
					$("#background").hide();
					$("#progressBar").hide();	
				}
			});

}

//-->
</script>
 </body>
</html>
