var AppPage;

Class.set("Gui.page",{
	options: {	
	} ,
	config:{
	   checkboxid:false,
	   page:3
	
	},

	initialize: function(options){		 
		
		G.extend(this.options,options);		
		G.$("#background").hide();
		G.$("#progressBar").hide();
		this.parseGrid();
		this.gridsearch();
		this.setevent();
		 
	},
	parseGrid:function(){
			 var The=this;
			G.$(".DataGrid").each(function(){	
				
				var cols=[];
				var This=G.$(this)
				G.$("ul>li",this).each(function(){
					   o={}
					   o.id=G.$(this).attr('id')
					   o.name=G.$(this).attr('title')
					   o.field=G.$(this).attr('id')
					   o.width=G.$(this).attr('width') 
					   o.type=G.$(this).attr('type')||'text' 
					   cols.push(o)
				})
			    var config=$(".gridconfig",this).val();
				     eval(config) ;
					 
					 config=G.extend(The.config,config);
					 
				var fh=0
			    if(This.attr('layoutH')){
					   fh=This.attr('layoutH')
					   
				}

				//dump([Gui.body().height,$(".pageHeader").height(),fh],1)
				var c=Gui.body().height-$(".pageHeader").height()-70-fh
				
				var w=G.isEmpty(This.attr("width"))?Gui.body().width:This.attr("width")
				var h= G.isEmpty(This.attr("height"))?c:This.attr("height")
 
				var b={
					columns:cols,
					showid:This.attr("id"),
					url:This.attr("url"),
					width:w,
					height:h,
					gridid:This.attr("id")
				}
					 
				
				
			 
				var str=G.$(".griddata").val();
				 //alert(str)
				str=Genv.json.decode(str);	
				// alert(str)
				new Ggrid(b);

				var instances = Uigrid.Grids.instances;
			    var grid=instances.get(b.gridid)

				
					 
				 grid.setData(str);
				/*
			    grid.trDbClick=function(){
				
					var title ='';				
					var options = {};
					var w = 600;
					var h = 400;
					if (w) options.width = w;
					if (h) options.height = h;
					options.max = true;
					options.mask = false;
					var url=PI.URL+'/edit/?id='+grid.rowdata[grid.selectIndex].id
					parent.openwin(url,escape(url),'修改', options);
					//event.preventDefault();
				
				}
				$M.G.add("resize",function(){
						var c=$M.body().height-G.$(".pageHeader").height()-G.$(".panelmessage").height()-90
						var w= $M.isEmpty(This.attr("width"))?$M.body().width:This.attr("width")
						var h= $M.isEmpty(This.attr("height"))?c:This.attr("height")
						instances.get(b.gridid).resize(w,h)
				})*/
			})	
	  
	},
	gridsearch:function(){
		var The=this;			 
		var grid= Uigrid.Grids.instances.get( G.$(".gridform").attr("target") ) 
		  
		G.$("input[type=submit],gridfrom").each(function(){	   
			G.$(this).click(function(event){	
				 
					var filter=serialize(G.$(".gridform")[0]);//.serializeArray() ;

					 /*
					var filter={}
					G.each(a,function(value,key){			 
						filter[key]=value;
					}) */
					 grid.filter=filter;				 
					 grid.initReady=false;
					 grid.getData();

					 //event.preventDefault();
					 return false;
					 
			 })   
		})			
	},
	indexPage:function(){
	 
	},
	setevent:function(){
		 G.$("#btadd").click(function(){
			// dump({title:'添加',url:PI.URL+"/add"})
		   var mywin=new  parent.Gwin(G.$(this),{title:'添加',url:PI.URL+"/add"})
			mywin.display();
		 
		 
		 })
	
	} 
});
//跳转;
function PageGo(page){

var grid= Uigrid.Grids.instances.get( G.$(".gridform").attr("target") ) 

	var filter=grid.filter;		
	
	grid.filter['page']=page;
	grid.initReady=false;
	grid.getData();
}
function init(){
	AppPage=new Gui.page();
} 
G.$(function(){
	init();
})


function serialize(form) {
	if (!form || form.nodeName !== "FORM") {
		return;
	}
	var i, j, q = {};
	for (i = form.elements.length - 1; i >= 0; i = i - 1) {
		if (form.elements[i].name === "") {
			continue;
		}
		switch (form.elements[i].nodeName) {
		case 'INPUT':
			switch (form.elements[i].type) {
			case 'text':
			case 'hidden':
			case 'password':
			case 'button':
			case 'reset':
			//case 'submit':
				q[form.elements[i].name]= encodeURIComponent(form.elements[i].value);
				break;
			case 'checkbox':
			case 'radio':
				if (form.elements[i].checked) {
					q[form.elements[i].name]= encodeURIComponent(form.elements[i].value);
					 
				}						
				break;
			case 'file':
				break;
			}
			break;			 
		case 'TEXTAREA':
		 
		    q[form.elements[i].name]= encodeURIComponent(form.elements[i].value);
			break;
		case 'SELECT':
			switch (form.elements[i].type) {
			case 'select-one':
				 
				q[form.elements[i].name]= encodeURIComponent(form.elements[i].value);
				break;
			case 'select-multiple':
				for (j = form.elements[i].options.length - 1; j >= 0; j = j - 1) {
					if (form.elements[i].options[j].selected) {
						q[form.elements[i].name]= encodeURIComponent(form.elements[i].options[j].value);						 
					}
				}
				break;
			}
			break;
		case 'BUTTON':
			switch (form.elements[i].type) {
			case 'reset':
			case 'submit':
			case 'button':				 
				q[form.elements[i].name]= encodeURIComponent(form.elements[i].value);
				break;
			}
			break;
		}
	}
	return q;
}