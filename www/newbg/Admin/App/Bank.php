<?php 
/*
银行管理


		$str='[{"name":"农行","num":"6228480081163549116","weight":"詹朝暖"},{"name":"中行","num":"6013821900055904176","weight":"詹朝暖"},{"name":"工行","num":"6222023602001456664","weight":"詹朝暖"},{"name":"招行","num":"6226090200522981","weight":"詹朝暖"},{"name":"建行","num":"3324889980110016144","weight":"詹朝暖"},{"name":"深圳招行","num":"6225886555388204","weight":"詹朝暖"},{"name":"工行公帐","num":"3602014619200051375","weight":"深圳市科迪报关有限公司广州分公司"},{"name":"广州银行公帐","num":"8002 0563 0208 012","weight":"深圳市科迪报关有限公司广州分公司"},{"name":"现金","num":"","weight":""},{"name":"往来相抵","num":"","weight":""},{"name":"深圳代收","num":"","weight":""}]';
		$json=new Genv_Json;		 
	    $data=$json->decode($str);
         $data=(array)$data;
		 //dump($data);
		foreach($data as $key=>$v){
          $v=(array)$v;
		 $data[$key]=(array)$data[$key]  ;
		  $data[$key]['bankname']=$v['name'];
		  $data[$key]['bankcode']=$v['num'];
		  $data[$key]['bankcustomer']=$v['weight'];
		
		}
		D('bank')->addall($data);
		dump($data);
		exit;
*/
class BankAction extends GlobalAction {
	#银行管理	
	 public function _postConstruct(){   
			parent::_postConstruct();
			$this->CheckPower('index');
	}	
	public function index(){
	    #银行管理		 



		$d=D('bank');	 		 
		$list['list']=$d->findall();
		$json=new Genv_Json;		 
	    $grid['listdata']=$json->encode($list);
		if($this->_request->isXhr()){
		  echo $grid['listdata'];
		
		}
        $grid['datasrc']=U('index');
		$this->assign($grid);
		$this->assign('datalist',$datalist); 
		 
	} 
	 
	public function edit(){
	   #编辑银行
	   $id=getgpc('id');
	   $rs=D('bank')->find($id);	   
	   $json=new Genv_Json;		 
	   echo $json->encode($rs);	
	}   
	public function save(){
	    //$data['id']=getgpc("id");		 
		//$data['name']=getgpc("name");
		//$data['remark']=getgpc("remark");
		$d=D('bank');
		$data=$d->create();
		//dump($data);
		if($data['id']){
			$rs= $d->save($data);
		
		}else{
		
			$d->add($data);
		}
		//$d->showsql();
		$this->index();
		exit;		
	}

 
	public function delete(){
		#删除银行
	    $data['id']=getgpc("id");			 
		$d=D('bank');
		$d->delete($data['id']);		 
		$this->index();
		exit;		
	}

}

?>