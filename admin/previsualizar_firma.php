<?php
    session_start();
    use setasign\Fpdi\Fpdi;
    use setasign\Fpdi\PdfParser\PdfParserException;
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    $firma_ruta = $_GET['firma_ruta'];
    $firmaPath = 'assets/firmas/'.$firma_ruta;
    $certificadoId = $_GET["certificado"];
    require_once('../fpdf181/fpdf.php'); 
    require_once('../fpdi2/src/autoload.php'); 
    require_once("../db/DatabaseConn.php");

    $db= new Connection();
    $result = $db->query("SELECT * FROM certificates WHERE id = '$certificadoId'");
    $certificado = $result->fetch_assoc();
    $file = $certificado["path_ejemplo"];
    
    $pdf = new FPDI($orientation = "L");
    # Pagina 1
    $pdf->AddPage(); 
    
    $file = $_SERVER["HTTP_HOST"]."/"."assets/pdf_ejemplo/$file";
    
    try{
        $pageCounnt = $pdf->setSourceFile($file); 
    }catch(Exception $e){
        header("Location:index.php?".$e->getMessage());
    }
    $tplIdx = $pdf->importPage(1); 
    $pdf->useTemplate($tplIdx); 
    $x = $certificado["firma_x"];
    $y = $certificado["firma_y"];
    $pdf->Image($firmaPath, $x, $y, $w=50, $h=0, $type='PNG', $link='');
    $pdf->Output('assets/VYWQ_15_12_2020.pdf', 'I');
?>