<?php
defined("__WINGMAN__") or exit("Chucks! this ain't working!");
#error_reporting(E_ALL);
ini_set('display_errors', '1');
### to_pdf.php export transaction to pdf
if (!isset($show_letterhead)) $show_letterhead = false;
if ( !file_exists($path->get("fs_lib") ."fpdf/fpdf.php") )          
        die("'" .$path->get("fs_lib") ."fpdf/fpdf.php' is required for this export module!!!");
if ( !file_exists($path->get("fs_lib") ."fpdf/fpdf_protection.php") )
        die("'" .$path->get("fs_lib") ."fpdf/fpdf_protection.php' is required for this export module!!!");
if ( !file_exists($path->get("fs_lib") ."fpdf/font/unvr57w.php") )
        die("'" .$path->get("fs_lib") ."fpdf/font/unvr57w.php' is required for this export module!!!");
if ( !file_exists($path->get("fs_lib") ."fpdf/font/unvr57w.z") )
        die("'" .$path->get("fs_lib") ."fpdf/font/unvr57w.z' is required for this export module!!!");
if ( !file_exists($path->get("fs_lib") ."fpdf/font/unvr67w.php") )
        die("'" .$path->get("fs_lib") ."fpdf/font/unvr67w.php' is required for this export module!!!");
if ( !file_exists($path->get("fs_lib") ."fpdf/font/unvr67w.z") )
        die("'" .$path->get("fs_lib") ."fpdf/font/unvr67w.z' is required for this export module!!!");
if ( !file_exists($path->get("fs_lib")."advanced/offer/footer.php") )
        die("'" .$path->get("fs_lib") ."advanced/offer/footer.php' is required for this export module!!!");
include_once($path->get("fs_lib") ."fpdf/fpdf_protection.php");
$data = new stdClass();
if (count($_GET["advanced"]) != 1) die( "too many or no ids to export. I expect only one!");
$data->id   = $_GET["advanced"][0];
$_offer     = api_offer::details($data, (int)$_GET["id"], $_GET["token"]);
$data->id   = $_offer->data->entity_id;
$_entity    = api_entity::details($data, (int)$_GET["id"], $_GET["token"]);
$data->id   = $_offer->data->vat_id;
$_vat       = api_vat::details($data, (int)$_GET["id"], $_GET["token"]);
$data->id   = $_offer->data->currency_id;
$_currency  = api_vat::details($data, (int)$_GET["id"], $_GET["token"]);
$conditions = new stdClass();
include_once("conditions." .$_entity->data->correspondence_language_id. ".php");
// Extend the base FPDF class for our purposes
include_once($path->get("fs_lib")."advanced/offer/footer.php");
$pdf=new PDF('P','mm','A4');
$pdf->SetProtection(array('print'));
$pdf->SetCreator("wingman.critter.be");
$pdf->SetTitle(html_entity_decode(ucfirst(locale::translate("offer",$_entity->data->correspondence_language_id))). " " .html_entity_decode($_offer->data->reference));
$pdf->SetAuthor("Critter BVBA");
$pdf->SetKeywords(html_entity_decode(ucfirst(locale::translate("offer",$_entity->data->correspondence_language_id))). " " .html_entity_decode($_offer->data->reference). " Critter");
$pdf->AddFont('Univers-Condensed-Bold','','unvr67w.php');
$pdf->AddFont('Univers-Condensed-Medium','','unvr57w.php');

$pdf->AliasNbPages();
$left = $pdf->GetX();

// Page 1 - Begin
$pdf->AddPage();
//letterhead
if ($show_letterhead) $pdf->Image($path->get("fs_advanced")."/transaction/letterhead.png",0,0,210);

$leftmargin = 60;

// Address - Begin
if (true) {
        $pdf->SetXY(110,35);
        $pdf->SetFont('Univers-Condensed-Bold','',12);
        $pdf->Cell(0, 4.5, html_entity_decode($_entity->data->name), 0, 2);
        $pdf->SetFont('Univers-Condensed-Medium','',12);
        $pdf->Cell(0, 4.5, html_entity_decode($_entity->address->invoicing->street), 0, 2);
        $pdf->Cell(0, 4.5, html_entity_decode($_entity->address->invoicing->code. " " .$_entity->address->invoicing->city), 0, 2);
        $pdf->Cell(0, 4.5, html_entity_decode($_entity->address->invoicing->country), 0, 1);
// Address - End
}

