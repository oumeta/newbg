<? if(!defined('IN_GENV')) exit('Access Denied');?>
<? include $this->gettpl('pagegrid');?>
<ul id="contextMenus" style="display:none;position:absolute;z-ingdex:2020">			 
	<li data="edit">修改</li>
	<li data="delete">删除</li>
	<li data="view">查看</li>
</ul>
<div id='topbar' class="datetitle">
	<ul  style="">	 
		<li  class="bt_s4"><a title="添加"  href="<? echo U('add')?>">添加</a></li>
		<li  class="bt_s4"><a title="删除"  href="javascript:void(0);" id='deleteselect'>删除</a></li>
		<li  class="bt_s4"><a title="查询"  href="javascript:void(0);" onclick='gridsearch()'>查询</a></li>
		<li  ><input type=text id=rsnum onchange=Rsnum()></li>
	 </ul> 
</div> 
<div id='searchbar' class="searchbar none">
<form   class="gridform" target='DataGrid' id="myform" onsubmit='return false;'>
	 标题<input type=text name='title'>
	 标题<input type=text name='title1'>
	 <input type=submit class=bt_s2 id=gsearch value='查询' />
</form>
</div> 
<div id="myGrid" style='width:100%;height:500px;'></div>
 
<div id="DataGrid" class=DataGrid style='align:left;display:none' url='<?=$datasrc?>'>
		    <ul>
				<li title='id' id='id' width="150" type='checkbox' sortable=true></li>
				<li title='标题' id='title' width="350" sortable=true></li>
				 
			</ul> 
			<textarea class="griddata" width=200><?=$listdata?></textarea>
			<textarea class="gridconfig" >var config={checkboxid:'sid',iscount:true}</textarea>
</div>
<div id=footbar class="pages "></div>
</div>
 </body>
</html>
