<?php

class GlobalAction extends Genv_Base{
     protected $_front;
	 protected $_action = null;  
     protected $_format = null; 
     protected $_action_default = 'index';

     protected $_template_path; 
	 
	 protected $_session;

     protected $_view_object;

	 protected $_config = array();   
	 protected $_controller = null;
	
	 public $_view = null;
	 public $_viewfile = null;
	 public $_layout = null;
     protected $_view_class = 'Genv_View';

	 protected $_format_type = array(
        null        => 'text/html',
        'atom'      => 'application/atom+xml',
        'css'       => 'text/css',
        'htm'       => 'text/html',
        'html'      => 'text/html',
        'js'        => 'text/javascript',
        'json'      => 'application/json',
        'pdf'       => 'application/pdf',
        'ps'        => 'application/postscript',
        'rdf'       => 'application/rdf+xml',
        'rss'       => 'application/rss+xml',
        'rss2'      => 'application/rss+xml',
        'rtf'       => 'application/rtf',
        'text'      => 'text/plain',
        'txt'       => 'text/plain',
        'xhtml'     => 'application/xhtml+xml',
        'xml'       => 'application/xml',
     );
     protected $_charset = 'utf-8';

	 protected $_errors = array();
	 
	 protected $_query = array();

	 protected $_info = array();

	 protected $_request;
	 protected $_rewrite;
	 protected $_response;

     public function setFrontController($front){
        $this->_front = $front;
     } 

	  protected function _postConstruct(){
        parent::_postConstruct();
        
		$this->_view_object = Genv::factory($this->_view_class);

        $class = get_class($this);
 //   dump($this->_config);
        // create the session object for this class
	 
        $this->_session = Genv::factory(
            'Genv_Session',
            array('class' => $class)
        );
 
          
        // get the registered response object
        $this->_response = Genv_Registry::get('response');
   
        // auto-set the name; for example Vendor_App_SomeThing => 'some-thing'
        if (empty($this->_controller)) {

			 
            $pos = strrpos($class, '_');

			 // dump($class);
            $this->_controller =$class;// substr($class, $pos );

			// dump( $this->_controller);
            $this->_controller = preg_replace(
                '/([a-z])([A-Z])/',
                '$1-$2',
                $this->_controller
            );
            $this->_controller = strtolower($this->_controller);
			$this->_controller = str_replace("-action",'',$this->_controller);
        }
 
       G('APP',$this->_controller);
	//   dump(G());
	  
	   define('PHP_SELF', $_SERVER['REQUEST_URI']);
       I('@.Help.myfunc'); //常用函数库
	   I('@.Help.mycache'); //内置函存;
//exit;
	   //dump($this->_controller);

        // get the current request environment
        $this->_request = Genv_Registry::get('request');
        
        // get the registered rewrite object
        $this->_rewrite = Genv_Registry::get('rewrite');
        

		//$this->_view=Genv::factory('Genv_View');
   //$this->_view_object = Genv::factory($this->_view_class);
		//dump($this->_view);
        // extended setup


		//$this->db = Genv_db::get();
        $this->_setup();
		$this->init_visitor();
    }

    //初始化登录者信息 ;
	function init_visitor(){
	

	 
		   if( !Genv_Cookie::get("username")){

			   //dump($_COOKIE);
 			   $this->login();
			   exit;
				//$this->_response->redirect(U("login"));		   
		   }else{
		   
		   
				$username = Genv_Cookie::get("username");
				
				$select=D();
				$select->selectfrom(gettable('sysmember')." AS a " ,'*' );	
				$select->leftJoin(gettable('sysrole').' AS b ','a.role_id=b.id','name as groupname');				
				$select->where("a.username ='".$username."' ");
				$sql=$select->fetch('sql');
				$rs=$select->query($sql);
				$rs=$rs[0];
				if($rs){
					Genv_Cookie::set("uid",$rs['id']);
					Genv_Cookie::set("username",$rs['username']);
					Genv_Cookie::set("role_id",$rs['role_id']);
					Genv_Cookie::set("groupname",$rs['groupname']);
					//$this->_response->setcookie("username",$rs['username']);
					$this->_response->setcookie("role_id",$rs['role_id']);
					 
				 }else{
					$this->error('登录出错');
				 }
		   
		   }
	
	
	
	}
 /**
     * 
     * Try to force users to define what their view variables are.
     * 
     * @param string $key The property name.
     * 
     * @param mixed $val The property value.
     * 
     * @return void
     * 
    
    public function __set($key, $val)
    {
        throw $this->_exception('ERR_NO_SUCH_PROPERTY', array(
            'class' => get_class($this),
            'property' => $key,
        ));
    } */
    
    /**
     * 
     * Try to force users to define what their view variables are.
     * 
     * @param string $key The property name.
     * 
     * @return void
     * 
     
    public function __get($key)
    {
        throw $this->_exception('ERR_NO_SUCH_PROPERTY', array(
            'class' => get_class($this),
            'property' => $key,
        ));
    }
	 */
	 public function fetch($spec = null){		 
        try {          
            $this->_load($spec);
            $this->_preRun();  

			 
            $this->_forward($this->_action, $this->_info);
            
            $this->_postRun();    
			 
            $this->_render();       
         
            return $this->_response;
            
        } catch (Exception $e) {            
          
            return $this->_exceptionDuringFetch($e);
            
        }
    } 

