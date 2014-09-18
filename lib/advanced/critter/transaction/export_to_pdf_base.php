<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * export_to_pdf_with_letterhead.php
 * 
 * export transaction to PDF with letterhead
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled

if (
    !file_exists(FS_ADVANCED .$_SESSION["space"]. "/fpdf/fpdf.php") ||
    !file_exists(FS_ADVANCED .$_SESSION["space"]. "/fpdf/fpdf_protection.php") ||
    !file_exists(FS_ADVANCED .$_SESSION["space"]. "/fpdf/font/unvr57w.php") ||
    !file_exists(FS_ADVANCED .$_SESSION["space"]. "/fpdf/font/unvr57w.z") ||
    !file_exists(FS_ADVANCED .$_SESSION["space"]. "/fpdf/font/unvr67w.php") ||
    !file_exists(FS_ADVANCED .$_SESSION["space"]. "/fpdf/font/unvr67w.z") ||
    !file_exists(FS_ADVANCED .$_SESSION["space"]. "/letterhead.png") ||
    count($ITEM_IDS) != 1
) {
    header("HTTP/1.1 500 Internal Server Error");
    exit();
}

include_once(FS_ADVANCED .$_SESSION["space"]. "/fpdf/fpdf_protection.php");
//include_once(FS_LANGUAGE.APP_LANGUAGE."/default.php");

if ( ! isset($show_letterhead) ) {
    $show_letterhead = false;
}

$ITEM_ID = $ITEM_IDS[0];
$TRANSACTION = new transaction($ITEM_ID);
$TRANSACTION->details();

$OFFER = new offer($TRANSACTION->get("offer_id"));
$OFFER->details();
$ENTITY = new entity($TRANSACTION->get("entity_id"));
$ENTITY->details();
//$VAT = new vat($OFFER->get("vat_id"));
//$VAT->details();

$TRANSACTION_OVERVIEW = array();
foreach ($TRANSACTION->get("item") as $o) {
    if ( !isset($OVERVIEW[$o->sku])) {
        $TRANSACTION_OVERVIEW[$o->sku] = $o;
    } else {
        $TRANSACTION_OVERVIEW[$o->sku]->volume += $o->volume;
    }
}

foreach ($ENTITY->get("address") as $o) {
    if ( $o->invoicing == 1 ) {
        $INVOICING_ADDRESS = $o;
        break;
    }
}

if (!isset($INVOICING_ADDRESS)) {
    header("HTTP/1.1 500 Internal Server Error");
    exit();
}

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
eval("\$translation_type = TRANSACTION_" .str_replace(" ","_",strtoupper($TRANSACTION->get("type"))).";");
$pdf->SetTitle($translation_type. " " .$TRANSACTION->get("uid"));
$pdf->SetAuthor("Critter BVBA");
$pdf->SetKeywords($translation_type. " " .$TRANSACTION->get("uid"));
$pdf->AddFont('Univers-Condensed-Bold','','unvr67w.php');
$pdf->AddFont('Univers-Condensed-Medium','','unvr57w.php');

$pdf->AliasNbPages();
$left = $pdf->GetX();

// Page 1 - Begin
$pdf->AddPage();

//letterhead
if ($show_letterhead === true) {
    $pdf->Image(FS_ADVANCED .$_SESSION["space"]."/letterhead.png",0,0,210);
}

$leftmargin = 60;

// Address - Begin
if (true) {
	$pdf->SetXY(110,35);
	$pdf->SetFont('Univers-Condensed-Bold','',12);
	$pdf->Cell(0, 4.5, iconv('UTF-8', 'windows-1252', $ENTITY->get("name")), 0, 2);
	$pdf->SetFont('Univers-Condensed-Medium','',12);
	$pdf->Cell(0, 4.5, iconv('UTF-8', 'windows-1252', $INVOICING_ADDRESS->street), 0, 2);
	$pdf->Cell(0, 4.5, iconv('UTF-8', 'windows-1252', $INVOICING_ADDRESS->code. " " .$INVOICING_ADDRESS->city), 0, 2);
	$pdf->Cell(0, 4.5, iconv('UTF-8', 'windows-1252', $INVOICING_ADDRESS->country), 0, 1);
// Address - End
}

