<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(APPPATH . 'libraries/App_items_table_template.php');

class App_items_table extends App_items_table_template
{
    public function __construct($transaction, $type, $for = 'html', $admin_preview = false)
    {
        // Required
        $this->type          = strtolower($type);
        $this->admin_preview = $admin_preview;
        $this->for           = $for;

        $this->set_transaction($transaction);
        $this->set_items($transaction->items);

        parent::__construct();
    }

    /**
     * Builds the actual table items rows preview
     * @return string
     */
    public function items()
    {
        $html = '';


        $descriptionItemWidth = $this->get_description_item_width();

        $regularItemWidth  = $this->get_regular_items_width(6);
        $customFieldsItems = $this->get_custom_fields_for_table();

        if ($this->for == 'html') {
            $descriptionItemWidth = $descriptionItemWidth - 5;
            $regularItemWidth     = $regularItemWidth - 5;
        }

        $i = 1;
        foreach ($this->items as $item) {
            $itemHTML = '';

            // Open table row
            $itemHTML .= '<tr' . $this->tr_attributes($item) . '>';

            // Table data number
            $itemHTML .= '<td' . $this->td_attributes() . ' align="center" width="5%">' . $i . '</td>';

            $itemHTML .= '<td class="description" align="left;" width="' . $descriptionItemWidth . '%">';

            /**
             * Item description
             */
            if (!empty($item['description'])) {
                $itemHTML .= '<span style="font-size:' . $this->get_pdf_font_size() . 'px;"><strong>'
                    . $this->period_merge_field($item['description'])
                    . '</strong></span>';

                if (!empty($item['long_description'])) {
                    $itemHTML .= '<br />';
                }
            }

            /**
             * Item long description
             */
            if (!empty($item['long_description'])) {
                $itemHTML .= '<span style="color:#424242;">' . $this->period_merge_field($item['long_description']) . '</span>';
            }

            $itemHTML .= '</td>';

            /**
             * Item custom fields
             */
            foreach ($customFieldsItems as $custom_field) {
                $itemHTML .= '<td align="left" width="' . $regularItemWidth . '%">' . get_custom_field_value($item['id'], $custom_field['id'], 'items') . '</td>';
            }
            /**
             * Item quantity
             */
            $itemHTML .= '<td align="right" width="' . $regularItemWidth . '%">' . e(floatVal($item['qty']));

            /**
             * Maybe item has added unit?
             */
            if ($item['unit']) {
                $itemHTML .= ' ' . e($item['unit']);
            }

            $itemHTML .= '</td>';

            /**
             * Item rate
             * @var string
             */
            $rate = hooks()->apply_filters(
                'item_preview_rate',
                app_format_money($item['rate'], $this->transaction->currency_name, $this->exclude_currency()),
                ['item' => $item, 'transaction' => $this->transaction]
            );

            $itemHTML .= '<td align="right" width="' . $regularItemWidth . '%">' . e($rate) . '</td>';

            /**
             * Items table taxes HTML custom function because it's too general for all features/options
             * @var string
             */
            $itemHTML .= $this->taxes_html($item, $regularItemWidth);

            /**
             * Possible action hook user to include tax in item total amount calculated with the quantiy
             * eq Rate * QTY + TAXES APPLIED
             */
            $item_amount_with_quantity = hooks()->apply_filters(
                'item_preview_amount_with_currency',
                app_format_money(($item['qty'] * $item['rate']), $this->transaction->currency_name, $this->exclude_currency()),
                $item,
                $this->transaction,
                $this->exclude_currency()
            );

            $itemHTML .= '<td class="amount" align="right" width="' . $regularItemWidth . '%">' . e($item_amount_with_quantity) . '</td>';

            // Close table row
            $itemHTML .= '</tr>';

            $html .= $itemHTML;

            $i++;
        }

        return $html;
    }
    public function mycustom_items()
    {
        $html = '';

        $descriptionItemWidth = $this->get_description_item_width();
        $regularItemWidth  = $this->get_regular_items_width(6);
        $customFieldsItems = $this->get_custom_fields_for_table();


        if ($this->for == 'html') {
            $descriptionItemWidth = $descriptionItemWidth - 5;
            $regularItemWidth     = $regularItemWidth - 5;
        }

        $i = 1;
        foreach ($this->items as $item) {
            $itemHTML = '';

            // Open table row
            $itemHTML .= '<tr' . $this->tr_attributes($item) . '>';

            // Table data number
            $itemHTML .= '<td' . $this->td_attributes() . ' align="center" width="10%" style="border-left:1px solid black; border-right: 1px solid black;">' . $i . '</td>';

            $itemHTML .= '<td class="description" width="40%" style="border-right:1px solid black;">';


            /**
             * Item description
             */
            if (!empty($item['description'])) {
                $itemHTML .= '<span style="font-size:' . $this->get_pdf_font_size() . 'px;"><strong>'
                    . $this->period_merge_field($item['description'])
                    . '</strong></span>';

                if (!empty($item['long_description'])) {
                    $itemHTML .= '<br />';
                }
            }

            /**
             * Item long description
             */
            if (!empty($item['long_description'])) {
                $itemHTML .= '<span style="color:#424242;">' . $this->period_merge_field($item['long_description']) . '</span>';
            }

            $itemHTML .= '</td>';
            foreach ($customFieldsItems as $custom_field) {
                $itemHTML .= '<td align="left" width="10%" style="border-right:1px solid black;">' . get_custom_field_value($item['id'], $custom_field['id'], 'items') . '</td>';
            }

            //Unit
            $itemHTML .= '<td class="unit" width="10%" align="center" style="border-right:1px solid black;"></td>';
            /**
             * Item custom fields
             */



            /**
             * Item quantity
             */
            $itemHTML .= '<td align="center" width="8%" style="border-right:1px solid black;">' . e(floatVal($item['qty']));


            $itemHTML .= '</td>';
            $itemHTML .= '<td align="center" width="8%" style="border-right:1px solid black;">' . e(floatVal($item['rate']));

            $itemHTML .= '</td>';

            /**
             * Item rate
             * @var string
             */
            $rate = hooks()->apply_filters(
                'item_preview_rate',
                app_format_money($item['rate'], $this->transaction->currency_name, $this->exclude_currency()),
                ['item' => $item, 'transaction' => $this->transaction]
            );


            /**
             * Items table taxes HTML custom function because it's too general for all features/options
             * @var string
             */
            // $itemHTML .= $this->taxes_html($item, $regularItemWidth);

            /**
             * Possible action hook user to include tax in item total amount calculated with the quantiy
             * eq Rate * QTY + TAXES APPLIED
             */
            $item_amount_with_quantity = hooks()->apply_filters(
                'item_preview_amount_with_currency',
                app_format_money(($item['qty'] * $item['rate']), $this->transaction->currency_name, $this->exclude_currency()),
                $item,
                $this->transaction,
                $this->exclude_currency()
            );

            $itemHTML .= '<td class="amount" align="right"  width="14%" style="border-right:1px solid black;">' . e($item_amount_with_quantity) . '</td>';

            // Close table row
            $itemHTML .= '</tr>';

            $html .= $itemHTML;

            $i++;
        }
        if ($i - 1 == 1)
            $space = 14;
        elseif ($i - 1 == 2)
            $space = 12;
        elseif ($i - 1 == 3)
            $space = 11;
        elseif ($i - 1 == 4)
            $space = 9;
        elseif ($i - 1 == 5)
            $space = 7;
        elseif ($i - 1 == 6)
            $space = 7;
        elseif ($i - 1 == 7)
            $space = 5;
        elseif ($i - 1 == 8)
            $space = 3;
        elseif ($i - 1 == 9)
            $space = 2;


        $j = 0;
        $extra_space = '';
        while ($j < $space) {
            $extra_space .= '<tr><td width="10%" style ="border-left: 1px solid black; border-right: 1px solid black;"></td>';
            $extra_space .= '<td width="40%" style ="border-right: 1px solid black;"></td>';
            $extra_space .= '<td width="10%" style ="border-right: 1px solid black;"></td>';
            $extra_space .= '<td width="10%" style ="border-right: 1px solid black;"></td>';
            $extra_space .= '<td width="8%" style ="border-right: 1px solid black;"></td>';
            $extra_space .= '<td width="8%" style ="border-right: 1px solid black;"></td>';
            $extra_space .= '<td width="14%" style ="border-right: 1px solid black;"></td></tr>';
            $j++;
        }
        $html .= $extra_space;

        return $html;
    }
    public function mycustom_qty()
    {
        $total_qty = 0;
        foreach ($this->items as $item) {
            $total_qty += $item['qty'];
        }
        return $total_qty;
    }

