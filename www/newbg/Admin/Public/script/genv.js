/*精简实用框架
Genv v0.1
2010-9-10
*/
(function(win,doc,Genv) {
var G=this.G=this.Genv=Genv,isReady = false,readyList = [],readyBound = false,
	toString = Object.prototype.toString,Function = this.Function,enumerables = true;
for (var i in {toString: 1}) enumerables = null;
if (enumerables) enumerables = ['hasOwnProperty', 'valueOf', 'isPrototypeOf', 'propertyIsEnumerable', 'toLocaleString', 'toString', 'constructor'];

Function.prototype.overloadSetter = function(usePlural){
	var self = this;
	return function(a, b){
		if (a == null) return this;
		if (usePlural || typeof a != 'string'){
			for (var k in a) self.call(this, k, a[k]);
			if (enumerables) for (var i = enumerables.length; i--;){
				k = enumerables[i];
				if (a.hasOwnProperty(k)) self.call(this, k, a[k]);
			}
		} else {
			self.call(this, a, b);
		}
		return this;
	};
};

Function.prototype.extend = function(key, value){
	 
	this[key] = value;
}.overloadSetter();

Function.prototype.implement = function(key, value){
	this.prototype[key] = value;
}.overloadSetter();

(function(Genv,ua){    
	var b = {
	    ie: /msie/.test(ua) && !/opera/.test(ua),
		ch: /chrome/.test(ua),
		op: /opera/.test(ua),
		ff: /firefox/.test(ua),
		sa: /webkit/.test(ua) && !/chrome/.test(ua)
	};	
	b.ver = ( ua.match(/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/)||[0,'0'] )[1];
	b.i7 = b.ie && b.ver >= 7;
	b.i8 = b.ie && b.ver == 8;
	b.isDTD = document.compatMode == 'CSS1Compat';	
	Genv.Browser = b;	
	Genv.props = {
	    'for': 'htmlFor',
		'class': 'className',
		'float': b.ie ? 'styleFloat' : 'cssFloat'
	};	
})(Genv,navigator.userAgent.toLowerCase());



Genv.extend(
{
	isEmpty : function(v, allowBlank){
		return v === null || v === undefined || ((Genv.isArray(v) && !v.length)) || (!allowBlank ? v === '' : false);
	},  
	isArray : function(v){
		return toString.apply(v) === '[object Array]';
	},  
	isDate : function(v){
		return toString.apply(v) === '[object Date]';
	},
	isObject : function(v){
		return !!v && Object.prototype.toString.call(v) === '[object Object]';
	},      
	isPrimitive : function(v){
		return G.isString(v) || G.isNumber(v) || G.isBoolean(v);
	}, 
		
	isPlainObject: function( obj ) {
		// Must be an Object.
		// Because of IE, we also have to check the presence of the constructor property.
		// Make sure that DOM nodes and window objects don't pass through, as well
		if ( !obj || toString.call(obj) !== "[object Object]" || obj.nodeType || obj.setInterval ) {
			return false;
		}
		
		// Not own constructor property must be Object
		if ( obj.constructor
			&& !hasOwnProperty.call(obj, "constructor")
			&& !hasOwnProperty.call(obj.constructor.prototype, "isPrototypeOf") ) {
			return false;
		}
		
		// Own properties are enumerated firstly, so to speed up,
		// if last one is own, then all properties are own.
	
		var key;
		for ( key in obj ) {}
		
		return key === undefined || hasOwnProperty.call( obj, key );
	},
    
	isFunction : function(v){
		return toString.apply(v) === '[object Function]';
	},
	isNumber : function(v){
		return typeof v === 'number' && isFinite(v);
	},
	isString : function(v){
		return typeof v === 'string';
	},     
	isBoolean : function(v){
		return typeof v === 'boolean';
	},      
	isElement : function(v) {
		return !!v && v.tagName;
	},
	isDefined : function(v){
		return typeof v !== 'undefined';
	},
	makeFunction: function(func, args) {
			if ( typeof func == "string" )
				func = eval("false||function("+ args +"){return " + func + "}");
			return func;
	},
	makeArray:function(){
             return Genv.Browser.ie ?
                 function(a, i, j, res){
                     res = [];
                     for(var x = 0, len = a.length; x < len; x++) {
                         res.push(a[x]);
                     }
                     return res.slice(i || 0, j || res.length);
                 } :
                 function(a, i, j){
                     return Array.prototype.slice.call(a, i || 0, j || a.length);
                 }
	}(),
 	 
	inArray: function (v, a){
      for (var i = 0, numA = a.length; i < numA; i++)

        if (a[i] == v) return i;
	  return -1;
    },
	indexOf: function(arr, o) {
			if (arr.indexOf) {
				return arr.indexOf(o);
			}
			for (var i = 0; i < arr.length; i++) {
				if (arr[i] === o) {
					return i;
				}
			}
			return -1;
	},		
    slice: Array.slice || (function() {
			var _slice = Array.prototype.slice;
			return function(arr) {
				return _slice.apply(arr, _slice.call(arguments, 1))
			}
	})(),
	merge: function( first, second ){		
		var i = 0, elem, pos = first.length;	
		if( Genv.Browser.ie ) {
			while( (elem = second[ i++ ]) != null )
				if( elem.nodeType != 8 )
					first[ pos++ ] = elem;

		}else
			while( (elem = second[ i++ ]) != null )
				first[ pos++ ] = elem;

		return first;
	},

	 
	each:function( object, fn, args ){
		var name, i = 0, length = object.length;
        
		if(G.isNumber(object)){
		     for (var i = 0; i < object; i++) {
						fn.apply(i,args||[]);
			 }
		
		}

		if( args ){
			if( length === undefined ){
				for( name in object ){
					if ( !name || object[ name ] === undefined || !object.hasOwnProperty(name) ) continue;
					if ( fn.apply( object[ name ], args ) === false ){
						break;
					}
				}
			}else
				for( ; i < length; ){
					if( fn.apply( object[ i++ ], args ) === false ){
						break;
					}
			    }
		}else{
			if( length === undefined ){
				for( name in object ){
					 if ( !name || object[ name ] === undefined || !object.hasOwnProperty(name) ) continue;
					if( fn.call( object[name], name, object[name] ) === false ){
						break;
					}
				}
			}else{
				for( var value = object[0];i < length && fn.call( value,  value ,i) !== false; value = object[++i] ){}
			}
		}

		return object;
	},	

	namespace : function(){
		var o, d;
		Genv.each(arguments, function(v) {
			d = v.split(".");
			o = window[d[0]] = window[d[0]] || {};
			Genv.each(d.slice(1), function(v2){
				o = o[v2] = o[v2] || {};
			});
		});			
		return o;
	},
	fixEvent: function(h) {
        h = window.event || h;
        var d = "altKey attrChange attrName bubbles button cancelable charCode clientX clientY ctrlKey currentTarget data detail eventPhase fromElement handler keyCode layerX layerY metaKey newValue offsetX offsetY originalTarget pageX pageY prevValue relatedNode relatedTarget screenX screenY shiftKey srcElement target toElement view wheelDelta which type".split(" ");
        var b = h;
        var f = {};
        for (var c = d.length,
        j; c;) {
            j = d[--c];
            f[j] = b[j]
        }
        if (!f.target) {
            f.target = f.srcElement || document
        }
        if (f.target.nodeType === 3) {
            f.target = f.target.parentNode
        }
        f.clientX = h.clientX - document.documentElement.clientLeft - document.body.clientLeft;
        f.clientY = h.clientY - document.documentElement.clientTop - document.body.clientTop;
        if (f.pageX == null && f.clientX != null) {
            var g = document.documentElement,
            a = document.body;
            f.pageX = f.clientX + (g && g.scrollLeft || a && a.scrollLeft || 0) - (g && g.clientLeft || a && a.clientLeft || 0);
            f.pageY = f.clientY + (g && g.scrollTop || a && a.scrollTop || 0) - (g && g.clientTop || a && a.clientTop || 0)
        }
        if (!f.which && f.button !== undefined) {
            f.which = (f.button & 1 ? 1 : (f.button & 2 ? 3 : (f.button & 4 ? 2 : 0)))
        }
        if (!f.relatedTarget && f.fromElement) {
            f.relatedTarget = f.fromElement === f.target ? f.toElement: f.fromElement
        }
        return f
    },
    isNative: function(a) {
        return !! a && (/\{\s*\[native code\]\s*\}/.test(String(a)) || /\{\s*\/\* source code not available \*\/\s*\}/.test(String(a)))
    },
	ready: function(fn) {
			// Attach the listeners
			if (!readyBound) G._bindReady();

			// If the DOM is already ready
			if (isReady) {
				// Execute the function immediately
				fn.call(win, this);
			} else {
				// Remember the function for later
				readyList.push(fn);
			}

			return this;
	},	
	_bindReady: function() {
		var self = this,
			doScroll = doc.documentElement.doScroll,
			eventType = doScroll ? 'onreadystatechange' : 'DOMContentLoaded',
			COMPLETE = 'complete',
			fire = function() {
				self._fireReady();
			};

		// Set to true once it runs
		readyBound = true;

		// Catch cases where ready() is called after the
		// browser event has already occurred.
		if (doc.readyState === COMPLETE) {
			return fire();
		}

		// w3c mode
		if (doc.addEventListener) {
			function domReady() {
				doc.removeEventListener(eventType, domReady, false);
				fire();
			}

			doc.addEventListener(eventType, domReady, false);

			// A fallback to window.onload, that will always work
			win.addEventListener('load', fire, false);
		}
		// IE event model is used
		else {
			function stateChange() {
				if (doc.readyState === COMPLETE) {
					doc.detachEvent(eventType, stateChange);
					fire();
				}
			}

			// ensure firing before onload, maybe late but safe also for iframes
			doc.attachEvent(eventType, stateChange);

			// A fallback to window.onload, that will always work.
			win.attachEvent('onload', fire);

			if (win == win.top) { // not an iframe
				function readyScroll() {
					try {
						// Ref: http://javascript.nwbox.com/IEContentLoaded/
						doScroll('left');
						fire();
					} catch(ex) {
						setTimeout(readyScroll, 1);
					}
				}
				readyScroll();
			}
		}
	},

	/**
	 * Executes functions bound to ready event.
	 */
	_fireReady: function() {
		if (isReady) return;

		// Remember that the DOM is ready
		isReady = true;

		// If there are functions bound, to execute
		if (readyList) {
			// Execute all of them
			var fn, i = 0;
			while (fn = readyList[i++]) {
				fn.call(win, this);
			}

			// Reset the list of functions
			readyList = null;
		}
	}
});

//合并属性，可多个参数;

Genv.extend = function() {	
	var target = arguments[0] || {}, i = 1, length = arguments.length, deep = false, options, name, src, copy;	
	if ( typeof target === "boolean" ) {
		deep = target;
		target = arguments[1] || {};		
		i = 2;
	}
	if ( typeof target !== "object" && !Genv.isFunction(target) ) {
		target = {};
	}
	if ( length === i ) {
		target = this;
		--i;
	}
	for ( ; i < length; i++ ) { 
		if ( (options = arguments[ i ]) != null ) {
			
			for ( name in options ) {
				src = target[ name ];
				copy = options[ name ];			
				if ( target === copy ) {
					continue;
				}			

				if ( deep && copy && ( Genv.isPlainObject(copy) || Genv.isArray(copy) ) ) {
					var clone = src && ( Genv.isPlainObject(src) || Genv.isArray(src) ) ? src
						: Genv.isArray(copy) ? [] : {};
				
					target[ name ] = Genv.extend( deep, clone, copy );			
				} else if ( copy !== undefined ) {
					target[ name ] = copy;
				}
			}
		}
	}
	return target;
};  
var arrayProto = Array.prototype;
Genv.each({
    each:function(fn){
		Genv.each(this,fn);
	},
	copy: function(){
		var newArray = [];
		for (var i = 0; i < this.length; i++) newArray.push(this[i]);
		return newArray;
	},
    include: function(a) {
        return this.index(a) != -1
    },
    index: function(c) {
        for (var b = 0,
        a = this.length; b < a; b++) {
            if (this[b] == c) {
                return b
            }
        }
        return - 1
    },
    unique: function() {
        for (var b = 0; b < this.length; b++) {
            var c = this[b];
            for (var a = this.length - 1; a > b; a--) {
                if (this[a] == c) {
                    this.splice(a, 1)
                }
            }
        }
        return this
    },
    del: function(b) {
        var a = this.index(b);
        if (a >= 0 && a < this.length) {
            this.splice(a, 1)
        }
        return this
    },
    insert: function(a, b) {
        return this.slice(0, a).concat(b, this.slice(a))
    },
    remove: function(a) {
        var c = this.slice(0, a);
        var b = this.slice(a + 1);
        return c.concat(b)
    },
    indexOf: function(c, a) {
        a || (a = 0);
        var b = this.length;
        if (a < 0) {
            a = b + a
        }
        for (; a < b; a++) {
            if (this[a] === c) {
                return a
            }
        }
        return - 1
    },
    lastIndexOf: function(b, a) {
        a = isNaN(a) ? this.length: (a < 0 ? this.length + a: a) + 1;
        var c = this.slice(0, a).reverse().indexOf(b);
        return (c < 0) ? c: a - c - 1
    },
    every: function(b) {
        var a = this.length;
        if (typeof b != "function") {
            throw new TypeError()
        }
        var d = arguments[1];
        for (var c = 0; c < a; c++) {
            if (c in this && !b.call(d, this[c], c, this)) {
                return false
            }
        }
        return true
    },
    filter: function(b) {
        var a = this.length;
        if (typeof b != "function") {
            throw new TypeError()
        }
        var f = new Array();
        var d = arguments[1];
        for (var c = 0; c < a; c++) {
            if (c in this) {
                var g = this[c];
                if (b.call(d, g, c, this)) {
                    f.push(g)
                }
            }
        }
        return f
    },
    forEach: function(b) {
        var a = this.length;
        if (typeof b != "function") {
            throw new TypeError()
        }
        for (var c = 0; c < a; c++) {
            if (c in this) {
                b(this[c], c)
            }
        }
    },
    map: function(b) {
        var a = this.length;
        if (typeof b != "function") {
            throw new TypeError()
        }
        var f = new Array(a);
        var d = arguments[1];
        for (var c = 0; c < a; c++) {
            if (c in this) {
                f[c] = b.call(d, this[c], c, this)
            }
        }
        return f
    },
    some: function(b) {
        var a = this.length;
        if (typeof b != "function") {
            throw new TypeError()
        }
        var d = arguments[1];
        for (var c = 0; c < a; c++) {
            if (c in this && b.call(d, this[c], c, this)) {
                return true
            }
        }
        return false
    },
	test: function(item){
		for (var i = 0; i < this.length; i++){
			if (this[i] == item) return true;
		};
		return false;
	},
	extend: function(newArray){
		for (var i = 0; i < newArray.length; i++) this.push(newArray[i]);
		return this;
	},
	associate: function(keys){
		var newArray = [];
		for (var i =0; i < this.length; i++) newArray[keys[i]] = this[i];
		return newArray;
	}
},
function(a, b) { 
    if (!Genv.isNative(arrayProto[a])) {
		
        arrayProto[a] = b
    }
});
/*扩展sting*/
Genv.extend(String.prototype,{
	test: function(regex, params){
		return this.match(new RegExp(regex, params));
	},
	toInt: function(){
		return parseInt(this);
	}, 
	toArray: function(str, o){
		return str.split(o||'');
	},
	camelCase: function(){
		return this.replace(/-\D/gi, function(match){
			return match.charAt(match.length - 1).toUpperCase();
		});
	},
	capitalize: function(){
		return this.toLowerCase().replace(/\b[a-z]/g, function(match){
			return match.toUpperCase();
		});
	},

	trim: function(){
		return this.replace(/^\s*|\s*$/g, '');
	},
	format: function() {
        if (arguments.length == 0) {
            return this;
        }
        for (var B = this, A = 0; A < arguments.length; A++) {
            B = B.replace(new RegExp("\\{" + A + "\\}", "g"), arguments[A]);
        }
        return B;
    },
	clean: function(){
		return this.replace(/\s\s/g, ' ').trim();
	}


});
Function.implement({
	bind: function(bind, args){
		var self = this;
		if (args != null) args = Genv.makeArray(args);
		return function(){
			return self.apply(bind, args || arguments);
		};
	},	
	bindEvent: function() {
        var f = this,
        d = arguments[0],
        b = [];
        for (var c = 1,
        a = arguments.length; c < a; c++) {
            b.push(arguments[c])
        }
        return function(h) {
            var g = b.concat();
            g.unshift(h || window.event);
            return f.apply(d, g)
        }
    },
	delay: function(delay, bind, args){
		return setTimeout(this.bind(bind, args || []), delay);
	},

	pass: function(args, bind){
		return this.bind(bind, args);
	},

	loop: function(periodical, bind, args){
		return setInterval(this.bind(bind, args || []), periodical);
	},

	run: function(args, bind){
		return this.apply(bind, Genv.makeArray(args));
	}

});
Date.now = Date.now || function() { return +new Date; }
/**
 * 对目标日期对象进行格式化
 * @name Genv.date.format
 * @function
 * @grammar Genv.date.format(source, pattern)
 * @param {Date} source 目标日期对象
 * @param {string} pattern 日期格式化规则
 * @remark
 * 
<b>格式表达式，变量含义：</b><br><br>
hh: 带 0 补齐的两位 12 进制时表示<br>
h: 不带 0 补齐的 12 进制时表示<br>
HH: 带 0 补齐的两位 24 进制时表示<br>
H: 不带 0 补齐的 24 进制时表示<br>
mm: 带 0 补齐两位分表示<br>
m: 不带 0 补齐分表示<br>
ss: 带 0 补齐两位秒表示<br>
s: 不带 0 补齐秒表示<br>
yyyy: 带 0 补齐的四位年表示<br>
yy: 带 0 补齐的两位年表示<br>
MM: 带 0 补齐的两位月表示<br>
M: 不带 0 补齐的月表示<br>
dd: 带 0 补齐的两位日表示<br>
d: 不带 0 补齐的日表示
		
 *             
 * @returns {string} 格式化后的字符串
 */
Genv.date={};
Genv.date.format = function (source, pattern) {
    if ('string' != typeof pattern) {
        return source.toString();
    }

    function replacer(patternPart, result) {
        pattern = pattern.replace(patternPart, result);
    }
    
    var pad     = Genv.number.pad,
        year    = source.getFullYear(),
        month   = source.getMonth() + 1,
        date2   = source.getDate(),
        hours   = source.getHours(),
        minutes = source.getMinutes(),
        seconds = source.getSeconds();

    replacer(/yyyy/g, pad(year, 4));
    replacer(/yy/g, pad(year.toString().slice(2), 2));
    replacer(/MM/g, pad(month, 2));
    replacer(/M/g, month);
    replacer(/dd/g, pad(date2, 2));
    replacer(/d/g, date2);

    replacer(/HH/g, pad(hours, 2));
    replacer(/H/g, hours);
    replacer(/hh/g, pad(hours % 12, 2));
    replacer(/h/g, hours % 12);
    replacer(/mm/g, pad(minutes, 2));
    replacer(/m/g, minutes);
    replacer(/ss/g, pad(seconds, 2));
    replacer(/s/g, seconds);

    return pattern;
};

/**
 * 将目标字符串转换成日期对象
 * @name Genv.date.parse
 * @function
 * @grammar Genv.date.parse(source)
 * @param {string} source 目标字符串
 * @remark
 * 
对于目标字符串，下面这些规则决定了 parse 方法能够成功地解析： <br>
<ol>
<li>短日期可以使用“/”或“-”作为日期分隔符，但是必须用月/日/年的格式来表示，例如"7/20/96"。</li>
<li>以 "July 10 1995" 形式表示的长日期中的年、月、日可以按任何顺序排列，年份值可以用 2 位数字表示也可以用 4 位数字表示。如果使用 2 位数字来表示年份，那么该年份必须大于或等于 70。 </li>
<li>括号中的任何文本都被视为注释。这些括号可以嵌套使用。 </li>
<li>逗号和空格被视为分隔符。允许使用多个分隔符。 </li>
<li>月和日的名称必须具有两个或两个以上的字符。如果两个字符所组成的名称不是独一无二的，那么该名称就被解析成最后一个符合条件的月或日。例如，"Ju" 被解释为七月而不是六月。 </li>
<li>在所提供的日期中，如果所指定的星期几的值与按照该日期中剩余部分所确定的星期几的值不符合，那么该指定值就会被忽略。例如，尽管 1996 年 11 月 9 日实际上是星期五，"Tuesday November 9 1996" 也还是可以被接受并进行解析的。但是结果 date 对象中包含的是 "Friday November 9 1996"。 </li>
<li>JScript 处理所有的标准时区，以及全球标准时间 (UTC) 和格林威治标准时间 (GMT)。</li> 
<li>小时、分钟、和秒钟之间用冒号分隔，尽管不是这三项都需要指明。"10:"、"10:11"、和 "10:11:12" 都是有效的。 </li>
<li>如果使用 24 小时计时的时钟，那么为中午 12 点之后的时间指定 "PM" 是错误的。例如 "23:15 PM" 就是错误的。</li> 
<li>包含无效日期的字符串是错误的。例如，一个包含有两个年份或两个月份的字符串就是错误的。</li>
</ol>
		
 *             
 * @returns {Date} 转换后的日期对象
 */

Genv.date.parse = function (source) {
    var reg = new RegExp("^\\d+(\\-|\\/)\\d+(\\-|\\/)\\d+\x24");
    if ('string' == typeof source) {
        if (reg.test(source) || isNaN(Date.parse(source))) {
            var d = source.split(/ |T/),
                d1 = d.length > 1 
                        ? d[1].split(/[^\d]/) 
                        : [0, 0, 0],
                d0 = d[0].split(/[^\d]/);
            return new Date(d0[0] - 0, 
                            d0[1] - 1, 
                            d0[2] - 0, 
                            d1[0] - 0, 
                            d1[1] - 0, 
                            d1[2] - 0);
        } else {
            return new Date(source);
        }
    }
    
    return new Date();
};

/**
 * @namespace Genv.number 操作number的方法。
 */
Genv.number = Genv.number || {};

/**
 * 对目标数字进行0补齐处理
 * @name Genv.number.pad
 * @function
 * @grammar Genv.number.pad(source, length)
 * @param {number} source 需要处理的数字
 * @param {number} length 需要输出的长度
 *             
 * @returns {string} 对目标数字进行0补齐处理后的结果
 */
Genv.number.pad = function (source, length) {
    var pre = "",
        negative = (source < 0),
        string = String(Math.abs(source));

    if (string.length < length) {
        pre = (new Array(length - string.length + 1)).join('0');
    }

    return (negative ?  "-" : "") + pre + string;
};
/*
 * Tangram
 * Copyright 2009 Genv Inc. All rights reserved.
 * 
 * path: Genv/number/comma.js
 * author: dron, erik, berg
 * version: 1.2.0
 * date: 2010/09/07 
 */



/**
 * 为目标数字添加逗号分隔
 * @name Genv.number.comma
 * @function
 * @grammar Genv.number.comma(source[, length])
 * @param {number} source 需要处理的数字
 * @param {number} [length] 两次逗号之间的数字位数，默认为3位
 *             
 * @returns {string} 添加逗号分隔后的字符串
 */
Genv.number.comma = function (source, length) {
    if (!length || length < 1) {
        length = 3;
    }

    source = String(source).split(".");
    source[0] = source[0].replace(new RegExp('(\\d)(?=(\\d{'+length+'})+$)','ig'),"$1,");
    return source.join(".");
};

var Class =this.Class= function(proto){
	var klass = function(){		
		if (!Class.$prototyping && G.isFunction(this.initialize)){
		 
			this.__class__ = arguments.callee.prototype;
			Genv.extend(this, Class.Methods);
			return this.initialize.apply(this, arguments);		
		}  
	};	
	klass.prototype = proto || {};      
	for( var m in klass.prototype) {
		if(!G.isEmpty(klass.prototype[m]))
			klass.prototype[m].name = m; 
	}
	klass.extend = this.extend;
	
	 
	klass.implement = this.implement;
    klass.include=this.include;
	return klass;
};
Genv.extend(Class,{
	empty : function(){},

	create : function(p){
		return new Class(p);
	},
	set:function(){
		var parent = Class.create(), scp = Genv.makeArray(arguments);
		if (!Genv.isString(scp[0])){
			return false;
		}
		className=scp.shift();		 
		var parts = className.split('.');
		var klass = window;
        if (Genv.isFunction(scp[0])) {
            parent = scp[0];
            obj = scp[1];
		}else {
            obj = scp[0];            
        }     
		var t =new this();	 
		for (var n in obj) {
			var i = obj[n];			 
			t[n]= i;
		}
		for (var i = 0; i < parts.length; i++) {
			if (! klass[parts[i]]) {				
				klass[parts[i]] =parent.extend(obj); 				 
			}
			klass = klass[parts[i]];
		}	
		return klass;
	},
	get:function(klass){
		Class.$prototyping = true;
		var proto = new klass;
		delete Class.$prototyping;
		return proto;
	},
	get_method: function (klass, args){
		var c = args.callee.caller;
		for (var method in klass)
			if (klass[method] == c)
				return method;
		return null;
	},	
	call_super: function (superclass, self, method, args){       
		if (superclass && superclass[method]){           
			var __class__  = self.__class__;
			self.__class__ = superclass;
			self.__super__ = superclass.__super__;

			try{
				superclass[method].apply(self, args);
			}
			finally{              
				self.__class__ = __class__;
				self.__super__ = superclass;
			}
		}
	},
	kind_of: function (object, klass){
		return eval('klass.prototype.isPrototypeOf(object)');
	}
}) 
 
Class.implement( {
	extend: function(subobj){
		//alert(subobj)
		Class.$prototyping = true;
		var subproto =Class.get(this);	
		Class.$prototyping = false;

		Genv.extend(subproto, subobj);

        subproto.__super__ = this.prototype;
        return Class.create(subproto);        
	},

	implement: function(properties){
		for (var property in properties) this.prototype[property] = properties[property];
	},
	include:function(){	
		var self=this;		 
		G.each(arguments,function(n){		  
	       self.implement( Class.get(n) )
	   })
	}
});
 
Class.Methods={
    extend: function (){	
        var i = arguments.length;
        while (--i >= 0)
            Genv.extend(this, arguments[i]);
        return this;
    },  	 
    kind_of: function (klass){	 
        return Class.kind_of(this, klass);
    },
    Parent: function (){
        var method = Class.get_method(this.__class__, arguments);
        Class.call_super(this.__super__, this, method, arguments);
    },
	Super:function(){
	   args=G.makeArray(arguments);
	   method=args.shift();
	   Class.call_super(this.__super__, this, method, args);
	}
};
//Hash 模拟;
 Class.set("Hash",{
	initialize:function(b){
	  var self=this;
	  G.each(b||{},function(n,v){	  
	    self.set(n,v)
	  })
 
	},
	get: function(key){
		return (this.hasOwnProperty(key)) ? this[key] : null;
	},

	set: function(key, value){
	 
		if (!this[key] || this.hasOwnProperty(key)) this[key] = value;
		return this;
	},
	has: Object.prototype.hasOwnProperty,
	each: function(fn, bind){
		Genv.each(this, fn, bind);
	},

	clear: function(){
		Hash.each(this, function(value, key){
			delete this[key];
		}, this);
		return this;
	},
	keys: function(object){
		var keys = [];
		for (var key in object){
			if (object.hasOwnProperty(key)) keys.push(key);
		}
		return keys;
	},
	
	values: function(object){
		var values = [];
		object=object||this;
		for (var key in object){
			if (object.hasOwnProperty(key)) values.push(object[key]);
		}
		return values;
	},
	extend: function(properties){
        var self=this;
		Genv.extend(this,properties)		
		return this;
	}
});
Genv.sio = Genv.sio || {};
/*
 * Tangram
 * Copyright 2009 Genv Inc. All rights reserved.
 * 
 * path: Genv/sio/_removeScriptTag.js
 * author: berg
 * thanks: kexin, xuejian
 * version: 1.0.0
 * date: 20100527
 */



/**
 * 删除script的属性，再删除script标签，以解决修复内存泄漏的问题
 *
 * 
 * @param {object}          scr               script节点
 */
Genv.sio._removeScriptTag = function(scr){
    if (scr.clearAttributes) {
        scr.clearAttributes();
    } else {
        for (var attr in scr) {
            if (scr.hasOwnProperty(attr)) {
                delete scr[attr];
            }
        }
    }
    if(scr && scr.parentNode){
        scr.parentNode.removeChild(scr);
    }
    scr = null;
};

/**
 * 通过script标签加载数据，加载完成由浏览器端触发回调
 * @name Genv.sio.callByBrowser
 * @function
 * @grammar Genv.sio.callByBrowser(url[, callback, options])
 * @param {string} url 加载数据的url
 * @param {Function} [callback] 数据加载结束时调用的函数
 * @param {Object} [options] 其他可选项
 * @config {String} [charset] script的字符集
 * @remark
 * 1、与callByServer不同，callback参数只支持Function类型，不支持string。
 * 2、如果请求了一个不存在的页面，onsuccess函数也可能被调用（在IE/opera下），因此使用者需要在onsuccess函数中判断数据是否正确加载。
 * @see Genv.sio.callByServer
 */
Genv.sio.callByBrowser = function (url, callback, options) {
    options = options || {};
    var scr = document.createElement("SCRIPT"),
        scriptLoaded = 0,
        attr,
        charset = options['charset'];
    
    // IE和opera支持onreadystatechange
    // safari、chrome、opera支持onload
    scr.onload = scr.onreadystatechange = function () {
        // 避免opera下的多次调用
        if (scriptLoaded) {
            return;
        }
        
        var readyState = scr.readyState;
        if ('undefined' == typeof readyState
            || readyState == "loaded"
            || readyState == "complete") {
            scriptLoaded = 1;
            try {
                ('function' == typeof callback) && callback();
            } finally {
                Genv.sio._removeScriptTag(scr);
            }
        }
    };
    
    scr.setAttribute('type', 'text/javascript');
    charset && scr.setAttribute('charset', charset);
    scr.setAttribute('src', url);
    document.getElementsByTagName("head")[0].appendChild(scr);
};
/*
 * Tangram
 * Copyright 2009 Genv Inc. All rights reserved.
 *
 * path: Genv/sio/callByServer.js
 * author: erik
 * version: 1.1.0
 * date: 2009/12/16
 */




/**
 * 通过script标签加载数据，加载完成由服务器端触发回调
 * @name Genv.sio.callByServer
 * @function
 * @grammar Genv.sio.callByServer(url[, callback, options])
 * @param {string} url 加载数据的url
 * @param {Function|string} [callback] 服务器端调用的函数或函数名
 * @param {Object} [options] 加载数据时的选项
				
 * @config {string} [charset] script的字符集
 * @config {string} [queryField] 服务器端callback请求字段名，默认为callback
 * @remark
 * 如果url中已经包含key为“callback”的query项，将会被替换成callback中参数传递或自动生成的函数名。
 * @meta standard
 * @see Genv.sio.callByBrowser
 */
Genv.sio.callByServer = function(url, callback, options) {
    options = options || {};
    var scr = document.createElement('SCRIPT'),
        prefix = 'bd__cbs__',
        callbackType = typeof callback,
        callbackName,
        attr,
        charset = options['charset'],
        queryField = options['queryField'] || 'callback';

    if ('string' == callbackType) {
        callbackName = callback;
    } else if ('function' == callbackType) {
        while (1) {
            callbackName = prefix
                + Math.floor(Math.random() * 2147483648).toString(36);
            if (!window[callbackName]) {
                window[callbackName] = function() {
                    try {
                        callback.apply(window, arguments);
                    } finally {
                        Genv.sio._removeScriptTag(scr);
                        window[callbackName] = null;
                    }
                };
                break;
            }
        }
    }

    if ('string' == typeof callbackName) {
        url = url.replace((new RegExp('(\\?|&)callback=[^&]*')), '\x241' + queryField + '=' + callbackName);
        if (url.search(new RegExp('(\\?|&)' + queryField + '=/')) < 0) {
            url += (url.indexOf('?') < 0 ? '?' : '&') + queryField + '=' + callbackName;
        }
    }

    scr.setAttribute('type', 'text/javascript');
    charset && scr.setAttribute('charset', charset);
    scr.setAttribute('src', url);
    document.getElementsByTagName('head')[0].appendChild(scr);
};


})(window,document,function(){});



