<?php
class App_pdf extends App_PDFAutoPrint{
    function __construct()
    {
        parent::FPDF();
    }
 
    // Page header
    function Header()
    {
        //echo $_SERVER['DOCUMENT_ROOT'].'/images/config/'.$_SESSION['logo'];
        // Logo
        $this->Image($_SERVER['DOCUMENT_ROOT'].'/images/config/'.$_SESSION['logo'],10,6,25);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        $this->SetTextColor(25,25,25);
        
        if($_SESSION['relatorio']=="FORMAÇÃO"):
            $date = str_replace('/', '-', $_SESSION['date']);
            $this->Cell(20);            
            $this->Cell(150,7,utf8_decode($_SESSION['title']),0,0,'C');
            $this->SetFont('Arial','',10);
            $this->MultiCell(20, 6, "ref.".$_SESSION['ref']."\n".date('d/m/Y', strtotime($date)), 0,'R');
      
        endif;
        
        if($_SESSION['relatorio']=="CLIENTE"):
            $this->SetFont('Arial','B',15);
            $this->SetTextColor(6,101,153);
            
            $this->Cell(90);            
            $this->Cell(295,7,utf8_decode('RELATÓRIO - '.$_SESSION['relatorio']),0,0,'C');     
       
            $this->Ln(12);
            
            $this->SetFont('Arial', 'B', 7);
            $this->SetFillColor(6,101,153);
            $this->SetTextColor(250,250,250);
            $this->Cell(10, 7, '-', 1,0,'C',1);
            $this->Cell(60, 7, 'NOME', 1,0,'C',1);
            $this->Cell(40, 7, 'MORADA', 1,0,'C',1);
            $this->Cell(25, 7, 'LOCALIDADE', 1,0,'C',1);        
            $this->Cell(40, 7, 'EMAIL', 1,0,'C',1);
            $this->Cell(30, 7, 'TELEFONE', 1,0,'C',1);
            $this->Cell(35, 7, utf8_decode('ÁREA'), 1,0,'C',1);
            $this->Cell(37, 7, 'CONTACTO', 1,0,'C',1);
        endif;
        $this->Ln();
        
    }
    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'B', 7 );
        $this->SetFillColor(255,255,255);
        $this->SetTextColor(110,110,110);
        // Page number
        if($_SESSION['relatorio'] == "TESTE"):
            $this->Cell(0,10,date("d/m/Y"),0,0,'L');
            $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C',1);
        else:
            $this->Cell(90,10,'FLYBIZZ-CRMClient-',0,0,'L',1,"https://flybizz.net");
            $this->Cell(100,10,utf8_decode(strtoupper('UTILIZADOR: '.$_SESSION['usuario_nome'])).'  -  '.date("d/m/Y H:s").'hs',0,0,'C',1);
            $this->Cell(90,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'R',1);
           
        endif;
        $this->Ln(5);
        //$this->SetY(-15);
        // Arial italic 8
        //$this->SetFont('Arial', 'B', 10 );
        //$this->Cell(0,10,date("d/m/Y"),0,0,'L');
        //$this->Cell(0,10,utf8_decode("USUÁRIO: ".$_SESSION['usuario_nome']),0,0,'R');
    }  
    // function myCell($w,$h,$x,$t){
    //   $height = $h/3;
    //   $first = $height+2;
    //   $second = $height+$height+$height+3;
    //   $len = strlen($t);
    //   if($len>15):
    //     $txt = str_split($t,15);        
    //     $this->SetX($x);
    //     $this->Cell($w,$first,$txt[0],'','','');
    //     $this->SetX($x);
    //     $this->Cell($w,$second,$txt[1],'','','');
    //     $this->SetX($x);
    //     $this->Cell($w,$h,'','LTRB','0','L',0);
    //   else:
    //     $this->SetX($x);
    //     $this->Cell($w,$h,$t,'LTRB','0','L',0);
    //   endif;
    // }   
    // function WordWrap(&$text, $maxwidth)
    // {
    //     $text = trim($text);
    //     if ($text==='')
    //         return 0;
    //     $space = $this->GetStringWidth(' ');
    //     $lines = explode("\n", $text);
    //     $text = '';
    //     $count = 0;
    //     foreach ($lines as $line)
    //     {
    //         $words = preg_split('/ +/', $line);
    //         $width = 0;
    //         foreach ($words as $word)
    //         {
    //             $wordwidth = $this->GetStringWidth($word);
    //             if ($wordwidth > $maxwidth)
    //             {
    //                 // Word is too long, we cut it
    //                 for($i=0; $i<strlen($word); $i++)
    //                 {
    //                     $wordwidth = $this->GetStringWidth(substr($word, $i, 1));
    //                     if($width + $wordwidth <= $maxwidth)
    //                     {
    //                         $width += $wordwidth;
    //                         $text .= substr($word, $i, 1);
    //                     }
    //                     else
    //                     {
    //                         $width = $wordwidth;
    //                         $text = rtrim($text)."\n".substr($word, $i, 1);
    //                         $count++;
    //                     }
    //                 }
    //             }
    //             elseif($width + $wordwidth <= $maxwidth)
    //             {
    //                 $width += $wordwidth + $space;
    //                 $text .= $word.' ';
    //             }
    //             else
    //             {
    //                 $width = $wordwidth + $space;
    //                 $text = rtrim($text)."\n".$word.' ';
    //                 $count++;
    //             }
    //         }
    //         $text = rtrim($text)."\n";
    //         $count++;
    //     }
    //     $text = rtrim($text);
    //     return $count;
    // } 
    public function MultiAlignCell($w,$h,$text,$border=0,$ln=0,$align='L',$fill=false)
    {
        // Store reset values for (x,y) positions
        $x = $this->GetX() + $w;
        $y = $this->GetY();
        // Make a call to FPDF's MultiCell
        $this->MultiCell($w,$h,$text,$border,$align,$fill);
        // Reset the line position to the right, like in Cell
        if( $ln==0 )
        {
            $this->SetXY($x,$y);
        }
    }
    var $widths;
    var $aligns;
    function SetWidths($w)
    {
        //Set the array of column widths
        $this->widths=$w;
    }

    function SetAligns($a)
    {
        //Set the array of column alignments
        $this->aligns=$a;
    }

    function Rowclient($data)
    {
        //Calculate the height of the row
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $h=5*$nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for($i=0;$i<count($data);$i++)
        {          
            $w=$this->widths[$i];
            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x=$this->GetX();
            $y=$this->GetY();
            //Draw the border
            $this->Rect($x,$y,$w,$h,"F");
            //Print the text
            $this->MultiCell($w,5,$data[$i],0,$a);
            //Put the position to the right of the cell
            $this->SetXY($x+$w,$y); 
        }
        //Go to the next line
        $this->Ln($h);
    }

    function Row($data)
    {
        //Calculate the height of the row
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $h=5*$nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for($i=0;$i<count($data);$i++)
        {          
            $w=$this->widths[$i];
            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x=$this->GetX();
            $y=$this->GetY();
            //Draw the border
            $this->Rect($x,$y,$w,$h,"F");
            //Print the text
            $this->MultiCell($w,7,$data[$i],1,$a);
            //Put the position to the right of the cell
            $this->SetXY($x+$w,$y); 
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }
    function NbLines($w,$txt)
    {
        //Computes the number of lines a MultiCell of width w will take
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
        while($i<$nb)
        {
            $c=$s[$i];
            if($c=="\n")
            {
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
            if($l>$wmax)
            {
                if($sep==-1)
                {
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