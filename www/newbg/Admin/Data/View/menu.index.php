<? if(!defined('IN_GENV')) exit('Access Denied');?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf8">
		<title> </title>

	<style type="text/css" media="screen"> 
	@import '<?=$_public?>style/style.css';
	</style>
</head>
<body> 
<script language="JavaScript" src="<?=$_public?>script/jquery-1.5.min.js"></script>

<script language="JavaScript" src="<?=$_public?>script/genv.js"></script>
<script language="JavaScript" src="<?=$_public?>script/genv.gui.js"></script>
 <script language="JavaScript" src="<?=$_public?>script/json2.js"></script>
 
<SCRIPT LANGUAGE="JavaScript">
 <!--
	<?=$pageinfo?>		  
//-->
 </SCRIPT>
<script language="JavaScript" src="<?=$_public?>script/listtable.js"></script>
 <div class="title-div">
	<table>
	<tr>
		<td><div class="title"><a href="index.php?act=main"> 管理中心</a> </span><span id="search_id" class="action-span1"> - 菜单管理 </span>

		</div><div style="clear:both"></div></td>
		<td align="right" class="action">
        		<div class="btn" onmouseover="tbtn_init(this)" onclick="javascript:window.location.href='<? echo U('add')?>'" title="">
			<div class="l"><span class="side"></span></div>
			<div class="m"><i class="add"></i><span><a href=<? echo U('add')?>>添加菜单</a>&nbsp;</span></div>
			<div class="r"><span class="side"></span></div>
		</div>
						</td>

	</tr>
	</table>
</div>


<form method="post" action="" name="listForm">
 
<div class="list-div" id="listDiv">


<table width="100%" cellspacing="1" cellpadding="2" id="list-table">
  <tr>
    <th>菜单名称</th>
    <th>所属应用</th>

	<th>唯一标志</th>
    <th>动作</th>
    <th>操作方式</th>
    <th>状态</th>
    <th>排序</th>    
    <th>动作</th>
  </tr>
  

  <? foreach((array)$menulist as $key => $v) {?>
  <tr align="center" class="<?=$v['level']?>">
    <td align="left" class="first-cell" >
      
      <img src="<?=$_public?>images/menu_minus.gif" width="9" height="9" border="0" style="margin-left:<?=$v['level']?>em" onclick="rowClicked(this)" />
     
      <span><a href="goods.php?act=list&cat_id=<?=$v['id']?>"><?=$v['menuname']?></a></span>
    </td>
    <td><span  ><?=$v['appid']?></span></td> 
	<td><span  ><?=$v['menu']?></span></td> 
    <td><span  ><?=$v['act']?></span></td>
	<td></td>
    <td width="10%" align="right">
	<img src="<?=$_public?>images/<? if($v['status']==1) { ?>yes<? } else { ?>no<? } ?>.gif" onclick="toggle(this, 'toggle_status', <?=$v['id']?>)" />
	
	 </td>
   	 <td><span onclick="listedit(this, 'edit_taxis', <?=$v['id']?>)"><?=$v['taxis']?></span></td>
	 
	<td width="24%" align="center">
      
      <a href="<? echo U('edit',array('id'=>$v['id']))?>">编辑</a> |
      <a href="javascript:;" onclick="listTable.remove(<?=$v['id']?>, '你确定要删除吗')" title="删除">删除</a>
    </td>
  </tr>
  <?}?>
</table>


</div>
</form>


<script language="JavaScript">
<!--
var Utils = new Object();
 

Utils.trim = function( text )
{
  if (typeof(text) == "string")
  {
    return text.replace(/^\s*|\s*$/g, "");
  }
  else
  {
    return text;
  }
}
Utils.fixEvent = function(e)
{
  var evt = (typeof e == "undefined") ? window.event : e;
  return evt;
}

Utils.srcElement = function(e)
{
  if (typeof e == "undefined") e = window.event;
  var src = document.all ? e.srcElement : e.target;

  return src;
}
var bisEmpty = function( val )
{
  switch (typeof(val))
  {
    case 'string':
      return Utils.trim(val).length == 0 ? true : false;
      break;
    case 'number':
      return val == 0;
      break;
    case 'object':
      return val == null;
      break;
    case 'array':
      return val.length == 0;
      break;
    default:
      return true;
  }
}

var Browser = new Object();