// Invoice Details - Begin
if (true) {
	$pdf->SetXY($leftmargin,60);
	$pdf->SetFont('Arial','B',14);
	$pdf->Cell(100, 7, iconv('UTF-8', 'windows-1252', ucfirst($translation_type)),0,1);
	$pdf->SetFont('Arial','B',6);
	$pdf->SetX($leftmargin);
	$pdf->Cell(20, 2, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_REFERENCE)));
	$pdf->Cell(30, 2, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_PERIOD)));
	$pdf->Cell(20, 2, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_UID)));
	$pdf->Cell(20, 2, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_DOCUMENT_DATE)));
	$pdf->Cell(20, 2, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_CUSTOMER_NUMBER)));
	$pdf->Cell(0, 2, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_VAT_NUMBER)),0,1);

	$pdf->SetFont('Arial','',7.5);
	$pdf->SetX($leftmargin);
	$pdf->Cell(20, 4.5, iconv('UTF-8', 'windows-1252', $TRANSACTION->get("reference")));
	$pdf->Cell(30, 4.5, iconv('UTF-8', 'windows-1252', $TRANSACTION->get("field0")));
	$pdf->Cell(20, 4.5, iconv('UTF-8', 'windows-1252', $TRANSACTION->get("uid")));
	$pdf->Cell(20, 4.5, iconv('UTF-8', 'windows-1252', date("d/m/Y",strtotime($TRANSACTION->get("date")))));
	$pdf->Cell(20, 4.5, iconv('UTF-8', 'windows-1252', $ENTITY->get("uid")));
	$pdf->Cell(0, 4.5, iconv('UTF-8', 'windows-1252', $ENTITY->get("VAT_number")),0,1);
	$pdf->Cell(0,10,'',0,1);
// Invoice Details - end
}

// Invoice Item Overview - Start //
if (true) {
	$top = 104;
	$top = 94;
	$pdf->SetXY($leftmargin,$top);
	$pdf->SetFont('Arial','B',7.5);
	$pdf->Cell(20, 2);
	$pdf->Cell(60, 2);
	$pdf->Cell(10, 2,'',0,0,'C');
	$pdf->Cell(20, 2, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_PRICE_PER)),0,0,'C');
	$pdf->Cell(20, 2, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_TOTAL)),0,0,'C');
	$pdf->Cell(10, 2, '',0,1,'C');

	$pdf->SetX($leftmargin);
	$pdf->Cell(20, 4.5, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_SKU)));
	$pdf->Cell(60, 4.5, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_DESCRIPTION)));
	$pdf->Cell(10, 4.5, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_VOLUME)),0,0,'C');
	$pdf->Cell(20, 4.5, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_UNIT)),0,0,'C');
	$pdf->Cell(20, 4.5, iconv('UTF-8', 'windows-1252', "(".ucfirst(CRITTER_TRANSACTION_NET).")"),0,0,'C');
	$pdf->Cell(10, 4.5, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_VAT)),0,1,'C');

	$pdf->Line(60,$top+6,200,$top+6);

	$pdf->SetFont('Arial','',7.5);
	$newY = $pdf->GetY();
	$total = 0;
	foreach ($TRANSACTION->get("calculated") as $item) {
		$Y = $newY;
		$pdf->SetXY($leftmargin,$Y);
		$pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', $item->sku));
		$pdf->SetXY($leftmargin+20,$Y);
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		$pdf->Cell(60, 4, iconv('UTF-8', 'windows-1252', $item->name),0,2);
		$pdf->SetFont('Arial','',5.5);
		$pdf->MultiCell(60, 2, iconv('UTF-8', 'windows-1252', preg_replace('/<br>/i', "\n", $item->description)));
		$newY = $pdf->GetY();
		$pdf->SetFont('Arial','',7.5);
		$pdf->SetXY($leftmargin+80,$Y);
		$pdf->Cell(10, 4, iconv('UTF-8', 'windows-1252', number_format($item->volume,3,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR)),0,0,'R');
		$pdf->SetXY($leftmargin+90,$Y);
		$pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', number_format($item->price, 2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR). " " .$OFFER->get("currency_symbol")),0,0,'R');
		$pdf->SetXY($leftmargin+110,$Y);
		$pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', number_format($item->price*$item->volume, 2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR). " " . $OFFER->get("currency_symbol")),0,0,'R');
		$pdf->SetXY($leftmargin+130,$Y);
		$VAT = new vat($item->vat_id);
		$VAT->details();
		$pdf->Cell(10, 4, $VAT->get("name"),0,1,'C');
        $total += $item->price*$item->volume;
	}


	$newY+=4;
	$pdf->Line(60,$newY,200,$newY);
	$pdf->SetXY($leftmargin,$newY);
	$pdf->SetFont('Arial','B',7.5);
	$pdf->Cell(110, 4, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_TOTAL)));
	
	$pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', number_format($total, 2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR). " " .$OFFER->get("currency_symbol")),0,1,'R');

	if ($TRANSACTION->get("notes")!="") {
		$Y = $newY+5;
		$pdf->SetXY($leftmargin+20,$Y);
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		$pdf->SetFont('Arial','B',7.5);
		$pdf->Cell(60, 4, iconv('UTF-8', 'windows-1252', "*** ".ucfirst(CRITTER_TRANSACTION_NOTES)." ***"),0,2);
		$pdf->SetFont('Arial','',5.5);
		$pdf->MultiCell(60, 2, iconv('UTF-8', 'windows-1252', preg_replace('/<br>/i', "\n", html_entity_decode($TRANSACTION->get("notes")))));
		$newY = $pdf->GetY();
	}