	protected function _exceptionDuringFetch(Exception $e){
        $this->_errors[] = $e;
        $this->_view = 'exception';
        $this->_response->setStatusCode(500);
        
        // render directly; because this came from the fetch process, we
        // can't depend on that process to complete successfully.
        $this->_render();
        return $this->_response;
    } 
	public function display($spec = null){
		 $this->_load('');
		 //dump($spec."__");
        $this->viefile($spec);
        $response = $this->getresponse();
		 
	 
        $response->display();
		 exit;

    }
	public function getresponse($spec=null){
	
	   try {         
		  
            $this->_load($spec);
            $this->_preRun();  

			 $this->_view = $this->_getActionView($this->_action);
            //$this->_forward($this->_action, $this->_info);
            
            $this->_postRun();    
			 
            $this->_render();       
         
            return $this->_response;
            
        } catch (Exception $e) {            
          
            return $this->_exceptionDuringFetch($e);
            
        }
	}
	//获取某一个模板;
	public function getview(){
		 
		G('AUTODISPLAY',false);
		 if($this->_viewfile==null){
			$this->viefile($this->_controller.".".$this->_view);
		
		}
 
		$tpl = $this->_viewfile ;
      
 
        try {
			 $str=$this->_view_object->fetch($tpl);
			 
			 return $str ;
 
        } catch (Genv_View_Exception_TemplateNotFound $e) {
            throw $this->_exception('ERR_VIEW_NOT_FOUND', array(
                'path' => $e->getInfo('path'),
                'name' => $e->getInfo('name'),
            ));
        } 
		 
		 exit;
		   	
	}

	protected function _load($spec){
        $this->_loadInfoQueryFormat($spec);
       
    } 


	protected function _loadInfoQueryFormat($spec){
        // process the action/param.format?query specification
        if (! $spec) {
            
            // no spec, use the current URI
            $uri = Genv::factory('Genv_Uri_Action');
            $this->_info = $uri->path;
            $this->_query = $uri->query;
            $this->_format = $uri->format;
            
        } elseif ($spec instanceof Genv_Uri_Action) {
            
            // pull from a Genv_Uri_Action object
            $this->_info = $spec->path;
            $this->_query = $spec->query;
            $this->_format = $spec->format;
            
        } else {
            
            // a string, assumed to be an action/param.format?query spec.
            $uri = Genv::factory('Genv_Uri_Action');
            $uri->set($spec);
            $this->_info = $uri->path;
            $this->_query = $uri->query;
            $this->_format = $uri->format;
            
        }
       
        // if the first param is the controller name, drop it.
        // needed when no spec is passed and we're using the default URI.
        $shift = ! empty($this->_info[0])
              && $this->_info[0] == $this->_controller;
              
        if ($shift) {
            array_shift($this->_info);
        }
       
        // ignore .php formats
        if (strtolower($this->_format) == 'php') {
			 
            $this->_format = null;
        }
        
        // now find the action from the info.
        // do we have an initial info element as an action method?
        if (empty($this->_info[0])) {
            // use the default action
            $this->_action = $this->_action_default;
        } else {
            // save it and remove from info
            $this->_action = array_shift($this->_info);
        }
	    G('ACT',$this->_action);
 
		//$this->_template_path = Genv::factory('Genv_Path_Stack'); 
 
    }


	 protected function _forward($action, $params = null)    {       
		
        $this->_action = $action;

        settype($params, 'array'); 
        $this->_preAction();       
         $method = $this->_getActionMethod($this->_action);
		

		 
        if (! $method) {            
            $this->_notFound($this->_action, $params);
            
        } else {            
           
            $this->_view = $this->_getActionView($this->_action);
			 
            call_user_func_array(
                array($this, $method),
                $params
            );
        }   
		
		 
        $this->_postAction();  


        $this->_action = $action;
		 
    }
 /*
   获取当前动作
   */
   protected function _getActionMethod($action){
       
        $method = str_replace('-', ' ', $action);
        $method = ucwords(trim($method));
        $method = str_replace(' ', '', $method);             
        if (method_exists($this, $method)) {
            return $method;
        } else {
            return false;
        }
    } 

	protected function _getActionView($action){
        // convert example-name to exampleName
        $view = str_replace('-', ' ', $action);
        $view = ucwords(trim($view));
        $view = str_replace(' ', '', $view);
        $view[0] = strtolower($view[0]);
        return $view;
    }

	

