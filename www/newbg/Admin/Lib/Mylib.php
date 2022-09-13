<?php
//一些常用的操作;
$php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
if ('/' == substr($php_self, -1))
{
    $php_self .= 'index.php';
}
define('PHP_SELF', $php_self);

class Mylib {	 
		 
	//切换模板引擎
	public function GV(){
			LC("@.Lib.Vengine");
			$smarty = new Vengine;

			$smarty->template_dir  =C("APPTPL");// ROOT_PATH . 'admin/templates';
			$smarty->compile_dir   =C("APPTPLCACHE");// ROOT_PATH . 'temp/compiled/admin';
			if ((DEBUG_MODE & 2) == 2)
			{
				$smarty->force_compile = true;
			}

			$_LANG=Mylib::GL();
			$GLOBALS['_LANG']=$_LANG;
			$smarty->assign('lang', $_LANG);
			$smarty->assign('public',APP_PUBLIC_URL );
	 
			$smarty->assign('url',str_replace("url/",'',U("url")) );
			$smarty->assign('style',APP_PUBLIC_URL."/styles/");
			//$smarty->assign('help_open', $_CFG['help_open']);
            /*
			if(isset($_CFG['enable_order_check']))  // 为了从旧版本顺利升级到2.5.0
			{
				$smarty->assign('enable_order_check', $_CFG['enable_order_check']);
			}
			else
			{
				$smarty->assign('enable_order_check', 0);
			}*/

			
			G("V",$smarty);

		
	}
	public function GL($ff){		 
		require(APPPATH."/Language/zn/Admin/common.php");
		require(APPPATH."/Language/zn/Admin/log_action.php");
		if($ff){
			$file=APPPATH."/Language/zn/Admin/".$ff.".php";
		}else{
			$file=APPPATH."/Language/zn/Admin/".strtolower(G("CAPP")).".php";
		
		}
	// ECHO $file;
		if (file_exists($file))
		{
			include($file);
		}	
		L($_LANG);
		//dump($_LANG);
		G("lang",$_LANG);
		return $_LANG;
	}


    //信息提示;
	public function tips($tips){
		echo ' <img src="'.APP_PUBLIC_URL.'/Images/help.png" width="11" height="11" title="'.$tips.'" alt="tips" class="c_p" onclick="Dconfirm(this.title);" />';
	}
	//产品组合分类;
    public function goods_group_cate(){	
	  return array(
		  array(
			'id'=>'color',
		    'name'=>'颜色',
		  ),
		  array(
			'id'=>'size',
		    'name'=>'尺寸',
	      )
	   );	
	}
	//品牌分类;
    public function brand_cate(){	
	  return array(
		  array(
			'id'=>'1',
		    'name'=>'女装',
		  ),
		  array(
			'id'=>'2',
		    'name'=>'男装',
	      ),
		  array(
			'id'=>'3',
		    'name'=>'配饰',
	      )
	   );	
	}

	//商城配置信息;
   
	/**
 * 载入配置信息
 *
 * @access  public
 * @return  array
 */
function shopconfig(){
    $arr = array();
	$data = read_static_cache('shop_config');
	 
    if ($data === false)
    {
        $sql = 'SELECT code, value FROM ' . gettable('shopconfig') . ' ';
        $res = G("db")->getAll($sql);

        foreach ($res AS $row)
        {
            $arr[$row['code']] = $row['value'];
        }
        
		
        write_static_cache('shop_config', $arr);
    }
    else
    {
        $arr = $data;
    }
	$GLOBALS['_CFG']=$arr;
	G('shopconf',(object)$arr);
    return $arr;
 }

