<?php
ini_set('memory_limit','500M');

require_once('fpdf.php');

class DEV_PDF extends FPDF{
    var $PDF = null;
    var $columnWidths = array();
    var $headers = array();
    var $multiLineHeaders = false;
    var $footers = array();
    var $pageContentWidth = 0;
    var $options = array();
    var $defaults = array();
    var $pageWidth = 0;
    var $pageHeight = 0;
    var $printingTable = false;
    var $isJointTable = false;
    var $tableLastLineHeight = 0;

    function init(){
        $this->defaults = array(
            'headers' => array()
            ,'footers' => array()
            ,'data' => array()
            ,'page_header' => true
            ,'page_footer' => true
            ,'multiFooters' => false
            ,'pageMargins' => array(12.7, 12.7, 12,7)
            ,'dataFontFamily' => ''
            ,'headerFontSize' => 9
            ,'footerFontSize' => 9
            ,'dataFontSize' => 9
            ,'headerFontStyle' => 'B'
            ,'footerFontStyle' => 'B'
            ,'dataFontStyle' => ''
            ,'headerLineColor' => array(111, 110, 110)
            ,'footerLineColor' => array(111, 110, 110)
            ,'dataLineColor' => array(111, 110, 110)
            ,'headerFillColor' => array(78, 170, 249)
            ,'footerFillColor' => array(212, 232, 249)
            ,'dataFillColor' => array(255, 255, 255)
            ,'headerTextColor' => array(0, 0, 0)
            ,'footerTextColor' => array(0, 0, 0)
            ,'dataTextColor' => array(0, 0, 0)
            ,'pageHeaderLeftText' => ''
            ,'pageFooterLeftText' => ''
            );
        $this->options = $this->defaults;
        }
    function resetOptions(){
        $this->options = $this->defaults;
        }
    function setOption($option, $value){
        $this->options[$option] = $value;
        }
    function getOption($option){
        return $this->options[$option];
        }
    function createPdf(){
        $this->AliasNbPages();

        $this->SetMargins($this->options['pageMargins'][0], $this->options['pageMargins'][1], $this->options['pageMargins'][2]);
        $this->AddPage();

        $this->pageWidth = $this->GetPageWidth();
        $this->pageHeight = $this->GetPageHeight();
        $this->pageContentWidth = $this->pageWidth - $this->options['pageMargins'][0] - $this->options['pageMargins'][2];
        }
    function jumpBackCellHeight(){
        $this->Ln(-$this->tableLastLineHeight);
        }
    function addJointTable($printHeader = true, $printBody = true, $printFooter = true){
        $this->jumpBackCellHeight();
        $this->addTable($printHeader, $printBody, $printFooter);
        }
    function addTable($printHeader = true, $printBody = true, $printFooter = true, $multiLineHeader = false){
        $this->headers = $this->options['headers'];
        $this->footers = $this->options['footers'];

        if($multiLineHeader) $this->multiLineHeaders = true;

        $this->columnWidths = array();
        //processing table headers
        if($this->multiLineHeaders ) $headers = $this->headers[0];
        else $headers = $this->headers;
        foreach($headers as $header){
            if(isset($header['width']))
                $thisWidth = ($header['width']/100)*$this->pageContentWidth;
            else
                $thisWidth = $this->GetStringWidth($header['text']);

            if($header['merged']){
                for($i=0;$i<$header['merged'];$i++){
                    $this->columnWidths[] = $thisWidth;
                }
            }
            else $this->columnWidths[] = $thisWidth;
            }

        $columnWidth = array_sum($this->columnWidths);

        if($columnWidth < $this->pageContentWidth){
            $additionalSpace = ($this->pageContentWidth - $columnWidth)/count($this->headers);
            foreach($this->columnWidths as $i=>$w) $this->columnWidths[$i] += $additionalSpace;
        }

        //printing table headers
        if($printHeader) $this->printTableHeaders();

        //printing table data
        if($printBody) $this->printTableRows();

        //printing table footers
        if($printFooter) $this->printTableFooters();

        $this->multiLineHeaders = false;
        }
    function printTableRows($section = 'data', $data = null){
        $fontSize = isset($this->options[$section.'FontSize']) ? $this->options[$section.'FontSize'] : '';
        $fontStyle = isset($this->options[$section.'FontStyle']) ? $this->options[$section.'FontStyle'] : '';
        $textColor = isset($this->options[$section.'TextColor']) ? $this->options[$section.'TextColor'] : '';
        $fillColor = isset($this->options[$section.'FillColor']) ? $this->options[$section.'FillColor'] : '';
        $lineColor = isset($this->options[$section.'LineColor']) ? $this->options[$section.'LineColor'] : '';

        $this->SetFont('Courier', $fontStyle, $fontSize);
        $this->SetTextColor($textColor[0],$textColor[1],$textColor[2]);
        $this->SetFillColor($fillColor[0],$fillColor[1],$fillColor[2]);
        $this->SetDrawColor($lineColor[0],$lineColor[1],$lineColor[2]);

        $this->SetLineWidth(.01);
        $data = !$data ? $this->options['data'] : $data;

        foreach($data as $eachData){
            $this->tableLastLineHeight = $this->Row($eachData,$section);
            $this->printingTable = true;
            }
        $this->printingTable = false;
        }
    function printTableHeaders(){
        if($this->multiLineHeaders) $data = $this->headers;
        else $data = array($this->headers);

        $this->printTableRows('header', $data);
        return true;
        }
    function printTableFooters(){
        $data = $this->options['multiFooters'] ? $this->footers : array($this->footers);
        $this->printTableRows('footer', $data);
        return true;
        }