	 protected function _render(){
 
        // if no view and no layout, there's nothing to render
        if (! $this->_view && ! $this->_layout) {
          //  $this->_setContentType();
           // return;
        }

	
        $this->_setContentType(); 
        $this->_setViewObject();
        $this->_preRender();
		
        $this->_view_object->assign($this);
        $this->_preView();//赋值常用的一些变量

		 
	 
        if ($this->_view) {
		 
            $this->_renderView();
        }
		 
        /*
        if ($this->_layout) {
            $this->_setLayoutTemplates();
            $this->_renderLayout();
        }
        */
        $this->_setContentType();
        
        $this->_postRender();
    }	
	public function assign($k,$v=null){
		 $this->_setViewObject();
		 $args = func_get_args();
		 
		 if (is_array($k)){
			$args  = func_get_args();		
			foreach ($args as $arg){
				foreach ($arg as $key => $value){
					$this->_view_object->assign($key, $value);
				}
			}
		}else{

		// dump($k);
			$this->_view_object->assign($k, $v);
		}	 
	
	}
//赋值常用的一些变量
    protected function _preView(){

			$client=Genv_Config::get('Genv', 'appdir');
			define('__URL__',PHP_FILE.'/'.G('APP'));        
			define('__ACTION__',__URL__.'/'.G('ACT'));
			
//"_public"=>"http://www.zheli.com/test/",
			 
			$a=array("_public"=>APPPUBLIC,
				'_url'=>__URL__,
				'_action'=>__ACTION__,
				'_self'=>__SELF__,
				'_app'=>__APP__,
				'random_num'=>rand(),
				'mid'=>getgpc('mid'),
				'WEB_PUBLIC_URL'=>WEB_PUBLIC_URL,
				"pageinfo"=>$this->pageinfo(),
				);
			
			// dump($a);
        	 
				//echo Genv_Config::get('Genv', 'appname');


				//dump($this->_config);
		  $this->assign($a);
    }
	function pageinfo(){
			 
			//define('__URL__',PHP_FILE.'/'.G('APP'));        
			//define('__ACTION__',__URL__.'/'.G('ACT'));	
		 
		    G('APPURL',PHP_FILE);
			//$query=getquery();//查询条件;
			//@extract($arr);
		     
			$query=$_SERVER["QUERY_STRING"];			 
			$s='';
			$s.= "function PageInfo(){";
			$s.=  "		var o={};";		 
			$s.=  "		o.ROOT='".__ROOT__."';";
			$s.=  "		o.APP='".PHP_FILE."';";
			$s.=  "		o.URL='".__URL__."';";
			$s.=  "		o.ACTION='".G('ACT')."';";
		    $s.=  "		o.mid='".getgpc('mid')."';";
			$s.=  "     o.query='".$query."';";
			//$s.=  "		o.SELF='".$SELF."';";	
			//$s.=  "     o.query='$query';";
			//$s.=  "		o.name='".APPNAME."';";			 
			//$s.=  "		o.data='".$data."';";
			 $s.=  "		o.webpulic='".WEB_PUBLIC_URL."';";
			 $s.="       o.apppublic='".APPPUBLIC."'; ";

			/*foreach($arr as $key=>$v){
			   if(!empty($v)){
				    $v=($v);
					//$s.="o.$key='$v';"; 
			   }
			}*/
			$s.=  "     return o;"; 
			$s.= "};";
			 

			$s.= "var PagePI=PI=new PageInfo();";	
			//$this->assign("pageinfo",$s);
			return $s;
	}
	protected function _setContentType(){
 	 
        if ($this->_response->getHeader('Content-Type')) {
            return;
        }
     
        // get the current format (the _fixFormat() method will have set the
        // default already, if needed)
        $format = $this->_format;
        
        // do we have a content-type for the format?
        if (! empty($this->_format_type[$format])) {
            
            // yes, retain the content-type
            $val = $this->_format_type[$format];
            
            // add charset if one exists
            if ($this->_charset) {
                $val .= '; charset=' . $this->_charset;
            }
            
            // set the response header for content-type
            $this->_response->setHeader('Content-Type', $val);
        }
    }
	protected function _setViewObject(){
        
    }
 
	 protected function viefile($templateFile){
		 

 


       C("VEXT","");
		$templateFile=strtolower($templateFile);
		//echo $templateFile;
        if(''==$templateFile) {
            // 如果模板文件名为空 按照默认规则定位
				$templateFile = strtolower(G('APP').'.'.G('ACT').C('VEXT'));
        }elseif(strpos($templateFile,'@')===0){	
			//一般为引入本模块下其它模板为;例 @add;

			 
			 $templateFile =strtolower(G('APP')).'.'.str_replace(array('@'),'',$templateFile).C('VEXT');			 
		}elseif(strpos($templateFile,'/')===0){
			// 引入其它模块的操作模板			 
            $templateFile   =str_replace('/','.',$templateFile).C('VEXT');
        }elseif(!is_file($templateFile))    {
            // 按正常路径解析;
            $templateFile =str_replace('/','.',$templateFile).C('VEXT');
        }		 
		//echo $templateFile;
		/*
		//$templateFile=strtolower($templateFile);
        if(!file_exists_case($templateFile)){			 
            E(L('template.no').'['.$templateFile.']');
        }*/
       // return $templateFile;


		//$file=$this->_controller.".".$file;	
		 // dump( $templateFile);
		$this->_viewfile=$templateFile;
	     
	 
	 }
     protected function _renderView(){
        // set the template name from the view and format
		// dump($this->_viewfile);
		// echo '6';
        if($this->_viewfile==null){
			$this->viefile($this->_controller.".".$this->_view);
		
		}
 
		$tpl = $this->_viewfile ;
      
 
        try {
				 
			  $this->_response->content = $this->_view_object->fetch($tpl);
 
        } catch (Genv_View_Exception_TemplateNotFound $e) {
            throw $this->_exception('ERR_VIEW_NOT_FOUND', array(
                'path' => $e->getInfo('path'),
                'name' => $e->getInfo('name'),
            ));
        }
    }