	//数据提交校验;以提高安全性;
	public function Signcode($k=''){
	   $sign=getgp("sign");
	   $mysign=$_SESSION['mysign'];	 
	   $sign=md5($mysign.C('md5rules').$k);
	   return $sign;	
	}
	/*****GirdView************************************************************************/
	//获取列表显示数值;
	public function getlistNum(){
	    return 10;
	}
	//获得数据表格;
	function getDatalist($fields,$list,$pageurl,$orderby1){
		
		$s="<table align=left style='table-layout:fixed;'  height=20 border=0 cellspacing=0 cellpadding=0  width=100% >
			<tr valign=top>
			<td height=25>
			<div id=divHead>		
			<TABLE class=dgHead id=dgHead cellSpacing=0 cellPadding=0 border=0  style='table-layout:fixed;' height=21 width=100%>
			<THEAD>
			<TR height=25 bgcolor=#EDF6FF valign=top>
			<TH style='border-right: 1px solid #e3e3e3;border-bottom: 1px solid #E3E3E3; '  valign=top><input type=checkbox id='checkAll' class=gcheckbox title='点击全选或取消' /></TH>";
		
		foreach($fields as $value){

			 $s.="<TH style='border-right: 1px solid #e3e3e3;border-bottom: 1px solid #E3E3E3;font-size:12px;font-weight: bold;text-align:center ;'  ondblclick=window.location='".$pageurl."&order=".$value['fieldid']."&orderby=".$orderby1."'>".$value['fieldname'] .$value['img']."&nbsp;</TH>";	 
		}
		$s.="
			<TH WIDTH=*  style='border-right: 1px solid #e3e3e3;border-bottom: 1px solid #E3E3E3;'>&nbsp;</TH>
			</TR>
			</THEAD>
			</TABLE>
			</div>
			</td>
			<td style='width:16px; background-color:#EFEBDE'><span style='width:16px;' >&nbsp;</span></td>
			</tr>
			<tr>
			<td colspan=2>
			<div id=divMain >"
		;
		$s.="<TABLE class=dgMain id=dgMain cellSpacing=0 cellPadding=0 border=0 style='table-layout:fixed;' > ";
		foreach($list as $value){
			$s.="<tbody> <tr    bgColor=#ffffff height=25>\n";
			$s.="<td class=tda ><input type=checkbox id='keyid' name='keyid' class=gcheckbox value=$value[id] title=$value[id]></td>\n";			
			foreach($fields as $ff){
			  $s.="<td  class=tdb>&nbsp;".$value[$ff['fieldid']]."</td>\n";
			}
			$s.="<td   WIDTH=*  class=tdc>&nbsp;</td>\n";
			$s.="</tr>\n";
			 
		}	
		$s.="</tbody></table>";
		$s.=" </div>
			 </td></tr>
			  <tr >					 
			</table>"
		;
	    return $s;	
	
	}
	/*******************************************************************************/
    //取得url
	function geturl($order,$orderby){		
		// import('ORG.Aiqi.url'); 		
		 $pageurl  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?");         
		 return $pageurl;	 
	}
	/**
	 * 内容预设值选择器
	 *
	 * @param string $inputname
	 * @param integer $getvalue
	 * @return string
	 */
	public function selectValue($inputname,$getvalue){ 
		//echo $getvalue;
		if($getvalue<10){ //10以内作为系统内置的选择器
			switch ($getvalue){
				case 0:
					return ;
				case 1:
					return ;
				case 2:
					$select = "<img id='show_$inputname' src='images/addcolor.gif' style='cursor:pointer;' align='absmiddle' onclick=\"showColor('show_".$inputname."','".$inputname."');\">";
					break;
				case 3:
					$select = "<img src='../public/images/addimg.gif' style='cursor:pointer;' align='absmiddle' onclick=\"selectImg('".$inputname."');\">";
					break;
				case 4:					
					$select = "<img src=".APP_PUBLIC_URL."/images/addtime.gif style='cursor:pointer;' align='absmiddle' onclick=\"ShowCalendar('".$inputname."');\">";
					break;
				case 5:
					$select = "<img src='images/rename.gif' style='cursor:pointer;' align='AbsBottom' onclick=\"selectTids('".$inputname."','".$inputtype."');\">";
					break;
				case 6:
					$select = "<img src='images/addimg.gif' style='cursor:pointer;' align='absmiddle' onclick=\"selectImg('".$inputname."');\">";
					break;
				}
		}else{
			//echo 444;
			$data=array();			
			$data['selectid']=$getvalue;
			$App=D("Selectvalue",'Admin_App');	
			$list=$App->order("taxis desc")->findAll($data);	
			
		
			$select = "<select onchange=\"selectValue('".$inputname."',this.value);\">";
			$select.="<option value=\"\"></option>";
			
		    foreach($list as $k=>$v){
				$select.="<option value=\"".$v['value']."\">".$v['value']."</option>";
			}
			$select.="</select>";
		}
		return $select; 
	}

/*
获取查询条件;取得此菜单ID对应的数据查看范围;
*/
	public function getData(){
		    $data=array();			
			$arr= $_REQUEST;
			$v=$arr['v'];
			  
			@extract($arr);	
			LC('@.Lib.HashMap');
			$data = new HashMap();
			for($i=0;$i<count($v);$i++){	
				switch ($arr['o'][$i]){
					case 'BET':				
						if(!empty($v[$i][0])&&!empty($v[$i][1])){
							$data->put($arr['f'][$i], array(array('EGT', $v[$i][0]), array('ElT', $v[$i][1]), 'and'));
						}
					    BREAK;
					case "LIKE":
						if(!empty($v[$i])){
						 $data->put($arr['f'][$i],array('like',"%$v[$i]%") );
						}
						 break;
					default: 
						if(!empty($v[$i])){
							 $data->put($arr['f'][$i],array('eq',$v[$i]) );
						}
					   break;
				}
			  		
			}		
			return $data;//Mylib::datalimit($data);
			
	}
	//得到数据限制规则
	public function datalimit($data){		 
		$menuid=getgp('menuid');
		$DL=D("Sysdatalimit")->where("menuid='$menuid' and cate=0")->findAll();		 
			if($DL){
				for($i=0;$i<count($DL);$i++){
					$kv=Mylib::getdefault($DL[$i][keyvalue]); 
					 if(ACTION_NAME==$DL[$i][action]&&in_array( Mylib::roleid,explode(",", $DL[$i][roleid]) ) ){					 
						$data->put($DL[$i][fields],array( $DL[$i][op],$kv) );
					 }
				
				}				
			}
			return $data;
	}

