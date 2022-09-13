<?php
/**
 * ���ָ�������µ��ӷ��������
 *
 * @access  public
 * @param   int     $id     �����ID
 * @param   int     $selected   ��ǰѡ�з����ID
 * @param   boolean $re_type    ���ص�����: ֵΪ��ʱ���������б�,���򷵻�����
 * @param   int     $level      �޶����صļ�����Ϊ0ʱ�������м���
 * @param   int     $is_show_all ���Ϊtrue��ʾ���з��࣬���Ϊfalse���ز��ɼ����ࡣ
 * @return  mix
 */
function menu_list($id = 0, $selected = 0, $re_type = true, $level = 0, $is_show_all = true)
{
    static $res = NULL;

    if ($res === NULL)
    {
		 	 
		$file="cat_pid_releate";
		$data=F($file);  
		//dump($data);
        if (!$data){
			$db=D();
			 
            $sql = "SELECT c.id, c.rootid,c.act, c.menuname, c.appid, c.status, c.taxis, c.menu, COUNT(s.id) AS has_children ".
                'FROM ' . gettable('sysmenu') . " AS c ".
                "LEFT JOIN " . gettable('sysmenu') . " AS s ON s.rootid=c.id ".
                "GROUP BY c.id ".
                'ORDER BY c.rootid, c.taxis ASC';
		 
            $res = $db->query($sql);
			// dump($res);
			F($file,$res);
        }
        else
        {
            $res = $data;
        }
    }

    if (empty($res) == true)
    {
        return $re_type ? '' : array();
    }
	 

    $options = menu_options($id, $res); // ���ָ�������µ��ӷ��������
 
    $children_level = 99999; //�����������Ľ���ɾ��
    if ($is_show_all == false)
    {
        foreach ($options as $key => $val)
        {
            if ($val['level'] > $children_level)
            {
                unset($options[$key]);
            }
            else
            {
                if ($val['status'] == 0)
                {
                    unset($options[$key]);
                    if ($children_level > $val['level'])
                    {
                        $children_level = $val['level']; //���һ�£������ӷ���Ҳ��ɾ��
                    }
                }
                else
                {
                    $children_level = 99999; //�ָ���ʼֵ
                }
            }
        }
    }

    /* ��ȡ��ָ������������ */
    if ($level > 0)
    {
        if ($id == 0)
        {
            $end_level = $level;
        }
        else
        {
            $first_item = reset($options); // ��ȡ��һ��Ԫ��
            $end_level  = $first_item['level'] + $level;
        }

        /* ����levelС��end_level�Ĳ��� */
        foreach ($options AS $key => $val)
        {
            if ($val['level'] >= $end_level)
            {
                unset($options[$key]);
            }
        }
    }

    if ($re_type == true)
    {
        $select = '';
        foreach ($options AS $var)
        {
            $select .= '<option value="' . $var['id'] . '" ';
            $select .= ($selected == $var['id']) ? "selected='ture'" : '';
            $select .= '>';
            if ($var['level'] > 0)
            {
                $select .= str_repeat('&nbsp;', $var['level'] * 4);
            }
            $select .= htmlspecialchars(addslashes($var['menuname']), ENT_QUOTES) . '</option>';
        }

        return $select;
    }
    else
    {
        foreach ($options AS $key => $value)
        {
            $options[$key]['url'] ="";// build_uri('category', array('cid' => $value['id']), $value['menuname']);
        }

        return $options;
    }
}

/**
 * ���˺��������з��࣬����һ�������������������
 *
 * @access  private
 * @param   int     $id     �ϼ�����ID
 * @param   array   $arr        �������з��������
 * @param   int     $level      ����
 * @return  void
 */

