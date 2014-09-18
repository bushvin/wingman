<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * export_to_pdf.php
 * 
 * export transaction to PDF
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

if (
    !file_exists(FS_ADVANCED .$_SESSION["space"]. "/fpdf/fpdf.php") ||
    !file_exists(FS_ADVANCED .$_SESSION["space"]. "/fpdf/fpdf_protection.php") ||
    !file_exists(FS_ADVANCED .$_SESSION["space"]. "/fpdf/font/unvr57w.php") ||
    !file_exists(FS_ADVANCED .$_SESSION["space"]. "/fpdf/font/unvr57w.z") ||
    !file_exists(FS_ADVANCED .$_SESSION["space"]. "/fpdf/font/unvr67w.php") ||
    !file_exists(FS_ADVANCED .$_SESSION["space"]. "/fpdf/font/unvr67w.z") ||
    count($ITEM_IDS) != 1
) {
    header("HTTP/1.1 500 Internal Server Error");
    exit();
}

include_once(FS_ADVANCED .$_SESSION["space"]. "/fpdf/fpdf_protection.php");

if ( ! isset($show_letterhead) ) {
    $show_letterhead = false;
}
$ITEM_ID = $ITEM_IDS[0];
$OFFER = new offer($ITEM_ID);
$OFFER->details();
//$OWNER
$ENTITY = new entity($OFFER->get("entity_id"));
$ENTITY->details();
$VAT = new vat($OFFER->get("vat_id"));
$VAT->details();

if ( file_exists(FS_LANGUAGE.$ENTITY->get("correspondence_language_short")."/default.php")) {
    include_once(FS_LANGUAGE.$ENTITY->get("correspondence_language_short")."/default.php");
}

class PDF extends FPDF_Protection {
	function Footer() {
		/* Create a footer, 1.5cm from the bottom, in Arial 8px */
		global $_entity;
		$this->SetTextColor(0,0,0);
		$this->SetY(-15);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,ucfirst(PAGE). ' '.$this->PageNo().' ' .PAGE_OF. ' {nb}',0,0,'C');
	}
}

$pdf=new PDF('P','mm','A4');
$pdf->SetProtection(array('print'));
$pdf->SetCreator("wingman.critter.be");
//eval("\$translation_type = TRANSACTION_" .str_replace(" ","_",strtoupper($OFFER->get("type"))).";");
$pdf->SetTitle(CRITTER_OFFER. " " .$OFFER->get("uid"));
$pdf->SetAuthor("Critter BVBA");
$pdf->SetKeywords(CRITTER_OFFER. " " .$OFFER->get("uid"));
$pdf->AddFont('Univers-Condensed-Bold','','unvr67w.php');
$pdf->AddFont('Univers-Condensed-Medium','','unvr57w.php');

$pdf->AliasNbPages();
$left = $pdf->GetX();

// Page 1 - Begin
$pdf->AddPage();

if ($show_letterhead === true) {
    $pdf->Image(FS_ADVANCED .$_SESSION["space"]."/letterhead.png",0,0,210);
}
$leftmargin = 60;

// Address - Begin
if (true) {
	$pdf->SetXY(110,35);
	$pdf->SetFont('Univers-Condensed-Bold','',12);
	$pdf->Cell(0, 4.5, $ENTITY->get("name"), 0, 2);
	$pdf->SetFont('Univers-Condensed-Medium','',12);
	$pdf->Cell(0, 4.5, html_entity_decode($OFFER->get("invoice_address")->street), 0, 2);
	$pdf->Cell(0, 4.5, html_entity_decode($OFFER->get("invoice_address")->code. " " .$OFFER->get("invoice_address")->city), 0, 2);
	$pdf->Cell(0, 4.5, iconv('UTF-8', 'windows-1252', $OFFER->get("invoice_address")->country), 0, 1);
// Address - End
}

// Greetings - Begin
if (true) {
        $pdf->SetXY($leftmargin,80);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0, 4.5, date("d/m/Y",strtotime($OFFER->get("date"))),0,2);

        $pdf->SetX($leftmargin);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0, 4.5, ucwords(CRITTER_OFFER_REFERENCE). ": " .$OFFER->get("reference"),0,2);


}
// Greetings - end