	public function pet(){
		return ;
		$arr=$_REQUEST;	
		
		foreach($arr as $key=>$v){
			   if(!empty($v)&&(in_array($key,array("m","a","menuid","p","order","orderby","getselor",'field','op','keyvalue','fm_html','Signcode','vid','ISSEARCH') ))  ){
					unset($arr[$key]);
			   }
			}
		//dump($arr);
		if(count($arr)==0){
			return '';
		}
		$pet=escape(json_encode($arr));
		//echo $pet;
        Mylib::saveview($pet);		
		return "&ISSEARCH=1";
	
	}
	//保存最后的几次查询为xml文件;
	public function saveview($pet){
		$menuid=getgp("menuid");
		if(empty($pet)||empty($menuid)){
			return ;		
		}
		//echo $pet;
		$file=Mylib::getDatafile("cache_view_$menuid.php","Datacache");	
	
		$list=array();
		if(file_read($file)){
				include($file);				
		}
		//echo $pet;
		$list[]=$pet;		 
		Mylib::setDatacache($list,$file); 
	}
	public function getDatafile($f,$dir="Datacache"){
		return C('APPDATA')."$dir/$f";
	 }
	//得到最后一次查询条件;
	public function getpet(){
	    $menuid=getgp("menuid");
		$vid=getgp("vid");
		 
		if(empty($menuid)){
			return ;		
		}
		$file=Mylib::getDatafile("cache_view_$menuid.php","Datacache");	
		if(read_file($file)){

				include ($file);	
				
				if($vid){
					 
					$rs= $list[$vid-1];
				}else{					
					$rs= $list[count($list)-1];
				}					
				return unescape($rs);
		}

	} 

	 public function setDatacache($list,$file){				 
				    foreach($list as $key=>$value){
						$val = addslashes($value);
						$dt.="\t'$key'=>'$val',\n";
						
					}
					$date=date('Y-m-d');
					$writetofile=
				"<?php
		 /**
		* $date
		*/
		\$list=array(
		$dt
		);
		".'?>';
				  
					 file_write( $file,$writetofile );
	}
	//取得菜单对应的字段;
	public function getFields(){
		$pid=getgp("menuid");		 
		include(MdlPATH."/Public/data/cache_edit_fields_".$pid.".php");		 
		return $list;
	
	}
	public function getactlist(){
		//按MENUID获取动作列表;
		   $menuid=getgp('menuid');
		   if (!$menuid) $this->error(L('_SELECT_NOT_EXIST_'));	
		  
		   $roleid=$this->visitor->get('role_id');
		   $cid=str_replace(",","_",$roleid);
		   $actList=F("Actlist_$cid");
		   if(empty($actList)){			   
			   $Menu=D('Sysrolepower');		
			   $tree=$Menu->where("roleid in($roleid) and actid!=0")->findAll(); 
			   
			   $actid=array();
			   foreach($tree as $k=>$v){				 			  
						$actid[]=$v[actid];				  
			   }	
			   $actid=implode(',',$actid);
			   /*先找出对应权限的id*/
			   $App=D('Sysact'); //本菜单对应的可用的动作
			   $tree=$App->where("id in($actid)  and status=1")->order(" taxis asc ")->findAll(); 
			  //echo $App->getlastsql();
			   $actList=array();
			 
			   foreach($tree as $k=>$v){
			        $actList[$v[menuid]][]=$v;			
				}
				// dump($actList);
				//exit;
			   F("Actlist_$cid",$actList,-1,DATA_PATH);			   
		  }
		 
		  $list=$actList[$menuid];
		  $pageact=array();
		  $pageselact=array();
		  foreach($list as $k=>$v){
			         
					//if(in_array(ACTION_NAME,explode(",", $v[showpage]))){
					  if($v['showtype']==0){
						$pageact[]=$v;
					  }elseif($v['showtype']==1){
					    $pageselact[]=$v;
					  }
					//}
		   }
		  $pageact=multi_sort($pageact,'taxis');
		  $pageselact=multi_sort($pageselact,'taxis');
		  $a = array();
		  $a["pageact"]=$pageact;
		  $a["pageselact"]=$pageselact;
		 // dump($pageact);
		  $this->assign($a);
		   //Mylib::display("Public:appact");
		  
	}
	//取得操作符;
	public function getOplist(){
		$data=array();
		$data[EQ]="=";
		$data[NEQ]="!=";
		$data[GT]=">";
		$data[EGT]=">=";
		$data[LT]="<";
		$data[ELT]="<=";
		$data[LIKE]="LIKE";
		$data[IN]="IN";
		$data['NOT IN']="NOT IN";
		$dt=array();
		foreach($data as $k=>$v){
			$v1[key]=$k;
			$v1[v]=$v;
			$dt[]=$v1;
		}
		 
		return $dt;
	}
	/****************以下函数为特殊处理 输助函数/*/
    /*ajax 数据接口 */
	 public function Ajaxdata(){
	  //模型名称;查询条件;类型识别;	   
	   import("ORG.Aiqi.Ajaxdata");
	   $data = file_get_contents("php://input");  
		 // dump($data);
		if(empty($data)){
			$data=$_POST;
		}  	     
	    //$b=$json->decode($data);
	    $dt= json_decode($data);
	   // dump($dt) ;
	   //echo $data;
	    $data=new Ajaxdata($dt);
	   //$data->init();
   }

