

var AppPage;
Class.set("Iframe", {
	initialize: function(id,h){  	
		 this.create(id,h)
	     this.h=10
		 frame=true
		  
	},
	create:function(id,h){
	   this.id=id;
	  
       iframe=$("<iframe id='"+id+"' src='' margin=0 frameborder=0 border=0 width='100%' ></iframe>").appendTo($('#ok'));
	   
	   iframe.height(h);
	   
	},
    resize:function(h){	
		
	    $("#"+this.id).height(h);
	
	},
	load:function(src){
		//alert(this.id)
		$("#"+this.id).attr("src",src);
	
	},
    reload:function(){
	
	  $("#"+this.id).attr("src", $("#"+this.id).attr("src"));
	
	}
});
var cureid=null;
function  LoadPage(key){
	
	//Mandala.widget.runBox.start(a,"divMain")
	AppPage.LinkShow(key);
	//dump(key)
}
var uid=Genv.Cookies.get('uid');
 

Class.set("Gui.page",{
	options: {
		 topH:60,
		 gapH:7,
		 bottomH:40,
		 centerW:6,
		 leftW:180,
		 index:false,
		 Iframe:false
		 
	} ,

	initialize: function(options){		 
		//this.setOptions(options);
		G.extend(this.options,options);	
		
		if(this.options.index){
			 this.indexPage();
			 this.setmenus();
			 this.setevent();
			 
			
		}
	    var key=Genv.Cookies.get('openbar'+uid)||0;
		var src= Genv.Cookies.get('openurl'+uid); 
		 
	    if(src){
			 
			$($(".leftsidebottom .leftsidetottomnav").get(key)).click();
			//alert($(".leftsidebottom .leftsidetottomnav").get(key).innerHTML)
		   this.Iframe.load(src);
		}else{
		 
			$($(".leftsidebottom .leftsidetottomnav").get(key)).click();
			src=defaultsrc;
		    this.Iframe.load(src);
		}
        	
		
		 var rect=Gui.body() 
		 this.bgmask=new Gui.maskBox({guid:'webpage',rect:rect});
		 
		 this.bgmask.hide();
		var self=this;
		G.Event.on(window,'resize',function(){
			 self.indexPage();
			Global.exec("winresize");
		})
			Global.add("winresize",this.indexPage);
	},
	setmenus:function(){
 
	   var box=new Gui.runBox();

	   $(".leftsidebottom .leftsidetottomnav").each(function(n,i){
		   
	         var key=$(this).attr('key')
			 
			 $(this).click(function(){
					// box.init(G.$(this));
				   // box.runto( G.$(".leftnav").get(0),function(){				 
					// $(".left_menus_top").html( $(self.elements[i]).html() )
					 
					Genv.Cookies.set('openbar'+uid,key);
					$(".leftsidebottom   a").each(function(b,j){
						if(b==n){
						$(this).addClass('active');
						}else{
						$(this).removeClass('active')
						}
					})
					
					  $('.leftnav').html( $("#menu_"+key).html());
				// } ) 
			    //$('.left_menus_top').html( $("#menu_"+key).html());
			 
			 })
				 

			// if(n==0){ $(this).click()}
	   
	   })	
	},
    
	indexPage:function(){
	//设置首页;
	  options=this.options;
	  this.document=$(document);
	 // alert(this.options.topH)
	 /* $("#top").css("height",options.topH);
	 
	  $("#bottom").css("height",options.bottomH)
		*/  
	  $("#dbkleftbg").css({
		  top:options.topH+options.gapH,
		  bottom:options.bottomH+options.gapH,
		  width:options.leftW
          
	  })
		// alert(options.bottomH+options.gapH)
		 
	
	  
	  if(Genv.Browser.ie&&Genv.Browser.ver=="6.0"){	
		  alert('您的浏览器版本太低，请升级以达到更好的效果')
		  $("#dbkleftbg").css({
				  "height":"80%" 
			  })
		 $("#dbkdrager").css({
				  "_top":options.topH+options.gapH+300,
				  bottom:options.bottomH+options.gapH+200,
				  left:options.leftW+1,
				  width:options.centerW
				  
		  }) 
		  $("#contents").css({			 
			  "_top":options.topH+options.gapH,
			  "_left":options.leftW+options.gapH-40,
			  bottom:options.bottomH+options.gapH
			  
			  
		  })
	  
		 
	  }else{
		 $("#dbkdrager").css({
				  top:options.topH+options.gapH,
				  bottom:options.bottomH+options.gapH,
				  left:options.leftW+1,
				  width:options.centerW
				  
			  }) 
		  $("#contents").css({
			  top:options.topH+options.gapH,
			  left:options.leftW+options.gapH,
			  "_top":options.topH+options.gapH,
			  "_left":options.leftW+options.gapH,
			  bottom:options.bottomH+options.gapH	  
			  
		  })
	  }
		
	  
		   
	  if(!this.Iframe){   
		  var rect=Gui.body() 
		 h=rect.height-70;
		this.Iframe=new Iframe("webIframe",h);
	  }else{
	   var rect=Gui.body() 
		 h=rect.height-70;
		// alert(h+"-")
		 
		AppPage.Iframe.resize(h)
	  
	  }

	  
 
	  //alert($("#displaytable").height()-10)
		//  h=500
		
	 
/*
	 this.mainFrame=CE("<iframe  border=1 src='grid.html'>").appendTo( $(".inner") )
	 this.mainFrame.css({
		  height:$("#outer").height()-5,
		  width:$("#outer").width()-5
		})
*/		 
	},
	setevent:function(){
		 this.resizebound = {
			'start': this.startResize.bind(this),
			'doing': this.onResizing.bind(this),
			'stop': this.endResize.bind(this)
		};
		this.resizeEl=G.$("#dbkdrager")
		this.resizeEl.css({ cursor:"w-resize" });
		this.resizeEl.on('mousedown',this.resizebound.start);
 
		this.maskEl= G.Element.create("<div class='genv_resize_moving'></div>").appendTo(G.$("body")) ;
		  
		$(document.body).append(this.maskEl.get(0));

		var self=this;
	 
		$("#setting").click(function(){	
	      
			 
			mywin=new  Gwin(G.$(this),{title:'修改密码',url:PI.APP+'/user/changepwd',width:400,height:250})
			mywin.display();
		}) 
		$("#notic").click(function(){	
	          src=PI.APP+"/article/read";
			  
			  self.Iframe.load(src);
		}) 
        $("#menus").click(function(){	
	      
			var h=$("#menus").html();
			if(h=='隐藏菜单'){
			   $("#menus").html('显示菜单');
			  // this.options.leftW=this.options.leftW

				 self.options.leftW=0
				self.indexPage();	
			}else{
				
 
				self.options.leftW=180
				self.indexPage();	
				$("#menus").html('隐藏菜单');
			
			}
			 
		}) 
		 
		 // miniYUI.doWhileExist('dbkdrager', dragerInit);

		G.Event.on(window,'resize',function(){
			//self.indexPage();
			Global.exec("winresize");
		})
		
	
	},
	startResize:function(e){

		 
		 G.Event.stop(e);	
	    options=this.options;

		this.resizeEl.un('mousedown',this.resizebound.start);
		
		this.resizeEl.addClass('genv_resize_moving');		
			 
		

		 this.maskEl.css({
		  top:options.topH+options.gapH,
		  bottom:options.bottomH+options.gapH,
		  left:options.leftW,
		  width:options.centerW,
		  cursor:"w-resize"         
		}).show(); 
		// this.maskEl.show();
		
		 this.bgmask.show();

		G.$(this.document).on('mousemove',this.resizebound.doing);
		G.$(this.document).on('mouseup',this.resizebound.stop);

	},
	onResizing:function(e){
		G.Event.stop(e)		 
		 
 
		var now=  G.Event.page(e).x	;         
		// dump(now)
		this.maskEl.css({		 
		  left:now           
	  }) 
	   //dump(now)
	},
	endResize:function(e){
		  G.Event.stop(e)	
		
		G.$(this.document).un('mousemove',this.resizebound.doing);
		G.$(this.document).un('mouseup',this.resizebound.stop);
		//G.Event.removeEvent(this.document,'mousemove',this.resizebound.doing);
		//G.Event.removeEvent(this.document,'mouseup',this.resizebound.stop);

		this.resizeEl.removeClass('genv_resize_moving');		

		this.options.leftW=G.Event.page(e).x

		 this.maskEl.hide();
		
		this.bgmask.hide();

		this.indexPage();		

		this.resizeEl.on('mousedown',this.resizebound.start);
		 
	},
	LinkShow:function(key){
		var link=LinksConfig[key];
		var src=link.url;
		Genv.Cookies.set('openurl'+uid,src);
        this.Iframe.load(src);	

		 		 
	
	}
});



function init(){
	var options={
		 topH:65,
		 gapH:0,
		 bottomH:0,
		 leftW:180, 
		 index:true
	}
	AppPage=new Gui.page(options);
	 

} 
var mywin;
function popendialog(x){
 
			mywin=new  Gwin(G.$(this),x)
			mywin.display();

}
function aiqi(a){
//mywin.display();
	G.$(".wrap_close").click();
}

//.appdialog(obj);
//app应用打开父页面窗口;
function appdialog(x){
	//alert(4)
//mywin=new  Gwin(G.$(this),{title:'修改密码',url:PI.APP+'/user/changepwd',width:400,height:250})
	var rect=Gui.body() 
	 if(!x.width){
	x.width=rect.width-100;
	
	}
	if(!x.height){
		x.height=rect.height-100;

	}
	mywin=new  Gwin(G.$("#setting"),x)
	mywin.display();

}
 function notice(a){
 $("#debug").val(a);
 
 }
 
$(function(){
	init();
	//dump(Genv.Browser,1)
});
;