	protected function _setup(){

		system_config();
    }
    
   
    protected function _preRun(){
    }
    
   
    protected function _preAction(){
    }
    protected function _postAction(){
    }
   
    protected function _postRun(){
    }    
 
    protected function _preRender(){
    }
  
    protected function _postRender(){
    } 
	protected function _error($key, $replace = null)
    {
        $this->_errors[] = $this->locale($key, 1, $replace);
        $this->_response->setStatusCode(500);
        return $this->_forward('error');
    }
	protected function _notFound($action, $params = null)
    {
        $this->_errors[] = "Controller: \"{$this->_controller}\"";
        $this->_errors[] = "Action: \"$action\"";
        $this->_errors[] = "Format: \"{$this->_format}\"";
        foreach ((array) $params as $key => $val) {
            $this->_errors[] = "Param $key: $val";
        }

		dump($this->_errors);
		exit;
        $this->_response->setStatusCode(404);
        
        // just set the view; if we call _forward('error') we'll get the
        // error view, not the not-found view.
        $this->_view = 'notFound';
    }

public function page2($num, $perpage, $curpage, $mpurl) {
		$ajaxtarget='';
		$multipage = '';
		$simple='';
		$realpages ='';
		$mpurl .= strpos($mpurl, '?') ? '&' : '?';
		if($num > $perpage) {
			
			$page = 5;
			$offset = 2;
 
			$pages = @ceil($num / $perpage);

			if($page > $pages) {
				$from = 1;
				$to = $pages;
			} else {
				$from = $curpage - $offset;
				$to = $from + $page - 1;
				if($from < 1) {
					$to = $curpage + 1 - $from;
					$from = 1;
					if($to - $from < $page) {
						$to = $page;
					}
				} elseif($to > $pages) {
					$from = $pages - $page + 1;
					$to = $pages;
				}
			}

			$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="'.$mpurl.'page=1" class="first"'.$ajaxtarget.'>1 ...</a>' : '').
			($curpage > 1 && !$simple ? '<a href="'.$mpurl.'page='.($curpage - 1).'" class="prev"'.$ajaxtarget.'>上一页</a>' : '');
			for($i = $from; $i <= $to; $i++) {
				$multipage .= $i == $curpage ? '<strong>'.$i.'</strong>' :
				'<a href="'.$mpurl.'page='.$i.($ajaxtarget && $i == $pages && $autogoto ? '#' : '').'"'.$ajaxtarget.'>'.$i.'</a>';
			}

			$multipage .= ($curpage < $pages && !$simple ? '<a href="'.$mpurl.'page='.($curpage + 1).'" class="next"'.$ajaxtarget.'>下一页</a>' : '').
			($to < $pages ? '<a href="'.$mpurl.'page='.$pages.'" class="last"'.$ajaxtarget.'>... '.$realpages.'</a>' : '').
			(!$simple && $pages > $page && !$ajaxtarget ? '<kbd><input type="text" name="custompage" size="3" onkeydown="if(event.keyCode==13) {window.location=\''.$mpurl.'page=\'+this.value; return false;}" /></kbd>' : '');

			$multipage = $multipage ? '<div class="pages">'.(!$simple ? '<em>&nbsp;'.$num.'&nbsp;</em>' : '').$multipage.'</div>' : '';
		}
		 
		return $multipage;
	}
 

	 function page($num, $perpage, $curpage, $mpurl,$ajaxtarget='') {
		$ajaxtarget='';
		$multipage = '';
		$simple='';
		$realpages ='';
		$multipage = '';
		$mpurl .= strpos($mpurl, '?') ? '&' : '?';
		if($num > $perpage) {
			$page = 10;
			$offset = 2;

			$pages = @ceil($num / $perpage);

			if($page > $pages) {
				$from = 1;
				$to = $pages;
			} else {
				$from = $curpage - $offset;
				$to = $from + $page - 1;
				if($from < 1) {
					$to = $curpage + 1 - $from;
					$from = 1;
					if($to - $from < $page) {
						$to = $page;
					}
				} elseif($to > $pages) {
					$from = $pages - $page + 1;
					$to = $pages;
				}
			}

			$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="javascript:PageGo(1)" class="first"'.$ajaxtarget.'>1 ...</a>' : '').
			($curpage > 1 && !$simple ? '<a href="javascript:PageGo('.($curpage - 1).')" class="prev"'.$ajaxtarget.'>上一页</a>' : '');
			for($i = $from; $i <= $to; $i++) {
				$multipage .= $i == $curpage ? '<strong  >'.$i.'</strong>' :
				'<a href="javascript:PageGo('.$i.($ajaxtarget && $i == $pages && $autogoto ? '#' : '').')"'.$ajaxtarget.'>'.$i.'</a>';
			}

			$multipage .= ($curpage < $pages && !$simple ? '<a href="javascript:PageGo('.($curpage + 1).')" class="next"'.$ajaxtarget.'>下一页</a>' : '').
			($to < $pages ? '<a href="javascript:PageGo('.$pages.')" class="last"'.$ajaxtarget.'>... '.$realpages.'</a>' : '').
			(!$simple && $pages > $page && !$ajaxtarget ? '' : '');
           /*
			$multipage .= ($curpage < $pages && !$simple ? '<a href="'.$mpurl.'page='.($curpage + 1).'" class="next"'.$ajaxtarget.'>&rsaquo;&rsaquo;</a>' : '').
			($to < $pages ? '<a href="'.$mpurl.'page='.$pages.'" class="last"'.$ajaxtarget.'>... '.$realpages.'</a>' : '').
			(!$simple && $pages > $page && !$ajaxtarget ? '<kbd ><input type="text" name="custompage" size="3" onkeydown="if(event.keyCode==13) {window.location=\''.$mpurl.'page=\'+this.value; return false;}" /></kbd>' : '');
          */
	 
		   $s="<div class=rsnum><select name=rsnum id=rsnum onchange='javascript:Rsnum(this.options[this.selectedIndex].value)'>";
	       $s.="<option value=10>10</option>";
		   $s.="<option value=50>50</option>";
		   $s.="<option value=200>200</option>";
		   $s.="<option value=500>500</option>";
		   $s.="</select>";

			$multipage = $multipage ? '<div class="pages"><em>总条数'.$num.'每页'.$perpage.'&nbsp;</em>'.(!$simple ? '<em>&nbsp;'.$curpage.'&nbsp;</em><em>&nbsp;'.$num.'/'.$curpage.'&nbsp;</em>' : '<div class="pages">'.$s.'</div>').$multipage.'</div>' : '';
		}
		return $multipage;
	}



