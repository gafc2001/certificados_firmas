<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
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
    // $result = $db->query("SELECT * FROM codes where sign_code = '$code'");
    $result = $db->query("SELECT c.*,u.firma_profesor,ct.firma_x,ct.firma_y,ct.firma_x_coordinador,ct.firma_y_coordinador FROM codes c 
                        INNER JOIN users_certificados u ON u.id = c.profesor_id
                        INNER JOIN certificates ct ON ct.id = c.certify_id
                        WHERE sign_code = '$code'
                        ");
    $result_code = $result->fetch_assoc();
    $pdf = new FPDI($orientation = "L");

    # Pagina 1
    $pdf->AddPage(); 
    $file = "assets/pdf/$file";
    try{
        $pageCounnt = $pdf->setSourceFile($file); 
        $tplIdx = $pdf->importPage(1); 
        $pdf->useTemplate($tplIdx); 
        $x = $result_code['firma_x'];
        $y = $result_code['firma_y'];
        $y_coordinador = $result_code['firma_y_coordinador'];
        $x_coordinador = $result_code['firma_x_coordinador'];
        $firma = $result_code['firma_profesor'];
        // var_dump($result_code);
        if(!$firma){
            echo "<h1>Parece que el profesor no configuro su firma</h1>";    
            exit;
        }
        $pdf->Image("assets/firmas/{$firma}", $x, $y, $w=50, $h=0, $type='PNG', $link='');
        $pdf->Image("assets/firmas/firma_coordinador.jpg", $x_coordinador,$y_coordinador, $w=40, $h=0, $type='JPG', $link='');
        $pdf->Output('assets/VYWQ_15_12_2020.pdf', 'I'); 
        unlink($file);
    }catch(Exception $e){
        // echo $e->getMessage();
        echo "<h1>Ups, ocurrio un error</h1>";
    }
    
?>