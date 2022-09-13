<?php
/*
公司管理;
*/
 
class  CompanyAction extends GlobalAction{
    #公司管理    
    
   public function index(){
        #列表
		//$list=$this->cache_storetype();	
		$this->CheckPower();
		$list =$this->getlist('index');

 
		$json=new Genv_Json;		 
	    $grid['listdata']=$json->encode($list);
        $grid['datasrc']=U('query');
		$grid['cate']=getgpc('cate');
		$grid['mid']=getgpc('mid');
		 
		 
		$this->assign($grid);		
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
		$this->CheckPower();
	    $select = D('article');
			 
		$select->where("`id`=0");
		$rs=$select->find();
		I('@.Lib.Form');
		 

		//$this->assign('build_editor', Form::editor('adbc')); 
	    //$this->assign('build_upload', MYlib::_build_upload()); // 构建
	   //$list=$this->cache_storetype();
	   //$this->assign('ctype',$list);
	    $rs['cate']=getgpc('cate');

		 
		
		$this->assign('mid',getgpc('mid'));
	    $this->assign('rs',$rs);
		$this->assign('doact','insert');
	 
	//	 $this->viefile("article.add");//赋值常用的一些变量
	}
	public function edit(){
		#编辑
		$this->CheckPower();
        $id=getgpc('id');
		$db=D('company');
		$rs=$db->find($id);
		I('@.Lib.Form');
	    $this->assign('rs',$rs);
		$this->assign('doact','update');
	 
		$this->viefile("@add");//赋值常用的一些变量
	}
	
	
	 public function save(){

	 
	    /** 初始化验证类 */
       $validator = new Genv_Validate();		 
       $validator->addRule('name', 'required', ('请输入公司名称'));   
	   $a=$this->_request->from('name');
	   

	   if ($error = $validator->run($this->_request->from('name'))) {
			 echo 'error';
			 die;
        }else{
		 
		
		}
		 
        $doact=getgpc('doact');
		
		//$data=$this->_request->from('title','content','id');

		//$data['stypeid']=implode(",",$stypeid);
        $db=D('company');
	 
		$data=$db->create();
 
		 
		if($doact=='insert'){
          $rs = $db->add($data);
          $dt['text']='添加成功';
		}elseif($doact=="update"){
		  $rs = $db->save($data);
          $dt['text']='更新成功';
		
		}
		
		F();
		 
		$this->assign('jumpUrl',U('index',array('cate'=>getgpc('cate'))));
		$this->success($dt);
	}

	function getlist($url=null){ 
	 //$result = $this->get_filter();	 
	 //dump($result);
	 
	 if(!$result){
			$filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'rank' : trim($_REQUEST['sort_by']);
            if(in_array($filter['sort_by'],array('title'))){
				$sort_by=' convert('.$filter['sort_by'].' USING gbk) COLLATE gbk_chinese_ci ';
            }else{
			
			   $sort_by=$filter['sort_by'];
			}
			
			$sort_order=getgpc('sort_order');
			if(empty($sort_order)){
			$sort_order=-1;
			}
			$filter['sort_order']=$sort_order;

			if(empty($sort_order)){
				$sort_order="DESC";			
			}else{
			
				$sort_order=$sort_order==-1?"DESC":'asc';
			}
			//$filter['sort_order'] = $sort_order;//empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
			$filter['title'] =  empty($_REQUEST['title']) ? '' : trim($_REQUEST['title']) ;
			$filter['cate'] = empty($_REQUEST['cate']) ? '0' : trim(getgpc('cate')) ;
			 
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

			$select->selectfrom(gettable('company'),array('id','name','rank'));	
			$where=' cate='.$filter['cate'].' ';
			if ($filter['title']){
				//$this->getsearch($filter['title']);
				$where .= " AND name LIKE '%" . $filter['title'] . "%'";
			}
			
			 
			$select->where($where);	
			$select->order($sort_by.' '. $sort_order );
			$count=$select->countPages("id");
			$select->setpage($filter['page_size']);
			$page=getgpc("page")?getgpc('page'):1;
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
 // echo $sql;
 // dump($filter);
		    
	  $d=D(); 
	  $rs=$d->query($sql); 
	  $url=U($url);
	  
	  $multipage = $this->page($filter['count'], $filter['page_size'], $page, $url);
	 
		$arr = array('list' => $rs, 'listpage' => $multipage, 'filter'=>$filter);
// dump($arr);
		return $arr;
	   exit;

		 



	}

	public function remove(){
       #删除
	   $this->CheckPower();
	    $ids=getgpc('id');	
		//dump($ids);
        $d=D('company');
		//dump($ids);
		$d->delete($ids);
		 F();
		if($this->_request->isXhr()){
		 $this->query();
		  exit;		
		}
		$dt['msg']='删除成功';
		$dt['url']=U('index');
       $this->msg($dt);

	 

	}
	public function apiedit(){
	
      $data=$_POST;
	  $rs=D('company')->save($data);
	 
	  if($rs==1){
	    $this->success('修改成功');	  
	  }
	  if($rs==0){
	    $this->error('修改失败');	  
	  }
	  
	}
}
