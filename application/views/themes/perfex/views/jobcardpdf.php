<?php
defined('BASEPATH') or exit('No direct script access allowed');


//Bill to
$cust_name = format_customer_info($invoice, 'invoice', 'billing');


$company_name .= '<table style="width: 100%; border: 1px solid black; border-collapse: collapse;">';

$company_name .= '<tr><td></td></tr><tr>';
$company_name .= '<td style="text-align: center; font-size: 20px;">';
$company_name .= '<h1>' . invoice_company_name() . '</h1>';
$company_name .= '</td>';
$company_name .= '</tr><tr><td></td></tr>';

$company_name .= '<tr>';
$company_name .= '<td style="text-align: center;">';
$company_name .= invoice_company_address() . ', ' . invoice_company_city() . '-' . invoice_company_zip() . ', ' . invoice_company_state() . '-'
    . invoice_company_country();
$company_name .= '</td>';
$company_name .= '</tr>';

$company_name .= '<tr>';
$company_name .= '<td style="text-align: center;">';
$company_name .= '(M) : ' . invoice_company_phone() . ' E-MAIL : ' . invoice_company_email();
$company_name .= '<br></td>';
$company_name .= '</tr>';

$company_name .= '<tr>';
$company_name .= '<td style="text-align: left; width: 50%;border-top: 1px solid black; font-weight: bold; font-size: 18px; ">';
$company_name .= 'GSTIN No : ' . invoice_company_vat();
$company_name .= '</td>';
$company_name .= '<td style="text-align: right; font-size: 18px; width: 50%; border-top: 1px solid black; font-weight: bold;">';

foreach ($pdf_custom_fields as $field) {
    $value = get_custom_field_value($invoice->id, $field['id'], 'invoice');
    if ($field['name'] == 'State Code') {
        if ($value == '') {
            continue;
        }
        $state_code = $value;
    }
}
$company_name .= 'STATE CODE : ' . $state_code;
$company_name .= '</td>';
$company_name .= '</tr>';

$company_name .= '<tr>';
$company_name .= '<td style="text-align: center; width: 80%;border-top: 1px solid black; border-bottom: 1px solid black;">';
$company_name .= '<span style="font-size: 25px; font-weight: bold;">TAX INVOICE</span> <br> Under Section 31 Read with Rule 7 GST 2017';
$company_name .= '</td>';
$company_name .= '<td style="text-align: right; width: 20%;border-top: 1px solid black; border-bottom: 1px solid black;font-size: 13.5px; ">';
$company_name .= 'Original <br> Duplicate';
$company_name .= '</td>';
$company_name .= '</tr>';


$company_name .= '<tr>';
$company_name .= '<td style="width: 60%; border-right: 1px solid black; font-weight: bold;"> ';
$company_name .= ' M/s : &nbsp; ' . $cust_name;
$company_name .= '</td>';
$company_name .= '<td style="width: 40%; font-weight: bold;"> ';
$company_name .= ' Invoice No : ' . $invoice_number;
$company_name .= '</td>';
$company_name .= '</tr>';

$company_name .= '<tr>';
$company_name .= '<td style="width: 60%; border-right: 1px solid black;"> ';
$company_name .= '<span style="font-weight: bold;"> Add : </span>';
$company_name .= '</td>';
$company_name .= '<td style="width: 40%;"> ';
$company_name .= ' Date : <span style="font-weight: bold;">' . $invoice->date;
$company_name .= '</span></td>';
$company_name .= '</tr>';

$company_name .= '<tr>';
$company_name .= '<td style="width: 60%; border-right: 1px solid black;"> ';
$company_name .= '</td>';
$company_name .= '<td style="width: 40%;"> ';
$company_name .= ' Transport : ';
$company_name .= '</td>';
$company_name .= '</tr>';

$company_name .= '<tr>';
$company_name .= '<td style="width: 60%; border-right: 1px solid black;"> ';
$company_name .= '</td>';
$company_name .= '<td style="width: 40%;"> ';
$company_name .= ' Lr No : ';
$company_name .= '</td>';
$company_name .= '</tr>';

