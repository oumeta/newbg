function dump(s,a){	 
	if(!this.dumpa){
		this.dumpa= Genv.Element.create("<textarea id='debugarea' style='position:absolute;right:30px;bottom:10px;width:350px;height:200px'></textarea>");
		G.$(document.body).append(this.dumpa.get(0))
			 
	}	
		 s=JSON.stringify(s)
		//s=Genv.json.encode(s)
	 //this.dumpa.val(s);//$("#debugarea").value+s+"\n"		 
	  //this.dumpa.val(s+"\n"+this.dumpa.val());//$("#debugarea").value+s+"\n"
	    this.dumpa.val(this.dumpa.val()+s+"\n")
		if(a==1){
			alert(this.dumpa.val()+s+"\n")
		}
		setTimeout(function(){
			//$(this.dumpa).fadein();
		},5000)
}

function K(fn){
  fn=fn||Class.empty;
  fn();
};

(function() {
    var A = {};
    G.getrd = function(C) {
        var D = C || 8;
        var E = "";
        while (D--) {
            E += B()
        }
        if (!A[E]) {
            A[E] = 1;
            return E
        } else {
            return getUniqueId(D)
        }
    };
    var B = function() {
        var D = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        var C = D.length;
        return D.charAt(Math.floor(Math.random() * C))
    }
})();

var Global={
	List:{},
	add:function(key,func){
		if(this.List[key]){
			this.List[key].push(func);
		}else{
			this.List[key]=[func];
		}
	},
	exec:function(key,args){
	 
		if (this.List[key]) {
			//alert(this.List[key].length)
			for(var i=0;i<this.List[key].length;i++){
				var func=this.List[key][i];				
				try{
					func(args);
				}catch(ex){
					// alert(ex.message);
				}				
			}			 
		}
	}	
} 
 

G.ns=G.namespace 
 
Gui={
	guid:G.getrd(), 
	body : function() {
		var A = 0, J = 0,E = 0,C = 0,B = 0,K = 0;
		var F = window,
		D = document,
		I = D.documentElement;
		A = I.clientWidth || D.body.clientWidth;
		J = F.innerHeight || I.clientHeight || D.body.clientHeight;
		C = D.body.scrollTop || I.scrollTop;
		E = D.body.scrollLeft || I.scrollLeft;
		B = Math.max(D.body.scrollWidth, I.scrollWidth || 0);
		K = Math.max(D.body.scrollHeight, I.scrollHeight || 0, J);
		return {
			scrollTop: C,
			scrollLeft: E,
			documentWidth: B,
			documentHeight: K,
			width: A,
			height: J
		}
	},
	rect:function(e){
	    
	  
	   return {
			left:e.left(),
			top:e.top(),
			width:e.width(),
			height:e.height()	    
	   }
	},
	getScroll : function (e){
		var t, l, w, h, iw, ih;
		if (e && e.nodeName.toLowerCase() != 'body') {
			t = e.scrollTop;
			l = e.scrollLeft;
			w = e.scrollWidth;
			h = e.scrollHeight;
			iw = 0;
			ih = 0;
		} else  {
			if (document.documentElement && document.documentElement.scrollTop) {
				t = document.documentElement.scrollTop;
				l = document.documentElement.scrollLeft;
				w = document.documentElement.scrollWidth;
				h = document.documentElement.scrollHeight;
			} else if (document.body) {
				t = document.body.scrollTop;
				l = document.body.scrollLeft;
				w = document.body.scrollWidth;
				h = document.body.scrollHeight;
			}
			iw = self.innerWidth||document.documentElement.clientWidth||document.body.clientWidth||0;
			ih = self.innerHeight||document.documentElement.clientHeight||document.body.clientHeight||0;
		}
		return { t: t, l: l, w: w, h: h, iw: iw, ih: ih };
	},	
	getPositionLite : function(el)	{
		var x = 0, y = 0;
		while(el) {
			x += el.offsetLeft || 0;
			y += el.offsetTop || 0;
			el = el.offsetParent;
		}
		return {x:x, y:y};
	},
	getSizeLite : function(el)
	{
		return {
			wb:el.offsetWidth||0,
			hb:el.offsetHeight||0
		};
	},
	getClient : function(e){
		var h, w, de;
		if (e) {
			w = e.clientWidth;
			h = e.clientHeight;
		} else {
			de = document.documentElement;
			w = window.innerWidth || self.innerWidth || (de&&de.clientWidth) || document.body.clientWidth;
			h = window.innerHeight || self.innerHeight || (de&&de.clientHeight) || document.body.clientHeight;
		}
		return {w:w,h:h};
	}

}
 