function menu_options($spec_cat_id, $arr){
    static $menu_options = array();

    if (isset($menu_options[$spec_cat_id]))
    {
        return $menu_options[$spec_cat_id];
    }

    if (!isset($menu_options[0]))
    {
        $level = $last_cat_id = 0;
        $options = $cat_id_array = $level_array = array();
       
		 	 
		$file="cat_option_static";
		$data=F($file);  
		
        if (!$data)
        {
            while (!empty($arr))
            {
                foreach ($arr AS $key => $value)
                {
					 
                    $id = $value['id'];
                    if ($level == 0 && $last_cat_id == 0)
                    {
                        if ($value['rootid'] > 0)
                        {
                            break;
                        }

                        $options[$id]          = $value;
                        $options[$id]['level'] = $level;
                        $options[$id]['id']    = $id;
                        $options[$id]['name']  = $value['menuname'];
                        unset($arr[$key]);

                        if ($value['has_children'] == 0)
                        {
                            continue;
                        }
                        $last_cat_id  = $id;
                        $cat_id_array = array($id);
                        $level_array[$last_cat_id] = ++$level;
                        continue;
                    }

                    if ($value['rootid'] == $last_cat_id)
                    {
                        $options[$id]          = $value;
                        $options[$id]['level'] = $level;
                        $options[$id]['id']    = $id;
                        $options[$id]['name']  = $value['menuname'];
                        unset($arr[$key]);

                        if ($value['has_children'] > 0)
                        {
                            if (end($cat_id_array) != $last_cat_id)
                            {
                                $cat_id_array[] = $last_cat_id;
                            }
                            $last_cat_id    = $id;
                            $cat_id_array[] = $id;
                            $level_array[$last_cat_id] = ++$level;
                        }
                    }
                    elseif ($value['rootid'] > $last_cat_id)
                    {
                        break;
                    }
                }

                $count = count($cat_id_array);
                if ($count > 1)
                {
                    $last_cat_id = array_pop($cat_id_array);
                }
                elseif ($count == 1)
                {
                    if ($last_cat_id != end($cat_id_array))
                    {
                        $last_cat_id = end($cat_id_array);
                    }
                    else
                    {
                        $level = 0;
                        $last_cat_id = 0;
                        $cat_id_array = array();
                        continue;
                    }
                }

                if ($last_cat_id && isset($level_array[$last_cat_id]))
                {
                    $level = $level_array[$last_cat_id];
                }
                else
                {
                    $level = 0;
                }
            }
            //���������󣬲����þ�̬���淽ʽ
            if (count($options) <= 2000)
            {
               // S('cat_option_static', $options);
				F($file,$options);
            }
        }
        else
        {
            $options = $data;
        }
        $menu_options[0] = $options;
    }
    else
    {
        $options = $menu_options[0];
    }

    if (!$spec_cat_id)
    {
        return $options;
    }
    else
    {
        if (empty($options[$spec_cat_id]))
        {
            return array();
        }

        $spec_cat_id_level = $options[$spec_cat_id]['level'];

        foreach ($options AS $key => $value)
        {
            if ($key != $spec_cat_id)
            {
                unset($options[$key]);
            }
            else
            {
                break;
            }
        }

        $spec_cat_id_array = array();
        foreach ($options AS $key => $value)
        {
            if (($spec_cat_id_level == $value['level'] && $value['id'] != $spec_cat_id) ||
                ($spec_cat_id_level > $value['level']))
            {
                break;
            }
            else
            {
                $spec_cat_id_array[$key] = $value;
            }
        }
        $menu_options[$spec_cat_id] = $spec_cat_id_array;

        return $spec_cat_id_array;
    }
}


/*
���з������
*/

