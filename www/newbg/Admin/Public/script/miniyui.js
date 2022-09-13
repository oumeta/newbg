// --added @2009.06.05 by lane.tan--
var miniYUI = {
	/*--event{{--*/
	stopEvent : function(ev) {
		this.stopPropagation(ev);
		this.preventDefault(ev);
	},

	stopPropagation : function(ev) {
		if (ev.stopPropagation) {
			ev.stopPropagation();
		} else {
			ev.cancelBubble = true;
		}
	},

	preventDefault : function(ev) {
		if (ev.preventDefault) {
			ev.preventDefault();
		} else {
			ev.returnValue = false;
		}
	},

	getEvent : function(e, boundEl) {
		var ev = e || window.event;

		if (!ev) {
			var c = this.getEvent.caller;
			while (c) {
				ev = c.arguments[0];
				if (ev && Event == ev.constructor) {
					break;
				}
				c = c.caller;
			}
		}

		return ev;
	},

	getTarget : function(ev, resolveTextNode) {
		var t = ev.target || ev.srcElement;
		return this.resolveTextNode(t);
	},

	resolveTextNode : function(n) {
		try {
			if (n && 3 == n.nodeType) {
				return n.parentNode;
			}
		} catch (e) {
		}

		return n;
	},

	on : function() {
		if (window.addEventListener) {
			return function(el, sType, fn, capture) {
				el.addEventListener(sType, fn, (capture));
			};
		} else if (window.attachEvent) {
			return function(el, sType, fn, capture) {
				el.attachEvent("on" + sType, fn);
			};
		} else {
			return function() {
			};
		}
	}(),
	/*--event}}--*/

	/*--dom{{--*/
	// has class
	hasClass : function(el, className) {
		return className
				&& (' ' + el.className + ' ').indexOf(' ' + className + ' ') > -1;
	},

	// add class
	addClass : function(el, className) {
		if(!el) return false;
		if (el.className === '') {
			el.className = className;
		} else if (el.className !== '' && !this.hasClass(el, className)) {
			el.className = el.className + ' ' + className;
		}
	},

	// remove class
	removeClass : function(el, className) {
		if (this.hasClass(el, className)) {
			el.className = (' ' + el.className + ' ').replace(
					' ' + className + ' ', ' ').replace(/^ | $/g, '');
		}
	},

	// getElementsByClassName
	getElementsByClassName : function(className, tag, root) {
		if (!root) {
			return [];
		}

		var nodes = [], elements = root.getElementsByTagName(tag);

		for (var i = 0, len = elements.length; i < len; i++) {
			if (this.hasClass(elements[i], className)) {
				nodes[nodes.length] = elements[i];
			}
		}

		return nodes;
	},

	// get previoussibling
	getPreviousSibling : function(node) {
		while (node) {
			node = node.previousSibling;
			if (node && node.nodeType == 1) {
				return node;
			}
		}
		return null;
	},

	// get nextsibling
	getNextSibling : function(node) {
		while (node) {
			node = node.nextSibling;
			if (node && node.nodeType == 1) {
				return node;
			}
		}
		return null;
	},

	// 取元素坐标，如元素或其上层元素设置position relative，应该getpos(子元素).y-getpos(父元素).y from
	// easyUI
	getPosition : function() {
		return document.documentElement.getBoundingClientRect && function(o) {// IE,FF,Opera
					var pos = o.getBoundingClientRect(), root = o.ownerDocument
							|| o.document;
					return {
						left : pos.left + root.documentElement.scrollLeft,
						top : pos.top + root.documentElement.scrollTop
					};
				} || function(o) {// Safari,Chrome,Netscape,Mozilla
					var x = 0, y = 0;
					do {
						x += o.offsetLeft;
						y += o.offsetTop;
					} while ((o = o.offsetParent));
					return {
						left : x,
						top : y
					};
				};
	}(),

	// get event position
	getXY : function(e) {
		return {
			x : e.pageX ? e.pageX + 4 : e.clientX
					+ document.documentElement.scrollLeft,
			y : e.pageY ? e.pageY + 4 : e.clientY
					+ document.documentElement.scrollTop
		};
	},

	// --check if the parent obj is sun or grandsun of the parent obj
	isChild : function(sunObj, parentObj) {
		if (!sunObj || !parentObj) {
			return;
		}

		var isChild = false;
		if (parentObj.tagName && parentObj.tagName.toLowerCase() == 'body') {
			return true;
		}
		while (sunObj && sunObj.tagName
				&& sunObj.tagName.toLowerCase() != 'body') {
			if (sunObj.parentNode == parentObj) {
				isChild = true;
				break;
			}
			sunObj = sunObj.parentNode;
		}
		return isChild;
	},
	/*--dom}}--*/

	clone : function(data) {
		if (typeof(data) != 'object') {
			return null;
		}
		var obj = {};
		if (!data.length) {
			for (key in data) {
				obj[key] = data[key];
			}
		} else {
			obj = [];
			for (var i = 0; i < data.length; i++) {
				obj[i] = data[i];
			}
		}

		return obj;
	},

	// init function
	doWhileExist : function(elId, fun) {
		var argu = Array.prototype.slice.call(arguments, 2);
		var module = document.getElementById(elId);

		 
		if (module) {
			argu.unshift(module);
			 
			fun.apply(null, argu);
		}
	}
};

