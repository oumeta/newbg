<? if(!defined('IN_GENV')) exit('Access Denied');?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title> </title>
		<style type="text/css" media="screen">		 
		@import '<?=$_public?>grid/slick.grid.css';		 
		@import '<?=$_public?>grid/css/smoothness/jquery-ui-1.8.5.custom.css';	
		@import '<?=$_public?>grid/slick.grid.css';
		@import '<?=$_public?>style/grid.css';
		@import '<?=$_public?>style/button.css';		 
		@import '<?=$_public?>grid/examples/examples.css';
		
		@import url(<?=$_public?>newcss/jquery.flexbox.css);
		 .slick-cell-checkboxsel {
            background: #f0f0f0;
            border-right-color: silver;
            border-right-style: solid;
        }
		.cell-title {
			font-weight: bold;
		}

		.cell-effort-driven {
			text-align: center;
		}
</style>
    
<style type="text/css" media="screen"> 
@import '<?=$_public?>style/layout.css';
@import '<?=$_public?>style/member.css';
@import '<?=$_public?>style/button.css';
@import '<?=$_public?>newcss/date_input.css';
@import '<?=$_public?>newcss/checkform.css';
</style>
</head>
<body> 
<script language="JavaScript" src="<?=$_public?>script/genv.js"></script>
<script language="JavaScript" src="<?=$_public?>script/genv.gui.js"></script>
 <script language="JavaScript" src="<?=$_public?>script/jquery-1.5.min.js"></script>
<script language="JavaScript" src="<?=$_public?>grid/lib/jquery-ui-1.8.5.custom.min.js"></script>
<script language="JavaScript" src="<?=$_public?>grid/lib/jquery.event.drag-2.0.min.js"></script>

<script language="JavaScript" src="<?=$_public?>grid/slick.core.js"></script>
<script language="JavaScript" src="<?=$_public?>grid/plugins/slick.checkboxselectcolumn.js"></script>
<script language="JavaScript" src="<?=$_public?>grid/plugins/slick.cellselectionmodel.js"></script>
<script src="<?=$_public?>grid/plugins/slick.autotooltips.js"></script>
<script src="<?=$_public?>grid/plugins/slick.cellrangedecorator.js"></script>
<script src="<?=$_public?>grid/plugins/slick.cellrangeselector.js"></script>
<script src="<?=$_public?>grid/plugins/slick.cellcopymanager.js"></script>
<script src="<?=$_public?>grid/plugins/slick.cellselectionmodel.js"></script>
<script src="<?=$_public?>grid/plugins/slick.rowselectionmodel.js"></script>
 

<script language="JavaScript" src="<?=$_public?>grid/slick.grid.js"></script>
<script language="JavaScript" src="<?=$_public?>script/grid.js"></script>
<script language="JavaScript" src="<?=$_public?>newjs/jquery.date_input.js"></script>
<script src="<?=$_public?>newjs/jquery.flexbox.js" type="text/javascript"></script>

<script src="<?=$_public?>newjs/checkform.js" type="text/javascript"></script>
 
<SCRIPT LANGUAGE="JavaScript">
 <!--
var billstatus=[];
billstatus['addbill']=0;
billstatus['action']=1;
billstatus['chektank']=2;
billstatus['fangxing']=3;
billstatus['lock']=4;
billstatus['unlock']=5;


<?=$pageinfo?>	

//-->
 </SCRIPT>
 <textarea class=none id=mysql>
<?=$sql?>
 </textarea>
 <script language="JavaScript" src="<?=$_public?>grid/slick.editors.js"></script>

 <script type="text/javascript">
 $($.date_input.initialize);
 </script>

<div id="alertBackground" class="alertBackground"></div>
<div id="dialogBackground" class="dialogBackground"></div>

<div id='background' class='background'></div>
<div id='progressBar' class='progressBar'>数据加载中，请稍等...</div>

