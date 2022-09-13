<?php 
/**
 *  所有类的基础类
 */
 class Base{	
    var $_errors = array();
    var $_errnum = 0;
    function __construct(){
        $this->Base();
    }
	function Base(){
        #TODO
    } 
	/**
     +----------------------------------------------------------
     * 自动变量设置
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param $name 属性名称
     * @param $value  属性值
     +----------------------------------------------------------
     */
    public function __set($name ,$value)
    {
        if(property_exists($this,$name)){
            $this->$name = $value;
        }
    }

    /**
     +----------------------------------------------------------
     * 自动变量获取
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param $name 属性名称
     +----------------------------------------------------------
     * @return mixed
     +----------------------------------------------------------
     */
    public function __get($name)
    {
        if(isset($this->$name)){
            return $this->$name;
        }else {
            return null;
        }
    }

    /**
     *    触发错误    
     */
    function _error($msg, $obj = ''){
        if(is_array($msg)){
            $this->_errors = array_merge($this->_errors, $msg);
            $this->_errnum += count($msg);
        }   else {
            $this->_errors[] = compact('msg', 'obj');
            $this->_errnum++;
        }
    }
    /**
     *    检查是否存在错误
	 *    
     */
    function has_error(){
        return $this->_errnum;
    }
    /**
     *    获取错误列表    
     */
    function get_error(){
        return $this->_errors;
    }
	function parseName($name,$type=0) {
        if($type) {
            return ucfirst(preg_replace("/_([a-zA-Z])/e", "strtoupper('\\1')", $name));
        }else{
            $name = preg_replace("/[A-Z]/", "_\\0", $name);
            return strtolower(trim($name, "_"));
        }
    }
}
?>