	function page_get_start($page, $ppp, $totalnum) {
		$totalpage = ceil($totalnum / $ppp);
		$page =  max(1, min($totalpage, intval($page)));
		return ($page - 1) * $ppp;
	}

	function set_filter($filter, $sql, $param_str = ''){
		$filterfile = basename(PHP_SELF, '.php');
		//$this->logs($filterfile);
		if ($param_str){
			$filterfile .= $param_str;
		}

		//dump($filter);
		 
		 setcookie('MDL[lastfilterfile]', sprintf('%X', crc32($filterfile)), time() + 600);
		 setcookie('MDL[lastfilter]',     urlencode(serialize($filter)), time() + 600);

		 setcookie('MDL[lastfiltersql]',  urlencode($sql), time() + 600);
	/*	$this->setcookie('MDL[lastfilterfile]', sprintf('%X', crc32($filterfile)), time() + 600);
		$this->setcookie('MDL[lastfilter]',     urlencode(serialize($filter)), time() + 600);
		$this->setcookie('MDL[lastfiltersql]',  urlencode($sql), time() + 600);
		*/ 
		//dump($_COOKIE);
	}

	/**
	 * 取得上次的过滤条件
	 * @param   string  $param_str  参数字符串，由list函数的参数组成
	 * @return  如果有，返回array('filter' => $filter, 'sql' => $sql)；否则返回false
	 */
	function get_filter($param_str = '')
	{
		//  return false;
		//echo PHP_SELF;
		//dump($_SERVER);
		 
		$filterfile = basename(PHP_SELF, '.php');
		 
		//$this->logs($filterfile);
		if ($param_str){
			$filterfile .= $param_str;
		}
	//	dump($filterfile);		
		if ( isset($_COOKIE['MDL']['lastfilterfile'])
			&& $_COOKIE['MDL']['lastfilterfile'] == sprintf('%X', crc32($filterfile)))
		{
			return array(
				'filter' => unserialize(urldecode($_COOKIE['MDL']['lastfilter'])),
				'sql'    => urldecode($_COOKIE['MDL']['lastfiltersql'])
			);
		}
		else
		{
			return false;
		}
	}
   //登录
	function login(){
	    G('autodisplay',false);
		 
	  //dump($this->_response->getCookies());
		if (!$this->_request->isPost()){  
			 
           // $this->display('login');
			//$this->viefile("login")	; 
			 
			 $this->display("login");
			 
        }else{	
 
			$username = trim($_POST['username']);
            $password  = $_POST['password'];
			 $select=D();
			$select->selectfrom(gettable('sysmember')." AS a " ,'*' );	
			$select->leftJoin(gettable('sysrole').' AS b ','a.role_id=b.id','name as groupname');
			
		//$d=D('sysmember');
		$select->where("a.username ='".$_POST['username']."' and a.password='".md5($_POST['password'])."'");
		//$d->bind( array('name' =>$_POST['username'],"pwd"=>md5($_POST['password']) ));
		$sql=$select->fetch('sql');
		 //dump($sql);
		$rs=$select->query($sql);
		$rs=$rs[0];
		 
		if($rs){
			Genv_Cookie::set("uid",$rs['id']);
			Genv_Cookie::set("username",$rs['username']);
			Genv_Cookie::set("role_id",$rs['role_id']);
			Genv_Cookie::set("groupname",$rs['groupname']);
		    //$this->_response->setcookie("username",$rs['username']);
			$this->_response->setcookie("role_id",$rs['role_id']);
			$this->_response->redirect(U("default/index"));
		 }else{
			$this->error('登录出错');
		    
		 
		 }
 		 
		}
	
	}

	public function logout(){
	
		Genv_Cookie::delete("username");
		Genv_Cookie::delete("role_id");
		$this->_response->redirect(U("login"));
	}



