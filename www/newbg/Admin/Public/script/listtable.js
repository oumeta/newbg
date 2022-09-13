
var listTable = new Object;
listTable.query = "query";

listTable.filter = new Object;

listTable.url = PI.URL;

//alert(listTable.url)
/**
 * 创建一个可编辑区
 */
listTable.edit = function(obj, act, id){
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
	  var url=listTable.url+"/"+act+"/?is_ajax=1";
      res = Ajax.call(url, "val=" + encodeURIComponent(Utils.trim(txt.value)) + "&id=" +id, null, "POST", "JSON", false);

      if (res.message){
        alert(res.message);
      }

      if(res.id && (res.act == 'goods_auto' || res.act == 'article_auto'))
      {
          document.getElementById('del'+res.id).innerHTML = "<a href=\""+ thisfile +"?goods_id="+ res.id +"&act=del\" onclick=\"return confirm('"+deleteck+"');\">"+deleteid+"</a>";
      }

      obj.innerHTML = (res.error == 0) ? res.content : org;
    }
    else
    {
      obj.innerHTML = org;
    }
  }
}

/**
 * 切换状态
 */
listTable.toggle = function(obj, act, id){
  var url=this.url+"/"+act+"/?is_ajax=1";;
  var val = (obj.src.match(/yes.gif/i)) ? 0 : 1;
  var res = Ajax.call(url, "val=" + val + "&id=" +id, null, "POST", "JSON", false);

  if (res.message)
  {
    alert(res.message);
  }

  if (res.error == 0)
  {
    obj.src = (res.content > 0) ? PI.public+'/Images/yes.gif' : PI.public+'/Images/no.gif';
  }
}

/**
 * 切换排序方式
 */
listTable.sort = function(sort_by, sort_order){
  var url=this.url+"/"+this.query+"/"
  var args = "&is_ajax=1&sort_by="+sort_by+"&sort_order=";

  if (this.filter.sort_by == sort_by) {
    args += this.filter.sort_order == "DESC" ? "ASC" : "DESC";
  }  else  {
    args += "DESC";
  }
  for (var i in this.filter) {
    if (typeof(this.filter[i]) != "function" &&
      i != "sort_order" && i != "sort_by" && !Utils.isEmpty(this.filter[i]))
    {
      args += "&" + i + "=" + this.filter[i];
    }
  }

  this.filter['page_size'] = this.getPageSize();
  Ajax.call(url, args, this.listCallback, "POST", "JSON");

}

/**
 * 翻页
 */
listTable.gotoPage = function(page)
{
  if (page != null) this.filter['page'] = page;

  if (this.filter['page'] > this.pageCount) this.filter['page'] = 1;
  this.filter['page_size'] = this.getPageSize(); 
  this.loadList();
}

/**
 * 载入列表
 */
listTable.loadList = function(){
  var url=this.url+"/"+this.query+"/?is_ajax=1";
  var args = this.compileFilter();
 

  Ajax.call(url, args, this.listCallback, "POST", "JSON");
}

/**
 * 删除列表中的一个记录
 */
listTable.remove = function(id, cfm, opt){
  if (opt == null){
    opt = "remove";
  }
  if (confirm(cfm)){
	var a={}
	a.url=this.url+"/"+opt+"/"
	//?id=" + id +"&is_ajax=1"; 
	a.params ={
		id:id,
		is_ajax:1,
	    mid:PI.mid
	}
     
	G.getJSON(a,listTable.removeCallback)
   // Ajax.call(url, args, this.removeCallback, "POST", "JSON");
  }
}

listTable.removeCallback = function(rs, txt)
{
 
  if (rs.error > 0)
  {
    alert(rs.message);
  }
  else
  {

	  
    try
    {
      location.reload();
    }
    catch (e)
    {
      alert(e.message);
    }
  }
}

listTable.gotoPageFirst = function()
{
  if (this.filter.page > 1)
  {
    listTable.gotoPage(1);
  }
}

listTable.gotoPagePrev = function()
{
  if (this.filter.page > 1)
  {
    listTable.gotoPage(this.filter.page - 1);
  }
}

listTable.gotoPageNext = function()
{
  if (this.filter.page < listTable.pageCount)
  {
    listTable.gotoPage(parseInt(this.filter.page) + 1);
  }
}

