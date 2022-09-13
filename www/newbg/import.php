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


$id=getgpc('id');
 
$data=S('bbbb_'.$id);

//var_dump($data);



$objPHPExcel = new PHPExcel();


$objPHPExcel->getProperties()->setCreator("广州科迪")
							 ->setLastModifiedBy("广州科迪")
							 ->setTitle("对账单")
							 ->setSubject("对账单")
							 ->setDescription("对账单")
							 ->setKeywords("对账单")
							 ->setCategory("对账单");


 
$objPHPExcel->setActiveSheetIndex(0)        
            ->setCellValue('A2', $data['mycompany']['j_name']);
 $A3="电话：".$data['mycompany']['j_tel']." 传真：".$data['mycompany']['j_fax']."联系人：".$data['mycompany']['j_linker']."　";

 // 
$objPHPExcel->getActiveSheet()->setCellValue('A3', $A3);
//$objPHPExcel->getActiveSheet()->setCellValue('A4', " ");
$objPHPExcel->getActiveSheet()->setCellValue('A4', "对账单");

$objPHPExcel->getActiveSheet()->setCellValue('A6', "TO:");
$objPHPExcel->getActiveSheet()->setCellValue('B6', $data['company']['name']);

$objPHPExcel->getActiveSheet()->setCellValue('B7', " 以下是贵公司".$data['d3']."年".$data['d4']."月份的代理出口费对帐单");
 


$objPHPExcel->getActiveSheet()->setCellValue('B8', '接单日期');
$objPHPExcel->getActiveSheet()->setCellValue('C8', '放行日期');
$objPHPExcel->getActiveSheet()->setCellValue('D8', '港口');
$objPHPExcel->getActiveSheet()->setCellValue('E8', '品名');
$objPHPExcel->getActiveSheet()->setCellValue('F8', '柜号/工作号');
$objPHPExcel->getActiveSheet()->setCellValue('G8', 'SO');
$objPHPExcel->getActiveSheet()->setCellValue('H8', '报关费');
 



$h=9;
foreach($data['list'] as $k=>$v){

	$objPHPExcel->getActiveSheet()->setCellValue('B'.$h, $v['postdate']);
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$h, $v['finshdate']);
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$h, $v['pc_port']);
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$h, $v['b_product_name']);
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$h, $v['b_tank_code']);
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$h, $v['b_so']);
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$h, $v['count_shu']);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$h.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	 

  $h++;
}
 

$objPHPExcel->getActiveSheet()->setCellValue('G'.$h, '合计');
$objPHPExcel->getActiveSheet()->setCellValue('H'.$h, $data['countdata']['count_shu']);
$objPHPExcel->getActiveSheet()->getStyle('H'.$h.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);
$objPHPExcel->getActiveSheet()->getStyle('B8:H'.$h)->applyFromArray($styleThinBlackBorderOutline);

 
 $h++;
foreach($data['banklist'] as $k=>$v){
 

	$vv=$v['bankname']."卡号:".$v['bankcode']." ".$v['bankcustomer']." ";
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$h, $vv);
	 $objPHPExcel->getActiveSheet()->mergeCells('A'.$h.':G'.$h);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$h.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$h.'')->getFont()->setSize(12);
	//$objPHPExcel->getActiveSheet()->getStyle('A'.$h.'')->getFont()->setBold(true);

	 

  $h++;
}
$h++;
$vv="你好！谢谢贵公司对我司的信任与支持！请在收到对账单起五天内予以确认审核并回传，否则视我司账单金额为准，并请尽快将此款项汇入我司账户并请提供水单。谢谢！";
$j=$h+2;



$objRichText = new PHPExcel_RichText();
  
$objRichText->createText($vv);

$objPHPExcel->getActiveSheet()->getCell('A'.$h)->setValue($objRichText);


//$objPHPExcel->getActiveSheet()->setCellValue('A'.$h, $vv);
$objPHPExcel->getActiveSheet()->mergeCells('A'.$h.':h'.$j);

//$objPHPExcel->getActiveSheet()->getCell('A18')->setValue($objRichText);

 
$objPHPExcel->getActiveSheet()->getStyle('A'.$h.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
$objPHPExcel->getActiveSheet()->getStyle('A'.$h.'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


$objPHPExcel->getActiveSheet()->getStyle('A'.$h.'')->getFont()->setSize(11);
$objPHPExcel->getActiveSheet()->getStyle('A'.$h.'')->getFont()->setBold(true);




//设置宽度
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
//设置样式
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//合并
$objPHPExcel->getActiveSheet()->mergeCells('A2:H2');
$objPHPExcel->getActiveSheet()->mergeCells('A3:H3');
$objPHPExcel->getActiveSheet()->mergeCells('A4:H4');
$objPHPExcel->getActiveSheet()->mergeCells('A7:H7');


$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setSize(15);
$objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setSize(13);
$objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setSize(13);
$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);


$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setSize(13);

 
$objPHPExcel->getActiveSheet()->getStyle('B8:H8')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID); 
$objPHPExcel->getActiveSheet()->getStyle('B8:H8')->getFill()->getStartColor()->setARGB('FF808080');





$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Paid');
$objDrawing->setDescription('Paid');
$objDrawing->setPath('./Public/paid.png');
$objDrawing->setCoordinates('F1');
 $objDrawing->setOffsetX(0);
 $objDrawing->setRotation(125);
$objDrawing->setHeight(120);
$objDrawing->setWidth(120);
 //$objDrawing->getShadow()->setVisible(true);
//$objDrawing->getShadow()->setDirection(45);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

 
 
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="01simple.xls"');
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