(function(win,doc) {
var rootGenv
Genv.Element = Class.create({
    initialize: function(a) {
        this._els = a;		 
        Array.prototype.push.apply(this, this._els);
        this._events = {}
    }
});
Genv.Element.implement( {
    length: 0,
    get: function(a) {
        return this._els[a]
    },
    getAll: function() {
        return this._els
    },
    each: function(fn,args) {		
		return Genv.each(this._els,fn,args);
    }, 
	find: function( selector ) {		 
		return Genv.$(selector,this._els);
	},
    attr: function(a, b) {
		 
        if (typeof b!='undefined') {
			 
            Genv.each(this._els,
			  function(c, d) {
				 
                switch (a) {
                case "class":
                    c.className = b;
                    break;
                case "style":
                    c.style.cssText = b;
                    break;
                default:
                    c.setAttribute(a, b)
                }
            });
            return this
        } else {
            switch (a) {
            case "class":
                return this._els[0].className;
            case "style":
                return this._els[0].style.cssText;
            default:
                return this._els[0].getAttribute(a)
            }
        }
    },
    removeAttr: function(a) {
        Genv.each(this._els,
        function(b, c) {
            b.removeAttribute(a)
        });
        return this
    }, 
			
	
  
	hasClass: function(b) {
        var a = this._els[0].className.replace(/^\s+|\s+$/g, "").replace(/\s+/g, " ").split(" ");		 
        return a.include(b)
    },
 	addClass: function(a) {
        Genv.each(this._els,
        function(b) {
            var c = b.className.replace(/^\s+|\s+$/g, "").replace(/\s+/g, " ").split(" "),
            d = c.indexOf(a);
            if (d == -1) {
                c.push(a);
                b.className = c.join(" ")
            }
        });
        return this
    },
    removeClass: function(a) {
        Genv.each(this._els,
        function(b) {
            var c = b.className.replace(/^\s+|\s+$/g, "").replace(/\s+/g, " ").split(" "),
            d = c.indexOf(a);
            if (d > -1) {
                c.splice(d, 1);
                b.className = c.join(" ")
            }
        }.bind(this));
        return this
    },
    addClass1: function(name){
		Genv.each(this._els,
    	function(it){
			Genv.style.addCss(it,name);
    	});
    	return this;
    },
    removeClass1: function(name){		
    	Genv.each(this._els,
			function(it){
				Genv.style.removeCss(it,name);
    		
    	});
    	return this;
    },
    pos: function(a) {
        if (a) {
            if (a.left) {
                this._els[0].style.left = a.left + "px"
            }
            if (a.top) {
                this._els[0].style.top = a.top + "px"
            }
        } else {
            return Genv.box.getPosition(this._els[0])
        }
    },    
    box: function() {
        var a = this._els[0],
        b = this.pos();
        b.width = $(a).css('width');//a.offsetWidth;
        b.height = $(a).css('height');//a.offsetHeight;
        b.bottom = b.top + b.height;
        b.right = b.left + b.width;
        return b
    },
	 
	html: function(value) {
		var name="innerHTML"
		if (typeof(value) == 'undefined') {
			return this._els[0].innerHTML;
		} else {
			Genv.each(this._els,
				function(el) {
				 
				el.innerHTML = value;
			});
			return this;
		}      
    },
    val: function(d) {
        var c = this._els[0],
        f = null;
        if (d != undefined) {
            c.value = d;
            return this
        } else {
            switch (c.tagName.toLowerCase()) {
            case "input":
            case "textarea":
                f = c.value;
                return f;
                break;
            case "select":
                var b = c.selectedIndex,
                a = c.options.length;
                if (a > 0) {
                    f = c.options[b > -1 ? b: 0].value
                }
                break;
			case 'checkbox':
               return c.checked ? true : false;
               break;
            case 'radio':
               return c.checked ? true : false;
               break;
            default:
                var g = c.getAttribute("data-value");
                if (g) {
                    f = g
                }
                break
            }
            return f
        }
    },
	css: function( name,value ) {
		var elem=this._els[0];
		if ( typeof(value) == 'undefined'&&(name === "width" || name === "height") ) {
			var val, props = { position: "absolute", visibility: "hidden", display:"block" };

			function getWH() {
				val = name === "width" ? elem.offsetWidth : elem.offsetHeight;				 
			}

			if ( elem.offsetWidth !== 0 ) {
				getWH();
			} else {
				this.swap( props, getWH );
			}

			return Math.max(0, Math.round(val));
		}

		return this.curCss(name,value);
	},	
	swap: function( options, callback ) {
		var old = {};
		elem=this._els[0];	
		for ( var name in options ) {
			old[ name ] = elem.style[ name ];
			elem.style[ name ] = options[ name ];
		}
		callback.call( elem );	
		for ( var name in options ) {
			 elem.style[ name ] = old[ name ];
		}
	},
	curCss: function (name, value) {
		var px = /left|top|right|bottom|width|height/,self=this,			
		transVal = function(k, v) {
				if (px.test(k) && typeof v == 'number') {
					
					v = v + 'px';
				}
				return v;
		};		
		if(G.isObject(name)){		 
		   G.each(name,function(v,n){
		       self.css( v.camelCase(),n)
		   })
		   return this;
		
		}
        if (typeof(value) == 'undefined') {
            var el = this._els[0];
			
            if (name == 'opacity') {
                if (G.Browser.ie) {
					 
                    return el.filter && el.filter.indexOf("opacity=") >= 0 ? parseFloat(el.filter.match(/opacity=([^)]*)/)[1]) / 100 : 1;
                } else {
                    return el.style.opacity ? parseFloat(el.style.opacity) : 1;
                }
            } else {
				function hyphenate(name) {
					return name.replace(/[A-Z]/g,function(match) {
						return '-' + match.toLowerCase();
					});
				}
				if (window.getComputedStyle) {				
					var b= window.getComputedStyle(el, null).getPropertyValue(hyphenate(name))
					return px.test(name)?parseInt(b):b;
				}
				if (document.defaultView && document.defaultView.getComputedStyle) {					
					var computedStyle = document.defaultView.getComputedStyle(el, null);
					if (computedStyle) return computedStyle.getPropertyValue(hyphenate(name));
					if (name == "display") return "none";
				}
				if (el.currentStyle) {
					name=name.camelCase();
					return px.test(name)?parseInt(el.currentStyle[name]):el.currentStyle[name];
				}
				 
				return px.test(name)?parseInt(el.style[name]):el.style[name];
            }
        } else {
			
            Genv.each(this._els,function(el){
                if(name == 'opacity'){
                    if(G.Browser.ie){						 
                        el.style.filter = 'Alpha(Opacity=' + value * 100 + ');';
                    } else {
                        el.style.opacity = (value == 1? '': '' + value);
                    }
                } else {	
					 // alert([name,value])
					 try{
                    el.style[name] =transVal(name, value );
					}catch(e){
					  alert(el.innerHTML)
					  alert([name,value])
					 }
                }
            });
            return this;
        }
	},
    
    
    elements: function() {
        return this._els
    },
    show: function() {
        Genv.each(this._els,
        function(a) {
            a.style.display = "block"
        });
        return this
    },
    hide: function() {
        Genv.each(this._els,
        function(a) {
            a.style.display = "none"
        });
        return this
    },
	toggle: function(){      
		Genv.each(this._els,
        function(a) {
			Genv.$(a)[a.style.display == 'none' ? 'show' : 'hide']()
            //a.style.display = a.style.display == 'none'? 'block' : 'none'
        });
        return this;
    },
    focus: function(a) {
        if (typeof a == "function") {
            Genv.Event.on(this._els[0], "focus", a)
        } else {
            this._els[0].focus()
        }
        return this
    },
    blur: function(a) {
        if (typeof a == "function") {
            Genv.Event.on(this._els[0], "blur", a)
        } else {
            this._els[0].blur()
        }
        return this
    },
    down: function(a) {
        return Genv.$(a, this._els[0])
    },
    on: function(a, b) {
        Genv.each(this._els,
        function(c) {
            if (!c._events) {
                c._events = {}
            }
            if (!c._events[a]) {
                c._events[a] = []
            }
            b.guid = c._events[a].length;
            var d = b._scopeApplied ? b: b.bindEvent(c);
            c._events[a].push(d);
            Genv.Event.on(c, a, d)
        });
        return this
    },
    un: function(a, b) {
        Genv.each(this._els,
        function(c) {
            var f = b.guid;
            if (typeof f == "undefined" || !c._events || !c._events[a] || f < 0 || f >= c._events[a].length) {
                return this
            }
            var d = c._events[a];
            Genv.Event.un(c, a, d[f]);
            delete b.guid;
            d.splice(f, 1)
        });
        return this
    },
	invoke: function(name){
		 Genv.each(this._els,
			function(el){			 
			Genv.Event.invoke(el, name);
		});
		return this;
	},
   
    out: function(name, fun, one){
       Genv.each(this._els,
		   function(el){
            Genv.Event.out(el, name, fun, one);
        });
        return this;
    },
    unout: function(name, fun){
        Genv.each(this._els,
			function(el){
            Genv.Event.unout(el, name, fun);
        });
        return this;
    },

	append: function () {
        var args = arguments;
         Genv.each(this._els,
		   function(it){
            for (var i=0, il=args.length; i<il; i++) {
                G.insert(it, args[i], 3);
            }
        });
        return this;
	},
    append1: function(a) {
		 
        this._els[0].appendChild(a);
        return this
    },
	appendTo: function(els) {
        //var el =this._els[0];		 
		Genv.each(this._els, 
		 function(it) {
            els._events?els.get(0).appendChild(it):els.appendChild(it);
        });
		return this;
    },
	prepend: function () {
        var args = arguments;
         Genv.each(this._els,
			function(it){
            for (var i = args.length-1; i>=0; i--) {
                G.insert(it, args[i], 2);
            }
        });
        return this;
	},
	before: function () {
        var args = arguments;
         Genv.each(this._els,
			function(it){
            for (var i=0, il=args.length; i<il; i++) {
                G.insert(it, args[i], 1);
            }
        });
        return this;
	},
	after: function () {
        var args = arguments;
         Genv.each(this._els,
			function(it){
            for (var i = args.length-1; i>=0; i--) {
                G.insert(it, args[i], 4);
            }
        });
        return this;
	},		
    parent: function() {
        var a = this._els[0];
        if (!a) {
            return
        }
        return Genv.$(a.parentNode)
    },
    dispose: function() {
        this._els.each(function(a) {
            if (a.parentNode) {
                a.parentNode.removeChild(a)
            }
        })

    },	
	empty: function(){
		 Genv.each(this._els,
		  function(a) {	
			 try{
				// Genv.$(a.childNodes)!=null?Genv.$(a.childNodes).dispose():'';
			 }catch(e){}
        });		
		return this;
	} 
});
this.CE=Genv.Element.create = function(html) {
	 var el = document.createElement('div');	
		 el.innerHTML = html.trim();
		//	return el.firstChild;
 
    var c = new Genv.Element([el.firstChild]);
    return c
};

Genv.$ = function( selector, context ) {


	    var els;		
		this.els = [];

		if ( !selector ) {
			return this;
		}
		
		if ( selector.nodeType ) {
			this.els[0] = selector;	
			return new Genv.Element(this.els)			
		}
		
		if ( selector === "body" && !context ) {		
			this.els[0] = document.body;
			return new Genv.Element(this.els)	
			
		}
		if(!context){
		    context=document
		}else{
			
		   context=context._events?context.get(0):context;	
			
	    }


		if(G.isFunction(selector)) {return rootGenv.ready(selector);}

		els = ( typeof selector == 'string' )
			? G.getQeury(selector, context)
			: els = selector;		
	   //if(els==null)  return new Genv.Element(this.els);	
		if ( typeof els.length != 'undefined' ) {			
			for (var i = 0, max = els.length; i < max; i++)
				this.els.push(els[i]);
		} else {			
			this.els.push( els );
		}	
		
		if ( this.els.length > 0) {
		
			 return new Genv.Element(this.els)
		}
		return [];
		 
};

G.each(['width','height','left','top'],function(name){
	Genv.Element.prototype[name]=function(value){
   
        if (typeof(value) == 'undefined') {		
			 
			return  Genv.$(this._els[0]).box()[name];
		} else {
			this._els.each(function(a) {

				Genv.$(a).css(name,value);
			});	
		}	
		return this;	 
  };
})

rootGenv = Genv.$(document);
rootGenv.ready=G.ready;

/*支持的一些属性检测*/
Genv.support={
	boxModel: null
}; 
	
Genv.$(function(){
	var div = document.createElement("div");
	div.style.width = div.style.paddingLeft = "1px";
	document.body.appendChild( div );	
	Genv.boxModel =Genv.support.boxModel=div.offsetWidth === 2;
 
	document.body.removeChild( div ).style.display = 'none';
	div = null;
	 

});

//css 样式选择器引擎;
 
G.extend(G,{
   parseSelectors: function (selectorText){
      var c              = selectorText.trim().split('');
      var sI             = -1;
      var bracketContent = '';
      var elements   = [],    attrs      = [],    separators = [];
      var inSelector = false, inBrackets = false, inQuotes   = false;

      for (var i = 0, len = c.length; i < len; i++)
         if (inSelector)
            {
               if (inBrackets)
                  switch (c[i])
                     {
                        case '"'  : inQuotes = !inQuotes; break;
                        case ']'  : if (!inQuotes) { attrs[sI].push(bracketContent); inBrackets = false; bracketContent = ''; } break;
                        case '\\' : bracketContent += c[++i]; break;
                        default   : bracketContent += c[i];
                     }
               else
                  switch (c[i])
                     {
                        case '['  : inBrackets = true; break;
                        case ' '  :
                        case '>'  :
                        case ','  : inSelector = false; separators[sI] = c[i]; break;
                        case '\\' : elements[sI] += c[++i]; break;
                        default   : elements[sI] += c[i];
                     }
            }
         else
            switch (c[i])
               {
                  case ' ' :
                  case '>' :
                  case ',' : separators[sI] += c[i]; break;
                  default  : inSelector = true; elements[++sI] = c[i]; attrs[sI] = [];
               }

      return { elements: elements, attrs: attrs, separators: separators };
   },
   processAttrs: function (match, a, exprs){
      for (var i = 0, numA = a.length, attr; i < numA; i++)
         {
            attr = (a[i] == 'class') ?
               (match.className ? match.className : null) :
               match.getAttribute(a[i]);

            switch (typeof exprs[i])
               {
                  case 'undefined' : if (attr == null)         return false; break;
                  case 'string'    : if (attr == exprs[i])     return false; break;
                  default          : if (!exprs[i].test(attr)) return false;
               }
         }
      return true;
   },
   processPseudo: function (match, pSelector, pA, pB){
      var pCache, nodeKey, parentChildren = [], pI = 0;

      if (!(nodeKey = match.parentNode.getAttribute('$Mnodekey')))
         match.parentNode.setAttribute('$Mnodekey', nodeKey = Math.random().toString().substr(2));

      if (pCache = this.pCache[nodeKey])
         parentChildren = pCache['parentChildren'], pI = pCache['pI'];
      else
         {
            var c = match.parentNode.firstChild;
            while (c)
               {
                  if (c.nodeType == 1) parentChildren.push(c);
                  c = c.nextSibling;
               }
            this.pCache[nodeKey] = { parentChildren: parentChildren, pI: 0 };
         }
      var parentNumChildren = parentChildren.length;

      switch (pSelector)
         {
            case 'first-child' : if (match == parentChildren[0]) return true; break;
            case 'last-child'  : if (match == parentChildren[parentNumChildren - 1]) return true; break;
            case 'only-child'  : if (parentNumChildren == 1) return true; break;
         }

      if (pSelector == 'nth-child')
         {
            var v    = pA * pI + pB;
            var oldV = -50;
            while (v > -50 && v <= parentNumChildren)
               {
                  if (v >= 0 && parentChildren[v - 1] == match)
                     {
                        this.pCache[nodeKey]['pI'] = (pA >= 0) ? pI + 1 : 0;
                        return true;
                     }
                  pI++, v += pA;
                  if (v == oldV) break;
                  oldV = v;
               }
         }
   },
   getMatches: function (target, s, a, oneLevelOnly){
      this.pCache = {};
      var matches = [], exprs = [];
      var chunks, objs, numObjs, pseudo, pSelector, pOption, pA, pB;


      this.postProcess = function (me){
         if (!numA && !pseudo)
            {
               matches.push(me);
               return;
            }
         var match = true;
         if (numA   && !this.processAttrs(me, a, exprs))           match = false;
         if (pseudo && !this.processPseudo(me, pSelector, pA, pB)) match = false;
         if (match) matches.push(me);
      }
      for (var i = 0, numA = a.length; i < numA; i++){
            chunks = a[i].match(/([a-z0-9_-]+)\s*([=^$*|~!]{0,2})\s*"?([^"]*)"?$/i);
            a[i]   = chunks[1];
            switch (chunks[2])
               {
                  case  '=' : exprs[i] = new RegExp('^' + chunks[3] + '$', 'i'); break;
                  case '^=' : exprs[i] = new RegExp('^' + chunks[3], 'i');       break;
                  case '$=' : exprs[i] = new RegExp(chunks[3] + '$', 'i');       break;
                  case '*=' : exprs[i] = new RegExp(chunks[3], 'i');             break;
                  case '~=' : exprs[i] = new RegExp('^' + chunks[3] + '$|^' + chunks[3] + '\\s|\\s' + chunks[3] + '\\s|\\s' + chunks[3] + '$', 'i'); break;
                  case '!=' : exprs[i] = chunks[3];
               }
     }
     if (s.indexOf(':') != -1){
            chunks    = s.split(':');
            s         = chunks[0];
            pseudo    = chunks[1].match(/([a-z-]+)\(?([a-z0-9+-]*)\)?/i);
            pSelector = pseudo[1].toLowerCase();

            switch (pOption = pseudo[2].toLowerCase())
               {
                  case 'odd'  : pOption = '2n+1'; break;
                  case 'even' : pOption = '2n';
               }

            chunks = pOption.match(/([0-9+-]*)(n?)([0-9+-]*)/i);
            pA     = parseInt(chunks[2] ? (chunks[1] ? ((chunks[1] == '-') ? -1 : chunks[1]) : 1) : 0);
            pB     = parseInt(chunks[3] ? chunks[3] : ((chunks[1] && !chunks[2]) ? chunks[1] : 0));
         }

      if (s.indexOf('#') != -1)
         this.postProcess(document.getElementById(s.substr(s.indexOf('#') + 1)));

      else if (s.indexOf('.') != -1)
         {
            chunks         = s.split('.');
            var classMatch = s.substr(chunks[0].length + 1).replace('.', ' ');
            var className  = new RegExp('^' + classMatch + '$|^' + classMatch + '\\s|\\s' + classMatch + '\\s|\\s' + classMatch + '$', 'i');
            objs           = target.getElementsByTagName(chunks[0] ? chunks[0] : '*');
            for (i = 0, numObjs = objs.length; i < numObjs; i++)
               if ((!oneLevelOnly && className.test(objs[i].className)) ||
                   (oneLevelOnly && className.test(objs[i].className) && objs[i].parentNode == target))
                  this.postProcess(objs[i]);
         }

      else if (s == '*' || /^[A-Za-z0-9]+$/.test(s))
         for (i = 0, objs = target.getElementsByTagName(s), numObjs = objs.length; i < numObjs; i++)
            if (!oneLevelOnly || (oneLevelOnly && objs[i].parentNode == target))
               this.postProcess(objs[i]);

      return matches;
   },

   getSelector: function (selectorText, startAt){
      var selectors = this.parseSelectors(selectorText);
	  
      var numS      = selectors['elements'].length;

      if (!startAt)
         startAt = document;

      if (numS == 1)
         {
            var idMatch = selectors['elements'][0].match(/^[a-z0-9*]*#([^,:]+)$/i);
            if (idMatch && selectors['attrs'][0] == '' && selectors['separators'] == '')
               return document.getElementById(idMatch[1]);
         }

      var objs    = this.getMatches(startAt, selectors['elements'][0], selectors['attrs'][0]);
      var allObjs = [], newObjs, separator;
      for (var i = 1; i < numS; i++)
         {
            newObjs   = [];
            separator = selectors['separators'][i - 1].trim();
            if (separator == ',')
               {
                  allObjs = this.concatUnique(allObjs, objs);
                  objs    = this.getMatches(startAt, selectors['elements'][i], selectors['attrs'][i]);
               }
            else
               {
                  var oneLevelOnly = (separator == '>') ? true : false;
                  for (var j = 0, numObjs = objs.length; j < numObjs; j++)
                     if (G.inArray(objs[j], newObjs)==-1)
                        newObjs = this.concatUnique(newObjs, this.getMatches(objs[j], selectors['elements'][i], selectors['attrs'][i]), oneLevelOnly);
                  objs = newObjs;
               }
         }

		
      allObjs       = this.concatUnique(allObjs, objs);   
      return allObjs;

   },
   concatUnique: function (a1, a2){
      var uniqA2 = [];
      for (var i = 0, numA2 = a2.length; i < numA2; i++)
         if (G.inArray(a2[i], a1)==-1)
            uniqA2.push(a2[i]);

      return a1.concat(uniqA2);
   },	
    
	wrapByArray : function(nodes) {
			if (nodes) {
				if (nodes.nodeType !== undefined || nodes.setInterval) {
					return [nodes];
				} else if (nodes.length) {
					return Genv.makeArray(nodes);
				}
			}
			return [];
	},
    eachNode : function(nodes, callback, args) {
			 
			Genv.each(Genv.wrapByArray(nodes), callback, args);
			return nodes;	
	},
	single : function(nodes, n) {
			null == n && ( n = 0 );
			return nodes.nodeType || nodes.setInterval ? (0 == n ? nodes : undefined) : nodes[n];
	}
}); 

 Genv.getQeury=Genv.getSelector;
 

Genv.Event = {
    on: function(b, a, c) {
        if (!b || !a || !c) {
            return this
        }
        if (b.addEventListener) {
            b.addEventListener(a, c, false)
        } else {
            if (b.attachEvent) {
                b.attachEvent("on" + a, c)
            } else {
                Genv.Event.manager.add(a, eventHandle)
            }
        }
        return this
    },
	bind:this.on,
    unbind: function(d, b, f) {
        for (var c = 0,
        a = f.length; c < a; c++) {
            this.un(d, b, f[c])
        }
        d["on" + b] = null;
        return this
    },
    un: function(b, a, c) {
        if (!b || !a) {
            return this
        }
        if (b.removeEventListener) {
            b.removeEventListener(a, c, false)
        } else {
            if (b.detachEvent) {
                b.detachEvent("on" + a, c)
            } else {
                Genv.Event.manager.del(a, c)
            }
        }
    },
		//模拟事件
	invoke: function(el, ename){
		if(!el) return;
		if(Genv.Browser.ie) {
			el[ename]();
		} else if (document.createEvent) {
			var ev = document.createEvent('HTMLEvents');
			ev.initEvent(ename, false, true);
			el.dispatchEvent(ev);
		}
	},
    stop: function(a) {
        Genv.Event.stopPropagation(a);
        Genv.Event.preventDefault(a)
    },
    stopPropagation: function(a) {
        a.cancelBubble = true;
        if (a.stopPropagation) {
            a.stopPropagation()
        }
    },
    preventDefault: function(a) {
        a.returnValue = false;
        if (a.preventDefault) {
            a.preventDefault()
        }
    },
    getEvent: function() {
        if (event) {
            return event
        }
        var b = arguments.callee.caller;
        var a;
        var c = 0;
        while (b != null && c < 40) {
            a = b.arguments[0];
            if (a && (a.constructor == Event || a.constructor == MouseEvent)) {
                return a
            }
            c++;
            b = b.caller
        }
        return a
    },
	//事件的绝对坐标{x, y}
    page: function(e){
		
    	if (e.pageX || e.pageY) {
			return {
				x: e.pageX,
				y: e.pageY
			};
		}
		return {
			x: e.clientX + (document.documentElement.scrollLeft || document.body.scrollLeft),
			y: e.clientY + (document.documentElement.scrollTop || document.body.scrollTop)
		};
    }
};




G.each( ("blur focus focusin focusout load resize scroll unload click dblclick " +
	"mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave " +
	"change select submit keydown keypress keyup error").split(" "), function( name ) {

	 
	Genv.Element.prototype[ name ] = function( fn ) {
		 
		return fn ? this.on( name, fn ) : this.invoke(name);
	};

	 
});

//自定义事件管理/

Genv.Cevent = function(A) {
    var B = {
        addEvent: function(E, D) {			
            if (typeof D != "function") {
                return
            } ! this.__listeners && (this.__listeners = {});
            var C = this.__listeners;
            typeof C[E] != "object" && (C[E] = []);
            C[E].push(D)
        },
        removeEvent: function(F, E) {
            var D = this.__listeners;
            if (!D[F]) {
                return
            }
            if (!E) {
                D[F] = []
            }
            for (var C in D[F]) {
                if (D[F][C] == E) {
                    D[F][C] = null
                }
            }
        },
        fireEvent: function(E) { ! this.__listeners && (this.__listeners = {});
            var D = this.__listeners;
			var scp=G.makeArray(arguments);
				scp.shift();		 
            if (typeof D[E] == "object") {

				G.each(D[E],function(it){
				     
					this && this.apply(this,scp)
				})
				 
            }
			 
        }
    };
    Genv.extend(A, B)
};
 


Genv.box = Genv.box || {
    version: "1.0.0",
    toString: function() {
        return "[Object Genv.box(version " + this.version + ")]"
    }
};
Genv.extend(Genv.box, {
    getDocumentHeight: function(a) {
        a = a || window;
        var d = a.document;
        var c = (d.compatMode != "CSS1Compat") ? d.body.scrollHeight: d.documentElement.scrollHeight;
        var b = Math.max(c, this.getViewportHeight(a));
        return b
    },
    getDocumentWidth: function(c) {
        c = c || window;
        var d = c.document;
        var b = (d.compatMode != "CSS1Compat") ? d.body.scrollWidth: d.documentElement.scrollWidth;
        var a = Math.max(b, this.getViewportWidth(c));
        return a
    },
    getViewportHeight: function(a) {
        a = a || window;
        var b = a.document;
        var c = b.documentElement;
        return a.innerHeight || (c && c.clientHeight + (c.style.borderTopWidth == "" ? 0 : c.style.borderTopWidth) + (c.style.borderBottomWidth == "" ? 0 : c.style.borderBottomWidth)) || b.body.clientHeight
    },
    getViewportWidth: function(a) {
        a = a || window;
        var b = a.document;
        var c = b.documentElement;
        return a.innerWidth || (c && c.clientWidth + (c.style.borderLeftWidth == "" ? 0 : c.style.borderLeftWidth) + (c.style.borderRightWidth == "" ? 0 : c.style.borderRightWidth)) || b.body.clientWidth
    },
    getPageScrollTop: function(a) {
        a = a || window;
        var b = a.document;
        var c = b.documentElement;
        return a.pageYOffset || (c && c.scrollTop) || b.body.scrollTop
    },
    getPageScrollLeft: function(a) {
        a = a || window;
        var b = a.document;
        var c = b.documentElement;
        return a.pageXOffset || (c && c.scrollLeft) || b.body.scrollLeft
    },
    getPosition: function(a) {
        var d = a.getBoundingClientRect(),
        j = a.ownerDocument,
        g = j.body,
        f = j.documentElement,
        c = f.clientTop || g.clientTop || 0,
        h = f.clientLeft || g.clientLeft || 0,
        i = d.top + (self.pageYOffset || f.scrollTop || g.scrollTop) - c,
        b = d.left + (self.pageXOffset || f.scrollLeft || g.scrollLeft) - h;
        return {
            top: i,
            left: b
        }
    }
});
win.G=Genv;
//window.$ = Genv.$;

})(window,document);