$company_name .= '<tr>';
$company_name .= '<td style="width: 60%; border-right: 1px solid black;"> ';
$company_name .= '<span style="font-weight: bold;"> GSTIN : .</span >';
$company_name .= '</td>';
$company_name .= '<td style="width: 40%;"> ';
$company_name .= ' Lr Date : ';
$company_name .= '</td>';
$company_name .= '</tr>';

$company_name .= '<tr>';
$company_name .= '<td style="width: 30%;"> ';
$company_name .= '<span style="font-weight: bold;"> Mob : .</span>';
$company_name .= '</td>';
$company_name .= '<td style="width: 20%; border: 1px solid black; text-align: center; font-weight: bold;"> ';
$company_name .= ' State Code : ';
$company_name .= '</td>';
$company_name .= '<td style="width: 10%; border: 1px solid black;  text-align: center; font-weight: bold;"> ';
$company_name .= $state_code;
$company_name .= '</td>';
$company_name .= '<td style="width: 40%;"> ';
$company_name .= ' Notes : ';
$company_name .= '</td>';
$company_name .= '</tr>';

// The items table
$items = get_items_table_data($invoice, 'invoice', 'pdf');

$company_name .= $items->mycustom_table();

$total_qty = $items->mycustom_qty();

$total =  app_format_money($invoice->subtotal, $invoice->currency_name);
$total_tax = app_format_money($invoice->total_tax, $invoice->currency_name);
$net_amount = app_format_money($invoice->subtotal + $invoice->total_tax, $invoice->currency_name);
$amt_in_words = strtoupper(numToWordsRec($invoice->subtotal + $invoice->total_tax));

//Bank Details
$company_name .= '<tr style="background-color: rgb(221, 221, 221);">';
$company_name .= '<td style="width: 50%; border: 1px solid black; font-size: 16px; text-align: center;"> ';
$company_name .= 'BANK DETAILS';
$company_name .= '</td>';
$company_name .= '<td style="width: 20%; border: 1px solid black; text-align: center; font-size: 16px;"> ';
$company_name .= ' Total Qty :';
$company_name .= '</td>';
$company_name .= '<td style="width: 8%; border: 1px solid black;  text-align: center; font-size: 16px;"> ';
$company_name .=  $total_qty;
$company_name .= '</td>';
$company_name .= '<td style="width: 8%;border: 1px solid black; font-size: 16px; text-align: center;"> ';
$company_name .= ' Total :';
$company_name .= '</td>';
$company_name .= '<td style="width: 14%;border: 1px solid black; font-size: 16px; text-align: center;"> ';
$company_name .= $total;
$company_name .= '</td>';
$company_name .= '</tr>';

$company_name .= '<tr>';
$company_name .= '<td style="width: 70%;font-size: 16px;"> ';
$company_name .= 'Bank : .';
$company_name .= '</td>';
$company_name .= '<td style="width: 16%; border: 1px solid black; text-align: right;font-size: 16px;"> ';
$company_name .= 'DISC 0% :';
$company_name .= '</td>';
$company_name .= '<td style="width: 14%; border: 1px solid black; font-size: 16px;"> ';
$company_name .=  '';
$company_name .= '</td>';
$company_name .= '</tr>';

$company_name .= '<tr>';
$company_name .= '<td style="width: 70%;font-size: 16px;"> ';
$company_name .= 'Branch : .';
$company_name .= '</td>';
$company_name .= '<td style="width: 16%; border: 1px solid black; text-align: right;font-size: 16px;"> ';
$company_name .= '';
$company_name .= '</td>';
$company_name .= '<td style="width: 14%; border: 1px solid black; font-size: 16px;"> ';
$company_name .=  '';
$company_name .= '</td>';
$company_name .= '</tr>';

$company_name .= '<tr>';
$company_name .= '<td style="width: 70%;font-size: 16px;"> ';
$company_name .= 'A/C No : .';
$company_name .= '</td>';
foreach ($items->taxes() as $tax) {
    if ($tax['taxname'] == 'CGST') {
        $company_name .= '<td align="right" width="16%" style="border: 1px solid black;">' . $tax['taxname'] . ' (' . app_format_number($tax['taxrate']) . '%)' . '</td>
    <td align="right" width="14%" style="font-size: 16px; border: 1px solid black;">' . app_format_money($tax['total_tax'], $invoice->currency_name) . '</td>';
    }
}
$company_name .= '</tr>';