/*
	$TRANSACTION_TOTAL = 0;
	$TRANSACTION_DISCOUNT_TOTAL = 0;
	foreach ( $TRANSACTION_OVERVIEW as $item ) {
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
		$pdf->Cell(20, 4, html_entity_decode(number_format($item->price*$item->volume, 2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR)). " " .iconv('UTF-8', 'windows-1252', $OFFER->get("currency_symbol")),0,0,'R');
		$pdf->SetXY($leftmargin+130,$Y);
		$pdf->Cell(10, 4, html_entity_decode($OFFER->get("vat")),0,1,'C');
		$TRANSACTION_TOTAL += $item->price*$item->volume;
		if ($item->discount>0) {
			$Y = $newY;
			$pdf->SetXY($leftmargin+20,$Y);
			$pdf->Cell(20, 4, html_entity_decode(ucfirst(CRITTER_TRANSACTION_DISCOUNT)));
			$x = $pdf->GetX();
			$y = $pdf->GetY();
			$pdf->SetXY($leftmargin+110,$Y);
			$discount_total = ($item->discount_type=="fixed"?$item->discount*$item->volume:$item->price*$item->volume/100*$item->discount);
			$pdf->Cell(20, 4, '- '.number_format($discount_total, 2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR). " " .iconv('UTF-8', 'windows-1252', $OFFER->get("currency_symbol")),0,0,'R');
			$pdf->SetXY($leftmargin+130,$Y);
			$pdf->Cell(10, 4, html_entity_decode($OFFER->get("vat")),0,1,'C');
			$newY = $pdf->GetY();
			$TRANSACTION_DISCOUNT_TOTAL += $discount_total;
		}

    	if ($TRANSACTION->get("notes")!="") {
    		$Y = $newY+5;
    		$pdf->SetXY($leftmargin+20,$Y);
    		$x = $pdf->GetX();
    		$y = $pdf->GetY();
    		$pdf->SetFont('Arial','B',7.5);
    		$pdf->Cell(60, 4, html_entity_decode("*** ".ucfirst(CRITTER_TRANSACTION_NOTES)." ***"),0,2);
    		$pdf->SetFont('Arial','',5.5);
    		$pdf->MultiCell(60, 2, preg_replace('/<br>/i', "\n", html_entity_decode($TRANSACTION->get("notes"))));
    		$newY = $pdf->GetY();
    	}

    	$newY+=4;
    	$pdf->Line(60,$newY,200,$newY);
    	$pdf->SetXY($leftmargin,$newY);
    	$pdf->SetFont('Arial','B',7.5);
    	$pdf->Cell(110, 4, html_entity_decode(ucfirst(CRITTER_TRANSACTION_TOTAL)));
    	
    	$pdf->Cell(20, 4, number_format($TRANSACTION_TOTAL-$TRANSACTION_DISCOUNT_TOTAL, 2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR). " " .iconv('UTF-8', 'windows-1252', $OFFER->get("currency_symbol")),0,1,'R');
	}

*/
/*
	if ($OFFER->get("vat_type")=="fixed") {
	    $TRANSACTION_TOTAL_VAT = $OFFER->get("vat_amount");
    	$TRANSACTION_DISCOUNT_TOTAL_VAT = 0;
	} else {
	    $TRANSACTION_TOTAL_VAT = $TRANSACTION_TOTAL/100*$OFFER->get("vat_amount");
    	$TRANSACTION_DISCOUNT_TOTAL_VAT = $TRANSACTION_DISCOUNT_TOTAL/100*$OFFER->get("vat_amount");
	}
*/
    $TRANSACTION_TOTAL_VAT = 0;
    $TRANSACTION_DISCOUNT_TOTAL_VAT = 0;
    $TRANSACTION_DISCOUNT_TOTAL = 0;
    $TRANSACTION_TOTAL = 0;
}