   /*
   取得默认值;
   */
    public function getdefault($v){		 
		$dt=explode("return_", $v);	
		 
		if(count($dt)==2){
			try{
				//echo $this->getName();
			 $dv=call_user_func(array($this, $dt[1]));
			}catch(Exception $e){
			//return $e;
			}
		}else{
			$dv=$v;
		}
		return $dv;
	}
    /**
	 * 添加内容时的输入部分
	 *
	 * @return array
	*/	 
	public function getInputArea(){		 
		$inputArea = array();
		$pid=GetGP("menuid");		
		include(Mylib::getDatafile("cache_edit_fields_".$pid.".php"));

		$fields=$list;		
		foreach ($fields as $field){			
			@extract($field);
			//echo "<pre>";
			//print_r($field);
			//echo "</pre>";
			//echo $value;
			 // dump($field);
			if($type=='custom' && !$ifcontribute) {
				continue;
			}
			/*
			if($value){
				$fieldvalue=$value($field);
			}else{
				$fieldvalue='';
			}*/
			$selectValue = Mylib::selectValue($fieldid,$getvalue,$inputtype);
			 
			 //echo $fieldid.$getvalue."<br>";
			$defaultvalue=Mylib::getdefault($field['defaultvalue']);
			switch ($inputtype){
				case 'input':					 
					$otherarr = $arr ? $arr : '';
				
				 
					$field['input']="<input class=\"textfield\" style=\"width:$width;height:$height;float:left\"  name=\"$fieldid\" type=\"text\" id=\"$fieldid\" value=\"$defaultvalue\" $otherarr > $selectValue <label class=\"tips\" id='".$fieldid."Tip' for=\"$fieldid\" >$inputlabel</label>";
					break;
			    case 'hidden':
				    $otherarr = $arr ? $arr : '';
					$field['input']="<input class=\"textfield\" style=\"width:$width;height:$height\"  name=\"$fieldid\" type=\"hidden\" id=\"$fieldid\" value=\"$defaultvalue\" $otherarr > $selectValue <label class=\"tips\" id='".$fieldid."Tip' for=\"$fieldid\" >$inputlabel</label>";				
					break;
				case 'textarea':
					$sizelimit = $inputsize>60 ? "cols=\"$inputsize\"" : "cols=\"60\"";
					$field['input']="<textarea name=\"$fieldid\" id=\"$fieldid\" $sizelimit rows='8'>$defaultvalue</textarea> $selectValue $inputlabel";
					break;
				case 'radio':
					$defaultvalue = explode('|',$defaultvalue);
					$i=0;
					$str='';
					$inputlabel = explode('|',$inputlabel);
					foreach ($defaultvalue as $value){
						$selected = $i==0 ? "checked" : '';
						$str.="<input type=\"radio\" value=\"$value\" name=\"$fieldid\" $selected /> $inputlabel[$i]";
						$i++;
					}
					$field['input']=$str;
					break;
				case 'checkbox':
					$defaultvalue = explode('|',$defaultvalue);
					$inputlabel = explode('|',$inputlabel);
					$i=0;
					$str='';
					foreach ($defaultvalue as $value){
						//$selected=$i==0 ? "checked" : '';
						$str.="<input type=\"checkbox\" value=\"$value\" name=\"{$fieldid}[]\" $selected> $inputlabel[$i] ";
						$i++;
					}
					$field['input']=$str;
					break;
				case 'select':
					$defaultvalue = explode('|',$defaultvalue);
					$inputlabel = explode('|',$inputlabel);
					$str="<select  name=\"$fieldid\">";
					$i=0;
					foreach ($defaultvalue as $value){
						$str.="<option value=\"$value\">$inputlabel[$i]</option>";
						$i++;
					}
					$str.="</select>";
					$field['input']=$str;
					break;
				case 'mselect':
					$str="<select multiple size=8 name=\"$fieldid\" id=\"$fieldid\">";
					$str.="</select><input type=hidden name=\"".$fieldid."_value\" id=\"".$fieldid."_value\" value=''> $selectValue <br /><img  onclick=del('$fieldid') src='images/admin/del.gif'><img onclick=moveUp('$fieldid') src='images/admin/up.png'><img onclick=moveDown('$fieldid') src='images/admin/down.png'>";
					$field['input']=$str;
					break;
				case 'edit':
					if($type=='custom') {
						$tooltype = 'CBasic';
					}else {
						$tooltype = 'Default';
					}
					$str = Mylib::Editor($fieldid,$tooltype);
					$str.= "<br />".$selectValue;
					$field['input']=$str;
					break;
				case 'basic':
					if($type=='custom') {
						$tooltype = 'CBasic';
					}else {
						$tooltype = 'Basic';
					}
					$str = Mylib::Editor($fieldid,$tooltype);
					$str.= $selectValue;
					$field['input']=$str;
					break;
			}
			$inputArea[]=$field;

			//$inputArea['id']= 0;
			//$inputArea['pid']= $pid;
		}
		return $inputArea;
	}
	/**
	 * 编辑表单
	 *
	 * @param integer $tid
	 * @return array
	 */
	function getEditArea($t){
		$menuid=getgp("menuid");		 
		$inputArea = array();
		include(Mylib::getDatafile("cache_edit_fields_".$menuid.".php"));
		//include(MdlPATH."/Public/data/cache_edit_fields_".$menuid.".php");
		$fields=$list;
		//dump(Mylib::getDatafile("cache_edit_fields_".$menuid.".php"));
		 
		foreach ($fields as $field){
			@extract($field);
			if($type=='custom' && !$ifcontribute) {
				continue;
			}
			$fieldvalue=$t[$fieldid];
			$selectValue = Mylib::selectValue($fieldid,$getvalue,$inputtype);
			switch ($inputtype){
				case 'input':					 
					$otherarr = $arr ? $arr : '';
					$field['input']="<input class=\"textfield\" style=\"width:$width;height:$height;float:left\"  name=\"$fieldid\" type=\"text\" id=\"$fieldid\" value=\"$fieldvalue\" $otherarr > $selectValue <label class=\"tips\" id='".$fieldid."Tip' for=\"$fieldid\" >$inputlabel</label>";
					break;
			    case 'hidden':
				    $otherarr = $arr ? $arr : '';
					$field['input']="<input class=\"textfield\" style=\"width:$width;height:$height\"  name=\"$fieldid\" type=\"hidden\" id=\"$fieldid\" value=\"$fieldvalue\" $otherarr > $selectValue <label class=\"tips\" id='".$fieldid."Tip' for=\"$fieldid\" >$inputlabel</label>";	
					break;
				 
				case 'textarea':
					$fieldvalue = stripslashes($fieldvalue);
					$sizelimit = $inputsize>60 ? "cols=\"$inputsize\"" : "cols=\"60\"";
					$field['input']="<textarea name=\"$fieldid\" id=\"$fieldid\" $sizelimit style=\"width:$width;height:$height;float:left\">$fieldvalue</textarea> $inputlabel";
					break;
				case 'radio':
					$defaultvalue = explode('|',$defaultvalue);
					$inputlabel = explode('|',$inputlabel);
					$str='';
					$i=0;
					foreach ($defaultvalue as $value){
						$selected = $fieldvalue==$value ? 'checked' : '';
						$str.="<input type=\"radio\" value=\"$value\" name=\"$fieldid\" $selected> $inputlabel[$i]";
						$i++;
					}
					$field['input']=$str;
					break;
				case 'checkbox':
					$defaultvalue = explode('|',$defaultvalue);
					$inputlabel = explode('|',$inputlabel);
					$fieldvalue = explode(',',$fieldvalue);
					$str='';
					$i=0;
					foreach ($defaultvalue as $value){
						$selected = in_array($value,$fieldvalue) ? 'checked' : '';
						$str.="<input type=\"checkbox\" value=\"$value\" name=\"{$fieldid}[]\" $selected> $inputlabel[$i] ";
						$i++;
					}
					$field['input']=$str;
					break;
				case 'select':
					$defaultvalue = explode('|',$defaultvalue);
					$inputlabel = explode('|',$inputlabel);
					$str="<select  name=\"$fieldid\">";
					$i=0;
					foreach ($defaultvalue as $value){
						$str.="<option value=\"$value\">$inputlabel[$i]</option>";
						$i++;
					}
					$str.="</select>";
					$str = str_replace("value=\"$fieldvalue\"","value=\"$fieldvalue\" selected",$str);
					$field['input']=$str;
					break;
				case 'mselect':
					/*
					$str="<select multiple size=8 name=\"$fieldid\" id=\"$fieldid\">";
					if($t[$fieldid] && preg_match('/^(\d+\,)*\d+$/',$t[$fieldid])) {
						$rt = D()->query("SELECT tid,title,url,cid FROM jp_contentindex WHERE tid IN($t[$fieldid])");
						$option = $fieldtids = array();
						while($contentlink = Mylib::mysql->fetch_array($rt)) {
							$option[$contentlink[tid]] = "<option value=\"$contentlink[tid]\">$contentlink[title]</option>";
						}
						$fieldtids = explode(",",$t[$fieldid]);
						foreach($fieldtids as $val) {
							if(!$val) continue;
							if($option[$val]) {
								$str.=$option[$val];
							}
						}
					}
					$str.="</select><input type=hidden name=\"".$fieldid."_value\" id=\"".$fieldid."_value\" value=''> $selectValue <br /><img  onclick=del('$fieldid') src='images/del.gif'><img onclick=moveUp('$fieldid') src='images/up.png'><img onclick=moveDown('$fieldid') src='images/down.png'>";
					$field['input']=$str;*/
					break;
				case 'edit':
					$fieldvalue = stripslashes($fieldvalue);
					if($type=='custom') {
						$tooltype = 'CBasic';
					}else {
						$tooltype = 'Default';
					}
					$str = Mylib::Editor($fieldid,$tooltype,$fieldvalue);
					$field['input']=$str;
					break;
				case 'basic':
					$fieldvalue = stripslashes($fieldvalue);
					if($type=='custom') {
						$tooltype = 'CBasic';
					}else {
						$tooltype = 'Basic';
					}
					$str = Mylib::Editor($fieldid,$tooltype,$fieldvalue);
					$field['input']=$str;
					break;
				case 'selor':
					$sizelimit = $inputsize ? "size=\"$field[inputsize]\"" : '';
					$field['input']="<input class=\"input\" name=\"$fieldid\" id=\"$fieldid\" value=\"$fieldvalue\" $sizelimit > <div id=fdiv$fieldid ></div><script>domsel(\"$fieldid\",$getsel)</script> $inputlabel";
					break;
			}
			$inputArea[]=$field;
		}
		//$inputArea['template']	= $t['template'];
		//$inputArea['digest']	= $t['digest'];
		//$inputArea['linkurl']	= $t['linkurl'];
		//$inputArea['photo']		= $t['photo'];
		//$inputArea['template']	= $t['template'];
		//$inputArea['titlestyle']= $t['titlestyle'];
		//$inputArea['id']= $t['id'];
		//$inputArea['pid']= $pid;
		//$inputArea['postdate']	= get_date($t['postdate'],'Y-m-d H:i:s');
		return $inputArea;
	}

