<?php
/*
分类管理;
1为产品分类;
*/ 
 //cat_option_static
 //cat_pid_releate


class  CateAction extends GlobalAction{
   #分类管理    
   public function index(){
       #列表
	    I('@.Help.myfunc');
		 
		$type=getgpc('type');
		$menulist = catelist(0, 0, false,$type);		 
		$addurl=U('add',array('type'=>$type));		 
		$this->assign('addurl',$addurl);
		$this->assign('type',$type);

		$this->assign('menulist',$menulist);   
   } 
	public function add(){
		#添加

		
		$list['cate_options']=$this->cate_options();
		 
		$rs=D("cate")->where("id=0")->find();
		$list['rs']=$rs;
		$list['doact']='insert';
		$list['type']=getgpc('type');
		$this->assign($list);
		
	}
	 public function edit(){
	    #编辑
		//X();
		//$this->CheckPower();		 
		$rs=D("cate")->where("id=".getgpc("id"))->find();
		$list['rs']=$rs;
		 $list['cate_options']=$this->cate_options();		
		 
		 $list['doact']='update';
		 $list['type']=getgpc('type');
        $this->assign($list);

		//dump($list);
		$this->display('@add');
	}
	//保存
	public function save(){		 
		$d=D("cate"); 	
		$data=$d->create();
		$doact=getgpc('doact');		
		
		if($doact=='insert'){
			unset($data['id']);
			 
			$rs=$d->add($data);	
			 
		}elseif($doact=='update'){		   
			$rs=$d->save($data);
		}	

		 
		$dt['msg']='操作成功';
		$dt['url']=U('index',array('type'=>$data['type']));
       $this->msg($dt); 
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
		//dump($ids);
		$dao=D("cate");
		$dao->where("`id` IN (".$ids.")");
		$dao->delete($ids); 
		//$dao->showsql();
		//F();
		F();
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
	public function  cate_options(){
	    $list=F("cate_options");
		$type=getgpc('type');
		if(!$list){
			$d=D('cate');
			$list=$d->where('rootid=0 and type='.$type.'')->order('taxis asc,id asc')->findall();			 		
			F("cate_options",$list);
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
	
}