// Invoice VAT Overview - Start
if (true) {
    $top = 200;
    $pdf->SetXY($leftmargin,$top);
    $pdf->SetFont('Arial','B',7.5);
    $pdf->Cell(10, 2);
    $pdf->Cell(20, 2, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_NET)),0,0,'C');
    $pdf->Cell(20, 2, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_VAT)),0,0,'C');
    $pdf->Cell(20, 2);
    $pdf->Cell(0, 2, '',0,1);
    
    $pdf->SetX($leftmargin);
    $pdf->Cell(10, 4.5, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_VAT)),0,0,'C');
    $pdf->Cell(20, 4.5, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_AMOUNT)),0,0,'C');
    $pdf->Cell(20, 4.5, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_AMOUNT)),0,0,'C');
    $pdf->Cell(20, 4.5, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_TOTAL)),0,0,'C');
    $pdf->Cell(0, 4.5, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_REMARK)),0,1);
    
    $pdf->Line(60,$top+6,200,$top+6);
    $pdf->SetFont('Arial','',7.5);
    
    switch ($TRANSACTION->get("type")) {
    	case "credit note":
    		$_factor = -1;
    		break;
    	case "invoice":
    	    $_factor = 1;
    	default:
    		$_factor = 1;
    }
    $newY = $pdf->GetY();
    
    $total_net = 0;
    $total_vat = 0;
    foreach ( $TRANSACTION->get("calculated_vat") as $vat_id=>$amount) {
        $pdf->SetXY($leftmargin,$newY);
        $VAT = new vat($vat_id);
        $VAT->details();
        $pdf->Cell(10, 4, html_entity_decode($VAT->get("name")),0,0,'C');
        $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', number_format(($amount)*$_factor,2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR). " " .$OFFER->get("currency_symbol")),0,0,'R');
        $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', number_format($VAT->calculate($amount)*$_factor,2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR). " " . $OFFER->get("currency_symbol")),0,0,'R');
        $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', number_format(($amount+$VAT->calculate($amount))*$_factor,2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR). " " .$OFFER->get("currency_symbol")),0,0,'R');
        $pdf->MultiCell(0, 4, iconv('UTF-8', 'windows-1252', preg_replace('/<br>/i', "\n", html_entity_decode($VAT->get("remark")))));
        $newY = $pdf->GetY();
        $total_net += $amount;
        $total_vat += $VAT->calculate($amount);
    }

    $newY+=4;
    $pdf->Line(60,$newY,200,$newY);
    $pdf->SetFont('Arial','B',7.5);
    $pdf->SetXY($leftmargin,$newY);
    $pdf->Cell(10, 4, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_TOTAL)));
    $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', number_format(($total_net)*$_factor,2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR). " " .$OFFER->get("currency_symbol")),0,0,'R');
    $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', number_format(($total_vat)*$_factor,2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR). " " .$OFFER->get("currency_symbol")),0,0,'R');
    $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', number_format(($total_net+$total_vat)*$_factor,2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR). " " .$OFFER->get("currency_symbol")),0,0,'R');
    $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', ucfirst(CRITTER_TRANSACTION_TOTAL_AMOUNT)));
	
// Invoice VAT Overview - end
}