var dragerInit = function(module) {
    var dTar, YUI = miniYUI;
    var _moveReady = false,
        _isMoving = false;
    var x,y,orX,newX,orY, oPos;
    
    var dLeft = document.getElementById('dbkleftbg');
    var dRight = document.getElementById('contents')
      
    var dRights = $("div.contents");
    var orWidth = 187, orRight = 207;
    
    YUI.on(document, 'mousedown', function(e) {
        dTar = YUI.getTarget(e);
        
        if (dTar.tagName.toLowerCase() == 'span' && dTar.parentNode && dTar.parentNode.id && dTar.parentNode.id == module.id) {
            dTar = dTar.parentNode;
        }
        
        if (dTar.id && dTar.id == module.id) {
            _moveReady = true;
            oPos = YUI.getXY(e);
            orX = oPos.x;
            newX = orX;
            
            orWidth = dLeft.offsetWidth;
            
            if (document.all) {
            	for(var i=0;i<dRights.length;i++){
            		if (dRights[i].style.display != 'none') {
                        orRight = parseInt(dRights[i].currentStyle['borderLeftWidth'].replace('px',''));
                    }
            	}            	   
            }else {
             	for(var i=0;i<dRights.length;i++){
            		if (dRights[i].style.display != 'none') {
            			orRight = YUI.getPosition(dRights[i]).left;
            			//alert(dRights[i].id);
                    }
            	}

            }
        }
    });

    YUI.on(document, 'mousemove', function(e) {
        dTar = YUI.getTarget(e);
         AppPage.bgmask.show();
        if (_moveReady) {
            _isMoving = true;
            
            oPos = YUI.getXY(e);
            x = oPos.x;

            oPos = YUI.getPosition(module);
            module.style.left = oPos.left + (x-newX) + 'px';
            
            newX = x;

            if (dLeft) {
                orWidth += x-orX;
                dLeft.style.width = orWidth + 'px';
            }
            
            if (dRight) {
                orRight += x-orX;
                if (document.all) {
                	for(var i=0;i<dRights.length;i++){
                		dRights[i].style.borderLeftWidth = orRight + 'px';
                	}
                }else {
                	for(var i=0;i<dRights.length;i++){
                		dRights[i].style.left = orRight + 'px';
                	}
                }
            }
            
            orX = x;
        }
    });

    YUI.on(module, 'mouseup', function(e) {
        dTar = YUI.getTarget(e);
		  AppPage.bgmask.hide();
        if (dTar.id && dTar.id == module.id && _isMoving) {
        	
            if (document.all) {
            	var myTable = jDbank.data.getGlobal('mytable');	// reset width
            	if (myTable) {
            		myTable.widthAdapt(myTable._tableObj, false);
            	}
            	
            	var myTransTable = jDbank.data.getGlobal('transTable');
            	if (myTransTable) {
            		myTransTable.widthAdapt(myTransTable._tableObj, false);
            	}
            	
            	var linkFileList = jDbank.data.getGlobal('linkListTable_instance');
            	if (linkFileList) {
            		linkFileList.widthAdapt(linkFileList._tableObj, false);
            	}
            	
            }
            
            oPos = YUI.getXY(e);
            x = oPos.x;

            oPos = YUI.getPosition(module);
        }

        _moveReady = false;
        _isMoving = false;
    });
    
    YUI.on(module, 'dblclick', function(e) {
        dTar = YUI.getTarget(e);
        dLeft.style.width = '';
        module.style.left = '';
        
        if (document.all) {
        	for(var i=0;i<dRights.length;i++){
        		dRights[i].style.borderLeftWidth = '';
        	}
        }else {
        	for(var i=0;i<dRights.length;i++){
        		dRights[i].style.left = '';
        	}
        }
    });
};