<?php
/*
文章管理;
*/
 
class  TestAction extends GlobalAction{
    #文章管理    
    
   public function index(){
        echo 333;
		exit;
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

	public function apiedit(){
	   // dump($_POST);
	  $db=D('article');
	  $data=$db->create();
	  $db->save($data);
	 // $db->showsql();
	
	
	}
	function getlist($url=null){
	 
 
	 // $result = $this->get_filter();
	 
	 //dump($result);
	 if(!$result){
			$filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'id' : trim($_REQUEST['sort_by']);
            if(in_array($filter['sort_by'],array('title'))){
				$sort_by=' convert('.$filter['sort_by'].' USING gbk) COLLATE gbk_chinese_ci ';
            }else{
			
			   $sort_by=$filter['sort_by'];
			}
			$filter['sort_order']=$sort_order=getgpc('sort_order');
			if(empty($sort_order)){
				$sort_order="DESC";			
			}else{
			
				$sort_order=$sort_order==-1?"DESC":'asc';
			}
			//$filter['sort_order'] = $sort_order;//empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
			$filter['title'] =  empty($_REQUEST['title']) ? '' : trim($_REQUEST['title']) ;
			 
			if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0)
			{
				$filter['page_size'] = intval($_REQUEST['page_size']);
			}
			elseif (isset($_COOKIE['MDL']['page_size']) && intval($_COOKIE['MDL']['page_size']) > 0)
			{
				$filter['page_size'] = intval($_COOKIE['MDL']['page_size']);
			}
			else
			{
				$filter['page_size'] = 10;
			}
			// dump($filter);

			$select =D();//Genv::factory('Genv_Sql_Select');

			$select->selectfrom(gettable('article'),array('id','title','author','hits'));	
			$where=' 1=1 ';
			if ($filter['title']){
				//$this->getsearch($filter['title']);
				$where .= " AND title LIKE '%" . $filter['title'] . "%'";
			}
			
			 
			$select->where($where);	
			$select->order($sort_by.' '. $sort_order );
			$count=$select->countPages("id");
			$select->setpage($filter['page_size']);
			$page=$this->_request->get("page")?$this->_request->get("page"):1;
			$select->page($page);	
			$sql=$select->fetch('sql');
			 //dump($count);
			 
			$filter['count']=$count['count'];
			 
			$this->set_filter($filter,$sql);
			 
		}
		else
		{
			 //echo 333;
			$sql = $result['sql'];
			//echo $sql;
			$count = $result['count'];
			$filter = $result['filter'];
			//dump($filter);
		}
		$this->logs($sql);
 //echo $sql;
		    
	  $d=D(); 
	  $rs=$d->query($sql); 
	  $url=U($url);
	  
	  $multipage = $this->page($filter['count'], $filter['page_size'], $page, $url);
	 
		$arr = array('list' => $rs, 'listpage' => $multipage, 'filter'=>$filter);
 
		return $arr;
	   exit;

		 



	}

	public function remove(){
       #删除
	    $ids=getgpc('id');	
		//dump($ids);
        $d=D('article');
		//dump($ids);
		$d->delete($ids);
		//$d->showsql();
		if($this->_request->isXhr()){
		 $this->query();
		  exit;		
		}
		$dt['msg']='删除成功';
		$dt['url']=U('index');
       $this->msg($dt);

	 

	}
	
}
