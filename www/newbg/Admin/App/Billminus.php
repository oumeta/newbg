<?php
/*
出库单管理
*/
 
class  BillminusAction extends GlobalAction{
    #出库单管理    
    
   public function index(){
        #列表
		//$list=$this->cache_storetype();		 
		$list =$this->getlist('index');
		$json=new Genv_Json;
		 
	    $grid['listdata']=$json->encode($list);
        $grid['datasrc']=U('query');
		$this->assign($grid);
		//dump($grid);
		$this->viefile('article.index');
   }
   public function query(){			 
		$list = $this->getlist('index');
		//$json=new Json();	
		$json=new Genv_Json;
	  //  $json->encode($list);
		die($json->encode($list));
	}
	 
	public function add(){
		#添加
	    $select = D('article');
			 
		$select->where("`id`=0");
		$rs=$select->find();
		I('@.Lib.Form');
		 

		//$this->assign('build_editor', Form::editor('adbc')); 
	    //$this->assign('build_upload', MYlib::_build_upload()); // 构建
	   //$list=$this->cache_storetype();
	   //$this->assign('ctype',$list);
	    $this->assign('rs',$rs);
		$this->assign('doact','insert');
	 
	//	 $this->viefile("article.add");//赋值常用的一些变量
	}
	public function edit(){
		#编辑
        $id=getgpc('id');
		$db=D('article');
		$rs=$db->find($id);
		I('@.Lib.Form');
	    $this->assign('rs',$rs);
		$this->assign('doact','update');
	 
		$this->viefile("@add");//赋值常用的一些变量
	}
	
	
	 public function save(){

	 
	    /** 初始化验证类 */
       $validator = new Genv_Validate();		 
       $validator->addRule('title', 'required', ('请输入标题'));   
	   $a=$this->_request->from('title');

	   if ($error = $validator->run($this->_request->from('title'))) {
			 echo 'error';
			 die;
        }else{
		 
		
		}
		
        $doact=getgpc('doact');
		
		$data=$this->_request->from('title','content','id');

		//$data['stypeid']=implode(",",$stypeid);
        $db=D('article');
		 
		if($doact=='insert'){
          $rs = $db->add($data);
          $dt['msg']='添加成功';
		}elseif($doact=="update"){
		  $rs = $db->save($data);
          $dt['msg']='更新成功';
		
		}
		//$db->showsql();
		$dt['url']=U('index');
       $this->msg($dt);
		// dump($this->_response);

	 

	}
	function getlist($url=null){
	 
 
	  $result = $this->get_filter();
	 if(!$result){
			$filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'id' : trim($_REQUEST['sort_by']);
			$filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
			$filter['title'] = getgpc("title");
			

			$select =D('article');//Genv::factory('Genv_Sql_Select');

			$select->selectfrom(gettable('article'),array('id','title'));	
			$where=' 1=1 ';
			if ($filter['title']){
				//$this->getsearch($filter['title']);
				$where .= " AND name LIKE '%" . $filter['title'] . "%'";
			}
			
			 
			$select->where($where);	
			$select->order('id DESC');
			$count=$select->countPages("id");
			$select->setpage(15);
			$page=$this->_request->get("page")?$this->_request->get("page"):1;
			$select->page($page);	
			$sql=$select->fetch('sql');
			 
			$filter['count']=$count;
			 
			$this->set_filter($filter,$sql);
			 
		}
		else
		{
			$sql = $result['sql'];
			$count = $result['count'];
			$filter = $result['filter'];
		}

		// echo $sql;
	  $d=D('article'); 
	  $rs=$d->query($sql);
//dump($rs);
      //处理分类;
	  /*
	  $list=$this->cache_storetype();
// dump($list);
       // D()->showsql();
	   if($rs){
			  $cat=array();
			  foreach($list as $key=>$v){	  
				 $cat[$v['cid']]=$v['cname'];	  
			  }
			  
			  foreach($rs as $key=>$v){
				 $rs[$key]['cat']=$cat[$v['stypeid']];
				   
			  }
	   }
	  */
		$url=U($url);
	  
	  $multipage = $this->page($count['count'], 15, $page, $url);
	 
		$arr = array('list' => $rs, 'listpage' => $multipage, 'fillter'=>array());
 
		return $arr;
	   exit;

		 



	}

	//设置
	public function seting(){
       #删除
		 

	    $sids=getgpc('sids');
		 

		//dump($sids);

 
		$sql=Genv_Registry::get("sql");


	 
		foreach ($sids as $sort=>$sid ) {
			
			  
			 $rs = $sql->delete('g_store',"sid=$sid");

           // $this->update(array('order' => $sort + 1),
            //$this->db->sql()->where('mid = ?', $mid)->where('type = ?', $type));
        }
		 
          $dt['msg']='删除成功';
		  $dt['url']=U('index');
          $this->msg($dt);
		// dump($this->_response);

	 

	}
	
}