$company_name .= '<tr>';
$company_name .= '<td style="width: 70%;font-size: 16px;"> ';
$company_name .= 'IFSC : .';
$company_name .= '</td>';
foreach ($items->taxes() as $tax) {
    if ($tax['taxname'] == 'SGST') {
        $company_name .= '<td align="right" width="16%" style="border: 1px solid black;">' . $tax['taxname'] . ' (' . app_format_number($tax['taxrate']) . '%)' . '</td>
    <td align="right" width="14%" style="font-size: 16px; border: 1px solid black;">' . app_format_money($tax['total_tax'], $invoice->currency_name) . '</td>';
    }
}
$company_name .= '</tr>';



$company_name .= '<tr>';
$company_name .= '<td style="width: 70%;font-size: 15px; border-top: 1px solid black;"> ';
$company_name .= '<b>RS : ' . $amt_in_words . ' ONLY</b>';
$company_name .= '</td>';
$company_name .= '<td style="width: 16%; border: 1px solid black; text-align: right;font-size: 14px;background-color: rgb(221, 221, 221);"> ';
$company_name .= 'Total Amount GST:';
$company_name .= '</td>';
$company_name .= '<td align="right" style="width: 14%; border: 1px solid black; font-size: 16px;background-color: rgb(221, 221, 221);"> ';
$company_name .=  $total_tax;
$company_name .= '</td>';
$company_name .= '</tr>';

$company_name .= '<tr>';
$company_name .= '<td style="width: 70%;font-size: 16px;"> ';
$company_name .= '';
$company_name .= '</td>';
$company_name .= '<td style="width: 16%; border: 1px solid black; text-align: right;font-size: 16px;background-color: rgb(221, 221, 221);"> ';
$company_name .= 'Net Amount :';
$company_name .= '</td>';
$company_name .= '<td align="right" style="width: 14%; border: 1px solid black; font-size: 16px;background-color: rgb(221, 221, 221);"> ';
$company_name .=  $net_amount;
$company_name .= '</td>';
$company_name .= '</tr>';

$company_name .= '<tr>';
$company_name .= '<td align="center" style="width: 70%;font-size: 16px;  border-top: 1px solid black;"> ';
$company_name .= '<b>Terms & Conditions:</b>';
$company_name .= '</td>';
$company_name .= '<td style="width: 30%; border-left: 1px solid black; text-align: center;font-size: 16px;"> ';
$company_name .= '<strong>For, ' . invoice_company_name() . '</strong>';
$company_name .= '</td>';
$company_name .= '</tr>';

$company_name .= '<tr>';
$company_name .= '<td style="width: 70%;font-size: 14px;"> ';
$company_name .= '1. Goods Once Sold Can Not Be Taken Back.';
$company_name .= '</td>';
$company_name .= '<td style="width: 30%; border-left: 1px solid black; text-align: center;font-size: 16px;"> ';
$company_name .= '';
$company_name .= '</td>';
$company_name .= '</tr>';

$company_name .= '<tr>';
$company_name .= '<td style="width: 70%;font-size: 14px;"> ';
$company_name .= '2. Interest @ 18% Will Be Charged On Over Due Payments.';
$company_name .= '</td>';
$company_name .= '<td style="width: 30%; border-left: 1px solid black; text-align: center;font-size: 16px;"> ';
$company_name .= '';
$company_name .= '</td>';
$company_name .= '</tr>';

$company_name .= '<tr>';
$company_name .= '<td style="width: 70%;font-size: 14px;"> ';
$company_name .= '3. Payment Within 30 Days.';
$company_name .= '</td>';
$company_name .= '<td style="width: 30%; border-left: 1px solid black; text-align: center;font-size: 16px;"> ';
$company_name .= '';
$company_name .= '</td>';
$company_name .= '</tr>';

$company_name .= '<tr>';
$company_name .= '<td style="width: 70%;font-size: 14px;"> ';
$company_name .= '4. Subject To Ahmedabad Juridiction';
$company_name .= '</td>';
$company_name .= '<td style="width: 30%; border-left: 1px solid black; text-align: center;font-size: 16px;"> ';
$company_name .= 'Authorised Signatory';
$company_name .= '</td>';
$company_name .= '</tr>';