function catelist($id = 0, $selected = 0, $re_type = true,$type=1, $level = 0, $is_show_all = true){
    static $res = NULL;

    if ($res === NULL){
		 	 
		$file="catelist";
		//$data=F($file);  
		//dump($data);
        if (!$data){
			$db=D();
			 
            $sql = "SELECT c.id, c.rootid, c.name,c.remark,c.status, c.taxis,c.type, COUNT(s.id) AS has_children ".
                'FROM ' . gettable('cate') . " AS c ".
                "LEFT JOIN " . gettable('cate') . " AS s ON s.rootid=c.id ".
                "GROUP BY c.id ".
				"Having c.`type`=$type ".
                'ORDER BY c.rootid, c.taxis ASC';
		 
            $res = $db->query($sql);
			// dump($res);
			F($file,$res);
        }
        else
        {
            $res = $data;
        }
    }

    if (empty($res) == true)
    {
        return $re_type ? '' : array();
    }
	 

    $options = cateoptions($id, $res); // ���ָ�������µ��ӷ��������
 
    $children_level = 99999; //�����������Ľ���ɾ��
    if ($is_show_all == false)
    {
        foreach ($options as $key => $val)
        {
            if ($val['level'] > $children_level)
            {
                unset($options[$key]);
            }
            else
            {
                if ($val['status'] == 0)
                {
                    unset($options[$key]);
                    if ($children_level > $val['level'])
                    {
                        $children_level = $val['level']; //���һ�£������ӷ���Ҳ��ɾ��
                    }
                }
                else
                {
                    $children_level = 99999; //�ָ���ʼֵ
                }
            }
        }
    }

    /* ��ȡ��ָ������������ */
    if ($level > 0)
    {
        if ($id == 0)
        {
            $end_level = $level;
        }
        else
        {
            $first_item = reset($options); // ��ȡ��һ��Ԫ��
            $end_level  = $first_item['level'] + $level;
        }

        /* ����levelС��end_level�Ĳ��� */
        foreach ($options AS $key => $val)
        {
            if ($val['level'] >= $end_level)
            {
                unset($options[$key]);
            }
        }
    }

    if ($re_type == true)
    {
        $select = '';
        foreach ($options AS $var)
        {
            $select .= '<option value="' . $var['id'] . '" ';
            $select .= ($selected == $var['id']) ? "selected='ture'" : '';
            $select .= '>';
            if ($var['level'] > 0)
            {
                $select .= str_repeat('&nbsp;', $var['level'] * 4);
            }
            $select .= htmlspecialchars(addslashes($var['name']), ENT_QUOTES) . '</option>';
        }

        return $select;
    }
    else
    {
        foreach ($options AS $key => $value)
        {
            $options[$key]['url'] ="";// build_uri('category', array('cid' => $value['id']), $value['menuname']);
        }

        return $options;
    }
}

/**
 * ���˺��������з��࣬����һ�������������������
 *
 * @access  private
 * @param   int     $id     �ϼ�����ID
 * @param   array   $arr        �������з��������
 * @param   int     $level      ����
 * @return  void
 */

function cateoptions($spec_cat_id, $arr){
    static $cat_options = array();

    if (isset($cat_options[$spec_cat_id]))
    {
        return $cat_options[$spec_cat_id];
    }

    if (!isset($cat_options[0]))
    {
        $level = $last_cat_id = 0;
        $options = $cat_id_array = $level_array = array();
       
		 	 
		$file="cate_static";
		$data=F($file);  
		
        if (!$data)
        {
            while (!empty($arr))
            {
                foreach ($arr AS $key => $value)
                {
					 
                    $id = $value['id'];
                    if ($level == 0 && $last_cat_id == 0)
                    {
                        if ($value['rootid'] > 0)
                        {
                            break;
                        }

                        $options[$id]          = $value;
                        $options[$id]['level'] = $level;
                        $options[$id]['id']    = $id;
                        $options[$id]['name']  = $value['name'];
                        unset($arr[$key]);

                        if ($value['has_children'] == 0)
                        {
                            continue;
                        }
                        $last_cat_id  = $id;
                        $cat_id_array = array($id);
                        $level_array[$last_cat_id] = ++$level;
                        continue;
                    }

                    if ($value['rootid'] == $last_cat_id)
                    {
                        $options[$id]          = $value;
                        $options[$id]['level'] = $level;
                        $options[$id]['id']    = $id;
                        $options[$id]['name']  = $value['name'];
                        unset($arr[$key]);

                        if ($value['has_children'] > 0)
                        {
                            if (end($cat_id_array) != $last_cat_id)
                            {
                                $cat_id_array[] = $last_cat_id;
                            }
                            $last_cat_id    = $id;
                            $cat_id_array[] = $id;
                            $level_array[$last_cat_id] = ++$level;
                        }
                    }
                    elseif ($value['rootid'] > $last_cat_id)
                    {
                        break;
                    }
                }

                $count = count($cat_id_array);
                if ($count > 1)
                {
                    $last_cat_id = array_pop($cat_id_array);
                }
                elseif ($count == 1)
                {
                    if ($last_cat_id != end($cat_id_array))
                    {
                        $last_cat_id = end($cat_id_array);
                    }
                    else
                    {
                        $level = 0;
                        $last_cat_id = 0;
                        $cat_id_array = array();
                        continue;
                    }
                }

                if ($last_cat_id && isset($level_array[$last_cat_id]))
                {
                    $level = $level_array[$last_cat_id];
                }
                else
                {
                    $level = 0;
                }
            }
            //���������󣬲����þ�̬���淽ʽ
            if (count($options) <= 2000)
            {
               // S('cat_option_static', $options);
				F($file,$options);
            }
        }
        else
        {
            $options = $data;
        }
        $cat_options[0] = $options;
    }
    else
    {
        $options = $cat_options[0];
    }

    if (!$spec_cat_id)
    {
        return $options;
    }
    else
    {
        if (empty($options[$spec_cat_id]))
        {
            return array();
        }

        $spec_cat_id_level = $options[$spec_cat_id]['level'];

        foreach ($options AS $key => $value)
        {
            if ($key != $spec_cat_id)
            {
                unset($options[$key]);
            }
            else
            {
                break;
            }
        }

        $spec_cat_id_array = array();
        foreach ($options AS $key => $value)
        {
            if (($spec_cat_id_level == $value['level'] && $value['id'] != $spec_cat_id) ||
                ($spec_cat_id_level > $value['level']))
            {
                break;
            }
            else
            {
                $spec_cat_id_array[$key] = $value;
            }
        }
        $cat_options[$spec_cat_id] = $spec_cat_id_array;

        return $spec_cat_id_array;
    }
}

