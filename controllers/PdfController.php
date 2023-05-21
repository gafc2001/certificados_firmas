<?php
use setasign\Fpdi\Fpdi;

require_once('../fpdf181/fpdf.php'); 
require_once('../fpdi2/src/autoload.php'); 
class PdfController{
    private FPDI$pdf;
    private $file;
    public function __construct(){
        $this->pdf = new FPDI($orientation = "L");
        $file = $_GET['file'];
    }
    public function importPdf(){
        $this->pdf->AddPage(); 
        $pageCounnt = $this->pdf->setSourceFile("assets/pdf/$this->file"); 
        $tplIdx = $this->pdf->importPage(1); 
        $this->pdf->useTemplate($tplIdx); 
    }
    public function signPdf(){
        $this->pdf->Image('assets/firma.png', 120, 20, 50);
        $this->pdf->Output('assets/VYWQ_15_12_2020.pdf', 'I'); 
    }
    public function execute(){
        $this->importPdf();
        $this->signPdf();
    }
}

$pdf = new PdfController();
$pdf->execute();

    
  

    
?>