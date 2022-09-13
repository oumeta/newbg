var grid;
var data = [];
var columns =[];;
var Utils = new Object();
var checkboxSelector = new Slick.CheckboxSelectColumn({
				cssClass: "slick-cell-checkboxsel"
});

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

var options = {
	editable: true,
	enableAddRow: false,
	 //autoHeight:true,
	enableCellNavigation: true,
	rowCssClasses: function(item) {
		// if a task is 100% done then its row gets an additional CSS class
		return (item.percentComplete == 100) ? 'complete' : '';
	} ,
	editCommandHandler:function(item,column,editCommand){
		 
			editCommand.execute();
			apiedit(item);
			//dump(column);
			//dump(editCommand);
	}
};
//调用api编辑字段;
function apiedit(item){
     //var j = $("#myform").serialize();//序列化name/value
	   //dump(item);
       $.ajax({
           url: apiediturl,
           type:'POST',
           data: item,
           //jsonp: 'callback',
           success: function(e) { //返回的json数据
             // alert(3)
				 
           } 
     })

}

G.Event.on(window,'resize',function(){	
	Global.exec("winresize");
})

var gfilter = new Object;

var AppPage;

Class.set("Gui.page",{
	options: {	
	} ,
	config:{
	   checkboxid:false,
	   page:3
	
	},
	H:0,

	initialize: function(options){		 
		
		G.extend(this.options,options);		
		$("#background").hide();
		$("#progressBar").hide();
		this.parseGrid();
		try{
		 
		this.setevent();
		}catch(e){
		 
		} 
		 Global.exec("gridstop");
	},
	parseGrid:function(){
			var The=this;
			//var columns=[];
			

			columns.push(checkboxSelector.getColumnDefinition());

				var This=$('#DataGrid');
				 
				$(".DataGrid ul>li").each(function(){
					   o={}
					   o.id=$(this).attr('id')
					   o.name=$(this).attr('title')
					   o.field=$(this).attr('id')
					   o.width=$(this).attr('width') 
					   o.field=$(this).attr('id')
					   o.sortable=$(this).attr('sortable')
					   o.editor=eval($(this).attr('editor'));
						// alert($(this).attr('editor'))
					  // o.formatter=$(this).attr('formatter')
						 //  alert($(this).attr('formatter'))
					   o.formatter=eval($(this).attr('formatter'))
					  // dump(o)
					    columns.push(o)
				})
						  // dump(columns);
				
			   //dump(columns);
				var toph=$("#topbar").height();
				var search=$("#searchbar").height();
				var footh=$("#footbar").height();
				//alert(footh)
				this.H=toph+footh;
				 

				 Global.add("winresize",function(){						 
					AppPage.winresize();
				 });
				
				
				var str=$(".griddata").val();
				 
				str=$.parseJSON(str);	
				data=str.list;
				gfilter=str.filter;
				//alert(str.listpage)
				 
				$("#footbar .pagesdiv").html(str.listpage)
				try{
					var rect=Gui.body() ;
		 
					$("#myGrid").height(rect.height-this.H)
					grid = new Slick.Grid($("#myGrid"), str.list, columns, options);
					grid.setSelectionModel(new Slick.RowSelectionModel({selectActiveRow:false}));
					grid.registerPlugin(checkboxSelector);
				}catch(e){
				//alert(3)
				}	  
	},
	 
	winresize:function(){		 
		var rect=Gui.body() ;
		//alert(rect.height-AppPage.H)
		 //dump([rect.height,AppPage.H])
		$("#myGrid").height(rect.height-AppPage.H-30)
			 
		grid.resizeCanvas();
		//	$("#myGrid").width(rect.height-AppPage.H)
	},
	compileFilter : function(){
	  var args = '';
	  for (var i in gfilter)
	  {
		// alert([i,gfilter[i]])
		if (typeof(gfilter[i]) != "function" && typeof(gfilter[i]) != "undefined")
		{
		  args += "&" + i + "=" + encodeURIComponent(gfilter[i]);
		}
	  }

	  return args;
	},
	gridsearch:function(){
		var The=this;			 
		//var grid= Uigrid.Grids.instances.get( G.$(".gridform").attr("target") ) 
		  
		G.$("input[type=submit],gridfrom").each(function(){	   
			G.$(this).click(function(event){
				var fields = $(".gridform").serializeArray();
				 
			 
				$.each( fields, function(i, field){	
					 
					// if(!G.isEmpty(field.value)){
						 
					 gfilter[field.name]=field.value
					 //}
				    
						 
				});
 
				PageGo(1)
				 return false;
					 
			 })   
		})
		G.$("input[type=reset],gridfrom").each(function(){	 
				
			G.$(this).click(function(event){
				$(".gridform")[0].reset();
				 G.$("input[type=hidden],gridform").each(function(i,n){
				  
					 $(this).val('') 
				 
				 }) 				 
				var fields = $(".gridform").serializeArray();			
				$.each( fields, function(i, field){	
					gfilter[field.name]=field.value;
						 
				});
				gfilter['status']='';
				
 
				 PageGo(1)
				 return false;
					 
			 })   
		})			
	},
	
	setevent:function(){
		 /*G.$("#btadd").click(function(){
			// dump({title:'添加',url:PI.URL+"/add"})
		   var mywin=new  parent.Gwin(G.$(this),{title:'添加',url:PI.URL+"/add"})
		   mywin.display();
		 })*/
		 this.gridsearch();
		  if((typeof gridstop)=="function"){
			 Global.add("gridstop",gridstop);
		  }
		 if((typeof setgrid)=="function"){
			try{
		    setgrid();
		    }catch(e){}
		 }else{
		      
			 grid.onDblClick.subscribe( function (e, dt){
				 var url=PI.URL+"/edit/?"+PI.query+"&id="+data[dt.row].id
				 var obj={
					title:'编辑',
					url:url,					
					finshed:function(){
					    $("#aiqibar").hide();
						PageGo(); 				 
					}
				}			 
				parent.appdialog(obj);			
				return true;

			 });
			 //alert(3)
			 grid.onSort.subscribe(function(e, data) {
                var sortCol = data.sortCol;
                var sortAsc = data.sortAsc;
                sortdir = sortAsc ? 1 : -1;
                sortcol = sortCol.field;
				//alert(sortdir);alert(sortcol);


				$("#background").show();
				$("#progressBar").show();	

				gfilter['sort_by'] = sortcol;
				gfilter['sort_order'] = sortdir;
				PageGo(1);
				return ;
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
							 if(data==null){
							 data=[];
							 
							}
							//dump(data)
							gfilter=str.filter;
							//dump(str.filter)
							grid.setData(data) 
								// grid.invalidateAllRows();
							grid.render();
							$("#footbar").html(str.listpage)
							$("#background").hide();
							$("#progressBar").hide();
						}
					});
 
            });
			
			$('#deleteselect').click(function(e){
			      var b=[];
				   
				  $.each(grid.getSelectedRows(),function(i,n){				 
					b.push(data[n].id);	
					 
				 })
				if (b.length==0){
					alert('请选择删除项！');
					return false;
				}
				if(!confirm('确实要删除吗?')){ return false;} 
				$.ajax({ 
					url: PI.URL+"/remove?"+PI.query+"", 
					type:'POST',
					data:'id='+b,
					before:function(){
						$("#background").show();
						$("#progressBar").show();
					},
					success: function(e){
					    
						str=$.parseJSON(e);
						// dump(str);
						if(str.status==0){
							alert(str.info);
							return ;
						}
						data=str.list;						 
						if(data==null){
							 data=[];
							 
						}
						grid.setData(data,0) ;
						try{	 
						// grid.invalidateAllRows();
                    grid.render();
					}catch(e){} 
						/*if(e==1){
														 
						}else{
						alert(e)
						}*/
						//grid.destroy();
						 grid.setSelectedRows([]);
						 $("#footbar .pagesdiv").html(str.listpage)
					$("#div_total").html(str.countdiv)
							$("#background").hide();
							$("#progressBar").hide();	
						
					}
				});

				  //for()
			 //alert( )
				 return ;
			 })
			 
			$("#contextMenus").click(function(e) {				 
					if (!$(e.target).is("li"))
						return;
					var row = $(this).data("row");
					 
					window.location.href=PI.URL+"/"+$(e.target).attr("data")+"/?id="+data[row].id
					//data[row].priority = ;
					//grid.updateRow(row);
		  
			})


		 
		 }
	
	} 
});