	function getForm($formlist){
		 // $formlist=Mylib::getEditArea($t);
		  $form=array();
		//  dump($formlist);
		  foreach($formlist as $k=>$v){
			 
			 $form[$v['fieldid']]=$v;
		  }
		 // dump($form);
		  return $form;
	}
	/**
	 * FCK编辑器生成
	 *
	 * @param string $inputname
	 * @param string $Value
	 * @return string
	 */
	function Editor($inputname,$tooltype,$Value='')
	{
		global $aiqi;
		if($tooltype=='Default')
		{
			$height=420;
			$width=650;
		}
		else
		{
			$height=150;
			$width=450;
		}
	    LC("@.Lib.Fckeditor");
		//require_once(Mandala_PATH.'/Class/fckeditor.php');
		$edit = new Fckeditor($inputname);
		$edit->Height = $height;
		$edit->Width = $width;
		$edit->ToolbarSet = $tooltype;
		//$edit->BasePath = 'require/';
		$edit->Value = $Value;
		$edit->BaseSrc = APP_PUBLIC_URL."/Js/FCKeditor/editor/";
		$edit->BaseName = '';
		$Html = $edit->CreateHtml();
		if($tooltype=='Default')
		{
			//$Html.="<input type=\"checkbox\" name=\"imagetolocal\" value=1 /> 外部图片本地化 <br />";
			//$Html.="<input type=\"checkbox\" name=\"selectimage\" value=1 /> 自动提取第一张图片为新闻图片<br />";
			//$Html.="<input type=\"checkbox\" name=\"autofpage\" checked value=1 /> 自动分页处理";
		}
		//$basedir = "editor";
		/*		if($this->IsCompatible()){
		$url = urlencode($basename);
		$Html.="<iframe src=\"$basename&action=wysiswyg&inputname=$inputname&basename=$url\" height=\"$height\" width=\"$width\" frameborder=\"0\" scrolling=\"no\"></iframe>";
		$Value = ltrim($Value);
		$Value = htmlspecialchars($Value);
		$Html.= "<input type=\"hidden\" name=\"$inputname\" id=\"$inputname\" value=\"$Value\">";
		}else{
		$Html = "<textarea name=\"{$inputname}\" rows=\"4\" cols=\"40\" style=\"width: {$width}px; height: {$height}px;\">{$Value}</textarea>" ;
		}*/
		return $Html;
	}
 //系统日志;
  public function logs($sql='',$content=''){
    $vo[username]=$this->visitor->get('username');
	$vo[ip]=real_ip();
	//$vo[content]=$content;
	$vo[sql]=$sql;
	$Dao = D("Syslog");
	$rs = $Dao->add($vo);
	//echo $Dao->getlastsql();
	//$success = "添加数据成功!";
  
  }
  //ajax 方式,成功返回记录;
  public function success($content, $message='', $append=array()){
    json_response($content, 0, $message, $append);
  }
 //ajax 方式,失败反回错误;
  public function error($msg){
		json_response('', 1, $msg);
  }

