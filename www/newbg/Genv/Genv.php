<?php
/*
框架核心;

*/


define('IS_CGI',substr(PHP_SAPI, 0,3)=='cgi' ? 1 : 0 );
define('IS_WIN',strstr(PHP_OS, 'WIN') ? 1 : 0 );
define('IS_CLI',PHP_SAPI=='cli'? 1   :   0);


if(!IS_CLI) {
    // 当前文件名
    if(!defined('PHP_FILE')) {
        if(IS_CGI) {
            //CGI/FASTCGI模式下
            $_temp  = explode('.php',$_SERVER["PHP_SELF"]);
            define('PHP_FILE',  rtrim(str_replace($_SERVER["HTTP_HOST"],'',$_temp[0].'.php'),'/'));
        }else {
            define('PHP_FILE',    rtrim($_SERVER["SCRIPT_NAME"],'/'));
        }
    }
    if(!defined('WEBURL')) {
        // 网站URL根目录
        if( strtoupper(APPNAME) == strtoupper(basename(dirname(PHP_FILE))) ) {
            $_root = dirname(dirname(PHP_FILE));
        }else {
            $_root = dirname(PHP_FILE);
        }
        define('WEBURL',   (($_root=='/' || $_root=='\\')?'':$_root));
    }  
}

class Genv{
   
    protected static $_Genv = array(
        'ini_set'      => array(),
        'registry_set' => array(),
		'defines'       =>array(),
        'start'        => array(),
        'stop'         => array(),
        'system'       => null,
    );
    
    public static $system = null; 

	protected static $_config = false;   
    
    protected static $_status = false;    
    
    final private function __construct() {}

    
    public static function start($config = array()){
	
        if (Genv::$_status) {
            return;
        }  
		$config=array_merge(Genv::$_config, $config);   
       
        $list = array(
            'Base',
            'Function',     
		    'Class',
            'Config',
            'File'		    
        );
     
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
 	
        foreach ($list as $name) {
           
            if (! class_exists('Genv_' . $name, false)) {			 
                require $dir . DIRECTORY_SEPARATOR . "$name.php";
            }
        }
      
        spl_autoload_register(array('Genv_Class', 'autoload'));
     
	 
		 
        if (ini_get('register_globals')) {
			
            Genv::cleanGlobals();
        }        
	   // Genv::cleanGlobals();

        Genv_Config::load($config);
       // $c=Genv_Config::get("Genv");
       //dump($c);
        $arch_config = Genv_Config::get('Genv');


        if (! $arch_config) {
            Genv_Config::set('Genv', null, Genv::$_Genv);
        } else {
            Genv_Config::set('Genv', null, array_merge(
                Genv::$_Genv,
                (array) $arch_config
            ));
        }
          //$c=Genv_Config::get();
       // dump($c);
      
        Genv::$system = Genv_Config::get('Genv', 'system');
        
   
        $settings = Genv_Config::get('Genv', 'ini_set', array());
 //dump($settings);
        foreach ($settings as $key => $val) {
            ini_set($key, $val);
        }

		$defines = Genv_Config::get('Genv', 'defines', array());

        foreach ($defines as $key => $val) {
            define($key, $val);
        }
        
        // user-defined registry entries
        $register = Genv_Config::get('Genv', 'registry_set', array());

		
        foreach ($register as $name => $list) {
            // make sure we have the class-name and a config

			 
            $list = array_pad((array) $list, 2, null);

			
            list($spec, $config) = $list;
            // register the item
 
			 
            Genv_Registry::set($name, $spec, $config);
        }
       
        // Genv itself needs these default objects registered ...
        $name_class = array(
            'inflect'  => 'Genv_Inflect',
            'locale'   => 'Genv_Locale',
            'rewrite'  => 'Genv_Uri_Rewrite',
            'request'  => 'Genv_Request',
            'response' => 'Genv_Http_Response',
        );
      
        // ... but only if not already registered by the user.
        foreach ($name_class as $name => $class) {
			
            if (! Genv_Registry::exists($name)) {
                Genv_Registry::set($name, $class);
            }
        } 
        
        // run any 'start' hooks
       $hooks = Genv_Config::get('Genv', 'start', array());

	 //  dump($hooks);
	  
       Genv::callbacks($hooks);
        
        // and we're done!
       Genv::$_status = true;

	   
    }
   
    public static function stop(){
        // run any 'stop' hook methods
        $hooks = Genv_Config::get('Genv', 'stop', array());
        Genv::callbacks($hooks);
        
        // unregister autoloader
        spl_autoload_unregister(array('Genv_Class', 'autoload'));
        
        // reset the status flag, and we're done.
        Genv::$_status = false;
    }
	
   
    public static function callbacks($callbacks){
        foreach ((array) $callbacks as $params) {
           /*
            // include a file as in previous versions of Genv
            if (is_string($params)) {
				 echo 333;

                Genv_File::load($params);
                continue;
            }
		 
            exit;*/
            // $spec is an object instance, class name, or registry key
            settype($params, 'array');
            $spec = array_shift($params);
			  
            if (! is_object($spec)) {
                // not an object, so treat as a class name ...
                $spec = (string) $spec;
                // ... unless it's a registry key.
                if (Genv_Registry::exists($spec)) {
                    $spec = Genv_Registry::get($spec);
                }
            }
            
            // the method to call on $spec
            $func = array_shift($params);
          // dump($params);
            // make the call
            if ($spec) {
                call_user_func_array(array($spec, $func), $params);
            } else {
                call_user_func_array($func, $params);
            }
        }
    }   
	//创建客户端
    public static function client($name){
		$config = array();
		 
		$config['Genv']['system'] = SYSPATH;
		$config['Genv']['sysdir']=SYSPATH."/Genv";
		$config['Genv']['appname'] = $name;
		$config['Genv']['appdir'] =SYSPATH."/".$name;
		require_once($config['Genv']['appdir']."/Conf/Webconf.php");
		
		define('APPPATH',$config['Genv']['appdir']);
		define('EXT',".php");
		define('__APP__',PHP_FILE);

		define('__ROOT__',WEBURL);
		define('__APPDIR__',WEBURL.'/'.$name);
		// echo APPPATH;
		//当前项目地址       
		define('__APP__',PHP_FILE);

		//echo PHP_FILE;
		//当前页面地址
		define('__SELF__',$_SERVER['PHP_SELF']);  
		define('APPPUBLIC',__APPDIR__.'/Public/');

		define('WEB_PUBLIC_URL',__ROOT__.'/Public/');
		 
        Genv::$_config=$config;	 
        
    }