	//权限叛断;
	public function autoCheckPower($act,$app){
		  // dump($_SESSION);
		  $cache = Genv::factory('Genv_Cache');
		  $roleid=$_COOKIE['role_id'];
		//  dump($_COOKIE);
		  $cid=str_replace(",","_",$roleid);
		  $file="power_$cid";
		 
		 
		 // $powerlist=$cache->fetch($file);	
	 	 
		   //dump($powerlist);
			 if(empty($powerlist)){
			  
					$select=D();
					$select->selectfrom(gettable('syspower')." AS a " ,array('roleid','groupid','menuid','actid') );	
				//	$select->leftJoin(gettable('sysmenu').' AS b ','a.menuid=b.id','appid as app,act');
					$select->leftJoin(gettable('sysnode').' AS c ','a.actid=c.md5key','appname as app,name as act');
					$select->where("roleid in($roleid) and actid!='0'");	
					//$select->order('pid DESC');
					$sql=$select->fetch('sql');;
					 
					$tree=$select->query($sql);
 
					 
					//$select->showsql();
 
					$powerlist=array();
					foreach($tree as $k=>$v){
					 /// dump($v);
					   $powerlist[$v['app'].$v['menuid']][]=$v['act'];
					  
					}
					$cache->save($file,$powerlist);
				   
			   } 
			   //免检动作;
			   
			   if( in_array($act,array('log') ) ){
			    return true;
			   }
			   //免检程序;
			   if(in_array($app,array('default'))){
				 return true;
			   }
			  // echo $app.getgpc('mid');


               // dump($powerlist['company60']);
			  // $app=G('CAPP'); 
			  // $act=G('CACT');
			  //echo $app.getgpc('mid');
			// dump($powerlist[$app.getgpc('mid')]);

			// echo getgpc('mid');
			//ECHO $app.getgpc('mid');
			//DUMP($powerlist[$app.getgpc('mid')]);
			   if(is_array($powerlist[$app.getgpc('mid')])&&in_array($act,$powerlist[$app.getgpc('mid')])){
			       //有权限则进行接下来的操作;
				   
					 return true;
			   }else{
				   
				    return false;
					  
			   }
			   
	 }
	 public function CheckPower($act=null,$app=null){
		    
	        if(empty($act)){
				$act=G('ACT');
			}
			 if(empty($app)){
				$app=G('APP');
			}
 
			$app=ucfirst($app);


			 
			$power=$this->autoCheckPower($act,$app);
			 
	        if($power==false){
			   $ajax=getgpc('is_ajax');
			   if($ajax==1){
				 make_json_error('无权操作');
			   }else{				    
				 $this->error('无权操作');
			   }
			}
	 }