  public  function _build_editor($params = array()){
        $name = isset($params['name']) ?  $params['name'] : null;
        $theme = isset($params['theme']) ?  $params['theme'] : 'normal';

        /* 指定哪个(些)textarea需要编辑器 */
        if ($name === null)
        {
            $mode = 'mode:"textareas",';
        }
        else
        {
            $mode = 'mode:"exact",elements:"' . $name . '",';
        }

        /* 指定使用哪种主题 */
        $themes = array(
            'normal'    =>  'plugins:"inlinepopups,preview,fullscreen,paste",
            theme:"advanced",
            theme_advanced_buttons1:"code,fullscreen,preview,removeformat,|,bold,italic,underline,strikethrough,|," +
                "formatselect,fontsizeselect,|,forecolor,backcolor",
            theme_advanced_buttons2:"bullist,numlist,|,outdent,indent,blockquote,|,justifyleft,justifycenter," +
                "justifyright,justifyfull,|,link,unlink,charmap,image,|,pastetext,pasteword,|,undo,redo",
            theme_advanced_buttons3 : "",',
            'simple'    =>  'theme:"simple",',
        );
        switch ($theme)
        {
            case 'simple':
                $theme_config = $themes['simple'];
            break;
            case 'normal':
                $theme_config = $themes['normal'];
            break;
            default:
                $theme_config = $themes['normal'];
            break;
        }
        /* 配置界面语言 */
        $_lang = substr(LANG, 0, 2);
        switch ($_lang)
        {
            case 'sc':
                $lang = 'zh_cn';
            break;
            case 'tc':
                $lang = 'zh';
            break;
            case 'en':
                $lang = 'en';
            break;
            default:
                $lang = 'zh_cn';
            break;
        }

        /* 输出 */
        $str = <<<EOT
<script type="text/javascript" src="./Public/Js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    setTimeout(function(){
		tinyMCE.init({
        {$mode}
        {$theme_config}
        language:"{$lang}",
        relative_urls : false,
        remove_script_host : false,
        theme_advanced_toolbar_location:"top",
        theme_advanced_toolbar_align:"left"
		},5000)
});
</script>
EOT;

        return $str;
    }


    function _build_upload($params = array()){
		 
        $goodsid = isset($params['goodsid']) ? $params['goodsid'] : 0; //上传文件所属模型
      //  $item_id = isset($params['item_id']) ? $params['item_id']: 0; //所属模型的ID
        $file_size_limit = isset($params['file_size_limit']) ? $params['file_size_limit']: '200 MB'; //默认最大2M
        $button_text = '点此选择图片';//isset($params['button_text']) ? Lang::get($params['button_text']) : sprintf(Lang::get('button_text'), $file_size_limit); //上传按钮文本
        $image_file_type = isset($params['image_file_type']) ? $params['image_file_type'] :"gif|jpg|jpeg|png|bmp";
        $upload_url = U('Swfupload/index');

        /* 允许类型 */
        $file_types = '';
        $image_file_type = explode('|', $image_file_type);
        foreach ($image_file_type as $type)
        {
            $file_types .=  '*.' . $type . ';';
        }
        $file_types = trim($file_types, ';');
		$session_id=$_SESSION['admin_id'];
		 
		$PUBLIC=APP_PUBLIC_URL;
        $str = <<<EOT
<link type="text/css" rel="stylesheet" href="{$PUBLIC}/Js/swfupload/css/default.css"/>
<script type="text/javascript" charset="utf-8" src="{$PUBLIC}/Js/swfupload/swfupload.js"></script>
<script type="text/javascript" charset="utf-8" src="{$PUBLIC}/Js/swfupload/js/handlers.js"></script>
<script type="text/javascript">
var swfu;
$2(function(){
    swfu = new SWFUpload({
        upload_url:"{$upload_url}",
        flash_url: "{$PUBLIC}/Js/swfupload/swfupload.swf",
        post_params: {
            "MDL_ID": "{$session_id}",
            "HTTP_USER_AGENT":"{$_SERVER['HTTP_USER_AGENT']}",
            'goods_id': {$goodsid},            
            'ajax': 1
        },
        file_size_limit: "{$file_size_limit}",
        file_types: "{$file_types}",
        custom_settings: {
            upload_target: "divFileProgressContainer"
        },
		debug:false,

        // Button Settings
        button_image_url:"{$PUBLIC}/Js/swfupload/images/SmallSpyGlassWithTransperancy_17x18.png",
        button_width: 170,
        button_height: 18,
        button_text: '<span class="button">{$button_text}</span>',
        button_text_style: '.button { font-family: Helvetica, Arial, sans-serif; font-size: 12pt; } .buttonSmall { font-size: 10pt; }',
        button_text_top_padding: 0,
        button_text_left_padding: 18,
        button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
        button_cursor: SWFUpload.CURSOR.HAND,

        // The event handler functions are defined in handlers.js
        file_queue_error_handler: fileQueueError,
        file_dialog_complete_handler: fileDialogComplete,
        upload_progress_handler: uploadProgress,
        upload_error_handler: uploadError,
        upload_success_handler: uploadSuccess,
        upload_complete_handler: uploadComplete,
        button_placeholder_id: "spanButtonPlaceholder"
    });
});
</script>
EOT;
         return $str;
    }
	//处理图片;
	function img_process($res){
		//LC('@.Help.function_image');
		
		LC('@.Lib.Images');
		$img=new Images();
		$file= end(explode('/',$res)); 
 //dump(G('shopconf'));
		//$file2=G('shopconf')->img_path."Public/product/".$file;
		//列表页图片;
		$file2=str_replace('Upload/Product/Thumb','product',G('shopconf')->thumb_dir).$file;
        //关联
		$file3=str_replace('Upload/Product/Thumb','prolink',G('shopconf')->thumb_dir).$file;

       //列表页小图
		$file4=str_replace('Thumb','Small',G('shopconf')->thumb_dir).$file;
	 
		//$rs=makethumb(G('shopconf')->img_path.$res,$file2.$file,340,408,400,400);
		//列表页
		$img->make_thumb(G('shopconf')->img_path.$res,G('shopconf')->thumb_dir.$file,160,200);
        //大图;
		$img->make_thumb(G('shopconf')->img_path.$res,$file2,340,408);
		//推荐，或者关联;
		$img->make_thumb(G('shopconf')->img_path.$res,$file3,100,125);

 		$img->make_thumb(G('shopconf')->img_path.$res,$file4,100,100);
		//$img->make_thumb(G('shopconf')->img_path.$res,$file4,340,408);
	 
 // echo $file3,
	// dump(G('shopconf')->thumb_dir.$file);
 
		//makethumb(G('shopconf')->img_path.$res,G('shopconf')->thumb_dir.$file);

		//makethumb(G('shopconf')->img_path.$res,G('shopconf')->thumb_dir.$file);

		//dump(G('shopconf')->img_path );
		$f['thumbimg']=str_replace(ROOTPATH,'', G('shopconf')->thumb_dir.$file);		
		$f['bigimg']=str_replace(ROOTPATH,'',$newfile);
		 
		return $f;

		 
	}


	 /*生成简单的数据列表*/
    public function GetSimpleList($fields,$list){			
		$fwidth="[";
		foreach($fields as $k=>$v){
			$fwidth.=$v[width].",";
		}	
		$fwidth.="]";
		$url=Mylib::geturl('','');		
	    $maindata=Mylib::getDatalist($fields,$list,$url ,''); 		
		$a = array();
		$a["fwidth"]=$fwidth;	
		$a["maindata"]=$maindata;

	    return $a;	
	} 

	// 清除缓存目录
    function clearCache($type=0,$path=NULL) {
        if(is_null($path)) {
            switch($type) {
            case 0:// 缓存目录
                $path = C('APPCACHE');
                break;
            case 1:// 视图缓存目录
                $path   =   C('APPTPLCACHE');
                break;
            case 2://  日志目录
                $path   =  C('APPLOGS');
                break;
            case 3://  数据目录
                $path   =   DATA_PATH;
            }
        }
        LC("@.Lib.Dir");
        Dir::del($path);
    }

	function url_select($name, $ext = 'htm', $type = 'list', $urlid = 0, $extend = '') {
 
		 require_once( APPPATH."/Help/Urlrule.php");
		 
		$select = '<select name="'.$name.'" '.$extend.'>';
		$types = count($urls[$ext][$type]);
		for($i = 0; $i < $types; $i++) {
			$select .= ' <option value="'.$i.'"'.($i == $urlid ? ' selected' : '').'>例 '.$urls[$ext][$type][$i]['example'].'</option>';
		}
		$select .= '</select>';
		
		return $select;
	}
    function create_select_opt($data,$selected){	
		$s='';
		foreach($data as $k=>$v){
			  if($k==$selected){
				 $s.='<option value='.$k.' selected>'.$v.'</option>';
			  }else{
				 $s.='<option value='.$k.' selected>'.$v.'</option>';
			  }
		}
		return $s;
	}


	 /**
     * 取得当前的域名
     *
     * @access  public
     *
     * @return  string      当前的域名
     */
    function get_domain()
    {
        /* 协议 */
        $protocol = Mylib::http();

        /* 域名或IP地址 */
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
        {
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
        }
        elseif (isset($_SERVER['HTTP_HOST']))
        {
            $host = $_SERVER['HTTP_HOST'];
        }
        else
        {
            /* 端口 */
            if (isset($_SERVER['SERVER_PORT']))
            {
                $port = ':' . $_SERVER['SERVER_PORT'];

                if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol))
                {
                    $port = '';
                }
            }
            else
            {
                $port = '';
            }

            if (isset($_SERVER['SERVER_NAME']))
            {
                $host = $_SERVER['SERVER_NAME'] . $port;
            }
            elseif (isset($_SERVER['SERVER_ADDR']))
            {
                $host = $_SERVER['SERVER_ADDR'] . $port;
            }
        }

        return $protocol . $host;
    }

    /**
     * 获得 ECSHOP 当前环境的 URL 地址
     *
     * @access  public
     *
     * @return  void
     */
    function url()
    {
        $curr = strpos(PHP_SELF, G('homepage').'/') !== false ?
                preg_replace('/(.*)('.G('homepage').')(\/?)(.)*/i', '\1', dirname(PHP_SELF)) :
                dirname(PHP_SELF);

        $root = str_replace('\\', '/', $curr);

        if (substr($root, -1) != '/')
        {
            $root .= '/';
        }

        return Mylib::get_domain() . $root;
    }
	 /**
     * 获得 ECSHOP 当前环境的 HTTP 协议方式
     *
     * @access  public
     *
     * @return  void
     */
    function http()
    {
        return (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';
    }



}