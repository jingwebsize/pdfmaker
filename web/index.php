<?php
require_once('../fpdf/fpdf.php');
require_once('../fpdi/src/autoload.php');
use setasign\Fpdi\Fpdi;

$pdf = new Fpdi();
// $file = 'Upload/all_to_path/report_file/FA065307C8FF0236FC2002DAA098D439.pdf';
// $filename ='flask-tutorial-2.0(1).pdf';
//http://pdfmaker.test/web/index.php?file=flask-tutorial-2.0.pdf&key=123456
$filename = $_GET['file'];
$file = 'files/'.$filename;
//获取页数
$pageCount = $pdf->setSourceFile($file);
//遍历所有页面
// $user= 'jingye';
// $string = '289500030'.'         '.'2112231';
$searchfor = $_GET['key'];
// $searchfor ='2345678';
$contents = file_get_contents('../userfile.txt');

// escape special characters in the query
$pattern = preg_quote($searchfor, '/');

// finalise the regular expression, matching the whole line
$pattern = "/^.*$pattern.*\$/m";

// search, and store all matching occurences in $matches
if(preg_match_all($pattern, $contents, $matches)){
// echo "Found matches:\n";
// echo implode("\n", $matches[0]);
    $string = implode("\n", $matches[0]);
}else{
    echo "无权限";
    exit;
}

for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++){
    //导入页面
    $templateId = $pdf->importPage($pageNo);
    //获取导入页面的大小
    $size = $pdf->getTemplateSize($templateId);
    //创建页面（横向或纵向取决于导入的页面大小）
    if ($size['width'] > $size['height']){
        $pdf->AddPage('L', array($size['width'], $size['height']));
    }else {
        $pdf->AddPage('P', array($size['width'], $size['height']));
    }
    //使用导入的页面
    $pdf->useTemplate($templateId);
        $pdf->SetFont('helvetica','','12');
        $pdf->SetTextColor(210,210,210);
        
        $center_x = rand(0,$size['width']/2);
        $center_y = rand(0,$size['height']/2);
        $d = $size['width']/10*rand(1,4);
        for($i=0;$i<2;$i++){
            $pdf->SetXY($center_x+$d, $center_y+$d*$i);
            $pdf->Write(5,$string);
        }
   }
    //I输出output，D下载download，F保存file_put_contents，S返回return
    $pdfdata = $pdf->Output('I',$filename,false);
    include('viewer.html')
?>