// Offer item overview - Start //
if (true) {
        $top = 94;
        $pdf->SetXY($leftmargin,$top);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->Cell(20, 2);
        $pdf->Cell(60, 2);
        $pdf->Cell(10, 2,'',0,0,'C');
        $pdf->Cell(20, 2, html_entity_decode(ucfirst(CRITTER_OFFER_PRICE_PER)),0,0,'C');
        $pdf->Cell(20, 2, html_entity_decode(ucfirst(CRITTER_OFFER_TOTAL)),0,0,'C');
        $pdf->Cell(10, 2, '',0,1,'C');

        $pdf->SetX($leftmargin);
        $pdf->Cell(20, 4.5, html_entity_decode(ucfirst(CRITTER_OFFER_SKU)));
        $pdf->Cell(60, 4.5, html_entity_decode(ucfirst(CRITTER_OFFER_DESCRIPTION)));
        $pdf->Cell(10, 4.5, html_entity_decode(ucfirst(CRITTER_OFFER_VOLUME)),0,0,'C');
        $pdf->Cell(20, 4.5, html_entity_decode(ucfirst(CRITTER_OFFER_UNIT)),0,0,'C');
        $pdf->Cell(20, 4.5, html_entity_decode("(".ucfirst(CRITTER_OFFER_NET).")"),0,0,'C');
        $pdf->Cell(10, 4.5, html_entity_decode(ucfirst(CRITTER_OFFER_VAT)),0,1,'C');

        $pdf->Line(60,$top+6,200,$top+6);

        $pdf->SetFont('Arial','',7.5);
        $newY = $pdf->GetY();
        $total = 0;
        $total_vat = 0;
        foreach ($OFFER->get("item") as $item) {
                $Y = $newY;
                $pdf->SetXY($leftmargin,$Y);
                $pdf->Cell(20, 4, html_entity_decode($item->sku));
                $pdf->SetXY($leftmargin+20,$Y);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->Cell(60, 4, html_entity_decode($item->name),0,2);
                $pdf->SetFont('Arial','',5.5);
                $pdf->MultiCell(60, 2, preg_replace('/<br>/i', "\n", html_entity_decode($item->description)));
                $newY = $pdf->GetY();
                $pdf->SetFont('Arial','',7.5);
                $pdf->SetXY($leftmargin+80,$Y);
                $pdf->Cell(10, 4, html_entity_decode(number_format($item->volume,3,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR)),0,0,'R');
                $pdf->SetXY($leftmargin+90,$Y);
                $pdf->Cell(20, 4, html_entity_decode(number_format($item->price, 2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR)). " " .iconv('UTF-8', 'windows-1252', $OFFER->get("currency_symbol")),0,0,'R');
                $pdf->SetXY($leftmargin+110,$Y);
                $pdf->Cell(20, 4, html_entity_decode(number_format($item->volume*$item->price, 2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR)). " " .iconv('UTF-8', 'windows-1252', $OFFER->get("currency_symbol")),0,0,'R');
                $pdf->SetXY($leftmargin+130,$Y);
                $pdf->Cell(10, 4, html_entity_decode($OFFER->get("vat")),0,1,'C');
                $total += $item->volume*$item->price;
                $total_vat += $item->volume*$item->vat;
        }
        $newY+=4;
        $pdf->Line(60,$newY,200,$newY);
        $pdf->SetXY($leftmargin,$newY);
        $pdf->SetFont('Arial','',7.5);
        $pdf->Cell(90, 4, "");
        $pdf->Cell(20, 4, html_entity_decode(ucfirst(CRITTER_OFFER_SUBTOTAL)));
        $pdf->Cell(20, 4, number_format($total, 2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR). " " .iconv('UTF-8', 'windows-1252', $OFFER->get("currency_symbol")),0,1,'R');

        $pdf->SetX($leftmargin);
        $pdf->Cell(90, 4, "");
        $pdf->Cell(20, 4, html_entity_decode(ucfirst(CRITTER_OFFER_TOTAL_VAT)));
        $pdf->Cell(20, 4, number_format($total_vat, 2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR). " " .iconv('UTF-8', 'windows-1252', $OFFER->get("currency_symbol")),0,1,'R');

        $pdf->SetX($leftmargin);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->Cell(90, 4, "");
        $pdf->Cell(20, 4, html_entity_decode(ucfirst(CRITTER_OFFER_TOTAL)));
        $pdf->Cell(20, 4, number_format($total+$total_vat, 2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR). " " .iconv('UTF-8', 'windows-1252', $OFFER->get("currency_symbol")),0,1,'R');

}

// Closing - Start //
if (true) {
        $newY = $pdf->GetY()+20;
        $pdf->SetXY($leftmargin,$newY);
        $pdf->SetFont('Arial','',7.5);
        $pdf->MultiCell(0,4.5,html_entity_decode(ucfirst(CRITTER_OFFER_KIND_REGARDS.",")));
        $newY = $pdf->GetY();

        $pdf->SetXY($leftmargin,$newY);
        $pdf->SetFont('Arial','',7.5);
        $pdf->MultiCell(0,4.5,html_entity_decode(ucfirst(CRITTER_OFFER_WILLIAM_LEEMANS.".")));

// Closing - Start //
}


// Voorwaarden - Start //
if (true) {
	$pdf->SetTextColor(153,153,153);
	$pdf->SetY(270);
	$pdf->SetFont('Univers-Condensed-Medium','',6);
	$pdf->MultiCell(0, 2, html_entity_decode(CRITTER_CONDITIONS_NOTE));
// Voorwaarden - End //
}
// Page 1 - End

if ($show_letterhead === true) {
        $pdf->AddPage();
        $pdf->SetTextColor(153,153,153);
        $pdf->SetFont('Univers-Condensed-Bold','',10);
        $pdf->MultiCell(0, 10, html_entity_decode(CRITTER_CONDITIONS_TITLE));
        $pdf->SetFont('Univers-Condensed-Medium','',10);
        $pdf->MultiCell(0, 4, html_entity_decode(CRITTER_CONDITIONS));
}

// Export the page to the correct format
$filename = str_replace(" ","_",CRITTER_OFFER. "_" .$OFFER->get("reference"). ".pdf");
$pdf->Output(html_entity_decode($filename),'D');

?>