// Closing - Start //
if (true) {
	$pdf->SetXY($leftmargin,235);
	$pdf->SetFont('Arial','',7.5);
	$pdf->MultiCell(0,4.5,html_entity_decode(ucfirst(CRITTER_TRANSACTION_KIND_REGARDS)));
	$newY = $pdf->GetY();
	
	$pdf->SetXY($leftmargin,$newY);
	$pdf->SetFont('Arial','',7.5);
	$pdf->MultiCell(0,4.5,html_entity_decode(ucfirst(CRITTER_TRANSACTION_SIGNATURE)));
	
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
    // Page 2 - Begin
    $pdf->AddPage();
    $pdf->SetTextColor(153,153,153);
    $pdf->SetFont('Univers-Condensed-Bold','',10);
    $pdf->MultiCell(0, 10, html_entity_decode(CRITTER_CONDITIONS_TITLE));
    $pdf->SetFont('Univers-Condensed-Medium','',10);
    $pdf->MultiCell(0, 4, html_entity_decode(CRITTER_CONDITIONS));
    // Page 2 - End
}

// Page 3 - Begin
$pdf->AddPage();
$pdf->SetTextColor(0,0,0);

//letterhead
if ($show_letterhead === true) {
    $pdf->Image(FS_ADVANCED .$_SESSION["space"]."/letterhead.png",0,0,210);
}

// Invoice Details - Begin
if (true) {
	$pdf->SetXY($leftmargin,10);
	$pdf->SetFont('Arial','B',14);
	$pdf->Cell(100, 7, html_entity_decode(ucfirst($translation_type)),0,1);
	$pdf->SetFont('Arial','B',6);
	$pdf->SetX($leftmargin);
	$pdf->Cell(20, 2, html_entity_decode(ucfirst(CRITTER_TRANSACTION_REFERENCE)));
	$pdf->Cell(30, 2, html_entity_decode(ucfirst(CRITTER_TRANSACTION_PERIOD)));
	$pdf->Cell(20, 2, html_entity_decode(ucfirst(CRITTER_TRANSACTION_UID)));
	$pdf->Cell(20, 2, html_entity_decode(ucfirst(CRITTER_TRANSACTION_DOCUMENT_DATE)));
	$pdf->Cell(20, 2, html_entity_decode(ucfirst(CRITTER_TRANSACTION_CUSTOMER_NUMBER)));
	$pdf->Cell(0, 2, html_entity_decode(ucfirst(CRITTER_TRANSACTION_VAT_NUMBER)),0,1);

	$pdf->SetFont('Arial','',7.5);
	$pdf->SetX($leftmargin);
	$pdf->Cell(20, 4.5, html_entity_decode($TRANSACTION->get("reference")));
	$pdf->Cell(30, 4.5, html_entity_decode($TRANSACTION->get("field0")));
	$pdf->Cell(20, 4.5, html_entity_decode($TRANSACTION->get("uid")));
	$pdf->Cell(20, 4.5, date("d/m/Y",strtotime($TRANSACTION->get("date"))));
	$pdf->Cell(20, 4.5, $ENTITY->get("id"));
	$pdf->Cell(0, 4.5, html_entity_decode($ENTITY->get("VAT_number")),0,1);
	$pdf->Cell(0,10,'',0,1);
// Invoice Details - end
}

