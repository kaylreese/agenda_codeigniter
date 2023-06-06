<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/third_party/fpdf/fpdf.php"; 
 
class Pdf2 extends FPDF { 
	public function __construct() { 
		parent::__construct(); 
	}

    function pdf_header($titulo, $subtitulo, $texto){
        $this->Image('./public/img/logo-ead.png', 10, 8, 35);
        $this->SetFont('Arial', 'B', 12);

        $this->Cell(35, 5,"",0,0,'C');
        $this->Cell(100, 5, utf8_decode(substr("OF. EDUCACIÓN A DISTANCIA",0,35)),0,0,'L');
        $this->Cell(100, 5, utf8_decode("CAMPUS VIRTUAL - UNSM"));
        $this->Ln(8); $this->SetFont('Arial', 'B', 10);
        $this->Cell(35, 5,"",0,0,'C');
        $this->Cell(100, 5, utf8_decode($titulo),0,0,'L');
        $this->Cell(100, 5, utf8_decode($subtitulo.": ".$texto));
        $this->Ln(5); $this->Cell(0,0.05,"",1,1,'L',1); $this->Ln(5);
    }

    function pdf_tabla_head($columnas,$medidas,$size){
        $this->SetFont('Arial','',$size);
        //$this->SetFillColor(20,20,0);
        $this->SetFillColor('227','227','220'); 
        $this->SetDrawColor(10,0,0);
        $this->SetFont('Arial','B');

        for($i=0;$i<count($columnas);$i++){
            $this->Cell($medidas[$i],6,utf8_decode($columnas[$i]),1,0,'C','true');
        }
        $this->Ln();
    }

    function pdf_header_asistencia($institucion,$oficina,$direccion,$lugar,$año){
        $this->Image('./public/img/logo-unsm.png', 10, 8, 20);
        $this->SetFont('Arial', 'B', 12);

        $this->Cell(35, 5,"",0,0,'C');
        $this->Cell(220, 5, utf8_decode(substr($institucion,0,50)),0,0,'C');
        $this->Ln(); $this->SetFont('Arial', 'B', 11);
        $this->Cell(290, 5, utf8_decode(substr($oficina,0,35)),0,0,'C');
        $this->Ln(); $this->SetFont('Arial', 'B', 7);
        $this->Cell(290, 3, utf8_decode(substr($direccion,0,80)),0,0,'C');
        $this->Ln(); $this->SetFont('Arial', 'B', 8);
        $this->Cell(290, 5, utf8_decode($lugar),0,0,'C');
        
        $this->Ln(); $this->SetFont('Arial', 'I', 8);
        $this->Cell(290, 5, utf8_decode('"'.$año.'"'),0,0,'C');
        $this->Ln(5); $this->Cell(0,0.05,"",1,1,'L',1); $this->Ln(2);
    }

	function Header(){
        /*$this->Image('./public/img/logo-ead.png', 10, 8, 35);
        $this->SetFont('Arial', 'B', 12);

        $this->Cell(35, 5,"",0,0,'C');
        $this->Cell(100, 5, utf8_decode(substr("UNIVERSIDAD NACIONAL DE SAN MARTÍN - TARAPOTO",0,35)),0,0,'L');
        $this->Cell(100, 5, utf8_decode("OF. EDUCACIÓN A DISTANCIA"));
        $this->Ln(8); $this->SetFont('Arial', 'B', 10);
        $this->Cell(35, 5,"",0,0,'C');
        $this->Cell(100, 5, utf8_decode($titulo),0,0,'L');
        $this->Cell(100, 5, utf8_decode($subtitulo.": ".$texto));
        $this->Ln(5); $this->Cell(0,0.05,"",1,1,'L',1); $this->Ln(5); */
    }

    function Footer(){
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0,10, utf8_decode("OF. EDUCACIÓN A DISTANCIA").' / PAGINA '.$this->PageNo(),0,0,'C');
    }

    // FPDF TABLE MULTICELL PERSONALIZADO //

    var $widths;
    var $aligns;
    var $lineHeight;

    function SetWidths($w){
        $this->widths = $w;
    }
    function SetAligns($a){
        $this->aligns = $a;
    }
    function SetLineHeight($h){
        $this->lineHeight = $h;
    }

    function Row($data){
        $nb=0;

        for($i=0;$i<count($data);$i++){
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        }
        
        $h=$this->lineHeight * $nb;
        $this->CheckPageBreak($h);

        for($i=0;$i<count($data);$i++){
            $w=$this->widths[$i];
            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';

            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x,$y,$w,$h);

            $this->MultiCell($w,5,$data[$i],0,$a);
            $this->SetXY($x+$w,$y);
        }
        $this->Ln($h);
    }

    function CheckPageBreak($h){
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w,$txt){
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $nl=1;
        while($i<$nb){
            $c=$s[$i];
            if($c=="\n"){
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep=$i;
            $l+=$cw[$c];
            if($l>$wmax){
                if($sep==-1){
                    if($i==$j)
                        $i++;
                }
                else
                    $i=$sep+1;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }
}