    /**
     * Html headings preview
     * @return string
     */
    public function html_headings()
    {
        $html = '<tr>';
        $html .= '<th align="center">' . $this->number_heading() . '</th>';
        $html .= '<th class="description" width="' . $this->get_description_item_width() . '%" align="left">' . $this->item_heading() . '</th>';

        $customFieldsItems = $this->get_custom_fields_for_table();
        foreach ($customFieldsItems as $cf) {
            $html .= '<th class="custom_field" align="left">' . $cf['name'] . '</th>';
        }

        $html .= '<th align="right">' . $this->qty_heading() . '</th>';
        $html .= '<th align="right">' . $this->rate_heading() . '</th>';
        if ($this->show_tax_per_item()) {
            $html .= '<th align="right">' . $this->tax_heading() . '</th>';
        }
        $html .= '<th align="right">' . $this->amount_heading() . '</th>';
        $html .= '</tr>';

        return $html;
    }

    /**
     * PDF headings preview
     * @return string
     */
    public function pdf_headings()
    {
        $descriptionItemWidth = $this->get_description_item_width();
        $regularItemWidth     = $this->get_regular_items_width(6);
        $customFieldsItems    = $this->get_custom_fields_for_table();

        $tblhtml = '<tr height="30" bgcolor="' . get_option('pdf_table_heading_color') . '" style="color:' . get_option('pdf_table_heading_text_color') . ';">';

        $tblhtml .= '<th width="5%;" align="center">' . $this->number_heading() . '</th>';
        $tblhtml .= '<th width="' . $descriptionItemWidth . '%" align="left">' . $this->item_heading() . '</th>';

        foreach ($customFieldsItems as $cf) {
            $tblhtml .= '<th width="' . $regularItemWidth . '%" align="left">' . $cf['name'] . '</th>';
        }

        $tblhtml .= '<th width="' . $regularItemWidth . '%" align="right">' . $this->qty_heading() . '</th>';
        $tblhtml .= '<th width="' . $regularItemWidth . '%" align="right">' . $this->rate_heading() . '</th>';

        if ($this->show_tax_per_item()) {
            $tblhtml .= '<th width="' . $regularItemWidth . '%" align="right">' . $this->tax_heading() . '</th>';
        }

        $tblhtml .= '<th width="' . $regularItemWidth . '%" align="right">' . $this->amount_heading() . '</th>';
        $tblhtml .= '</tr>';

        return $tblhtml;
    }
    public function pdf_headings_custom()
    {
        $tblhtml = '<tr height="30" bgcolor="">';

        $tblhtml .= '<th width="10%;" align="center" style="border:1px solid black;font-weight: bold;">No</th>';
        $tblhtml .= '<th width="40%" align="center" style="border:1px solid black;font-weight: bold;">Description of Product / Service</th>';
        $tblhtml .= '<th width="10%" align="center" style="border:1px solid black;font-weight: bold;">HSN</th>';
        $tblhtml .= '<th width="10%" align="center" style="border:1px solid black;font-weight: bold;">Unit</th>';
        $tblhtml .= '<th width="8%" align="center" style="border:1px solid black;font-weight: bold;">Qty</th>';
        $tblhtml .= '<th width="8%" align="center" style="border:1px solid black;font-weight: bold;">Rate</th>';
        $tblhtml .= '<th width="14%" align="center" style="border:1px solid black;font-weight: bold;">Amount</th>';

        $tblhtml .= '</tr>';

        return $tblhtml;
    }