$company_name .= '</table>';

$pdf->writeHTML($company_name, true, false, false, false, '');




// $info_right_column .= '<span style="font-weight:bold;font-size:27px;">' . _l('invoice_pdf_heading') . '</span><br />';
// $info_right_column .= '<b style="color:#4e4e4e;"># ' . $invoice_number . '</b>';

// if (get_option('show_status_on_pdf_ei') == 1) {
//     $info_right_column .= '<br /><span style="color:rgb(' . invoice_status_color_pdf($status) . ');text-transform:uppercase;">' . format_invoice_status($status, '', false) . '</span>';
// }

// if (
//     $status != Invoices_model::STATUS_PAID && $status != Invoices_model::STATUS_CANCELLED && get_option('show_pay_link_to_invoice_pdf') == 1
//     && found_invoice_mode($payment_modes, $invoice->id, false)
// ) {
//     $info_right_column .= ' - <a style="color:#84c529;text-decoration:none;text-transform:uppercase;" href="' . site_url('invoice/' . $invoice->id . '/' . $invoice->hash) . '"><1b>' . _l('view_invoice_pdf_link_pay') . '</1b></a>';
// }

// Add logo
// $info_left_column .= pdf_logo_url();

// //Write top left logo and right column info/text
// pdf_multi_row($info_left_column, $info_right_column, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

// $pdf->ln(10);



//Bill to
// $invoice_info = '<b>' . _l('invoice_bill_to') . ':</b>';
// $invoice_info .= '<div style="color:#424242; margin-top:120px">';
// $invoice_info .= format_customer_info($invoice, 'invoice', 'billing');
// $invoice_info .= '</div>';


//ship to to
// if ($invoice->include_shipping == 1 && $invoice->show_shipping_on_invoice == 1) {
//     $invoice_info .= '<br /><b>' . _l('ship_to') . ':</b>';
//     $invoice_info .= '<div style="color:#424242;">';
//     $invoice_info .= format_customer_info($invoice, 'invoice', 'shipping');
//     $invoice_info .= '</div>';
// }

// $invoice_info .= '<br />' . _l('invoice_data_date') . ' ' . _d($invoice->date) . '<br />';

// $invoice_info = hooks()->apply_filters('invoice_pdf_header_after_date', $invoice_info, $invoice);

// if (!empty($invoice->duedate)) {
//     $invoice_info .= _l('invoice_data_duedate') . ' ' . _d($invoice->duedate) . '<br />';
//     $invoice_info = hooks()->apply_filters('invoice_pdf_header_after_due_date', $invoice_info, $invoice);
// }

// if ($invoice->sale_agent && get_option('show_sale_agent_on_invoices') == 1) {
//     $invoice_info .= _l('sale_agent_string') . ': ' . get_staff_full_name($invoice->sale_agent) . '<br />';
//     $invoice_info = hooks()->apply_filters('invoice_pdf_header_after_sale_agent', $invoice_info, $invoice);
// }

// if ($invoice->project_id && get_option('show_project_on_invoice') == 1) {
//     $invoice_info .= _l('project') . ': ' . get_project_name_by_id($invoice->project_id) . '<br />';
//     $invoice_info = hooks()->apply_filters('invoice_pdf_header_after_project_name', $invoice_info, $invoice);
// }

// $invoice_info = hooks()->apply_filters('invoice_pdf_header_before_custom_fields', $invoice_info, $invoice);

// foreach ($pdf_custom_fields as $field) {
//     $value = get_custom_field_value($invoice->id, $field['id'], 'invoice');
//     if ($value == '') {
//         continue;
//     }
//     $invoice_info .= $field['name'] . ': ' . $value . '<br />';
// }

// $invoice_info = hooks()->apply_filters('invoice_pdf_header_after_custom_fields', $invoice_info, $invoice);
// $organization_info = hooks()->apply_filters('invoicepdf_organization_info', $organization_info, $invoice);
// $invoice_info = hooks()->apply_filters('invoice_pdf_info', $invoice_info, $invoice);

// $left_info = $swap == '1' ? $invoice_info : $organization_info;
// $right_info = $swap == '1' ? $organization_info : $invoice_info;