	public static function factory($class, $config = null){		 
        Genv_Class::autoload($class); 

        $obj = new $class($config); 
		 
        if ($obj instanceof Genv_Factory) {           
            return $obj->factory();
        }
		
        return $obj;
    }
    
  
    public static function dependency($class, $spec){
        // is it an object already?
        if (is_object($spec)) {
            return $spec;
        }
        
        // check for registry objects
        if (is_string($spec)) {
            return Genv_Registry::get($spec);
        }
     //   dump($class);
        // not an object, not in registry.
        // try to create an object with $spec as the config
        return Genv::factory($class, $spec);
    }
  
    public static function exception($spec, $code, $text = '',$info = array()) {
        // is the spec an object?
        if (is_object($spec)) {
            // yes, find its class
            $class = get_class($spec);
        } else {
            // no, assume the spec is a class name
            $class = (string) $spec;
        }

		 
        
        // drop 'ERR_' and 'EXCEPTION_' prefixes from the code
        // to get a suffix for the exception class
        $suffix = $code;
        if (strpos($suffix, 'ERR_') === 0) {
            $suffix = substr($suffix, 4);
        } elseif (strpos($suffix, 'EXCEPTION_') === 0) {
            $suffix = substr($suffix, 10);
        }
        
        // convert "STUDLY_CAP_SUFFIX" to "Studly Cap Suffix" ...
        $suffix = ucwords(strtolower(str_replace('_', ' ', $suffix)));
        
        // ... then convert to "StudlyCapSuffix"
        $suffix = str_replace(' ', '', $suffix);
        
        // build config array from params
        $config = array(
            'class' => $class,
            'code'  => $code,
            'text'  => $text,
            'info'  => (array) $info,
        );
        
        // get all parent classes, including the class itself
        $stack = array_reverse(Genv_Class::parents($class, true));
        
        // add the vendor namespace to the stack as a fallback, even though
        // it's not strictly part of the hierarchy, for generic vendor-wide
        // exceptions.
        $vendor = Genv_Class::vendor($class);
        if ($vendor != 'Genv') {
            $stack[] = $vendor;
        }
        
        // add Genv as the final fallback
        $stack[] = 'Genv';
        
echo (Genv::get_caller());
  dump($config);
 exit;
 // die('出错');

		//exit;
        // track through class stack and look for specific exceptions
		/*
        foreach ($stack as $class) {
            try {
                $obj = Genv::factory("{$class}_Exception_$suffix", $config);
                return $obj;
            } catch (Exception $e) {
                // do nothing
            }
        }
        
        // track through class stack and look for generic exceptions
        foreach ($stack as $class) {
            try {
                $obj = Genv::factory("{$class}_Exception", $config);
                return $obj;
            } catch (Exception $e) {
                // do nothing
            }
        }*/
        
        // last resort: a generic Genv exception
        return Genv::factory('Genv_Exception', $config);
    }    
   
    public static function dump($var, $label = null){
        $obj = Genv::factory('Genv_Debug_Var');
        $obj->display($var, $label);
    }    
   
    public static function cleanGlobals()
    {
        $list = array(
            'GLOBALS',
            '_POST',
            '_GET',
            '_COOKIE',
            '_REQUEST',
            '_SERVER',
            '_ENV',
            '_FILES',
        );
        
        // Create a list of all of the keys from the super-global values.
        // Use array_keys() here to preserve key integrity.
        $keys = array_merge(
            array_keys($_ENV),
            array_keys($_GET),
            array_keys($_POST),
            array_keys($_COOKIE),
            array_keys($_SERVER),
            array_keys($_FILES),
            // $_SESSION is null if you have not started the session yet.
            // This insures that a check is performed regardless.
            isset($_SESSION) && is_array($_SESSION) ? array_keys($_SESSION) : array()
        );
      // dump($keys);
        // Unset the globals.
        foreach ($keys as $key) {
            if (isset($GLOBALS[$key]) && ! in_array($key, $list)) {
                unset($GLOBALS[$key]);
            }
        }
    }

	public static function get_caller() {
		$trace  = array_reverse( debug_backtrace() );
		$caller = array();

		foreach ( $trace as $call ) {
			if ( isset( $call['class'] ) && __CLASS__ == $call['class'] )
				continue; // Filter out wpdb calls.
			$caller[] = isset( $call['class'] ) ? "{$call['class']}->{$call['function']}" : $call['function'];
		}

		return join( '<br> ', $caller );
	}
	 
}