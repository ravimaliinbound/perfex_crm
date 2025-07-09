<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-12">
    <div class="tw-mb-2 sm:tw-mb-4">
        <div class="_buttons">
            <?php $this->load->view('admin/jobcard/jobcard_top_stats'); ?>
            <?php if (staff_can('create',  'invoices')) { ?>
                <a href="<?php echo admin_url('jobcard/jobcard'); ?>"
                    class="btn btn-primary pull-left new new-invoice-list mright5">
                    <i class="fa-regular fa-plus tw-mr-1"></i>
                    <?php echo _l('create_new_jobcard'); ?>
                </a>
            <?php } ?>
          
            <div class="display-block pull-right tw-space-x-0 sm:tw-space-x-1.5">
                <a href="#" class="btn btn-default btn-with-tooltip toggle-small-view hidden-xs"
                    onclick="toggle_small_view('.table-invoices','#invoice'); return false;" data-toggle="tooltip"
                    title="<?php echo _l('invoices_toggle_table_tooltip'); ?>"><i
                        class="fa fa-angle-double-left"></i>
                    </a>
                <a href="#" class="btn btn-default btn-with-tooltip invoices-total"
                    onclick="slideToggle('#stats-top'); init_invoices_total(true); return false;" data-toggle="tooltip"
                    title="<?php echo _l('view_stats_tooltip'); ?>">
                    <i class="fa fa-bar-chart"></i>
                </a>
                <app-filters
                            id="<?php echo $invoices_table->id(); ?>"
                            view="<?php echo $invoices_table->viewName(); ?>"
                            :rules="extra.invoicesRules || <?php echo app\services\utilities\Js::from($this->input->get('status') ? $invoices_table->findRule('status')->setValue([$this->input->get('status')]) : ($this->input->get('not_sent') ?  $invoices_table->findRule('sent')->setValue("0") : [])); ?>"
                            :saved-filters="<?php echo $invoices_table->filtersJs(); ?>"
                            :available-rules="<?php echo $invoices_table->rulesJs(); ?>">
                </app-filters>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" id="small-table">
            <div class="panel_s">
                <div class="panel-body panel-table-full">
                    <!-- if invoiceid found in url -->
                    <?php echo form_hidden('invoiceid', $invoiceid); ?>
                    <?php $this->load->view('admin/jobcard/table_html'); ?>
                </div>
            </div>
        </div>
        <div class="col-md-7 small-table-right-col">
            <div id="invoice" class="hide"></div>
        </div>
    </div>
</div>