/*动画部分*/
(function(win,doc){

var re_Number = /^\d+(?:\.\d+)?[^\s]*$/,
	re_relNumber = /^([+-])=(\d+(?:\.\d+)?)[^\s]*$/,
	defaultStyleValues  = {
		opacity : 1
	};
function parseStyle(val,n) {
     
	try{
	return re_Number.test(val) ? parseFloat(val) || 0 : val.toLowerCase();
	}catch(e){
	
		alert(e)
	}
}
// 获取元素当前样式集合，并转换最终样式值

function getStyles(elems,finalStyles){

var curStyles = [], n, i, len = elems.length;	
	for (n in finalStyles) {
		finalStyles[n] = parseStyle(finalStyles[n],n);
		for (i = 0; i < len; i++) {		      
			!curStyles[i] && ( curStyles[i] = { } );			 
			curStyles[i][n] =Genv.$(elems[i]).css(n)		 

		}
	}

	//dump(curStyles)
	return curStyles;

}


/// 动画特效处理
Genv.fx = {
	animateSpace :  "GenvAnimateId",
	_deletedAnimate : {},
	
	animate : function(elems, finalStyle, duration, mode, callback) {
		var t = Genv.fx;
		if (mode != null) {
			typeof mode !== "function" && ( mode = Genv.Easing[mode] );
		} else {			
			mode =Genv.Easing.easeNone;
		}		
		t.stop(elems);
		// 获取当前样式
		var curStyles = getStyles(elems, finalStyle);      

//dump(curStyles)
//return;
		// 先设为可见，不然看不到动画效果
		"visible" == finalStyle.visibility && Genv.$(elems).css({ visibility : "visible" });
		"block" == finalStyle.display && Genv.$(elems).css( { display : "block" });
 
		var startTime = Date.now(),
			timerId = setInterval(function() {
				var runTime = Date.now() - startTime, progress = runTime / duration,
					cVal, fVal, nVal, s, i, temp, len = curStyles.length;

				for (s in finalStyle) {
					i = -1;

					while (++i < len) {
						cVal = curStyles[i][s];
						fVal = finalStyle[s];

						re_relNumber.test(fVal) && ( fVal = parseFloat(fVal) + cVal );

						if ("number" == typeof cVal && cVal != fVal) {
						
							nVal = cVal + (fVal - cVal) * mode( progress);
			
					//var time = S.now(),
                   // t = time > finish ? 1 : (time - start) / duration,
							

							temp = {};
							temp[s] = (fVal > cVal && nVal > fVal) || (fVal < cVal && nVal < fVal) ? fVal : nVal;
							
							Genv.$(elems[i]).css(temp) 
							//Genv.style.addCss(elems[i], temp);
						}
					}
				}

				if (runTime >= duration) {
					if (!t._deletedAnimate[timerId]) {
						window.clearInterval(timerId);
						//Genv.style.addCss(elems, finalStyle);
						"hidden" == finalStyle.visibility && Genv.$(elems).css({ visibility : "hidden" }); 
						"none" == finalStyle.display && Genv.$(elems).css({ visibility : "none" }); 
						callback && callback.apply(elems);
						t._deletedAnimate[timerId] = true;
					}
				}
			}, 13);

		return Genv.eachNode(elems, function() {
			this[t.animateIdSpace] = new Number(timerId);
		});
	},

	/// 停止动画
	/// @param {HTMLElement,HTMLCollection,Array} 动画主体
	/// @return {HTMLElement,HTMLCollection,Array} 动画主体
	stop : function(elems) {
		var t = Genv.fx;
		Genv.eachNode(elems, function() {
			var id = this[t.animateSpace];
			if (id !== undefined) {
				if (!t._deletedAnimate[id]) {
					window.clearInterval(id);
					t._deletedAnimate[id] = true;
				}
				delete this[animateIdSpace];
			}
		});
	}
};

    var M = Math, PI = M.PI,
        pow = M.pow, sin = M.sin,
        BACK_CONST = 1.70158,

        Easing = {

            /**
             * Uniform speed between points.
             */
            easeNone: function (t) {
                return t;
            },

            /**
             * Begins slowly and accelerates towards end. (quadratic)
             */
            easeIn: function (t) {
                return t * t;
            },

            /**
             * Begins quickly and decelerates towards end.  (quadratic)
             */
            easeOut: function (t) {
                return ( 2 - t) * t;
            },

            /**
             * Begins slowly and decelerates towards end. (quadratic)
             */
            easeBoth: function (t) {
                return (t *= 2) < 1 ?
                    .5 * t * t :
                    .5 * (1 - (--t) * (t - 2));
            },

            /**
             * Begins slowly and accelerates towards end. (quartic)
             */
            easeInStrong: function (t) {
                return t * t * t * t;
            },

            /**
             * Begins quickly and decelerates towards end.  (quartic)
             */
            easeOutStrong: function (t) {
                return 1 - (--t) * t * t * t;
            },

            /**
             * Begins slowly and decelerates towards end. (quartic)
             */
            easeBothStrong: function (t) {
                return (t *= 2) < 1 ?
                    .5 * t * t * t * t :
                    .5 * (2 - (t -= 2) * t * t * t);
            },

            /**
             * Snap in elastic effect.
             */

            elasticIn: function (t) {
                var p = .3, s = p / 4;
                if (t === 0 || t === 1) return t;
                return -(pow(2, 10 * (t -= 1)) * sin((t - s) * (2 * PI) / p));
            },

            /**
             * Snap out elastic effect.
             */
            elasticOut: function (t) {
                var p = .3, s = p / 4;
                if (t === 0 || t === 1) return t;
                return pow(2, -10 * t) * sin((t - s) * (2 * PI) / p) + 1;
            },

            /**
             * Snap both elastic effect.
             */
            elasticBoth: function (t) {
                var p = .45, s = p / 4;
                if (t === 0 || (t *= 2) === 2) return t;

                if (t < 1) {
                    return -.5 * (pow(2, 10 * (t -= 1)) *
                        sin((t - s) * (2 * PI) / p));
                }
                return pow(2, -10 * (t -= 1)) *
                    sin((t - s) * (2 * PI) / p) * .5 + 1;
            },

            /**
             * Backtracks slightly, then reverses direction and moves to end.
             */
            backIn: function (t) {
                if (t === 1) t -= .001;
                return t * t * ((BACK_CONST + 1) * t - BACK_CONST);
            },

            /**
             * Overshoots end, then reverses and comes back to end.
             */
            backOut: function (t) {
                return (t -= 1) * t * ((BACK_CONST + 1) * t + BACK_CONST) + 1;
            },

            /**
             * Backtracks slightly, then reverses direction, overshoots end,
             * then reverses and comes back to end.
             */
            backBoth: function (t) {
                if ((t *= 2 ) < 1) {
                    return .5 * (t * t * (((BACK_CONST *= (1.525)) + 1) * t - BACK_CONST));
                }
                return .5 * ((t -= 2) * t * (((BACK_CONST *= (1.525)) + 1) * t + BACK_CONST) + 2);
            },

            /**
             * Bounce off of start.
             */
            bounceIn: function (t) {
                return 1 - Easing.bounceOut(1 - t);
            },

            /**
             * Bounces off end.
             */
            bounceOut: function (t) {
                var s = 7.5625, r;

                if (t < (1 / 2.75)) {
                    r = s * t * t;
                }
                else if (t < (2 / 2.75)) {
                    r =  s * (t -= (1.5 / 2.75)) * t + .75;
                }
                else if (t < (2.5 / 2.75)) {
                    r =  s * (t -= (2.25 / 2.75)) * t + .9375;
                }
                else {
                    r =  s * (t -= (2.625 / 2.75)) * t + .984375;
                }

                return r;
            },

            /**
             * Bounces off start and end.
             */
            bounceBoth: function (t) {
                if (t < .5) {
                    return Easing.bounceIn(t * 2) * .5;
                }
                return Easing.bounceOut(t * 2 - 1) * .5 + .5;
            }
        };

    Genv.Easing = Easing;
 
/// 创建动画
/// @param {Object} 最终样式
/// @param {Number} 动画时长，单位毫秒
/// @param {String,Function} 动画效果名称或函数
/// @param {Function} 动画完成后的回调函数
/// @return {HTMLElement,Array} 当前元素
/*Genv.Element.prototype.animate = function(finalStyle, duration, mode, callback) {
	return Genv.fx.animate(this, finalStyle, duration, mode, callback);
};*/
Genv.Element.implement({

animate :function(finalStyle, duration, mode, callback) {
	return Genv.fx.animate(this, finalStyle, duration, mode, callback);
},
stop: function() {
	return Genv.fx.stop(this);
}
})

/// 停止动画
/// @return {HTMLElement,Array} 当前元素
})();

  
Genv.Element.implement({

fadeout :function(fn) {
    var h=$(this).css('height'),
	    o=$(this).css('opacity')
    $(this).css({	 
	 height:0
	});//.show();

	fn=fn||Class.empty;
	 
    
	return Genv.fx.animate(this, {height:h },200, 'easeNone', function(){
	    $(this).show();   
		//fn();
	
	});
}, 
fadein :function(fn) {
    fn=fn||Class.empty;
	return Genv.fx.animate(this, {opacity:0,height:0,width:11,'border-left-width':20},1000, 'easeNone', function(){
	    $(this).hide();   
		fn();
	
	});
} 
});;
//扩展window属性质；
(function(a) {
    a.$C = function(b) {
        return document.createElement(b)
    };
    a.$E = function(b) {
        return document.getElementById(b)
    };
    a.getElementByClz = function(d, c, j) {
        d = d || document;
        var f = [];
        j = " " + j + " ";
        var l = d.getElementsByTagName(c),
        h = l.length;
        for (var g = 0; g < h; ++g) {
            var b = l[g];
            if (b.nodeType == 1) {
                var k = " " + b.className + " ";
                if (k.indexOf(j) != -1) {
                    f[f.length] = b
                }
            }
        }
        return f
    };
    a.getElmsByAttr = function(g, c, h) {
        var d = [];
        for (var f = 0,
        b = g.childNodes.length; f < b; f++) {
            if (g.childNodes[f].nodeType == 1) {
                if (g.childNodes[f].getAttribute(c) == h) {
                    d.push(g.childNodes[f])
                }
                if (g.childNodes[f].childNodes.length > 0) {
                    d = d.concat(arguments.callee.call(null, g.childNodes[f], c, h))
                }
            }
        }
        return d
    };
    a.insertAfter = function(d, b) {
        var c = b.parentNode;
        if (c.lastChild == b) {
            c.appendChild(d)
        } else {
            c.insertBefore(d, b.nextSibling)
        }
        return d
    };
    a.$wrap = function(d, b) {
        var c = $C(b);
        insertAfter(c, d);
        c.appendChild(d);
        return c
    };
    a.$setStyle = function(f, d) {
        function g(h, i, j) {
            if (!Genv.IE) {
                if (i == "float") {
                    i = "cssFloat"
                }
                if (!h) {
                    return
                }
                h.style[i] = j
            } else {
                switch (i) {
                case "opacity":
                    h.style.filter = "alpha(opacity=" + (j * 100) + ")";
                    if (!h.currentStyle || !h.currentStyle.hasLayout) {
                        h.style.zoom = 1
                    }
                    break;
                case "float":
                    i = "styleFloat";
                default:
                    h.style[i] = j
                }
            }
        }
        var c = function(k) {
            var h = k.split("-");
            var l = h[0];
            for (var j = 1; j < h.length; j++) {
                l += h[j].charAt(0).toUpperCase() + h[j].substring(1)
            }
            return l
        };
        if (typeof f == "string") {
            f = $E(f)
        }
        for (var b in d) {
            styleAttr = c(b);
            g(f, styleAttr, d[b])
        }
    }
})(window);