/**
 * �����ϴ�������֤
 * @param $args   ����
 * @param $operation   ��������(���ܽ���)
 */

function upload_key($args, $operation = 'ENCODE') {
	$pc_auth_key = md5(Genv_Config::get('App','auth_key').$_SERVER['HTTP_USER_AGENT']);
	$authkey = sys_auth($args, $operation, $pc_auth_key);
	return $authkey;
}
/**
* �ַ������ܡ����ܺ���
*
*
* @param	string	$txt		�ַ���
* @param	string	$operation	ENCODEΪ���ܣ�DECODEΪ���ܣ���ѡ������Ĭ��ΪENCODE��
* @param	string	$key		��Կ�����֡���ĸ���»���
* @return	string
*/
function sys_auth($txt, $operation = 'ENCODE', $key = '') {
	$key	= $key ? $key : Genv_Config::get('App','auth_key');
	$txt	= $operation == 'ENCODE' ? (string)$txt : base64_decode($txt);
	$len	= strlen($key);
	$code	= '';
	for($i=0; $i<strlen($txt); $i++){
		$k		= $i % $len;
		$code  .= $txt[$i] ^ $key[$k];
	}
	$code = $operation == 'DECODE' ? $code : base64_encode($code);
	return $code;
}


/**
	 * ��ȡվ��������Ϣ
	 * @param  $siteid վ��id
	 */
	function get_site_setting($siteid) {
		$siteinfo = array (
					  1 => 
					  array (
						'siteid' => '1',
						'name' => 'Ĭ��վ��',
						'dirname' => '',
						'domain' => 'http://localhost:88/phpcms/',
						'site_title' => 'PHPCMS��ʾվ',
						'keywords' => 'PHPCMS��ʾվ',
						'description' => 'PHPCMS��ʾվ',
						'release_point' => '',
						'default_style' => 'default',
						'template' => 'default',
						'setting' => 'array (
					  \'upload_maxsize\' => \'2048\',
					  \'upload_allowext\' => \'jpg|jpeg|gif|bmp|png|doc|docx|xls|xlsx|ppt|pptx|pdf|txt|rar|zip|swf\',
					  \'watermark_enable\' => \'1\',
					  \'watermark_minwidth\' => \'300\',
					  \'watermark_minheight\' => \'300\',
					  \'watermark_img\' => \'/statics/images/water/mark.png\',
					  \'watermark_pct\' => \'85\',
					  \'watermark_quality\' => \'80\',
					  \'watermark_pos\' => \'9\',
					)',
						'uuid' => 'fb592a31-6462-102e-83e8-92e8d36b9ccf',
						'url' => 'http://localhost:88/phpcms/',
					  ),
					);
		return string2array($siteinfo[$siteid]['setting']);
	}