    /**
     * Check for period merge field for recurring invoices
     *
     * @return string
     */
    protected function period_merge_field($text)
    {
        if ($this->type != 'invoice') {
            return $text;
        }

        // Is subscription invoice
        if (!property_exists($this->transaction, 'recurring_type')) {
            return $text;
        }

        $startDate       = $this->transaction->date;
        $originalInvoice = $this->transaction->is_recurring_from ?
            $this->ci->invoices_model->get($this->transaction->is_recurring_from) :
            $this->transaction;

        if (!preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $startDate)) {
            $startDate = to_sql_date($startDate);
        }

        if ($originalInvoice->custom_recurring == 0) {
            $originalInvoice->recurring_type = 'month';
        }

        $nextDate = date('Y-m-d', strtotime(
            '+' . $originalInvoice->recurring . ' ' . strtoupper($originalInvoice->recurring_type),
            strtotime($startDate)
        ));

        return str_ireplace('{period}', _d($startDate) . ' - ' . _d(date('Y-m-d', strtotime('-1 day', strtotime($nextDate)))), $text);
    }

    protected function get_description_item_width()
    {
        $item_width = hooks()->apply_filters('item_description_td_width', 38);

        // If show item taxes is disabled in PDF we should increase the item width table heading
        return $this->show_tax_per_item() == 0 ? $item_width + 15 : $item_width;
    }

    protected function get_regular_items_width($adjustment)
    {
        $descriptionItemWidth = $this->get_description_item_width();
        $customFieldsItems    = $this->get_custom_fields_for_table();
        // Calculate headings width, in case there are custom fields for items
        $totalheadings = $this->show_tax_per_item() == 1 ? 4 : 3;
        $totalheadings += count($customFieldsItems);

        return (100 - ($descriptionItemWidth + $adjustment)) / $totalheadings;
    }
}