/*扩展一些常用功能*/
G.extend(G,{
    getX: function(a) {
        return a.offsetParent ? a.offsetLeft + G.getX(a.offsetParent) : a.offsetLeft
    },
    getY: function(a) {
        return a.offsetParent ? a.offsetTop + G.getY(a.offsetParent) : a.offsetTop
    },
    within: function(a, b) {
        var c = G.getX(b) - G.scrollX(),
        e = G.width(b) + c,
        d = G.getY(b) - G.scrollY();
        b = G.height(b) + d;
        var f = {};
        if (a[0] > c && a[0] < e && a[1] > d && a[1] < b) {
            if (a[0] - c < (e - c) / 2) f.left = true;
            if (a[1] - d < (b - d) / 2) f.top = true;
            return f
        }
    },
    frameX: function(a) {
        return a.frameElement ? G.getX(a.frameElement) + G.frameX(a.parent) : 0
    },
    frameY: function(a) {
        return a.frameElement ? G.getY(a.frameElement) + G.frameY(a.parent) : 0
    }, 
    pageWidth: function() {
        return document.body.scrollWidth || document.documentElement.scrollWidth
    },
    pageHeight: function() {
        return document.body.scrollHeight || document.documentElement.scrollHeight
    },
    windowWidth: function() {
        var a = document.documentElement;
        return self.innerWidth || a && a.clientWidth || document.body.clientWidth
    },
    windowHeight: function() {
        var a = document.documentElement;
        return self.innerHeight || a && a.clientHeight || document.body.clientHeight
    },
    scrollX: function(a) {
        var b = document.documentElement;
        if (a) {
            var c = a.parentNode,
            e = a.scrollLeft || 0;
            if (a == b) e = G.scrollX();
            return c ? e + G.scrollX(c) : e
        }
        return self.pageXOffset || b && b.scrollLeft || document.body.scrollLeft
    },
    scrollY: function(a) {
        var b = document.documentElement;
        if (a) {
            var c = a.parentNode,
            e = a.scrollTop || 0;
            if (a == b) e = G.scrollY();
            return c ? e + G.scrollY(c) : e
        }
        return self.pageYOffset || b && b.scrollTop || document.body.scrollTop
    } 
});