listTable.gotoPageLast = function()
{
  if (this.filter.page < listTable.pageCount)
  {
    listTable.gotoPage(listTable.pageCount);
  }
}

listTable.changePageSize = function(e)
{
    var evt = Utils.fixEvent(e);
    if (evt.keyCode == 13)
    {
        listTable.gotoPage();
        return false;
    };
}

listTable.listCallback = function(rs, txt)
{
  if (rs.error > 0)
  {
    alert(rs.message);
  }
  else
  {
    try
    {
      document.getElementById('listDiv').innerHTML = rs.content;

      if (typeof rs.filter == "object")
      {
        listTable.filter = rs.filter;
      }

      listTable.pageCount = rs.page_count;
    }
    catch (e)
    {
      alert(e.message);
    }
  }
}

listTable.selectAll = function(obj, chk)
{
  if (chk == null)
  {
    chk = 'checkboxes';
  }

  var elems = obj.form.getElementsByTagName("INPUT");

  for (var i=0; i < elems.length; i++)
  {
    if (elems[i].name == chk || elems[i].name == chk + "[]")
    {
      elems[i].checked = obj.checked;
    }
  }
}

listTable.compileFilter = function()
{
  var args = '';
  for (var i in this.filter)
  {
	// alert([i,this.filter[i]])
    if (typeof(this.filter[i]) != "function" && typeof(this.filter[i]) != "undefined")
    {
      args += "&" + i + "=" + encodeURIComponent(this.filter[i]);
    }
  }

  return args;
}

listTable.getPageSize = function(){

  var ps = 10;
  pageSize = document.getElementById("pageSize");
  if (pageSize){
    ps = Utils.isInt(pageSize.value) ? pageSize.value : 10;
    //document.cookie = "MDL[page_size]=" + ps + ";";
	$M.Cookies.set("MDL[page_size]",ps)
  }

  return ps;
}

listTable.addRow = function(checkFunc)
{
  cleanWhitespace(document.getElementById("listDiv"));
  var table = document.getElementById("listDiv").childNodes[0];
  var firstRow = table.rows[0];
  var newRow = table.insertRow(-1);
  newRow.align = "center";
  var items = new Object();
  for(var i=0; i < firstRow.cells.length;i++) {
    var cel = firstRow.cells[i];
    var celName = cel.getAttribute("name");
    var newCel = newRow.insertCell(-1);
    if (!cel.getAttribute("ReadOnly") && cel.getAttribute("Type")=="TextBox")
    {
      items[celName] = document.createElement("input");
      items[celName].type  = "text";
      items[celName].style.width = "50px";
      items[celName].onkeypress = function(e)
      {
        var evt = Utils.fixEvent(e);
        var obj = Utils.srcElement(e);

        if (evt.keyCode == 13)
        {
          listTable.saveFunc();
        }
      }
      newCel.appendChild(items[celName]);
    }
    if (cel.getAttribute("Type") == "Button")
    {
      var saveBtn   = document.createElement("input");
      saveBtn.type  = "image";
	

      saveBtn.src = PI.public+"/Images/icon_add.gif";
	   
      saveBtn.value = save;
      newCel.appendChild(saveBtn);
      this.saveFunc = function()
      {
        if (checkFunc)
        {
          if (!checkFunc(items))
          {
            return false;
          }
        }
        var str = "act=add";
        for(var key in items)
        {
          if (typeof(items[key]) != "function")
          {
            str += "&" + key + "=" + items[key].value;
          }
        }
        res = Ajax.call(listTable.url+"/?is_ajax=1", str, null, "POST", "JSON", false);
        if (res.error)
        {
          alert(res.message);
          table.deleteRow(table.rows.length-1);
          items = null;
        }
        else
        {
          document.getElementById("listDiv").innerHTML = res.content;
          if (document.getElementById("listDiv").childNodes[0].rows.length < 6)
          {
             listTable.addRow(checkFunc);
          }
          items = null;
        }
      }
      saveBtn.onclick = this.saveFunc;

      //var delBtn   = document.createElement("input");
      //delBtn.type  = "image";
      //delBtn.src = "./images/no.gif";
      //delBtn.value = cancel;
      //newCel.appendChild(delBtn);
    }
  }

}