// Invoice item details - Begin
if (true) {
	$top = 40;
	$pdf->SetXY($leftmargin,$top);
	$pdf->SetFont('Arial','B',7.5);
	$pdf->Cell(20, 2);
	$pdf->Cell(20, 2);
	$pdf->Cell(60, 2);
	$pdf->Cell(20, 2, html_entity_decode(ucfirst(CRITTER_TRANSACTION_PRICE_PER)),0,0,'C');
	$pdf->Cell(10, 2, '',0,1,'C');

	$pdf->SetX($leftmargin);
	$pdf->Cell(15, 4.5, html_entity_decode(ucfirst(CRITTER_TRANSACTION_DATE)));
	$pdf->Cell(15, 4.5, html_entity_decode(ucfirst(CRITTER_TRANSACTION_SKU)));
	$pdf->Cell(60, 4.5, html_entity_decode(ucfirst(CRITTER_TRANSACTION_DESCRIPTION)));
	$pdf->Cell(10, 4.5, html_entity_decode(ucfirst(CRITTER_TRANSACTION_VOLUME)),0,0,'C');
	$pdf->Cell(20, 4.5, html_entity_decode(ucfirst(CRITTER_TRANSACTION_UNIT)),0,0,'C');
	$pdf->Cell(20, 4.5, html_entity_decode(ucfirst(CRITTER_TRANSACTION_VAT)),0,1,'C');

	$pdf->Line(60,$top+6,200,$top+6);

	$pdf->SetFont('Arial','',7.5);
	$newY = $pdf->GetY();
	$_total = new stdClass();
	$_total->net = 0;
	$_total->vat = 0;
	foreach ( $TRANSACTION->get("item") as $item ) {
		$pdf->SetFont('Arial','',7.5);
		$pdf->SetX($leftmargin);
		$pdf->Cell(15, 4, date("d/m/Y",strtotime($item->date)));
		$pdf->Cell(15, 4, html_entity_decode($item->sku));
		$pdf->Cell(60, 4, html_entity_decode($item->name));
		$pdf->Cell(10, 4, html_entity_decode(number_format($item->volume,3,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR)),0,0,'R');
		$pdf->Cell(20, 4, html_entity_decode(number_format($item->price,2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR)). " " .iconv('UTF-8', 'windows-1252', $OFFER->get("currency_symbol")),0,0,'R');
        $vat = ($VAT->get("type")=="fixed"?$VAT->get("amount"):$item->price/100*$VAT->get("amount"));
		$pdf->Cell(20, 4, number_format($vat,2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR). " " .iconv('UTF-8', 'windows-1252', $OFFER->get("currency_symbol")),0,1,'R');
		$_total->net += $item->volume*$item->price;
		$_total->vat += $item->volume*$vat;
		if ($item->discount > 0) {
			$pdf->SetX($leftmargin+20);
			$pdf->Cell(80, 2, html_entity_decode(ucfirst(CRITTER_TRANSACTION_DISCOUNT)));
			switch ($item->discount_type) {
				case "relative":
					$discount = $item->price*$item->discount/100;
					break;
				default:
					$discount = $item->discount;
			}
			$pdf->Cell(20, 2, '- '.number_format($discount,2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR). " " .iconv('UTF-8', 'windows-1252', $OFFER->get("currency_symbol")),0,0,'R');
//			$temp = new stdClass();
//			$temp->id = $_transaction->data->vat_id;
//			$temp->volume = $item->volume;
//			$temp->amount = $discount;
//			$vat = api_vat::calculate($temp,$_GET["id"], $_GET["token"])->calculated;
            $dvat = ($VAT->get("type")=="fixed"?0:$item->discount/100*$VAT->get("amount"));
			$pdf->Cell(20, 2, '- '.number_format($dvat,2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR). " " .iconv('UTF-8', 'windows-1252', $OFFER->get("currency_symbol")),0,1,'R');
			$_total->net -= $item->volume*$discount;
			$_total->vat -= $item->volume*$dvat;
		}
		
	}
	$newY = $pdf->GetY();
	$newY+=4;
	$pdf->Line(60,$newY-1,200,$newY-1);
	$pdf->SetXY($leftmargin,$newY);
	$pdf->SetFont('Arial','B',7.5);
	$pdf->Cell(100, 3, html_entity_decode(ucfirst(CRITTER_TRANSACTION_TOTAL)));
	$pdf->Cell(20, 3, number_format($_total->net,2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR). " " .iconv('UTF-8', 'windows-1252', $OFFER->get("currency_symbol")),0,0,'R');
	$pdf->Cell(20, 3, number_format($_total->vat,2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR). " " .iconv('UTF-8', 'windows-1252', $OFFER->get("currency_symbol")),0,1,'R');
// Invoice item details - End
}

// Voorwaarden - Start //
if (true) {
	$pdf->SetTextColor(153,153,153);
	$pdf->SetY(270);
	$pdf->SetFont('Univers-Condensed-Medium','',6);
	$pdf->MultiCell(0, 2, html_entity_decode(CRITTER_CONDITIONS_NOTE));
// Voorwaarden - End //
}
// Page 3 - End

if ($show_letterhead === true) {
    // Page 4 - Begin
    $pdf->AddPage();
    $pdf->SetTextColor(153,153,153);
    $pdf->SetFont('Univers-Condensed-Bold','',10);
    $pdf->MultiCell(0, 10, html_entity_decode(CRITTER_CONDITIONS_TITLE));
    $pdf->SetFont('Univers-Condensed-Medium','',10);
    $pdf->MultiCell(0, 4, html_entity_decode(CRITTER_CONDITIONS));
    // Page 4 - End
}

// Export the page to the correct format
$filename = str_replace(" ","_",$translation_type. "_" .$TRANSACTION->get("uid"). ".pdf");
$pdf->Output(html_entity_decode($filename),'D');

?>