// pdf_multi_row($left_info, $right_info, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

// // The Table
// $pdf->Ln(hooks()->apply_filters('pdf_info_and_table_separator', 6));

// The items table
// $items = get_items_table_data($invoice, 'invoice', 'pdf');

// $tblhtml = $items->mycustom_table();

// $pdf->writeHTML($tblhtml, true, false, false, false, '');
// $pdf->Ln(8);


// $tbltotal = '';
// $tbltotal .= '<table cellpadding="6" style="font-size:' . ($font_size + 4) . 'px">';
// $tbltotal .= '
// <tr>
//     <td align="right" width="85%"><strong>' . _l('invoice_subtotal') . '</strong></td>
//     <td align="right" width="15%">' . app_format_money($invoice->subtotal, $invoice->currency_name) . '</td>
// </tr>';

// if (is_sale_discount_applied($invoice)) {
//     $tbltotal .= '
//     <tr>
//         <td align="right" width="85%"><strong>' . _l('invoice_discount');
//     if (is_sale_discount($invoice, 'percent')) {
//         $tbltotal .= ' (' . app_format_number($invoice->discount_percent, true) . '%)';
//     }
//     $tbltotal .= '</strong>';
//     $tbltotal .= '</td>';
//     $tbltotal .= '<td align="right" width="15%">-' . app_format_money($invoice->discount_total, $invoice->currency_name) . '</td>
//     </tr>';
// }

// foreach ($items->taxes() as $tax) {
//     $tbltotal .= '<tr>
//     <td align="right" width="85%"><strong>' . $tax['taxname'] . ' (' . app_format_number($tax['taxrate']) . '%)' . '</strong></td>
//     <td align="right" width="15%">' . app_format_money($tax['total_tax'], $invoice->currency_name) . '</td>
// </tr>';
// }

// if ((int) $invoice->adjustment != 0) {
//     $tbltotal .= '<tr>
//     <td align="right" width="85%"><strong>' . _l('invoice_adjustment') . '</strong></td>
//     <td align="right" width="15%">' . app_format_money($invoice->adjustment, $invoice->currency_name) . '</td>
// </tr>';
// }

// $tbltotal .= '
// <tr style="background-color:#f0f0f0;">
//     <td align="right" width="85%"><strong>' . _l('invoice_total') . '</strong></td>
//     <td align="right" width="15%">' . app_format_money($invoice->total, $invoice->currency_name) . '</td>
// </tr>';

// if (count($invoice->payments) > 0 && get_option('show_total_paid_on_invoice') == 1) {
//     $tbltotal .= '
//     <tr>
//         <td align="right" width="85%"><strong>' . _l('invoice_total_paid') . '</strong></td>
//         <td align="right" width="15%">-' . app_format_money(sum_from_table(db_prefix() . 'invoicepaymentrecords', [
//         'field' => 'amount',
//         'where' => [
//             'invoiceid' => $invoice->id,
//         ],
//     ]), $invoice->currency_name) . '</td>
//     </tr>';
// }

// if (get_option('show_credits_applied_on_invoice') == 1 && $credits_applied = total_credits_applied_to_invoice($invoice->id)) {
//     $tbltotal .= '
//     <tr>
//         <td align="right" width="85%"><strong>' . _l('applied_credits') . '</strong></td>
//         <td align="right" width="15%">-' . app_format_money($credits_applied, $invoice->currency_name) . '</td>
//     </tr>';
// }

// if (get_option('show_amount_due_on_invoice') == 1 && $invoice->status != Invoices_model::STATUS_CANCELLED) {
//     $tbltotal .= '<tr style="background-color:#f0f0f0;">
//        <td align="right" width="85%"><strong>' . _l('invoice_amount_due') . '</strong></td>
//        <td align="right" width="15%">' . app_format_money($invoice->total_left_to_pay, $invoice->currency_name) . '</td>
//    </tr>';
// }

// $tbltotal .= '</table>';
// $pdf->writeHTML($tbltotal, true, false, false, false, '');