/*
a =$M.getDD();
alert(a())
*/
G.getDD=function(){
	var D1 = new Date();
	return function(b){
		var D2= new Date();	
 
		return (D2.valueOf() - D1.valueOf()+"ms");
		}
}
Class.set("Gui.Panel",{
    guid:'',
	initialize:function(options){
		 this.guid=G.getrd();
         G.extend(this,options);
		 
		 
		 this.create();
	},
	
	temp:function(){
		 
		return '<div class="ui_panel"  id="{0}"></div>'.format(this.guid)
	},
	create:function(){
	   temp=CE(this.temp());
	   if(this.cid){ 
			G.$("#"+this.cid).append( temp[0] )
	   }else{			 
			G.$('body').append( temp[0] )
	   }	 
		//$("#"+this.guid).addClass("ui_panel") 
    },
	show:function(){

	 
	   G.$("#"+this.guid).show();	
	   
	},
	hide:function(){
		G.$("#"+this.guid).hide();	  
	},
	setrect:function(rect){
		 
		  this.setwh(rect.width,rect.height)
		  this.setxy(rect.left,rect.top)
    },
	setxy:function(x,y){
			 
	   G.$("#"+this.guid).css({left:x,top:y});
	  // $("#"+this.guid).top(y)
	},
	setwh:function(w,h){		  
	   G.$("#"+this.guid).width(w);
	   G.$("#"+this.guid).height(h)
	},
    getsize:function(el){
         try{
			  return {
			   width:G.$(el).width(),
			   height:G.$(el).height(),
			   top:G.$(el).top(),
			   left:G.$(el).left()		 
			 }  
		 }catch(e){
			 if(G.isObject(el)){
			  return el;
			 }
		 }	   
	}
	 
})

Class.set("Gui.runBox",Gui.Panel,{
	initialize:function(options){
	 
        this.Parent(options ) ; 
		G.$("#"+this.guid).addClass(" gui_box gui_border0")
		
	},
	init:function(id){
		this.show();
		
		var rect=Gui.rect(id)
         // alert(id.html())
		 
	    this.setrect(rect)	
		this.crect=rect;
	},
	runto:function(to,fn,dur){
       

         var rc=this.getsize(to);
		 dur=dur||200
	 
		  //dump({width:rc.width,height:rc.height,left:rc.left,top:rc.top});
 		  // return ;
       //  alert("#"+this.guid)
	   var self=this;
		 G.$("#"+this.guid).animate({width:rc.width,height:rc.height,left:rc.left,top:rc.top},dur,'easeNone',function(){
			 K(fn); $(this).hide();
		 })  
        
	}

});
Class.set("Gui.maskBox",Gui.Panel,{
	initialize:function(options){
        this.Parent(options) ;
		 
		G.$("#"+this.guid).addClass("gui_box genv_mask ").hide();		
		 G.$("#"+this.guid).css({		 
		"background-color":'#cccccc',
		'opacity':0.1,		 
		'z-index':1000
		 
		});
		if(options.rect){
			this.showid(options.rect)
		}
	},
	showid:function(el){
	    var rect=this.getsize(el);
		 
		this.setrect(rect);
	}
});

/*别样的手风琴*/
Class.set("Gui.accordion",{

	initialize: function(options){
		 
		 
		 this.resize();
		 
		 Global.add("winresize",this.resize);

		 var stretchers =this.stretchers=$('div.accordion') ;
		 var self=this;
		 stretchers.each(function(item){
			G.$(item).css({'height': '0', 'overflow': 'hidden'});
		 });
		 this.elements = [];
		 
		 G.each(stretchers,function(el){
		 
			el.elements.push( this );
		 },[this]);
		 this.setevent(); 

	},
	resize:function(){
		 
		G.$('.menus_accordion').css({
		   height:$('.gleft').height()-10,
		   width:$('.gleft').width()-5		 
		 })

		var h=G.$('.left_menus').parent().height()-G.$('.left_menus').height();			
		G.$('.left_menus_top').height( h );//.addClass("gui_border1");
	
	},
	setevent:function(){
	    var self=this;
		var togglers = G.$('h3.toggler');
		
		var box=new Gui.runBox()
		 togglers.each(function(toggler,i){
			 
			G.$(toggler).click( function(){
			
 				  box.init($(this));
  
				  box.runto( G.$(".left_menus_top").get(0),function(){				 
					 G.$(".left_menus_top").html( $(self.elements[i]).html() )
				  } ) 
			 
			})		 
		 
		 })

		  G.$(togglers[0]) .click();
	
	}

});
//窗口模拟;
 
