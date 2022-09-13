<? if(!defined('IN_GENV')) exit('Access Denied');?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title></title>

		<style type="text/css" media="screen">		 
		 @import '<?=$_public?>css/reset-fonts-grids.css';	 
		 @import '<?=$_public?>menu/image/style.css';
	 
		.none{display:none;}
	
		</style>
	</head>

<script src="<?=$_public?>script/jquery.js" type="text/javascript"></script>
<script src="<?=$_public?>script/common.js" type="text/javascript"></script>
<script src="<?=$_public?>menu/menus.js" type="text/javascript"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
	<?=$pageinfo?>
	var tabs_on='<?=$tabs_on?>';
//-->
</SCRIPT>
<style>
.bd {
	BORDER-RIGHT: #a7c5e2 1px solid; BORDER-TOP: #a7c5e2 1px solid; BORDER-LEFT: #a7c5e2 1px solid; BORDER-BOTTOM: #a7c5e2 1px solid
}
.settingtable {
	BACKGROUND: #eff5fb;
	font-size:13px;
	color:red;
}
</style>
</head>
<body>
 

<div class="menu" onselectstart="return false">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td valign="bottom">
<table cellpadding="0" cellspacing="0">
<tr>
<td width="10">&nbsp;</td>
<?=$menu?>
</tr>
</table>
</td>
<td width="110"><div> </div></td>
</tr>
</table>
</div>