function okaction(e){

str=$.parseJSON(e);		
data=str.list;
 if(data==null){
	grid.setData([],0); 
	grid.render();
	return false;
}
if(!grid){	
	try{
		 
	grid = new Slick.Grid($("#myGrid"), str.list, columns, options);
	grid.setSelectionModel(new Slick.RowSelectionModel({selectActiveRow:false}));
	grid.registerPlugin(checkboxSelector);
	}catch(e){
		alert(e)
	}
} 
grid.setData(str.list,0); 
grid.render();
	 						
}
 function PageGo(page){
	 if(grid){
	    grid.setSelectedRows([]);
	 }
		$("#background").show();
		$("#progressBar").show();
		gfilter['mid']=PI.mid;
		if (page != null) {
			 
			gfilter['page'] = page;
		} 
		var args = AppPage.compileFilter();
		// dump(gfilter)
		$.ajax({ 
				url: PI.URL+"/query/", 
			//	context: document.body,
				//ifModified:true,
				type:'POST',
				data:args,
				success: function(e){				 
					str=$.parseJSON(e);		
					data=str.list; 
					 if(data==null){
						 data=[];
						 
					}

					if(!grid){	
						try{
							 
						grid = new Slick.Grid($("#myGrid"), str.list, columns, options);
						grid.setSelectionModel(new Slick.RowSelectionModel({selectActiveRow:false}));
						grid.registerPlugin(checkboxSelector);
						}catch(e){
							//alert(e)
						}
					} 
					 
					
					gfilter=str.filter;
					grid.setData(data) 
					
					try{	
						
                    grid.render();
					}catch(e){} 
					$("#footbar .pagesdiv").html(str.listpage)
					$("#div_total").html(str.countdiv)
					$("#background").hide();
					$("#progressBar").hide();
				}
			});
		
 }