/**
 * ���ؾ�stripslashes��������ַ���������
 * @param $string ��Ҫ������ַ���������
 * @return mixed
 */
function new_stripslashes($string) {
	if(!is_array($string)) return stripslashes($string);
	foreach($string as $key => $val) $string[$key] = new_stripslashes($val);
	return $string;
}

	/**
* ���ַ���ת��Ϊ����
*
* @param	string	$data	�ַ���
* @return	array	���������ʽ�������dataΪ�գ��򷵻ؿ�����
*/
function string2array($data) {
	if($data == '') return array();
	eval("\$array = $data;");
	return $array;
}
/**
* ������ת��Ϊ�ַ���
*
* @param	array	$data		����
* @param	bool	$isformdata	���Ϊ0����ʹ��new_stripslashes������ѡ������Ĭ��Ϊ1
* @return	string	�����ַ����������dataΪ�գ��򷵻ؿ�
*/
function array2string($data, $isformdata = 1) {
	if($data == '') return '';
	if($isformdata) $data = new_stripslashes($data);
	return addslashes(var_export($data, TRUE));
}
	/**
	 * ��ȡswfupload��������
	 * @param array $args flash�ϴ�������Ϣ
	 */
	function getswfinit($args) {
		$siteid = 1;
		$site_setting = get_site_setting($siteid);
		$site_allowext = $site_setting['upload_allowext'];
		$args = explode(',',$args);
		$arr['file_upload_limit'] = intval($args[0]) ? intval($args[0]) : '8';
		$args['1'] = ($args[1]!='') ? $args[1] : $site_allowext;
		$arr_allowext = explode('|', $args[1]);
		foreach($arr_allowext as $k=>$v) {
			$v = '*.'.$v;
			$array[$k] = $v;
		}
		$upload_allowext = implode(';', $array);
		$arr['file_types'] = $upload_allowext;
		$arr['file_types_post'] = $args[1];
		$arr['allowupload'] = intval($args[2]);
		$arr['thumb_width'] = intval($args[3]);
		$arr['thumb_height'] = intval($args[4]);
		$arr['watermark_enable'] = ($args[5]=='') ? 1 : intval($args[5]);
		return $arr;
	}	

	/**
* ת���ֽ���Ϊ������λ
*
*
* @param	string	$filesize	�ֽڴ�С
* @return	string	���ش�С
*/
function sizecount($filesize) {
	if ($filesize >= 1073741824) {
		$filesize = round($filesize / 1073741824 * 100) / 100 .' GB';
	} elseif ($filesize >= 1048576) {
		$filesize = round($filesize / 1048576 * 100) / 100 .' MB';
	} elseif($filesize >= 1024) {
		$filesize = round($filesize / 1024 * 100) / 100 . ' KB';
	} else {
		$filesize = $filesize.' Bytes';
	}
	return $filesize;
}

/**
 * ȡ���ļ���չ
 * 
 * @param $filename �ļ���
 * @return ��չ��
 */
function fileext($filename) {
	return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
}
	/**
	 * �ж��Ƿ�ΪͼƬ
	 */
	function is_image($file) {
		$ext_arr = array('jpg','gif','png','bmp','jpeg','tiff');
		$ext = fileext($file);
		return in_array($ext,$ext_arr) ? $ext_arr :false;
	}
	
	/**
	 * �ж��Ƿ�Ϊ��Ƶ
	 */
	function is_video($file) {
		$ext_arr = array('rm','mpg','avi','mpeg','wmv','flv','asf','rmvb');
		$ext = fileext($file);
		return in_array($ext,$ext_arr) ? $ext_arr :false;
	}

function dhtmlspecialchars($string) {
    return is_array($string) ? array_map('dhtmlspecialchars', $string) : htmlspecialchars($string, ENT_QUOTES);
}



function guid(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid =
            substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);

        return $uuid;
    }
}
?>