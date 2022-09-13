<?php
/*
菜单管理;
*/ 
 //cat_option_static
 //cat_pid_releate
class  MenuAction extends GlobalAction{
   #菜单管理    
    public function _postConstruct(){   
			parent::_postConstruct();



			$this->CheckPower('index');
	}	
   public function index(){
       #列表
	    I('@.Help.myfunc');
		$menulist = menu_list(0, 0, false);		
		$this->assign('menulist',$menulist);   
   }


 
	public function add(){
		#添加
		
		$list['menus_cat_options']=$this->menus_cat_options();
		$list['node_options']=$this->node_options();
		$rs=D("sysmenu")->where("id=0")->find();
		$list['rs']=$rs;
		$list['doact']='insert';		
		$this->assign($list);
		
	}
	 public function edit(){
	    #编辑
		//X();
		//$this->CheckPower();		 
		$rs=D("sysmenu")->where("id=".getgpc("id"))->find();
		$list['rs']=$rs;
		 $list['menus_cat_options']=$this->menus_cat_options();
		 
		 $list['node_options']=$this->node_options();  
		 $list['doact']='update';	
        $this->assign($list);

		// dump($list['node_options']);
		$this->display('@add');
	}
	//保存
	public function save(){
		 
		$d=D("sysmenu"); 
	 // dump($_POST);
		$data=$d->create();
		$doact=getgpc('doact');
		if(empty($data['menu'])){
			$data['menu']=$data['appid'];
		}
		 
		
		if($doact=='insert'){
			unset($data['id']);
			 
			$rs=$d->add($data);	
			 
		}elseif($doact=='update'){		   
			$rs=$d->save($data);
		}	
        F();
		// dump($rs);
		// $d->showsql();
		// $dt['msg']='操作成功';
		// $dt['url']=U('index');
       $this->success('操作成功'); 
	   exit;
	//   F();
		 // $d->getsql();
		if($rs){
			
			$m['statusCode']=202;
			$m['message']='操作成功';
			$m['navTabId']='';
			$m['callbackType']='closeCurrent';
			$m['forwardUrl']='';
			$json=new Genv_Json;
			//dump($m);
			die($json->encode($m));
		 	
		}else{
			$m['statusCode']=202;
			$m['message']='操作失败';
			$m['navTabId']='';
			$m['callbackType']='closeCurrent';
			$m['forwardUrl']='';

			//dump($m);
			$json=new Genv_Json;
 
			die($json->encode($m));
		}		 
	}
	 public function remove(){
		#删除
		//$this->CheckPower();
		$ids = getgpc('id');
		 
		$dao=D("sysmenu");
		$dao->where("`id` IN (".$ids.")");
		$dao->delete($ids); 
		// $dao->showsql();
		 F();
		F('cat_option_static',null);
		$m['error']=0;
		$m['message']='操作相当成功';			 
		$m['callback']='forward';
		$m['forwardUrl']=U('Menu/index');
		$json=new Genv_Json();
		//dump($json);
	    die($json->encode($m));
		exit;
	}

		//所属类别;
	public function  menus_cat_options(){
	    $list=F("menus_cat_options");
		if(!$list){
			$d=D('sysmenu');
			$list=$d->where('rootid=0')->order('taxis asc,id asc')->findall();
			//$d->showsql();			
			F("menus_cat_options",$list);
		}
		return $list;
	
	}
	public  function node_options(){
	    $list=F("node_options");
		if(!$list){
			$d=D('sysnode');
			$allnodes=$d->where('rootid!=0')->order('taxis asc,id asc')->findall();
			 //$d->showsql();
			$nodes_1=array();
			$nodes_2=array();
			foreach($allnodes as $k=>$v){
				   $v['title']=trim($v['title']);
				   if($v['level']==2){
					 $nodes_1[]=$v;
				   }
				   if($v['level']==3){
					 $nodes_2[$v['rootid']][]=$v;
				   }
			}
			 
			$list['nodes_1']=$nodes_1;
			 
			$list['nodes_2']=$nodes_2;

			F("node_options",$list);
		}
		return $list;
	
	}
	//切换状态;
	public function toggle_status(){
		#切换状态
		$id = getgpc('id');
		$val = getgpc('val');
	 
		$data['id']=$id;
		$data['status']=$val;
		//dump($data);
		D('sysmenu')->save($data);
		//D()->showsql();
		F();
		$info['text']='成功';
		$info['val']=$val;
		$this->success($info);
		
		//D()->showsql();

		
	}
	//改变排序;
	public function edit_taxis(){
		#切换状态
		$id = getgpc('id');
		$val = getgpc('val');
	 
		$data['id']=$id;
		$data['taxis']=$val;
		//dump($data);
		D('sysmenu')->save($data);
		F();
		$info['text']='成功';
		$info['val']=$val;
		$this->success($info);
		//D()->showsql();

		
	}

	
}
