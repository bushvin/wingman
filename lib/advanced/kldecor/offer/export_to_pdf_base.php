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
 * export offer to PDF
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

if (
    !file_exists(FS_ADVANCED .$_SESSION["space"]. "/fpdf/fpdf.php") ||
    !file_exists(FS_ADVANCED .$_SESSION["space"]. "/fpdf/fpdf_protection.php") ||
    !file_exists(FS_ADVANCED .$_SESSION["space"]. "/fpdf/font/merriweather-light300.php") ||
    !file_exists(FS_ADVANCED .$_SESSION["space"]. "/fpdf/font/merriweather-light300.z") ||
    !file_exists(FS_ADVANCED .$_SESSION["space"]. "/briefhoofd_top.png") ||
    !file_exists(FS_ADVANCED .$_SESSION["space"]. "/briefhoofd_bottom.png") ||
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
//$VAT = new vat($OFFER->get("vat_id"));
//$VAT->details();

if ( file_exists(FS_LANGUAGE.$ENTITY->get("correspondence_language_short")."/default.php")) {
    include_once(FS_LANGUAGE.$ENTITY->get("correspondence_language_short")."/default.php");
}

class PDF extends FPDF_Protection {
}
$pdf=new PDF('P','pt','A4');
$pdf->SetProtection(array('print'));
$pdf->SetCreator("wingman.critter.be");
$pdf->SetTitle(KLDECOR_OFFER. " " .$OFFER->get("uid"));
$pdf->SetAuthor("KL Decor");
$pdf->SetKeywords(KLDECOR_OFFER. " " .$OFFER->get("uid"));
$pdf->AddFont('Merriweather-light','','merriweather-light300.php');
$pdf->AddFont('Merriweather-bold','','merriweather-700.php');

//$left = $pdf->GetX();


// Page 1 - Begin
$pdf->AddPage();

if ($show_letterhead === true) {
    $pdf->Image(FS_ADVANCED .$_SESSION["space"]."/briefhoofd_top.png",0,0,595);
    $pdf->Image(FS_ADVANCED .$_SESSION["space"]."/briefhoofd_bottom.png",0,750,595);
}

// intro - Begin
if (true) {
    // left column - begin
    $top = 156;
    $left = 28;
	$pdf->SetXY($left,$top);
	$pdf->SetFont('Merriweather-light','',8);

	// document title //
	$pdf->SetTextColor(130,130,130);
	$pdf->Cell(0, 11.3, iconv('UTF-8', 'windows-1252', strtoupper(KLDECOR_OFFER)), 0, 2);
	$c2top = $pdf->GetY();
	
	// line #1
	
	$pdf->SetXY($left,$top+25);
	$pdf->SetTextColor(130,130,130);
	$pdf->Cell(37, 11.3, iconv('UTF-8', 'windows-1252', ucfirst(KLDECOR_OFFER_REFERENCE).":"), 0, 0);
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(241, 11.3, iconv('UTF-8', 'windows-1252', $OFFER->get("description")), 0, 2);

	// line #2
    $pdf->SetX($left);
	$pdf->SetTextColor(130,130,130);
	$pdf->Cell(37, 11.3, iconv('UTF-8', 'windows-1252', ucfirst(KLDECOR_OFFER_DATE).":"), 0, 0);
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(241, 11.3, date("d/m/Y",strtotime($OFFER->get("date"))), 0, 2);

    // line #3
    $pdf->SetX($left);
	$pdf->SetTextColor(130,130,130);
	$pdf->Cell(37, 11.3, iconv('UTF-8', 'windows-1252', ucfirst(KLDECOR_OFFER_CUSTOMER).":"), 0, 0);
	$pdf->SetTextColor(0,0,0);
	$tleft = $pdf->GetX();
	$addr = $OFFER->get("invoice_address");
	$pdf->SetX($tleft);
	$pdf->Cell(241, 11.3, iconv('UTF-8', 'windows-1252', ucfirst(KLDECOR_OFFER_UID).": ".$ENTITY->get("uid")),0,2);
	$pdf->SetX($tleft);
	$pdf->Cell(241, 11.3, iconv('UTF-8', 'windows-1252', $ENTITY->get('name')),0,2);
	$pdf->SetX($tleft);
	$pdf->Cell(241, 11.3, iconv('UTF-8', 'windows-1252', $addr->street. ", " .$addr->code. " ". $addr->city),0,2);
    if ( $ENTITY->get('VAT_number') != "" ) {
    	$pdf->SetX($tleft);
    	$pdf->Cell(241, 11.3, iconv('UTF-8', 'windows-1252', $ENTITY->get('VAT_number')),0,2);
    }
    if ( $ENTITY->get("tel") != "" ) {
    	$pdf->SetX($tleft);
    	$pdf->Cell(241, 11.3, iconv('UTF-8', 'windows-1252', ucfirst(KLDECOR_OFFER_TEL).": ".$ENTITY->get("tel")),0,2);
    }
    if ( $ENTITY->get("email") != "" ) {
    	$pdf->SetX($tleft);
    	$pdf->Cell(241, 11.3, iconv('UTF-8', 'windows-1252', ucfirst(KLDECOR_OFFER_EMAIL).": ".$ENTITY->get("email")), 0, 2);
    }
	// left column - end
	
	// right column - begin
	$top = $c2top;
	$left = $left+37+241;
	$pdf->SetXY($left,$top);

    // line #1
	$pdf->SetTextColor(130,130,130);
	$pdf->Cell(71, 11.3, iconv('UTF-8', 'windows-1252', ucfirst(KLDECOR_OFFER_NUMBER).":"), 0, 0);
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(241, 11.3, iconv('UTF-8', 'windows-1252', $OFFER->get("reference_prefix").$OFFER->get("reference")), 0, 2);
	
	// line #2
    $pdf->SetX($left);
	$pdf->SetTextColor(130,130,130);
	$pdf->Cell(71, 11.3, iconv('UTF-8', 'windows-1252', ucfirst(KLDECOR_OFFER_DELIVERY_ADDRESS).":"), 0, 0);
	$pdf->SetTextColor(0,0,0);
	$tleft = $pdf->GetX();
	$addr = $OFFER->get("delivery_address");
	$pdf->Cell(241, 11.3, iconv('UTF-8', 'windows-1252', $addr->street), 0, 2);
	$pdf->SetX($tleft);
	$pdf->Cell(241, 11.3, iconv('UTF-8', 'windows-1252', $addr->code). " ". $addr->city, 0, 2);
	if ( $ENTITY->get("tel") != "" ) {
    	$pdf->SetX($tleft);
    	$pdf->Cell(241, 11.3, iconv('UTF-8', 'windows-1252', ucfirst(KLDECOR_OFFER_TEL).": ".$ENTITY->get("tel")), 0, 2);
	}
	if ( $ENTITY->get("email") != "" ) {
    	$pdf->SetX($tleft);
    	$pdf->Cell(241, 11.3, iconv('UTF-8', 'windows-1252', ucfirst(KLDECOR_OFFER_EMAIL).": ".$ENTITY->get("email")), 0, 2);
	}
	
	// line #3
	if ( $OFFER->get("contact") != "" ) {
        $pdf->SetX($left);
    	$pdf->SetTextColor(130,130,130);
    	$pdf->Cell(71, 11.3, ucfirst(KLDECOR_OFFER_CONTACT).":", 0, 0);
    	$pdf->SetTextColor(0,0,0);
    	$pdf->Cell(71, 11.3, strtotime($OFFER->get("contact")), 0, 2);
	}
	
    // line #4
    if ( $OFFER->get("remark") != "" ) {
        $pdf->SetX($left);
    	$pdf->SetTextColor(130,130,130);
    	$pdf->Cell(71, 11.3, iconv('UTF-8', 'windows-1252', ucfirst(KLDECOR_OFFER_REMARK).":"), 0, 0);
    	$pdf->SetTextColor(0,0,0);
    	$pdf->Cell(71, 11.3, iconv('UTF-8', 'windows-1252', strtotime($OFFER->get("remark"))), 0, 2);
    }

}

// artikel overzicht - begin
if (true) {
    $page = 1; 
    $left = 28;
    $cw = array(65,26,201,26,56,60);

    $items = $OFFER->get("item");
    $itemcount = 0;
    while ($itemcount < count($items)) {
        if ($page == 1) {
            $top = 279;
        } else {
            $top = 156;
        }
    	$pdf->SetXY($left,$top);
    	$startY = $pdf->GetY();
    	$pdf->SetFont('Merriweather-light','',8);
    	$pdf->SetTextColor(130,130,130);
    	$pdf->Cell($cw[0], 11.3, iconv('UTF-8', 'windows-1252', strtolower(KLDECOR_OFFER_SKU)), 0, 0);
    	$pdf->setX($pdf->GetX()+17);
    	$pdf->Cell($cw[1], 11.3, iconv('UTF-8', 'windows-1252', strtolower(KLDECOR_OFFER_COLOR)), 0, 0);
    	$pdf->setX($pdf->GetX()+17);
    	$pdf->Cell($cw[2], 11.3, iconv('UTF-8', 'windows-1252', strtolower(KLDECOR_OFFER_DESCRIPTION)), 0, 0);
    	$pdf->setX($pdf->GetX()+17);
    	$pdf->Cell($cw[3], 11.3, iconv('UTF-8', 'windows-1252', strtolower(KLDECOR_OFFER_VOLUME)), 0, 0);
    	$pdf->setX($pdf->GetX()+17);
    	$pdf->Cell($cw[4], 11.3, iconv('UTF-8', 'windows-1252', strtolower(KLDECOR_OFFER_PRICE)), 0, 0);
    	$pdf->setX($pdf->GetX()+17);
    	$pdf->Cell($cw[5], 11.3, iconv('UTF-8', 'windows-1252', strtolower(KLDECOR_OFFER_PRICE_TOTAL)), 0, 2);
        
        $total = 0;
        $ntop = $pdf->GetY();
        $addpage = false;
        for ( $icount=$itemcount;$icount<count($items); $icount++) {
            $itemcount = $icount;
            $item = $items[$icount];
        	$pdf->SetTextColor(0,0,0);
        	$pdf->setXY($left,$ntop+10);
        	$ttop = $pdf->GetY();
        	$pdf->Cell($cw[0], 11.3, iconv('UTF-8', 'windows-1252', $item->sku), 0, 0);
    	    $pdf->setX($pdf->GetX()+17);
    	    if ( strlen($item->field0) <= 5 ) {
    	        $f = $item->field0;
    	    } else {
    	        $f = substr($item->field0,0,3)."...";
    	    }
        	$pdf->Cell($cw[1], 11.3, iconv('UTF-8', 'windows-1252', $f), 0, 0);
    	    $pdf->setX($pdf->GetX()+17);
        	$pdf->Multicell($cw[2], 11.3, iconv('UTF-8', 'windows-1252', $item->name."\n". $item->description), 0, 'L');
        	$ntop = $pdf->GetY();
        	$pdf->setXY($left+$cw[0]+$cw[1]+$cw[2]+17*2,$ttop);
        	$pdf->setX($pdf->GetX()+17);
        	$pdf->Cell($cw[3], 11.3, iconv('UTF-8', 'windows-1252', number_format($item->volume, 2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR)), 0, 0);
    	    $pdf->setX($pdf->GetX()+17);
        	$pdf->Cell($cw[4], 11.3, iconv('UTF-8', 'windows-1252', number_format($item->price, 2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR)." ".$OFFER->get("currency_symbol")), 0, 0,'R');
        	$pdf->setX($pdf->GetX()+17);
        	$pdf->Cell($cw[5], 11.3, iconv('UTF-8', 'windows-1252', number_format($item->volume*$item->price, 2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR)." ".$OFFER->get("currency_symbol")), 0, 2, 'R');
        	$total += $item->volume*$item->price;
        	$pdf->setY($ntop);
        	if ( $pdf->GetY() > 644) {
        	    $addpage = true;
        	    break;
        	}
        }
        $itemcount++;
        
        $itemY = ($pdf->GetY() - $startY +11.4);

        $pdf->SetDrawColor(130,130,130);
        $pdf->SetLineWidth(.5);
        $pdf->Line(17,$top-8,550,$top-8);
        $pdf->Line(17,$top-8+$itemY,550,$top-8+$itemY);

        $pdf->Line(17,$top-8,17,$top-8+$itemY);
        $pdf->Line(102,$top-8,102,$top-8+$itemY);
        $pdf->Line(145,$top-8,145,$top-8+$itemY);
        $pdf->Line(363,$top-8,363,$top-8+$itemY);
        $pdf->Line(406,$top-8,406,$top-8+$itemY);
        $pdf->Line(474,$top-8,474,$top-8+$itemY);
        $pdf->Line(550,$top-8,550,$top-8+$itemY);
        
        $ptop = $pdf->GetY();
    	$pdf->SetXY($left,$top-18);
    	$pdf->SetTextColor(130,130,130);
    	$pdf->SetFont('Merriweather-light','',8);
        $pdf->Cell(71, 11.4, iconv('UTF-8', 'windows-1252', KLDECOR_OFFER_PAGE. " $page"), 0, 0);
        $pdf->setY($ptop);

        if ( $addpage === true ) {
            $pdf->AddPage();
            $page ++;
            if ($show_letterhead === true) {
                $pdf->Image(FS_ADVANCED .$_SESSION["space"]."/briefhoofd_top.png",0,0,595);
                $pdf->Image(FS_ADVANCED .$_SESSION["space"]."/briefhoofd_bottom.png",0,750,595);
            }
        }
    }
    
    
	$startY = $pdf->GetY();
	$pdf->setY($ntop+14);

	$pdf->SetTextColor(0,0,0);
	$total_vat = 0;
	$itemcount = 0;
	foreach ($OFFER->get("calculated_vat") as $vat_id=>$vat) {
        $VAT = new vat($vat_id);
        $VAT->details();
    	$pdf->setX($left);
        $pdf->Cell($cw[0]+$cw[1]+$cw[2]+$cw[3]+$cw[4]+17*4, 11.3, iconv('UTF-8', 'windows-1252', KLDECOR_OFFER_VAT. " " .$VAT->get("name")), 0, 0, 'R');
        $pdf->setX($pdf->GetX()+17);
        $pdf->Cell($cw[5], 11.3, iconv('UTF-8', 'windows-1252', number_format($VAT->calculate($vat), 2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR)." ".$OFFER->get("currency_symbol")), 0, 2, 'R');
        $total_vat += $VAT->calculate($vat);
        $itemcount++;
	}
	
	$pdf->SetFont('Merriweather-bold','',8);
	$pdf->setXY($left,$pdf->GetY()+10);
    $pdf->Cell($cw[0]+$cw[1]+$cw[2]+$cw[3]+$cw[4]+17*4, 11.3, iconv('UTF-8', 'windows-1252', KLDECOR_OFFER_TOTAL_VAT_INCL), 0, 0, 'R');
    $pdf->setX($pdf->GetX()+17);
    $pdf->Cell($cw[5], 11.3, iconv('UTF-8', 'windows-1252', number_format($total+$total_vat, 2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR)." ".$OFFER->get("currency_symbol")), 0, 2, 'R');
	$totalY = ($pdf->GetY() - $startY +11.4);

    $pdf->SetDrawColor(130,130,130);
    $pdf->SetLineWidth(.5);
    $pdf->Line(17,$top-8+$itemY,550,$top-8+$itemY);
    $pdf->Line(17,$top-8+$itemY+$totalY,550,$top-8+$itemY+$totalY);

    $pdf->Line(17,$top-8+$itemY,17,$top-8+$itemY+$totalY);
    $pdf->Line(474,$top-8+$itemY,474,$top-8+$itemY+$totalY);
    $pdf->Line(550,$top-8+$itemY,550,$top-8+$itemY+$totalY);


	$pdf->setXY($left,$top-8+$itemY+$totalY+10);
	$pdf->SetFont('Merriweather-light','',6);
    $pdf->Multicell(0, 9, iconv('UTF-8', 'windows-1252', KLDECOR_OFFER_CONDITIONS ), 0, 'L');
    $pdf->setY($pdf->GetY()+10);
    $pdf->Multicell(0, 9, iconv('UTF-8', 'windows-1252', KLDECOR_SIGN_FOR_OK), 0, 'L');
    
} // artikel overzicht - end
// Export the page to the correct format
$filename = str_replace(" ","_",KLDECOR_OFFER. "_" .$OFFER->get("reference_prefix").$OFFER->get("reference"). ".pdf");
$pdf->Output(html_entity_decode($filename),'D');

?>