	 //仅叛断有无操作权限
	 public function CPK($act=null,$app=null){
		  
	        if(empty($act)){
				$act=G('ACT');
			}
			 if(empty($app)){
				$app=G('APP');
			}
 
			$app=ucfirst($app);
			 
			$power=$this->autoCheckPower($act,$app);
			 
	        return $power;
	 }
     //获取
	 public function getbillpower($act){
		$roleid=$_COOKIE['role_id'];
		//echo $roleid;
		$rs=D('sysrole')->find($roleid);

		 
		$json=new Genv_Json;
		$bb['status']=-1;
		$bb['limitd']=-1;
		$bb['limitd1']=-1;
	    $billpower=$json->decode(stripslashes($rs['billpower']),true);
		

		//dump($billpower);
		$cc=array();
		if(is_array($billpower['status'])){
		   foreach($billpower['status'] as $k=>$v){
			  $d=explode("_",$v);				 
			  $cc['status'][$d[0]][]=$d[1];			     
		   }
		}
		 
		if(is_array($cc['status'][$act])){
			$bb['status'] = implode(",", $cc['status'][$act]);
		}
		$myid=0;

		 
		if(is_array($billpower['limitd'])){
		   foreach($billpower['limitd'] as $k=>$v){
			  $d=explode("_",$v);
			  
			  if($d[1]==0){
				  $myid=$_COOKIE['uid'];
				  $cc['limitd'][$d[0]][]=$d[1];	
			  }else{
				  $cc['limitd'][$d[0]][]=$d[1];			     
			  }
		   }
		}
 

		 

 
		
		if(is_array($cc['limitd'][$act])){
			$depid=implode(",", $cc['limitd'][$act]);

			 
			$userid=D('sysmember')->select('id')->where('dep_id in('.$depid.')')->findall();
			
			$uid=array();
			foreach((array)$userid as $k=>$v){
				   $uid[]=$v['id'];
			}
			$uid[]=$myid;
			$uid=array_unique($uid);
			 
			
			if(is_array($uid)){
			$bb['limitd'] = implode(",", $uid);
			}
        }
		 

		 
		 //dump($bb);
		
        //操作权限
		$myid=0;
		if(is_array($billpower['limitd1'])){
		   foreach($billpower['limitd1'] as $k=>$v){
			  $d=explode("_",$v);
			  if($d[1]==0){
				  $myid=$_COOKIE['uid'];
				  $cc['limitd1'][$d[0]][]=$d[1];
			  }else{
				$cc['limitd1'][$d[0]][]=$d[1];			     
			  }
		   }
		}
		if(is_array($cc['limitd1'][$act])){
			$depid=implode(",", $cc['limitd1'][$act]);
			
			$userid=D('sysmember')->select('id')->where('dep_id in('.$depid.')')->findall();
			
			$uid=array();
			foreach($userid as $k=>$v){
				   $uid[]=$v['id'];
			}
			$uid[]=$myid;
			$uid=array_unique($uid);	
			if(is_array($uid)){
			$bb['limitd1'] = implode(",", $uid);
			}
		}
	 
		return $bb;
	 
	 }
	 /**
     +----------------------------------------------------------
     * 操作错误跳转的快捷方法
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param string $message 错误信息
     * @param Boolean $ajax 是否为Ajax方式
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function error($message,$ajax=false)
    {
		 
        $this->_dispatch_jump($message,0,$ajax);
    }

    /**
     +----------------------------------------------------------
     * 操作成功跳转的快捷方法
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param string $message 提示信息
     * @param Boolean $ajax 是否为Ajax方式
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    protected function success($message,$ajax=false)
    {
        $this->_dispatch_jump($message,1,$ajax);
    }
	  /**
     +----------------------------------------------------------
     * Ajax方式返回数据到客户端
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param mixed $data 要返回的数据
     * @param String $info 提示信息
     * @param boolean $status 返回状态
     * @param String $status ajax返回类型 JSON XML
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    protected function ajaxReturn($data,$info='',$status=1,$type='')
    {
        // 保证AJAX返回后也能保存日志
       // if(C('LOG_RECORD')) Log::save();
        $result  =  array();
        $result['status']  =  $status;
        $result['info'] =  $info;
        $result['data'] = $data;
        if(empty($type)) $type  = "JSON";//  C('DEFAULT_AJAX_RETURN');
        if(strtoupper($type)=='JSON') {
            // 返回JSON数据格式到客户端 包含状态信息
            header("Content-Type:text/html; charset=utf-8");
            exit(json_encode($result));
			$json=new Genv_Json;
	   
		    die($json->encode($list));
        }elseif(strtoupper($type)=='XML'){
            // 返回xml格式数据
            header("Content-Type:text/xml; charset=utf-8");
            exit(xml_encode($result));
        }elseif(strtoupper($type)=='EVAL'){
            // 返回可执行的js脚本
            header("Content-Type:text/html; charset=utf-8");
            exit($data);
        }else{
            // TODO 增加其它格式
        }
		exit;
    }

	/**
     +----------------------------------------------------------
     * 默认跳转操作 支持错误导向和正确跳转
     * 调用模板显示 默认为public目录下面的success页面
     * 提示页面为可配置 支持模板标签
     +----------------------------------------------------------
     * @param string $message 提示信息
     * @param Boolean $status 状态
     * @param Boolean $ajax 是否为Ajax方式
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    private function _dispatch_jump($message,$status=1,$ajax=false)
    {
        // 判断是否为AJAX返回
		 
        if($ajax || $this->_request->isXhr()){
	 
			$this->ajaxReturn($ajax,$message,$status);
			 
		}
        // 提示标题
        $this->assign('msgTitle',$status? L('_OPERATION_SUCCESS_') : L('_OPERATION_FAIL_'));
        //如果设置了关闭窗口，则提示完毕后自动关闭窗口
         if($this->_view_object->get('closeWin'))    $this->assign('jumpUrl','javascript:window.close();');
      
		$this->assign('status',$status);   // 状态
        $this->assign('message',$message);// 提示信息
        //保证输出不受静态缓存影响
      //  C('HTML_CACHE_ON',false);

	 //  dump($message);
	 //  echo !is_array($message);
	//	dump($this->_view_object->vars);

        if($status) { //发送成功信息
            // 成功操作后默认停留1秒
             if(!$this->_view_object->get('waitSecond')) $this->assign('waitSecond',"1");
            // 默认操作成功自动返回操作前页面
            if(!$this->_view_object->get('jumpUrl')) $this->assign("jumpUrl", $_SERVER["HTTP_REFERER"]);			
            $this->display('success');
        }else{
            //发生错误时候默认停留3秒
            if(!$this->_view_object->get('waitSecond')) $this->assign('waitSecond',"3");
            // 默认发生错误的话自动返回上页
             if(!$this->_view_object->get('jumpUrl')) $this->assign('jumpUrl',"javascript:history.back(-1);");
            $this->display('success');
        }
        
        exit ;
    }

    //信息提示;
	public function msg($d=array()){
	 
		$this->success($d['msg']);

	}
	 
	 
	function show_menu($menus = array(),$tabs=1) {	
		$menu = '';
		foreach($menus as $id=>$m) {
			$id++;
			if(isset($m[1])) {
				$extend = isset($m[2]) ? $m[2] : '';
				$menu .= '<td id="Tab'.$id.'" class="tab"><a href="'.$m[1].'" '.$extend.'>'.$m[0].'</a></td><td class="tab_nav">&nbsp;</td>';
			} else {
				$class = $id == 0 ? 'tab_on' : 'tab';
				$menu .= '<td id="Tab'.$id.'" class="'.$class.'"><a href="javascript:Tab('.$id.');">'.$m[0].'</a></td><td class="tab_nav">&nbsp;</td>';
			}
		}
		$this->assign('tabs_on',$tabs);
		$this->assign('menu',$menu);
		 
		//$this->display('menu');	
	}
	function logs($sql){
	 $config = array(
         'adapter' => 'Genv_Log_Adapter_File',
         'events'  => '*',
         'file'    => Genv_Config::get('Genv','appname').'/Data/Logs/sql.log.txt',
     );
       $log = Genv::factory('Genv_Log', $config);       
       $log->save($sql,'','');
	  }
	 //订单状态;
	public function get_bill_status(){
	
	  
	  return array('下单','操作','查柜','放行','锁定','解锁');
	
	}
	//根据ＩＤ获取公司名称;
	public function getcompany($id){
	    $data=F("company_list");
	    if(empty($data)){
           $rs=D('company')->select('id,name')->findall();
           $data=array();
		   foreach($rs as $key=>$v){
		    $data[$v['id']]=$v['name'];		   
		   }
		   F('company_list',$data);
		}
		return $data[$id];
	
	
	}

	
		//根据ＩＤ获取客户名称;
	public function getcustomer($id){
	    $data=F("getcustomer_list");
	    if(empty($data)){
           $rs=D('customer')->select('id,j_company')->findall();
           $data=array();
		   foreach($rs as $key=>$v){
		    $data[$v['id']]=$v['j_company'];		   
		   }
		   F('getcustomer_list',$data);
		}
		 
		return $data[$id];
	
	
	}
	 //银行信息;
	public function get_banklist(){
	
	    $data=F("bank_list");
	    if(empty($data)){
           $rs=D('bank')->select('*')->findall();
           $data=array();
		   foreach($rs as $key=>$v){
		    $data[$v['id']]=$v['bankname'];		   
		   }
		   F('bank_list',$data);
		}
		return $data;
	   
	
	}


	public function get_banklist1(){
	
	    $data=F("bank_list1");
	    if(empty($data)){
           $rs=D('bank')->select('*')->findall();
           $data=array();
		   foreach($rs as $key=>$v){
		    $data[]=$v;		   
		   }
		   F('bank_list1',$data);
		}
		return $data;
	   
	
	}
	
	public function getuserlist(){	    
		 
		$data=F("getuserlist_1");
	    if(empty($data)){
           $rs=D('sysmember')->select('id,username,real_name')->findall();
           $data=array();
		   foreach($rs as $key=>$v){
		    $data[$v['id']]=$v['username'];		   
		   }
		   F('getuserlist_1',$data);
		}
		return $data;		 
	}
	
	public function getbillcount($data){
		//客户应付var s=['b_bgf','b_dzf','b_sxf','b_gjf','b_sjf','b_qtf','b_cgf','br_money']

		$bill_s=array(
			'b_bgf'=>'报关费',
			'b_gjf'=>'港建费',
			'b_dzf'=>'单证费',			
			'b_sxf'=>'码头费',
			'b_cgf'=>'查柜费',			
			'br_money'=>'托车费',
			'b_qtf'=>'其它费',
				
		);
		//公司应付var s=['pb_dzf_0','pb_dzf_1','pb_dzf_2','pb_dzf_3','pc_bgmoney','pc_portmoney','pc_tankmoney','pc_other','pk_money','pk_code','pr_money','pr_portmoney','po_money1','po_money2']

		$bill_f=array(
			
			//'pb_price'=>'单价',
			//'pb_hkd'=>'申报金额HKD',
			//'pb_usd'=>'USD',
			'pb_dzf'=>'单证费',
			
			'pc_bgmoney'=>'报关费',
			'pc_portmoney'=>'港建费',
			'pc_tankmoney'=>'查柜费',
			'pc_other'=>'其它费',
			
			'pc_bgmoney2'=>'报关费',
			'pc_tankmoney2'=>'查柜费',			
			'pc_other2'=>'其他费',
			
			'pr_money'=>'托车费',
			'pr_portmoney'=>'码头费',
			'pr_other'=>'其它费',			
			//'shi_rebate'=>'实际回扣'
		);
		$a=0;
		foreach($bill_s as $k=>$v){
		  $a+=floatval($data[$k]);
		
		}
		$dt['count_shu']=$a;
		$a=0;
		foreach($bill_f as $k=>$v){
		  $a+=floatval($data[$k]);		
		}
		$dt['count_zhi']=$a;
		$dt['account']=$dt['count_shu']-$dt['count_zhi']-floatval($data['rebate']);
		return $dt;

	}
}

function make_json_error($msg)
{
    json_response('', 1, $msg);
}
/**
 * 创建一个JSON格式的数据
 *
 * @access  public
 * @param   string      $content
 * @param   integer     $error
 * @param   string      $message
 * @param   array       $append
 * @return  void
 */
function json_response($content='', $error="0", $message='', $append=array())
{
   // include_once(ROOT_PATH . 'includes/cls_json.php');
	$json=new Genv_Json;
    $res = array('error' => $error, 'message' => $message, 'content' => $content);
     if (!empty($append)) {
        foreach ($append AS $key => $val)
        {
            $res[$key] = $val;
        }
    }

    $val = $json->encode($res);

    exit($val);
}