// Greetings - Begin
if (true) {
        $pdf->SetXY($leftmargin,80);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0, 4.5, locale::formatDate(strtotime($_offer->data->date), "long",$_entity->data->correspondence_language_id),0,2);

        $pdf->SetX($leftmargin);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0, 4.5, html_entity_decode(ucfirst(locale::translate("reference",$_entity->data->correspondence_language_id)). ": " .$_offer->data->reference),0,2);


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
        $pdf->Cell(20, 2, html_entity_decode(ucfirst(locale::translate("price per",$_entity->data->correspondence_language_id))),0,0,'C');
        $pdf->Cell(20, 2, html_entity_decode(ucfirst(locale::translate("total",$_entity->data->correspondence_language_id))),0,0,'C');
        $pdf->Cell(10, 2, '',0,1,'C');

        $pdf->SetX($leftmargin);
        $pdf->Cell(20, 4.5, html_entity_decode(ucfirst(locale::translate("sku",$_entity->data->correspondence_language_id))));
        $pdf->Cell(60, 4.5, html_entity_decode(ucfirst(locale::translate("description",$_entity->data->correspondence_language_id))));
        $pdf->Cell(10, 4.5, html_entity_decode(ucfirst(locale::translate("volume",$_entity->data->correspondence_language_id))),0,0,'C');
        $pdf->Cell(20, 4.5, html_entity_decode(ucfirst(locale::translate("unit",$_entity->data->correspondence_language_id))),0,0,'C');
        $pdf->Cell(20, 4.5, html_entity_decode(ucfirst(locale::translate("(net)",$_entity->data->correspondence_language_id))),0,0,'C');
        $pdf->Cell(10, 4.5, html_entity_decode(ucfirst(locale::translate("vat",$_entity->data->correspondence_language_id))),0,1,'C');

        $pdf->Line(60,$top+6,200,$top+6);

        $pdf->SetFont('Arial','',7.5);
        $newY = $pdf->GetY();
        $total = 0;
        $total_vat = 0;
        foreach ($_offer->item->data as $item) {
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
                $pdf->Cell(10, 4, html_entity_decode(locale::formatNumber($item->volume,3)),0,0,'R');
                $pdf->SetXY($leftmargin+90,$Y);
                $pdf->Cell(20, 4, html_entity_decode(locale::formatNumber($item->price, 2)). " " .iconv('UTF-8', 'windows-1252', $_offer->data->currency_symbol),0,0,'R');
                $pdf->SetXY($leftmargin+110,$Y);
                $pdf->Cell(20, 4, html_entity_decode(locale::formatNumber($item->total, 2)). " " .iconv('UTF-8', 'windows-1252', $_offer->data->currency_symbol),0,0,'R');
                $pdf->SetXY($leftmargin+130,$Y);
                $pdf->Cell(10, 4, html_entity_decode($_offer->data->vat),0,1,'C');
                $total += $item->total;
                switch ($_offer->data->vat_type)  {
                        case "relative":
                                $total_vat += ($item->total/100*(int)$_offer->data->vat_amount);
                                break;
                }
        }

        $newY+=4;
        $pdf->Line(60,$newY,200,$newY);
        $pdf->SetXY($leftmargin,$newY);
        $pdf->SetFont('Arial','',7.5);
        $pdf->Cell(90, 4, "");
        $pdf->Cell(20, 4, html_entity_decode(ucfirst(locale::translate("subtotal",$_entity->data->correspondence_language_id))));
        $pdf->Cell(20, 4, locale::formatNumber($total, 2). " " .iconv('UTF-8', 'windows-1252', $_offer->data->currency_symbol),0,1,'R');

        $pdf->SetX($leftmargin);
        $pdf->Cell(90, 4, "");
        $pdf->Cell(20, 4, html_entity_decode(ucfirst(locale::translate("total vat",$_entity->data->correspondence_language_id))));
        $pdf->Cell(20, 4, locale::formatNumber($total_vat, 2). " " .iconv('UTF-8', 'windows-1252', $_offer->data->currency_symbol),0,1,'R');

        $pdf->SetX($leftmargin);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->Cell(90, 4, "");
        $pdf->Cell(20, 4, html_entity_decode(ucfirst(locale::translate("total",$_entity->data->correspondence_language_id))));
        $pdf->Cell(20, 4, locale::formatNumber($total+$total_vat, 2). " " .iconv('UTF-8', 'windows-1252', $_offer->data->currency_symbol),0,1,'R');

}

// Closing - Start //
if (true) {
        $newY = $pdf->GetY()+20;
        $pdf->SetXY($leftmargin,$newY);
        $pdf->SetFont('Arial','',7.5);
        $pdf->MultiCell(0,4.5,html_entity_decode(ucfirst(locale::translate("kind regards",$_entity->data->correspondence_language_id))));
        $newY = $pdf->GetY();

        $pdf->SetXY($leftmargin,$newY);
        $pdf->SetFont('Arial','',7.5);
        $pdf->MultiCell(0,4.5,html_entity_decode(ucfirst(locale::translate("William Leemans, manager",$_entity->data->correspondence_language_id))));

// Closing - Start //
}

// Voorwaarden - Start //
if (true) {
        $pdf->SetTextColor(153,153,153);
        $pdf->SetY(270);
        $pdf->SetFont('Univers-Condensed-Medium','',6);
        $pdf->MultiCell(0, 2, html_entity_decode($_conditions->note));
// Voorwaarden - End //
}
// Page 1 - End

if ($show_letterhead) {
        $pdf->AddPage();
        $pdf->SetTextColor(153,153,153);
        $pdf->SetFont('Univers-Condensed-Bold','',10);
        $pdf->MultiCell(0, 10, html_entity_decode($_conditions->title));
        $pdf->SetFont('Univers-Condensed-Medium','',10);
        $pdf->MultiCell(0, 4, html_entity_decode($_conditions->general));
}

// Export the page to the correct format
$filename = str_replace(" ","_",locale::translate("offer",$_entity->data->correspondence_language_id). "_" .$_offer->data->reference. ".pdf");
$pdf->Output(html_entity_decode($filename),'D');
?>