//改变每页显示的数量;
function Rsnum(){
  var ps = 10;
  pageSize = $('#rsnum').val();
  if (pageSize){
    ps = pageSize || 10;
    //document.cookie = "MDL[page_size]=" + ps + ";";
	G.Cookies.set("MDL[page_size]",ps)
  }
  gfilter['page_size'] = ps;
 
  PageGo(1)
  //return ps;
}
var a=false;
 function gridsearch(){
   
	$("#searchbar").slideToggle(function(){ 
      
		  a=!a;
	      if(a){AppPage.H=AppPage.H+$("#searchbar").height()}else{AppPage.H=AppPage.H-$("#searchbar").height()}
		
		  Global.exec("winresize");
	});
 }
		
//跳转;
 
G.$(function(){
	AppPage=new Gui.page();
	Global.exec("winresize");

	
})

function statusFormatter(row, cell, value, columnDef, dataContext) {
  var a;
  var statuss=['下单','操作','查柜','放行','锁定','解锁'];
  var color=['blue','green','red','gray','purple','black'];
   a="<span style='color:"+color[value]+";'>"+statuss[value]+"</span>";
  return a;   
             
}
function ischaguiFormatter(row, cell, value, columnDef, dataContext) {
  var a;
   dd=value==1?'查过':"";
   a="<span style='color:red;'>"+dd+"</span>";
  return a;   
             
}

function gridadd(url){

 
				 var obj={
					title:'添加',
					url:url,					
					finshed:function(){
					$("#aiqibar").hide();
						PageGo(); 				 
					}
				}	
				
				//dump(obj,true)
				parent.appdialog(obj);			
				return true;
}


function getlinkers(cid){		 
		$.ajax({ 
				url: PI.APP+"/api/billcomlinker/", 
			//	context: document.body,
				//ifModified:true,
				type:'GET',
				data:'cid='+cid,
				success: function(e){
					$("#DivLinker").show(2);
					$("#DivLinker").html(e);					
				}
			});
		
 }