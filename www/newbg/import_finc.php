<?php
/*
框架核心;
*/
//框架路径;
define('IN_GENV',true);

define("SYSPATH", str_replace("\\", "/", dirname(__FILE__)));
header('Content-type: text/html;charset=utf-8');
set_time_limit(0);
 
  
require_once "Classes/PHPExcel.php";
require_once "Genv/Function.php";



$uid=$_COOKIE["uid"];

  
$data=S("fincbill_data".$uid);
 
 
$objPHPExcel = new PHPExcel();


$objPHPExcel->getProperties()->setCreator("广州科迪")
							 ->setLastModifiedBy("广州科迪")
							 ->setTitle("财务费用确认")
							 ->setSubject("费用确认")
							 ->setDescription("费用确认")
							 ->setKeywords("费用确认")
							 ->setCategory("费用确认");


 
$objPHPExcel->setActiveSheetIndex(0)        
            ->setCellValue('A2', $data['mycompany']['j_name']);
 $A3="电话：".$data['mycompany']['j_tel']." 传真：".$data['mycompany']['j_fax']."联系人：".$data['mycompany']['j_linker']."　";


$h=3;

$objPHPExcel->getActiveSheet()->setCellValue('B'.$h, '状态');
$objPHPExcel->getActiveSheet()->setCellValue('C'.$h, '业务员');
$objPHPExcel->getActiveSheet()->setCellValue('D'.$h, '操作员');
$objPHPExcel->getActiveSheet()->setCellValue('E'.$h, '客户');
$objPHPExcel->getActiveSheet()->setCellValue('F'.$h, '品名');
$objPHPExcel->getActiveSheet()->setCellValue('G'.$h, '柜号');
$objPHPExcel->getActiveSheet()->setCellValue('H'.$h, '口岸');
$objPHPExcel->getActiveSheet()->setCellValue('I'.$h, '单号');
$objPHPExcel->getActiveSheet()->setCellValue('J'.$h, 'S/O');
$objPHPExcel->getActiveSheet()->setCellValue('K'.$h, '报关过程');
$objPHPExcel->getActiveSheet()->setCellValue('L'.$h, '应收');
$objPHPExcel->getActiveSheet()->setCellValue('M'.$h, '实收');
$objPHPExcel->getActiveSheet()->setCellValue('N'.$h, '应付');
$objPHPExcel->getActiveSheet()->setCellValue('O'.$h, '实付');
$objPHPExcel->getActiveSheet()->setCellValue('P'.$h, '利润');
$objPHPExcel->getActiveSheet()->setCellValue('Q'.$h, '下单日期');
$objPHPExcel->getActiveSheet()->setCellValue('R'.$h, '放行日期');
$objPHPExcel->getActiveSheet()->setCellValue('S'.$h, '最后修改');
$objPHPExcel->getActiveSheet()->setCellValue('T'.$h, '核销');
$objPHPExcel->getActiveSheet()->setCellValue('U'.$h, '报关1');
$objPHPExcel->getActiveSheet()->setCellValue('V'.$h, '报关2');
$objPHPExcel->getActiveSheet()->setCellValue('W'.$h, '托车');


$h=4;
 
foreach($data['list'] as $k=>$v){	 

	$objPHPExcel->getActiveSheet()->setCellValue('B'.$h, $v['status']);
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$h, $v['busname']);
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$h, $v['opratename']);
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$h, $v['j_company']);
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$h, $v['b_product_name']);
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$h, $v['b_tank_code']);
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$h, $v['pc_port']);
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$h, $v['b_code']);
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$h, $v['b_so']);
	$objPHPExcel->getActiveSheet()->setCellValue('K'.$h, $v['ischaguiFormatter']);
	$objPHPExcel->getActiveSheet()->setCellValue('L'.$h, $v['count_shu']);
	$objPHPExcel->getActiveSheet()->setCellValue('M'.$h, $v['shi_shu']);
	$objPHPExcel->getActiveSheet()->setCellValue('N'.$h, $v['count_zhi']);
	$objPHPExcel->getActiveSheet()->setCellValue('O'.$h, $v['shi_zhi']);
	$objPHPExcel->getActiveSheet()->setCellValue('P'.$h, $v['account']);
	$objPHPExcel->getActiveSheet()->setCellValue('Q'.$h, $v['postdate']);
	$objPHPExcel->getActiveSheet()->setCellValue('R'.$h, $v['finshdate']);
	$objPHPExcel->getActiveSheet()->setCellValue('S'.$h, $v['editdate']);
	$objPHPExcel->getActiveSheet()->setCellValue('T'.$h, $v['com1']);
	$objPHPExcel->getActiveSheet()->setCellValue('U'.$h, $v['com2']);
	$objPHPExcel->getActiveSheet()->setCellValue('V'.$h, $v['com3']);
	$objPHPExcel->getActiveSheet()->setCellValue('W'.$h, $v['com4']);	 

  $h++;
}
 

 

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');


 
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="02simple.xls"');
header('Cache-Control: max-age=0');



$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
/*
header('Content-Type: application/pdf');
header('Content-Disposition: attachment;filename="01simple.pdf"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
$objWriter->save('php://output');*/
exit;
