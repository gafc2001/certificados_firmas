<?php
    use setasign\Fpdi\Fpdi;
    use setasign\Fpdi\PdfParser\PdfParserException;

    $location = [
        '7' => [180,152],
        '8' => [180,129],
        '9' => [180,145],
        '10' => [180,145],
        '11' => [160,158],
        '12' => [200,160],
    ];


    $file = $_GET['file'];
    $code = $_GET['code'];
    require_once('fpdf181/fpdf.php'); 
    require_once('fpdi2/src/autoload.php'); 
    require_once("db/DatabaseConn.php");

    $db= new Connection();
    $result = $db->query("SELECT * FROM codes where sign_code = '$code'");
    $result_code = $result->fetch_assoc();
    $pdf = new FPDI($orientation = "L");

    # Pagina 1
    $pdf->AddPage(); 
    $file = "assets/pdf/$file";
    try{
        $pageCounnt = $pdf->setSourceFile($file); 
    }catch(Exception $e){
        header("Location:index.php?$e->getMessage()");
    }
    $tplIdx = $pdf->importPage(1); 
    $pdf->useTemplate($tplIdx); 
    $x = $location[$result_code['certify_id']][0];
    $y = $location[$result_code['certify_id']][1];
    $pdf->Image('assets/firma.png', 180, 152, 50, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    $pdf->Output('assets/VYWQ_15_12_2020.pdf', 'I'); 
    unlink($file);
?>