// if (get_option('total_to_words_enabled') == 1) {
//     // Set the font bold
//     $pdf->SetFont($font_name, 'B', $font_size);
//     $pdf->writeHTMLCell('', '', '', '', _l('num_word') . ': ' . $CI->numberword->convert($invoice->total, $invoice->currency_name), 0, 1, false, true, 'C', true);
//     // Set the font again to normal like the rest of the pdf
//     $pdf->SetFont($font_name, '', $font_size);
//     $pdf->Ln(4);
// }

// if (count($invoice->payments) > 0 && get_option('show_transactions_on_invoice_pdf') == 1) {
//     $pdf->Ln(4);
//     $border = 'border-bottom-color:#000000;border-bottom-width:1px;border-bottom-style:solid; 1px solid black;';
//     $pdf->SetFont($font_name, 'B', $font_size);
//     $pdf->Cell(0, 0, _l('invoice_received_payments') . ':', 0, 1, 'L', 0, '', 0);
//     $pdf->SetFont($font_name, '', $font_size);
//     $pdf->Ln(4);
//     $tblhtml = '<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="5" border="0">
//         <tr height="20"  style="color:#000;border:1px solid #000;">
//         <th width="25%;" style="' . $border . '">' . _l('invoice_payments_table_number_heading') . '</th>
//         <th width="25%;" style="' . $border . '">' . _l('invoice_payments_table_mode_heading') . '</th>
//         <th width="25%;" style="' . $border . '">' . _l('invoice_payments_table_date_heading') . '</th>
//         <th width="25%;" style="' . $border . '">' . _l('invoice_payments_table_amount_heading') . '</th>
//     </tr>';
//     $tblhtml .= '<tbody>';
//     foreach ($invoice->payments as $payment) {
//         $payment_name = $payment['name'];
//         if (!empty($payment['paymentmethod'])) {
//             $payment_name .= ' - ' . $payment['paymentmethod'];
//         }
//         $tblhtml .= '
//             <tr>
//             <td>' . $payment['paymentid'] . '</td>
//             <td>' . $payment_name . '</td>
//             <td>' . _d($payment['date']) . '</td>
//             <td>' . app_format_money($payment['amount'], $invoice->currency_name) . '</td>
//             </tr>
//         ';
//     }
//     $tblhtml .= '</tbody>';
//     $tblhtml .= '</table>';
//     $pdf->writeHTML($tblhtml, true, false, false, false, '');
// }

// if (found_invoice_mode($payment_modes, $invoice->id, true, true)) {
//     $pdf->Ln(4);
//     $pdf->SetFont($font_name, 'B', $font_size);
//     $pdf->Cell(0, 0, _l('invoice_html_offline_payment') . ':', 0, 1, 'L', 0, '', 0);
//     $pdf->SetFont($font_name, '', $font_size);

//     foreach ($payment_modes as $mode) {
//         if (is_numeric($mode['id'])) {
//             if (!is_payment_mode_allowed_for_invoice($mode['id'], $invoice->id)) {
//                 continue;
//             }
//         }
//         if (isset($mode['show_on_pdf']) && $mode['show_on_pdf'] == 1) {
//             $pdf->Ln(1);
//             $pdf->Cell(0, 0, $mode['name'], 0, 1, 'L', 0, '', 0);
//             $pdf->Ln(2);
//             $pdf->writeHTMLCell('', '', '', '', $mode['description'], 0, 1, false, true, 'L', true);
//         }
//     }
// }

// if (!empty($invoice->clientnote)) {
//     $pdf->Ln(4);
//     $pdf->SetFont($font_name, 'B', $font_size);
//     $pdf->Cell(0, 0, _l('invoice_note'), 0, 1, 'L', 0, '', 0);
//     $pdf->SetFont($font_name, '', $font_size);
//     $pdf->Ln(2);
//     $pdf->writeHTMLCell('', '', '', '', $invoice->clientnote, 0, 1, false, true, 'L', true);
// }

// if (!empty($invoice->terms)) {
//     $pdf->Ln(4);
//     $pdf->SetFont($font_name, 'B', $font_size);
//     $pdf->Cell(0, 0, _l('terms_and_conditions') . ':', 0, 1, 'L', 0, '', 0);
//     $pdf->SetFont($font_name, '', $font_size);
//     $pdf->Ln(2);
//     $pdf->writeHTMLCell('', '', '', '', $invoice->terms, 0, 1, false, true, 'L', true);
// }