Class.set("Gwin",{

	setOptions: function(options){
			this.options = Genv.extend({
				
				width:800,
				height:350,
				title:'',
				html:'',
				url:false
			}, options || {});
			var g=Gui.body();//保障碍居中显示；
//alert((g.height-this.options.height)/2);
//alert([g.height,this.options.height])
			//dump(this.options,1)

			this.options.rect={width:this.options.width,
							   height:this.options.height,
							   left: (g.width-this.options.width)/2,
							   top:(g.height-this.options.height)/2}

			 
	},
	initialize: function(el,options){
		var css = '<style type="text/css">#zxxBlank{position:absolute;z-index:2000;left:0;top:0;width:100%;height:0;background:black;}.wrap_out{padding:5px;background:#eee;box-shadow:0 0 6px rgba(0,0,0,.5);-moz-box-shadow:0 0 6px rgba(0,0,0,0.5);-webkit-box-shadow:0 0 6px rgba(0,0,0,.5);position:absolute;z-index:2000;}.wrap_in{background:#fafafa;border:1px solid #ccc;}.wrap_bar{width:100%;background:#f7f7f7;border-top:3px solid #f9f9f9;border-bottom:4px solid #eee;margin-top:2px;}.wrap_title{line-height:5px;background:#f3f3f3;border-top:4px solid #f5f5f5;border-bottom:5px solid #f1f1f1;margin-top:3px;}.wrap_title span{position:relative;margin-left:10px;cursor:text;}.wrap_body{display:inline-block;border-top:1px solid #ddd;background:white;}.wrap_close{margin-top:-18px;color:#34538b;font-weight:bold;margin-right:10px;font-family:Tahoma;text-decoration:none;cursor:pointer;float:right;}.wrap_close:hover{text-decoration:none;color:#f30;}.wrap_remind{width:16em;padding:30px 40px;}.wrap_remind p{margin:10px 0 0;}.submit_btn{display:inline-block;padding:3px 12px 1.99px;background:#486aaa;border:1px solid;border-color:#a0b3d6 #34538b #34538b #a0b3d6;color:#f3f3f3;line-height:16px;cursor:pointer;overflow:visible;}.submit_btn:hover{text-decoration:none;color:#ffffff;}.cancel_btn{display:inline-block;padding:3px 12px 1.99px;background:#eee;border:1px solid;border-color:#f0f0f0 #bbb #bbb #f0f0f0;color:#333;line-height:16px;cursor:pointer;overflow:visible;}</style>';
		 
		//G.loadCss(css)
		this.setOptions(options);
		this.el=el;
		this.runbox=new Gui.runBox();
		this.guid=guid=Gui.guid;
		this.WRAP = '<div class="wrap_out" id="wrapOut'+guid+'">'+
						'<div class="wrap_in" id="wrapIn'+guid+'">'+
							'<div id="wrapBar'+guid+'" class="wrap_bar"  onselectstart="return false;">'+
							'<div class="wrap_title"><span>'+this.options.title+'</span></div>'+
							'<a href="javasctipt:void(0);" class="wrap_close" id="wrapClose'+guid+'">×</a></div>'+
							'<div class="wrap_body" id="wrapBody'+guid+'"></div>'+
						     '<div style="font: 0px/0px sans-serif;clear: both;display: block"> </div> '+ 
						'</div>'+
					'</div>';

	},
	display:function(){
			this.runbox.init(this.el);
            var self=this;
		   
		   G.$(document.body).append(this.WRAP);
		   G.$(".wrap_out").hide();
		 // dump(self.options.rect,1);
		  // return;
			this.runbox.runto(self.options.rect,function(){				
				
				  G.$(".wrap_out").addClass('gui_box').css(self.options.rect);
				  //dump(self.options.rect,1)
				  G.$('.wrap_body').width('100%').css('height',self.options.rect.height-35)  ;
				  var html=self.options.html ;
				 
				  if(self.options.url){
					html="<iframe id='"+self.guid+"_iframe' src='"+self.options.url+"' height='"+(self.options.rect.height-35)+"' margin=0 frameborder=0 border=0 width='100%' ></iframe>"
				  
				  } 
				 
				  G.$('.wrap_body').html( html  )
 
					//  $("#"+self.guid+"_iframe").height()
				  G.$(".wrap_close").click(function(e){
						G.Event.stop(e);

						var b=self.el.box(),c={};
							c.opacity=10;
							c.left=b.left;
							c.top=b.top;
							c.width=b.width;
							c.height=b.height;
							// dump(b);
							//return;
						G.$(".wrap_body").height(0).empty();
						G.$(".wrap_out").animate(c,200, 'easeNone', function(){
							G.$(".wrap_out").hide().dispose();
						
						});
				  
				  });
				var rect=Gui.body();
				//alert(rect)
				//var bgmask=new Gui.maskBox({guid:'webpage',rect:rect});
				//bgmask.hide();
			G.$(".wrap_out").show();
				G.$('.wrap_out').Drag({
					handle:$('.wrap_title').get(0), 
					onstart:function(){					
					   bgmask.show();
					},
					onend:function(){
					  
					   bgmask.hide();
					}
				 }); 
/*
				 $('.wrap_out').drag({
					hadle:$('.wrap_title').get(0),
					onDragStart:function(){					
					   bgmask.show();
					},
					onDragEnd:function(){
					  
					   bgmask.hide();
					}
				 }); */

				  //Drag.init($('.wrap_title').get(0) ,$('.wrap_out')[0])
				 
				   G.$(".wrap_out").show();

				 //  alert(G.$(".wrap_out").html())
				//$bg.width(1500).height(1500).css("opacity", 1);
			})


	}

})

