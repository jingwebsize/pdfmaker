<?php
require_once('../fpdf/fpdf.php');
require_once('../fpdi/src/autoload.php');
use setasign\Fpdi\Fpdi;

$pdf = new Fpdi();
// $file = 'Upload/all_to_path/report_file/FA065307C8FF0236FC2002DAA098D439.pdf';
// $filename ='flask-tutorial-2.0(1).pdf';
//http://pdfmaker.test/web/index.php?file=flask-tutorial-2.0.pdf&key=123456
$filename = $_GET['pdf'];
$file = 'files/'.$filename;
// $file = 'compressed.tracemonkey-pldi-09.pdf';
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
    //I输出output，D下载download，F保存file_put_contents，S返回return string
    // $pdfdata = $pdf->Output('F',$filename,false);
    // $pdfdata = base64_encode(file_get_contents('compressed.tracemonkey-pldi-09.pdf'));//
    // $pdfdata = base64_encode(file_get_contents('doc.pdf'));
    // $pdfdata = 'JVBERi0xLjcKCjEgMCBvYmogICUgZW50cnkgcG9pbnQKPDwKICAvVHlwZSAvQ2F0YWxvZwog'.
    // 'IC9QYWdlcyAyIDAgUgo+PgplbmRvYmoKCjIgMCBvYmoKPDwKICAvVHlwZSAvUGFnZXMKICAv'.
    // 'TWVkaWFCb3ggWyAwIDAgMjAwIDIwMCBdCiAgL0NvdW50IDEKICAvS2lkcyBbIDMgMCBSIF0K'.
    // 'Pj4KZW5kb2JqCgozIDAgb2JqCjw8CiAgL1R5cGUgL1BhZ2UKICAvUGFyZW50IDIgMCBSCiAg'.
    // 'L1Jlc291cmNlcyA8PAogICAgL0ZvbnQgPDwKICAgICAgL0YxIDQgMCBSIAogICAgPj4KICA+'.
    // 'PgogIC9Db250ZW50cyA1IDAgUgo+PgplbmRvYmoKCjQgMCBvYmoKPDwKICAvVHlwZSAvRm9u'.
    // 'dAogIC9TdWJ0eXBlIC9UeXBlMQogIC9CYXNlRm9udCAvVGltZXMtUm9tYW4KPj4KZW5kb2Jq'.
    // 'Cgo1IDAgb2JqICAlIHBhZ2UgY29udGVudAo8PAogIC9MZW5ndGggNDQKPj4Kc3RyZWFtCkJU'.
    // 'CjcwIDUwIFRECi9GMSAxMiBUZgooSGVsbG8sIHdvcmxkISkgVGoKRVQKZW5kc3RyZWFtCmVu'.
    // 'ZG9iagoKeHJlZgowIDYKMDAwMDAwMDAwMCA2NTUzNSBmIAowMDAwMDAwMDEwIDAwMDAwIG4g'.
    // 'CjAwMDAwMDAwNzkgMDAwMDAgbiAKMDAwMDAwMDE3MyAwMDAwMCBuIAowMDAwMDAwMzAxIDAw'.
    // 'MDAwIG4gCjAwMDAwMDAzODAgMDAwMDAgbiAKdHJhaWxlcgo8PAogIC9TaXplIDYKICAvUm9v'.
    // 'dCAxIDAgUgo+PgpzdGFydHhyZWYKNDkyCiUlRU9G';
    // $data = base64_decode($pdfdata);//转换
    // file_put_contents('test.pdf', $data);//写

    $pdfdata = base64_encode($pdf->Output('S'));
    // $pdfdata = $pdf->Output('S');
    // echo $pdfdata;
    include('viewer.html')
?>