<? if(!defined('IN_GENV')) exit('Access Denied');?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"> 

<style type="text/css" media="screen"> 
 
@import '<?=$_public?>style/skin.css';

@import '<?=$_public?>style/layout.css';
@import '<?=$_public?>style/member.css';
@import '<?=$_public?>style/mycss.css';
.logo h2 {
color:white;
font: bold 25px Bookman Old Style;
text-decoration: none;
margin:1px 0 0 2px;
position:relative;
text-transform:uppercase;
}
.logo h2 em {
color:black;
font: bold 25px Bookman Old Style;
text-decoration: none;
left:-2px;
position:absolute;
top:-2px;

}
#notic{
color:red;

}
</style><link rel="shortcut icon" href="favicon.ico">
<script src="<?=$_public?>script/jquery.js" type="text/javascript"></script>
<script src="<?=$_public?>script/genv.js" type="text/javascript"></script>
<script src="<?=$_public?>script/genv.gui.js" type="text/javascript"></script>
<script type="text/javascript">
<!--
	var defaultsrc="<? echo U('main')?>";
//-->
</script>
<script src="<?=$_public?>script/genv.index.js" type="text/javascript"></script>
<script src="<?=$_public?>script/json2.js" type="text/javascript"></script>
<script src="<?=$_public?>script/common.js" type="text/javascript"></script>
<script src="<?=$_public?>script/miniyui.js" type="text/javascript"></script>

<SCRIPT LANGUAGE="JavaScript">
<!--
var LinksConfig={
<? foreach((array)$menus_list as $key => $t) {?>	 	 
<? foreach((array)$t['menu'] as $key1 => $sub_menus) {?>	
Moudle_<?=$sub_menus['id']?>:{ url:"<?=$sub_menus['href']?>",group:"<?=$key1?>",title:"<?=$sub_menus['menuname']?>"},
<?}?>	
<?}?>   
nolink:{url:"",site:""}	
}
<?=$pageinfo?>	
//-->
</SCRIPT>
 
<body class="bodybg" id="doc">

<!--  头部 -->
	<div class="headbg">

	<div class="headbgleft"></div>
         <div id="dbkhead" class="head">

            <span class="logo"  ><h2>管理平台<em>管理平台</em></h2></span>
            <div class="headside">
                <h1 id="sayHiUserName" style="">你好，<strong id="dbknickname"><?=$username?></strong></h1>
                <!--span id="clientDownloadsoftLink" style="display:none" class="uploadclienticon"><em></em><a href="http://st4.dbank.com/client/MiniDbankSetUp.exe" target="_blank">下载客户端</a></span-->
                <p>
                <span id="sayUserLver" style="">用户等级：<a onclick="javascript:void(0);" id="curUserProduct" href="javascript:;"><?=$groupname?></a></span>
                <!--span id="sayUserPoint" style="">积分：<a onclick="javascript:void(0);" id="curUserPoints" href="javascript:;">50分</a></span-->

           		</p>
            </div>
            <div class="headtip" style="width: 800px;">
			   <div id=mymessage class=none> <textarea id=debug    style='width:100%;height:40px'></textarea> 
			   </div>
            	<ul>
            	<!--   add by mxd begin -->

	            <li>
            		<div id="upload_complete" style="display: none; margin: 0px; float: left;" onclick="javascript:readyShowUploadWindow();" class="sendsucces">
                    <em class="successicons"></em>上传完成
                    </div>

                </li>
                <li id="topunreadmsg" style="display: none;">
            	<a id="topunreas" style="display: block; float: left; width: 73px;" href="javascript:void(0);" onclick="javascript:clickOnPrivateReciveBox(1);initUnreadMessage();">未读消息<em id="unreadAllMessage"></em></a>|
				</li>
					<!--   add by mxd  end  -->
				 
<li><a onclick="javascript:void(0);" href="javascript:void(0);" id="notic">公司公告</a>|</li>
                    <li><a onclick="javascript:void(0);" href="javascript:void(0);" id="setting">修改密码</a>|</li>

                    
                    <li><a onclick="javascript:void(0);" href="javascript:void(0);" id="menus">隐藏菜单</a>|</li>
                    
                    <li><a href="#" onclick="window.location.href='<? echo U('logout')?>'">退出</a></li>
                </ul>
            </div>
        </div>
		 
	</div>
	<div class="clewinfo"><span id="dbkmsgtip" style="display: none;"></span></div>
	<!--  左边导航 -->
	<div class="leftbg" id="dbkleftbg">
		 <div class="left" id="dbkleft">
			<div class="leftnav">
			
				 

			</div>
				<ul   class="leftsidebottom">
					<? foreach((array)$menus_list as $key => $menus) {?>	
					<li class="hrline"></li>
					<li id="<?=$menus['id']?>"  key='<?=$key?>' class='leftsidetottomnav'><a class="activetitle active" href="javascript:void(0);"><span class="leftsidemyicon"></span><strong><strong><?=$menus['menuname']?></strong></strong></a></li>
					 
					 <?}?>

					<!--pli class="hrline"></li>
					<li id="netDiskOnclickID"><a class="activetitle active" href="javascript:void(0);"><span class="leftsidemyicon"></span><strong><strong>网站管理</strong></strong></a></li>
					<li id="outLinkOnclickID"><a class="activetitle" href="javascript:void(0);"><span class="leftlinkicon"></span><strong><strong>内容管理</strong></strong></a></li>
					<li id="stationOnclickID"><a class="activetitle" href="javascript:void(0);"><span class="leftsideicon"></span><strong><strong>系统管理</strong></strong></a></li-->
				</ul>

		 </div>
	</div>
	<!--  分隔条 -->
	<div id="dbkdrager" class="sidescroll">
		<span></span>
	</div>
	<!--  主要内容部分  -->
	<div  class="contents" style="display: block;" id='contents'>
		 
		<div id="dbkfolderandfiles" tabindex="0" style="outline: medium none; display: block;" class="dbtable" >
		 
		 
			<div id=ok style='margin:3px 3px 3px 10px; '>
			 
			</div>

		 </div>
		<div id="netdisk_status" class="statusbar none">
			<span class="status-size">当前文件夹大小：0MB</span>
			<span class="status-num">共有文件夹5个，文件0个</span>
		</div>

</div>
<? foreach((array)$menus_list as $key => $menus) {?>	 
       <div id="menu_<?=$key?>" class='none'>
		 <div   class="leftsidebg "><h1 class="leftsidetitlebg"><?=$menus['menuname']?></h1></div>
			<div class="leftside ">						 
						<div style="height: 278px;" id="leftsidemain" class="leftsidemain">				
					     <ul   class="leftsidetop">
			 <? foreach((array)$menus['menu'] as $k => $sub_menus) {?>	 	
			 
			 <li id="netDiskOnclickID"><a class="activetitle active" href="javascript:void(0);" onclick="LoadPage('Moudle_<?=$sub_menus['id']?>')"><strong><strong><?=$sub_menus['menuname']?></strong></strong></a></li>
				 		 
			 <?}?>
		 </ul>  							 
		</div>
	</div>
	</div>
 <?}?>
</body>
</html>
