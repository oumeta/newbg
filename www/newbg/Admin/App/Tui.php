<?php
/*
退税核销单;
*/
 
class  TuiAction extends GlobalAction{
    #退税核销单    
    
   public function index(){
        #列表	
		$this->CheckPower();
		$list =$this->getlist('index');
		$json=new Genv_Json;		 
	    $grid['listdata']=$json->encode($list);
        $grid['datasrc']=U('query');
		$this->assign($grid);
		 
   }
   public function query(){			 
		$list = $this->getlist('index');		
		$json=new Genv_Json;	 
		die($json->encode($list));
	}
	 
	public function add(){
		#添加
		$this->CheckPower();
	    $select = D('tui');			 
		$select->where("`id`=0");
		$rs=$select->find();
		$rs['add_data']=date('Y-m-d');

		I('@.Lib.Form');		
	    $this->assign('rs',$rs);
		$this->assign('doact','insert');
	
	}
	public function edit(){
		#编辑
		$this->CheckPower();
        $id=getgpc('id');
		$db=D('tui');
		$rs=$db->find($id);
		I('@.Lib.Form');
	    $this->assign('rs',$rs);
		$this->assign('doact','update');
	 
		$this->viefile("@add");//赋值常用的一些变量
	}
	
	
	 public function save(){

	 
	    /** 初始化验证类 */
      /* $validator = new Genv_Validate();		 
       $validator->addRule('title', 'required', ('请输入标题'));   
	   $a=$this->_request->from('title');

	   if ($error = $validator->run($this->_request->from('title'))) {
			 echo 'error';
			 die;
        }else{
		 
		
		}*/
		
        $doact=getgpc('doact');	
        $db=D('tui');
		$data=$db->create();//D('tui');
		 
		if($doact=='insert'){
          $rs = $db->add($data);
          $dt['msg']='添加成功';
		}elseif($doact=="update"){
		  $rs = $db->save($data);
          $dt['msg']='更新成功';
		
		}

		$this->assign('jumpUrl',U('index'));
		//$db->showsql();
		//$dt['url']=U('index');
        $this->success($dt['msg']);
		// dump($this->_response);

	 

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
			 
			$filter['customers'] =getgpc('customers');
			$filter['taitou'] = getgpc('taitou');
			$filter['billcode'] = getgpc('billcode');
			$filter['company'] = getgpc('company'); 
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

			$select->selectfrom(gettable('tui'),'*');	
			$where=' 1=1 ';
			 
			if ($filter['customers']){
				//$this->getsearch($filter['title']);
				$where .= " AND customers LIKE '%" . $filter['customers'] . "%'";
			}
			if ($filter['taitou']){
				//$this->getsearch($filter['title']);
				$where .= " AND taitou LIKE '%" . $filter['taitou'] . "%'";
			}
			if ($filter['billcode']){
				//$this->getsearch($filter['title']);
				$where .= " AND billcode LIKE '%" . $filter['billcode'] . "%'";
			}
			if ($filter['company']){
				//$this->getsearch($filter['title']);
				$where .= " AND company LIKE '%" . $filter['company'] . "%'";
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
 //echo $sql;
		    
	  $d=D(); 
	  $rs=$d->query($sql); 
	  $url=U($url);
	  
	  $multipage = $this->page($filter['count'], $filter['page_size'], $page, $url);	 
	  $arr = array('list' => $rs, 'listpage' => $multipage, 'filter'=>$filter); 
	  return $arr;
	}

	public function remove(){
       #删除
	   $this->CheckPower();
	    $ids=getgpc('id');	
		//dump($ids);
        $d=D('tui');
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