Browser.isMozilla = (typeof document.implementation != 'undefined') && (typeof document.implementation.createDocument != 'undefined') && (typeof HTMLDocument != 'undefined');
Browser.isIE = window.ActiveXObject ? true : false;
Browser.isFirefox = (navigator.userAgent.toLowerCase().indexOf("firefox") != - 1);
Browser.isSafari = (navigator.userAgent.toLowerCase().indexOf("safari") != - 1);
Browser.isOpera = (navigator.userAgent.toLowerCase().indexOf("opera") != - 1);

var imgPlus = new Image();
imgPlus.src = "<?=$_public?>images/menu_plus.gif";

function toggle(obj, act, id){
  var url=PI.URL+"/"+act+"/?is_ajax=1&";;
 
  var val = (obj.src.match(/yes.gif/i)) ? 0 : 1;
  
   url+="val=" + val + "&id=" +id+"&mid="+PI.mid;
    //alert(url)
    $.ajax({
			   type: "POST",
			   url: url,
			   dataType:'json',
			   success: function(e) { //返回的json数据
				 	 
					if(e.status==1){
					 
					    obj.src = (e.info.val > 0) ? "<?=$_public?>images/yes.gif" :'<?=$_public?>images/no.gif';

					}else{
					
					alert(e.info.text);
					}
			   } 
		 })
 
}

function listedit(obj, act, id){

  var tag = obj.firstChild.tagName;
  if (typeof(tag) != "undefined" && tag.toLowerCase() == "input")
  {
    return;
  }

  /* 保存原始的内容 */
  var org = obj.innerHTML;
  var val = Browser.isIE ? obj.innerText : obj.textContent;

  /* 创建一个输入框 */
  var txt = document.createElement("INPUT");
  txt.value = (val == 'N/A') ? '' : val;
  txt.style.width = (obj.offsetWidth + 12) + "px" ;

  /* 隐藏对象中的内容，并将输入框加入到对象中 */
  obj.innerHTML = "";
  obj.appendChild(txt);
  txt.focus();

  /* 编辑区输入事件处理函数 */
  txt.onkeypress = function(e)
  {
    var evt = Utils.fixEvent(e);
    var obj = Utils.srcElement(e);

    if (evt.keyCode == 13)
    {
      obj.blur();

      return false;
    }

    if (evt.keyCode == 27)
    {
      obj.parentNode.innerHTML = org;
    }
  }

  /* 编辑区失去焦点的处理函数 */
  txt.onblur = function(e)
  {
    if (Utils.trim(txt.value).length > 0)
    {

	 var url=PI.URL+"/"+act+"/?is_ajax=1&";;
 
	 
  
    url+="val=" + encodeURIComponent(Utils.trim(txt.value)) + "&id=" +id+"&mid="+PI.mid;
    //alert(url)
    $.ajax({
			   type: "POST",
			   url: url,
			   dataType:'json',
			   success: function(e) { //返回的json数据
				 	 
					if(e.status==1){
					 
					   // obj.src = (e.info.val > 0) ? "<?=$_public?>images/yes.gif" :'<?=$_public?>images/no.gif';
						obj.innerHTML =   e.info.val;
					}else{					
					  alert(e.info.text);
					  obj.innerHTML = org;
					}
			   } 
		 })
	 
 

      
      
    }
    else
    {
      obj.innerHTML = org;
    }
  }
 
 }
/**
 * 折叠分类列表
 */
function rowClicked(obj)
{
  obj = obj.parentNode.parentNode;
 
  var tbl = document.getElementById("list-table");
  var lvl = parseInt(obj.className);
  var fnd = false;
 
  for (i = 0; i < tbl.rows.length; i++)
  {
      var row = tbl.rows[i];
   
      if (tbl.rows[i] == obj)
      {
          fnd = true;
      }
      else
      {
          if (fnd == true)
          {
              var cur = parseInt(row.className);
			  
              if (cur > lvl)
              {
                  row.style.display = (row.style.display != 'none') ? 'none' : (Genv.Browser.ie) ? 'block' : 'table-row';

				  
              }
              else
              {
                  fnd = false;
                  break;
              }
          }
      }
  }

  for (i = 0; i < obj.cells[0].childNodes.length; i++)
  {
      var imgObj = obj.cells[0].childNodes[i];
      if (imgObj.tagName == "IMG" && imgObj.src != '<?=$_public?>images/menu_arrow.gif')
      {
          imgObj.src = (imgObj.src == imgPlus.src) ? '<?=$_public?>images/menu_minus.gif' : imgPlus.src;
      }
  }
}
//-->
</script>
