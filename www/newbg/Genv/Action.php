<?php

class Genv_Action extends Genv_Base{
   
    protected $_Genv_Action = array(
        'classes' => array('Genv_App'),
        'disable' => array(),
        'default' => null,
        'routing' => array(),
        'rewrite' => array(),
        'replace' => array(),
        'explain' => false,
		'global' =>array(),
    );  
    protected $_default;    
  
    protected $_disable = array();
 
    protected $_rewrite = array();
    
    
    protected $_routing = array();
    
  
    protected $_routing_key;
    
  
    protected $_stack;
    
 
    protected $_explain;

	var $_res;
	var $ok;
    
   
    protected function _postConstruct(){
        parent::_postConstruct();
           // set string var from config

		// dump($this->_config);

		if ($this->_config['default']) {
            $this->_default = (string) $this->_config['default'];
        }
        
        // merge array vars from config
        $vars = array('disable', 'routing');
        foreach ($vars as $key) {
            if ($this->_config[$key]) {
                $var = "_$key";
                $this->$var = array_merge(
                    $this->$var,
                    (array) $this->_config[$key]
                );
            }
        }
		//dump($this->_config);
        foreach($this->_config['global'] as $key=>$v){
		
		    include_once Genv_Config::get("Genv",'appname').'/App/'.$v.".php";
		
		}
		//echo 333;
        // set up a class stack for finding apps
        $this->_stack = Genv::factory('Genv_Class_Stack');

		//dump($this->_config['classes']);
        $this->_stack->add($this->_config['classes']);
       
        // retain the registered rewriter
        $this->_rewrite = Genv_Registry::get('rewrite');
        
        // merge our rewrite rules
        if ($this->_config['rewrite']) {
            $this->_rewrite->mergeRules($this->_config['rewrite']);
        }
      //  dump($this->_rewrite);
        // merge our rewrite replacement tokens
        if ($this->_config['replace']) {
            $this->_rewrite->mergeReplacements($this->_config['replace']);
        }


         
        // extended setup
        $this->_setup();
       
        
    }   

    protected function _setup(){
    }
    
  
    public function fetch($spec = null){

		if ($spec instanceof Genv_Uri_Action) {
            // a uri object was passed directly
            $uri = $spec;
        } else {
            // spec is a uri string; if empty, uses the current uri
            $uri = Genv::factory('Genv_Uri_Action', array(
                'uri' => (string) $spec,
            ));
        }

	 	
       
        $this->_explain['fetch_uri'] = $uri->getFrontPath();
         //dump($this->_explain['fetch_uri']);
		 //exit;
        // rewrite the uri path
        $this->_rewrite($uri);


 //dump($uri);
        list($page, $class) = $this->_routing($uri);
 

	 
        // do we have a page-controller class?
        if (! $class) {
            return $this->_notFound($page);
        }
        
	// dump($this->_config);
        // instantiate the controller class and fetch its content
	 
 
 
      $obj = Genv::factory($class);
		 
 
		
	 
        $obj->setFrontController($this);
        
        // was this the result of a static routing? if so, force the
        // page controller to use the route name.
        if ($this->_routing_key) {
            $obj->setController($this->_routing_key);
        }
     
        // done, fetch the page-controller results
 
		//  dump($obj->fetch($uri));
     
	  
        return $obj->fetch($uri);
		 
		 
        
    }
     public function display($spec = null)    {	
		
			return  $this->fetch($spec);
		 
    } 

    /* public function display1($spec = null){
		 
		 $this->fetch($spec);
		// dump($response);
		$response = $this->_res;
        $response->display();
    }*/
    
   
    protected function _rewrite($uri){
       
        $newpath = $this->_rewrite->match($uri);
		 

		 //dump($newpath);

		// echo $newpath;

        if ($newpath) {
            $uri->setPath($newpath);
            $this->_explain['rewrite_rule'] = $this->_rewrite->explain();
            $this->_explain['rewrite_uri'] = $uri->getFrontPath();
        } else {
            $this->_explain['rewrite_rule'] = $this->_rewrite->explain();
        }
    }
    
    
    protected function _routing($uri){

        // first path-element is the page-name.
        $page = strtolower(reset($uri->path));
		 
 		 // dump($page);
        if (empty($page)) {
           
            $class = $this->_getPageClass($this->_default); 
            array_shift($uri->path);
            $this->_explain['routing_page']  = "empty, using default page '{$this->_default}'";

        } elseif (in_array($page, $this->_disable)) {  
			
            $class = $this->_getPageClass($this->_default);
            $this->_explain['routing_page'] = "'$page' disabled, using default page '{$this->_default}'";

        } else {
            // look for a controller for the requested page.
            $class = $this->_getPageClass($page);
			/*已经加载了Action 文件类*/ 
            if (! $class) {
				
                $class = $this->_getPageClass($this->_default);
                $this->_explain['routing_page'] = "no class for page '$page', using default page '{$this->_default}'";
            } else {
               
                array_shift($uri->path);
                $this->_explain['routing_page'] = $page;
            }
        }
        
        $this->_explain['routing_class'] = $class;
        $this->_explain['routing_uri']   = $uri->getFrontPath();


		//dump(array($page, $class));
        return array($page, $class);
    }
    
  
    protected function _getPageClass($page){
	 
        if (! empty($this->_routing[$page])) {
            // found an explicit route
            $this->_routing_key = $page;
            $class = $this->_routing[$page];
        } else {

            // no explicit route
            $this->_routing_key = null;
            
            // try to find a matching class
            $page = str_replace('-',' ', strtolower($page));
            $page = str_replace(' ', '', ucwords(trim($page)));
	 
            $class = $this->_stack->load($page, false);
			//$class=$page;
        }
        return $class;
    }
    

	protected function _notFound($page)
    {
        $content[] = "<html><head><title>Not Found</title></head><body>";
        $content[] = "<h1>404 Not Found</h1>";
        $content[] = "<p>"
                   . htmlspecialchars("Page controller class for '$page' not found.")
                   . "</p>";
        
        if ($this->_config['explain']) {
            $content[] = "<h2>Track</h2>";
            $content[] = "<dl>";
            foreach ($this->_explain as $code => $text) {
                $content[] = "<dt><code>{$code}:</code></dt>";
                $content[] = "<dd><code>"
                           . ($text ? htmlspecialchars($text) : "<em>empty</em>")
                           . "</code></dd>";
            }
            $content[] = "</dl>";
            
            $content[] = "<h2>Page Class Prefixes</h2>";
            $content[] = '<ol>';
            foreach ($this->_stack->get() as $class) {
                $content[] = "<li>$class*</li>";
            }
            $content[] = '</ol>';
        }
        
        $content[] = "</body></html>";
        
        $response = Genv_Registry::get('response');
        $response->setStatusCode(404);
        $response->content = implode("\n", $content);
        
        return $response;
    }
 
}