    function Header(){
        $this->SetFont('Arial','',8);

        if($this->options['page_header'] == true){
           $this->Cell($this->pageContentWidth/3,10,$this->options['pageHeaderLeftText'],0,0,'L');
            $this->Cell($this->pageContentWidth/3,10,'',0,0,'C');
            $this->Cell($this->pageContentWidth/3,10,'Page '.$this->PageNo().'/{nb}',0,0,'R');
            $this->Line($this->options['pageMargins'][0], 20, ($this->GetPageWidth() - $this->options['pageMargins'][1]), 20);
            $this->Ln(10); 
            }
        if($this->printingTable) $this->printTableHeaders();
        }
    function Footer(){
        $this->SetY(-$this->options['pageMargins'][1]);
        $this->SetFont('Arial','',8);
        if($this->options['page_footer']== true){
            $footerLineHeight = $this->pageHeight - 10;
            $this->Line($this->options['pageMargins'][0], $footerLineHeight, ($this->GetPageWidth() - $this->options['pageMargins'][1]), $footerLineHeight);
            $this->Cell($this->pageContentWidth/3,10,$this->options['pageFooterLeftText'],0,0,'L');
            $this->Cell($this->pageContentWidth/3,10,'',0,0,'C');
            $this->Cell($this->pageContentWidth/3,10,'Page '.$this->PageNo().'/{nb}',0,0,'R');
            }
        }
    function outputPdf($mode = 'I', $fileName = 'download.pdf', $utf8 = true){
        $this->Output($mode, $fileName, $utf8);
        exit();
        }
    //TABLE RELATED FUNCTIONS
    function Row($data, $section){
        $NbLines = array();
        $lh = 5;
        //Calculate the width of each data column,
        //it is required as some columns can be merged columns,
        //thus we can't use the header width and print cell, we have to skip it

        $columnPointer = 0;

        foreach($data as $i=>$v){
            if(!is_array($v)) $data[$i] = array('text' => $v);
            if(!strlen($data[$i]['text'])) $data[$i]['text'] = ' ';

            if(!isset($data[$i]['calculated_width'])) $data[$i]['calculated_width'] = 0;

            if(isset($v['merged']) && $v['merged']){
                $mergedRowsCount = 0;
                while($mergedRowsCount < $v['merged']){
                    $data[$i]['calculated_width'] += $this->columnWidths[$columnPointer];
                    $columnPointer += 1;
                    $mergedRowsCount += 1;
                    }
                }
            else{
                $data[$i]['calculated_width'] = $this->columnWidths[$columnPointer];
                $columnPointer += 1;
                }

            if(!isset($data[$i]['align'])) $data[$i]['align'] = $this->headers[$columnPointer - 1]['align'];
            }

        //Calculate the height of the row
        $nb = 1;
        foreach($data as $i=>$v){
            if(!isset($v['text']) || !strlen($v['text'])) continue;
            $NbLines[$i] = $this->NbLines($v['calculated_width'], $v['text']);
            }
        $nb = $NbLines ? max($NbLines) : $nb;
        // append new line to cell texts if other
        // one or more cell are having bigger height
        // so that height of all cells are equal to the height
        // of the most tall cell. so the line below will be printed okay
        foreach($data as $i=>$v){
            if($NbLines[$i] < $nb){
                $addLines = $nb - $NbLines[$i];
                for($j = 0; $j < $addLines; $j++)
                    $data[$i]['text'] .= "\n ";
                }
            }

        $h = $lh * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        $tempSection = null;
        foreach($data as $i=>$v){
            if(isset($v['copy_section_style'])){
                $tempSection = $v['copy_section_style'];
                $fontSize = isset($this->options[$tempSection.'FontSize']) ? $this->options[$tempSection.'FontSize'] : '';
                $fontStyle = isset($this->options[$tempSection.'FontStyle']) ? $this->options[$tempSection.'FontStyle'] : '';
                $textColor = isset($this->options[$tempSection.'TextColor']) ? $this->options[$tempSection.'TextColor'] : '';
                $fillColor = isset($this->options[$tempSection.'FillColor']) ? $this->options[$tempSection.'FillColor'] : '';
                $lineColor = isset($this->options[$tempSection.'LineColor']) ? $this->options[$tempSection.'LineColor'] : '';

                $this->SetFont('Courier', $fontStyle, $fontSize);

                $this->SetTextColor($textColor[0],$textColor[1],$textColor[2]);
                $this->SetFillColor($fillColor[0],$fillColor[1],$fillColor[2]);
                $this->SetDrawColor($lineColor[0],$lineColor[1],$lineColor[2]);
                }

            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Print the text
            $this->MultiCell($v['calculated_width'], $lh, $v['text'], 1, $v['align'], true);
            //Put the position to the right of the cell
            $this->SetXY($x + $v['calculated_width'], $y);
            }
        //Go to the next line
        $this->Ln($h);
        //revert style if the this row had a different style than the section of the row (header, footer, body)
        if($tempSection){
            $fontSize = isset($this->options[$section.'FontSize']) ? $this->options[$section.'FontSize'] : '';
            $fontStyle = isset($this->options[$section.'FontStyle']) ? $this->options[$section.'FontStyle'] : '';
            $textColor = isset($this->options[$section.'TextColor']) ? $this->options[$section.'TextColor'] : '';
            $fillColor = isset($this->options[$section.'FillColor']) ? $this->options[$section.'FillColor'] : '';
            $lineColor = isset($this->options[$section.'LineColor']) ? $this->options[$section.'LineColor'] : '';

            $this->SetFont('Courier', $fontStyle, $fontSize);

            $this->SetTextColor($textColor[0],$textColor[1],$textColor[2]);
            $this->SetFillColor($fillColor[0],$fillColor[1],$fillColor[2]);
            $this->SetDrawColor($lineColor[0],$lineColor[1],$lineColor[2]);
            }
        return $h;
        }
    function CheckPageBreak($h){
        //If the height h would cause an overflow, add a new page immediately
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
        }
    function NbLines($w,$txt){
        //Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if($nb > 0 && $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while($i < $nb){
            $c = $s[$i];
            if($c == "\n"){
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if($l > $wmax){
                if($sep == -1){
                    if($i==$j)
                        $i++;
                }
                else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
        }

    function defaultPDFHeader($company_info, $logoWidth = 30, $logoHeight = 30){
        /*
         * $company_info = company_logo, company_title, company_address, company_mobile, company_email
         * */
        $pushForLogo = $this->options['pageMargins'][0] + 0;
        $currentY = $this->GetY();

        if(strlen($company_info['company_logo']) && file_exists(_path('uploads','absolute').'/'.$company_info['company_logo'])){
            $theImage = get_image($company_info['company_logo'],'200x200x2');
            $this->Image($theImage,null,null,$logoWidth, $logoHeight);
            $pushForLogo += $logoWidth;
        }

        $this->SetFont('Times','B',30);
        $this->SetXY($pushForLogo, $currentY);
        $this->Cell($this->pageContentWidth-$logoWidth,12,$company_info['company_title'],0,1,'R');

        $this->SetFont('Times','',12);
        $this->SetX($pushForLogo);
        $this->MultiCell($this->pageContentWidth-$logoWidth,6,$company_info['company_address'],0,'R');
        $this->Ln(0);
        $this->SetX($pushForLogo);
        $this->Cell($this->pageContentWidth-$logoWidth,6,$company_info['company_mobile'].' | '.$company_info['company_email'],0,1,'R');
        $this->Ln(2);

        if($this->options['pageMargins'][0]+$logoHeight+10 > $this->GetY()){
            $this->Ln($this->options['pageMargins'][0]+$logoHeight+10 - $this->GetY() + 10);
        }
        $this->Cell(0,2,'','T',1);
    }

    function defaultLeftSideRightSidePrint($leftSideData = array(), $rightSideData = array(), $maxFontSize = 14){
        $minFontSize = floor((80/100)*$maxFontSize);
        $leftSideWidth = floor(($this->pageContentWidth/100)*60);
        $rightSideWidth = $this->pageContentWidth - $leftSideWidth - 10;
        $innerWidth = $this->pageContentWidth - $leftSideWidth - $rightSideWidth;

        if($leftSideData == null) $leftSideData = array();
        if($rightSideData == null) $rightSideData = array();

        $first = true;
        $theY = $this->GetY();
        $theX = $this->GetX();

        foreach($leftSideData as $i=>$v){
            if($first){
                $first = false;
                $this->SetFont('Courier','B',$maxFontSize);
                $this->Cell($leftSideWidth, 5, $i, 0, 1, 'L');
            }
            else{
                $this->SetFont('Courier','B',$minFontSize);
                $w = floor($this->GetStringWidth($i));
                $this->Cell($w, 5, $i, 0, 0, 'L');

                $this->SetFont('Courier','',$minFontSize);
                $this->MultiCell($leftSideWidth - $w,5,$v,0,'L');

                $this->Ln(0);
            }
        }

        $this->SetY($theY);

        $first = true;
        //pre($this->GetY(),0);
        foreach($rightSideData as $i=>$v){
            $this->SetX($theX+$leftSideWidth+$innerWidth);
            //pre($this->GetY(),0);
            if($first){
                $first = false;
                $this->SetFont('Courier','B',$maxFontSize);
                $this->Cell($rightSideWidth, 5, $i, 0, 1, 'R');
            }
            else{
                $this->SetFont('Courier','B',$minFontSize);
                $w = floor($this->GetStringWidth($i));
                $this->Cell($w, 5, $i, 0, 0, 'L');

                $this->SetFont('Courier','',$minFontSize);
                $this->MultiCell($rightSideWidth - $w,5,$v,0,'R');

                $this->Ln(0);
            }
        }
        $this->Ln(15);
        //pre($this->GetY());
        $this->SetX($theX);
    }

    static function reportControlPanel($footer = false){
        ?>
        <div class="panel-<?php echo $footer ? 'footer' : 'heading'?>">
            <a class="btn btn-warning btn-xs btn-labeled btn-flat print-pdf" href="javascript:" data-pdf-url="<?php echo build_url(array('reportToPdf' => 1,'mode'=>'I','uniqueId'=>time()))?>"><i class="fa fa-print btn-label"></i>&nbsp;Print</a>
            <a class="btn btn-primary btn-xs btn-labeled btn-flat" target="_blank" href="<?php echo build_url(array('reportToPdf' => 1,'mode'=>'I'))?>"><i class="fa fa-file-pdf-o btn-label"></i>&nbsp;PDF Document</a>
            <a class="btn btn-primary btn-xs btn-labeled btn-flat" target="_blank" href="<?php echo build_url(array('reportToPdf' => 1,'mode'=>'D'))?>"><i class="fa fa-download btn-label"></i>&nbsp;PDF Download</a>
        </div>
        <?php
    }
}