Genv.extend({
	insert: function(elem, content, where){
        var doit = function (el, value){
            switch (where){
                case 1:{
                    el.parentNode.insertBefore(value, el);
                    break;
                }
                case 2:{
                    el.insertBefore(value, el.firstChild);
                    break;
                }
                case 3:{					 
                    if(el.tagName.toLowerCase() == 'table' && value.tagName.toLowerCase() == 'tr'){
                    	if(el.tBodies.length == 0){
                    		el.appendChild(document.createElement('tbody'));
                    	}
                    	el.tBodies[0].appendChild(value);
                    } else {
						 
                    	el.appendChild(value);
                    }
                    break;
                }
                case 4:{
                    el.parentNode.insertBefore(value, el.nextSibling);
                    break;
                }
            }
        };
        where = where || 1;
        if(typeof(content) == 'object'){
            if(content.cache){
                if(where == 2) content = content.reverse();
                G.each(content, function(it){
                   doit(elem, it);
                });
            } else {
                doit(elem, content);
            }
        } else {
            if(typeof(content) == 'string'){
                var div = document.createElement('div');
                div.innerHTML = content;
                var childs = div.childNodes;
				var nodes = [];
				for (var i=childs.length-1; i>=0; i--) {
					nodes.push(div.removeChild(childs[i]));
				}
				nodes = nodes.reverse();
                for (var i = 0, il = nodes.length; i < il; i++){
                    doit(elem, nodes[i]);
                }
            }
        }
        return this;
    },
	formatDate: function(d,B) {
     	 B = B || "yyyy-MM-dd";
		 var C = {
			"M+": d.getMonth() + 1,
			"d+": d.getDate(),
			"h+": d.getHours(),
			"m+": d.getMinutes(),
			"s+": d.getSeconds(),
			"q+": Math.floor((d.getMonth() + 3) / 3),
			"S": d.getMilliseconds()
		};
		if (/(y+)/.test(B)) {
			B = B.replace(RegExp.$1, (d.getFullYear() + "").substr(4 - RegExp.$1.length));
		}
		for (var A in C) {
			if (new RegExp("(" + A + ")").test(B)) {
				B = B.replace(RegExp.$1, RegExp.$1.length == 1 ? C[A] : ("00" + C[A]).substr(("" + C[A]).length));
			}
		}
		return B;
    },
	addZero: function(a, b) {
        b || (b = 2);
        return Array(Math.abs(("" + a).length - (b + 1))).join(0) + a
    },
	parseTpl : function(tpl, values, isCached) {
		if (null == tpl) { return; }
		if (null == values) { return tpl; }
		
		var fn = tplCache[tpl];
		if (!fn) {
			fn = new Function("obj", "var _=[];with(obj){_.push('" +
					tpl.replace(/[\r\t\n]/g, " ")
					.replace(/'(?=[^#]*#>)/g, "\t")
					.split("'").join("\\'")
					.split("\t").join("'")
					.replace(/<#=(.+?)#>/g, "',$1,'")
					.split("<#").join("');")
					.split("#>").join("_.push('")
					+ "');}return _.join('');");
			isCached !== false && (tplCache[tpl] = fn);
		}
		
		return fn(values);
	},	 
	loadCss:function (a){
		if(document.all){
			window.style=a;
			document.createStyleSheet("javascript:style");
		}else{
			var style = document.createElement('style');
			style.type = 'text/css';
			style.innerHTML=a;
			document.getElementsByTagName('HEAD').item(0).appendChild(style);
		}
	}


});


Genv.json = Genv.json || {};
Genv.json.parse = function(A) {
    if (!/^[\],:{}\s]*$/.test(A.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, ""))) {
        return null
    }
    return window.JSON && window.JSON.parse ? window.JSON.parse(A) : (new Function("return " + A))()
};
Genv.json.stringify = (function() {
    var B = {
        "\b": "\\b",
        "\t": "\\t",
        "\n": "\\n",
        "\f": "\\f",
        "\r": "\\r",
        '"': '\\"',
        "\\": "\\\\"
    };
    function A(F) {
        if (/["\\\x00-\x1f]/.test(F)) {
            F = F.replace(/["\\\x00-\x1f]/g,
            function(G) {
                var H = B[G];
                if (H) {
                    return H
                }
                H = G.charCodeAt();
                return "\\u00" + Math.floor(H / 16).toString(16) + (H % 16).toString(16)
            })
        }
        return '"' + F + '"'
    }
    function D(K) {
        var G = ["["],
        H = K.length,
        F,
        I,
        J;
        for (I = 0; I < H; I++) {
            J = K[I];
            switch (typeof J) {
            case "undefined":
            case "function":
            case "unknown":
                break;
            default:
                if (F) {
                    G.push(",")
                }
                G.push(Genv.json.stringify(J));
                F = 1
            }
        }
        G.push("]");
        return G.join("")
    }
    function C(F) {
        return F < 10 ? "0" + F: F
    }
    function E(F) {
        return '"' + F.getFullYear() + "-" + C(F.getMonth() + 1) + "-" + C(F.getDate()) + "T" + C(F.getHours()) + ":" + C(F.getMinutes()) + ":" + C(F.getSeconds()) + '"'
    }
    return function(J) {
        switch (typeof J) {
        case "undefined":
            return "undefined";
        case "number":
            return isFinite(J) ? String(J) : "null";
        case "string":
            return A(J);
        case "boolean":
            return String(J);
        default:
            if (J === null) {
                return "null"
            } else {
                if (J instanceof Array) {
                    return D(J)
                } else {
                    if (J instanceof Date) {
                        return E(J)
                    } else {
                        var G = ["{"],
                        I = Genv.json.stringify,
                        F,
                        H;
                        for (key in J) {
                            if (J.hasOwnProperty(key)) {
                                H = J[key];
                                switch (typeof H) {
                                case "undefined":
                                case "unknown":
                                case "function":
                                    break;
                                default:
                                    if (F) {
                                        G.push(",")
                                    }
                                    F = 1;
                                    G.push(I(key) + ":" + I(H))
                                }
                            }
                        }
                        G.push("}");
                        return G.join("")
                    }
                }
            }
        }
    }
})();
Genv.json.encode = function(A) {
    return Genv.json.stringify(A)
};
Genv.json.decode = function(A) {
    return Genv.json.parse(A)
};
 /*ajax支持*/

Genv.ajax = function() {
	if(this.ajax) return new this.ajax();
	this.xhr = window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
};
Genv.ajax.type = {
	html: 'text/html',
	text: 'text/plain',
	xml: 'application/xml, text/xml',
	json: 'application/json, text/javascript',
	script: 'text/javascript, application/javascript',
	'default': 'application/x-www-form-urlencoded'
};
Genv.ajax.accept = '*\/*';
Genv.ajax.prototype.open = function(options) {
	 
	Genv.extend(this, {
		method: options.method || 'GET',
		url: options.url || location.href,
		async: options.async !== false,
		user: options.user || null,
		password: options.password || null,
		params: options.params || null,
		processData: options.processData === true,
		timeout: options.timeout || 0,
		contentType: Genv.ajax.type[options.contentType] || Genv.ajax.type['default'],
		dataType: Genv.ajax.type[options.dataType] ? Genv.ajax.type[options.dataType] + ', *\/*' : Genv.ajax.accept,
		requestHeaders: options.requestHeaders || null,
		success: options.success,
		error: options.error
	});
	// dump(options,1)
	 if(this.params) {
		var options = [], process = this.process;
       
		Genv.each(this.params, function(key,value) {
			 //alert([key,value])
			options.push([key, '=', process ? encodeURIComponent(value) : value].join(''));
		});
		this.params = options.join('&');
		
	}
	try {
		this.xhr.open(this.method, this.method == 'GET' && this.params ? this.url + '?' + this.params : this.url, this.async, this.user, this.password);
		this.xhr.setRequestHeader('Accept', this.dataType);
		this.xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
		this.xhr.setRequestHeader('Content-Type', this.contentType);
		var ajax = this;
		if(this.requestHeaders) Genv.forEach(this.requestHeaders, function(key, value) {
			ajax.xhr.setRequestHeader(key, value);
		});
		this.xhr.onreadystatechange = function() {
			if(ajax.xhr.readyState == 4) {
				if(ajax.xhr.status == 200 || ajax.xhr.status == 0 && ajax.success) ajax.success(ajax.xhr.responseText);
				else if(ajax.error && !ajax.aborted) ajax.error(ajax.xhr.statusText);
			}
		};
		this.xhr.send(this.params);;
		if(this.async && this.timeout) setTimeout(function() {
			if(ajax.xhr.readyState != 4) {
				ajax.aborted = true;
				ajax.xhr.abort();
				if(ajax.error) ajax.error('Time is out');
			}
		}, this.timeout);
	}
	catch(error) {		 
		if(this.error) this.error(error);
	}
};

Genv.ajax.form = function (form, options) {
    options = options || {};
    var elements    = form.elements,
        len         = elements.length,
        method      = form.getAttribute('method'),
        url         = form.getAttribute('action'),
        replacer    = options.replacer || function (value, name) {
            return value;
        },
        sendOptions = {},
        data = [],
        i, item, itemType, itemName, itemValue, 
        opts, oi, oLen, oItem;
        
    /**
     * 向缓冲区添加参数数据
     * @private
     */
    function addData(name, value) {
        data.push(name + '=' + value);
    }
    
    // 复制发送参数选项对象
    for (i in options) {
        if (options.hasOwnProperty(i)) {
            sendOptions[i] = options[i];
        }
    }
    
    for (i = 0; i < len; i++) {
        item = elements[i];
        itemName = item.name;
        
        // 处理：可用并包含表单name的表单项
        if (!item.disabled && itemName) {
            itemType = item.type;
            itemValue = item.value;
        
            switch (itemType) {
            // radio和checkbox被选中时，拼装queryString数据
            case 'radio':
            case 'checkbox':
                if (!item.checked) {
                    break;
                }
                
            // 默认类型，拼装queryString数据
            case 'textarea':
            case 'text':
            case 'password':
            case 'hidden':
            case 'select-one':
                addData(itemName, replacer(itemValue, itemName));
                break;
                
            // 多行选中select，拼装所有选中的数据
            case 'select-multiple':
                opts = item.options;
                oLen = opts.length;
                for (oi = 0; oi < oLen; oi++) {
                    oItem = opts[oi];
                    if (oItem.selected) {
                        addData(itemName, replacer(oItem.value, itemName));
                    }
                }
                break;
            }
        }
    }
    
    // 完善发送请求的参数选项
    sendOptions.data = data.join('&');
    sendOptions.method = form.getAttribute('method') || 'POST';
    new Genv.ajax().open(Genv.extend(options, {method: 'POST'}));
	return this;
    // 发送请求
    return Genv.ajax.request(url, sendOptions);
};

Genv.get = function(options) { 
	Genv.extend(options, {method: 'GET'})
	 
	new Genv.ajax().open(options);
	return this;
};
Genv.post = function(options) {
	new Genv.ajax().open(Genv.extend(options, {method: 'POST'}));
	return this;
};

Genv.getJSON = function(options, callback, error) {
	new Genv.ajax().open(Genv.extend(options, {dataType: 'json', success: function(response) {
		try {
			callback(eval('(' + response + ')'));
		}
		catch(error) {
			if(this.error) this.error(error);
		}
	}, error: error}));
	return this;
}; 


G.plugin={
 Drag:function(options){    
          this.options = {
                    container:null, /*主容器*/
                    handle:null, /*拖动的把手，如果没有把手，默认container为把手*/
                    border:'1px dotted #00FF00', /*拖动时，整个主容器加上一个虚的外边框，拖动完成后取消此边框*/
                    isLimited:false, /*是否进行范围限定*/
                    maxLeft:0, /*左边界限定*/
                    maxTop:0, /*上边界限定*/
                    maxRight:9999, /*右边界限定*/
                    maxBottom:9999,   /*下边界限定*/           
                    lockX:false, /*锁定左右拖动*/
                    lockY:false, /*锁定上下拖动*/
					onstart:Class.empty,
					onend:Class.empty,
					onmove:Class.empty

          };
          this.x = 0;
          this.y = 0;   
          this.funcMove = Class.empty;
          this.funcStop = Class.empty;   
          this.oldStyle_BorderWidth = null;    
          
          this.init = function(){
                G.extend(this.options, options || {}); 
               
                this.options.container = this.options.container || this._els[0];            

                this.options.container.style.position ='absolute'; 
                this.options.handle = this.options.handle || this.options.container;              

                this.funcMove = this.moveDrag.bindEvent(this);
                this.funcStop = this.stopDrag.bind(this);        

                this.options.handle.style.cursor = 'move'; 

				G.$(this.options.handle).noselect();

                G.Event.on(this.options.handle, 'mousedown', this.startDrag.bindEvent(this)   );
          };
          
          this.startDrag = function(e){
			 

				$(this.options.container).addClass("ui_drag_border");
                
               //如果this.options.container设置了margin就需要用此方法来修正
               var marginLeft = parseInt($(this.options.container).css('margin-left')) || 0; 
               var marginTop = parseInt($(this.options.container).css('margin-top')) || 0;

                this.x = e.clientX - Gui.getPositionLite(this.options.container).x + marginLeft;
                this.y = e.clientY - Gui.getPositionLite(this.options.container).y + marginTop;

                G.Event.on(document, 'mousemove', this.funcMove);
                G.Event.on(document, 'mouseup', this.funcStop);
                this.options.onstart();
                //解决窗口焦点失去后，同时释放拖拽鼠标
                if(G.Browser.ie){
                     G.Event.on(this.options.container, "losecapture", this.funcStop);
                    this.options.container.setCapture();
                }else{
                     G.Event.on(window, "blur", this.funcStop);      
                }
          };      
          
          this.moveDrag = function(e){
                //清空选中的内容，不然鼠标就会显示禁止操作拖动会失败。
                window.getSelection ? window.getSelection().removeAllRanges() : document.selection.empty();
                var iLeft = e.clientX - this.x;
                var iTop = e.clientY - this.y;
                //处理限定范围
                if(this.options.isLimited){                
                    iLeft = Math.max(Math.min(iLeft, this.options.maxRight - Gui.getSizeLite(this.options.container).wb), this.options.maxLeft);
                    iTop = Math.max(Math.min(iTop, this.options.maxBottom - Gui.getSizeLite(this.options.container).hb), this.options.maxTop);
                }
                //处理x或y方向上的锁定
                if(!this.options.lockX)this.options.container.style.left = iLeft  + 'px';;
                if(!this.options.lockY)this.options.container.style.top = iTop  + 'px';;

				this.options.onmove();
          };
          
          this.stopDrag = function(e){
               // this.options.container.style.border = this.oldStyle_Border || "";
                 $(this.options.container).removeClass("ui_drag_border");

                G.Event.un(document, 'mousemove', this.funcMove);
                G.Event.un(document, 'mouseup', this.funcStop);
                
                if(G.Browser.ie){
                    G.Event.un(this.options.container, "losecapture", this.funcStop);
                    this.options.container.releaseCapture();
                }else{
                    G.Event.un(window, "blur", this.funcStop);      
                }
				this.options.onend();
          };
           
          this.init();
          return this;  
      }    
 
};
Genv.Element.implement(G.plugin);
/*禁止选择*/
Genv.Element.implement({
	noselect:function(b){
	   G.each(this._els,
	   function(el){
		 G.$(el).attr('unselectable', 'on').css('MozUserSelect', 'none').on('selectstart', function() { return false; });  
	   })
	}
});
Genv.Cookies = {
		get : function(n){
			var dc = "; "+document.cookie+"; ";
			var coo = dc.indexOf("; "+n+"=");
			if (coo!=-1){
				var s = dc.substring(coo+n.length+3,dc.length);
				return unescape(s.substring(0, s.indexOf("; ")));
			}else{
				return null;
			}
		},
		set : function(name,value,expires,path,domain,secure){
			var expDays = expires*24*60*60*1000;
			var expDate = new Date();
			expDate.setTime(expDate.getTime()+expDays);
			var expString = expires ? "; expires="+expDate.toGMTString() : "";
			var pathString = "; path="+(path||"/");
			var domain = domain ? "; domain="+domain : "";
			document.cookie = name + "=" + escape(value) + expString + domain + pathString + (secure?"; secure":"");
		},

		del : function(n){
			var exp = new Date();
			exp.setTime(exp.getTime() - 1);
			var cval=this.get(n);
			if(cval!=null) document.cookie= n + "="+cval+";expires="+exp.toGMTString();
		}
    };	