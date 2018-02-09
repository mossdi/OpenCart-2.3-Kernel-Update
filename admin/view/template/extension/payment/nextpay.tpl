<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_nextpay; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-nextpay" class="form-horizontal">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#main" data-toggle="tab"><?php echo $tab_general; ?></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="main" class="tab-pane fade active in">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="nextpay_key"><?php echo $entry_nextpay_key; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" required="" name="nextpay_key" value="<?php echo $nextpay_key; ?>" placeholder="<?php echo $entry_nextpay_key; ?>" id="nextpay_key" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="nextpay_product"><?php echo $entry_nextpay_product; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" required="" name="nextpay_product" value="<?php echo $nextpay_product; ?>" placeholder="<?php echo $entry_nextpay_product; ?>" id="nextpay_product" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-order-currency"><?php echo $entry_order_currency; ?></label>
                                <div class="col-sm-10">
                                    <select name="nextpay_order_currency_id" id="input-order-currency" class="form-control">
                                        <?php foreach ($order_currencies as $order_currency) { ?>
                                        <?php
                                        //$symbol_left = $order_currency['symbol_left'];
                                        //$symbol_right = $order_currency['symbol_right'];
                                        //$symbol = $symbol_left == null ? $symbol_right : $symbol_left;
                                        ?>
                                        <?php if ($order_currency['currency_id'] == $nextpay_order_currency_id) { ?>
                                        <option selected="selected" value="<?php echo $order_currency['currency_id']; ?>"><?php echo $order_currency['code']; ?></option>
                                        <?php } else if ($nextpay_order_currency_id == null && $order_currency['code'] === "RUB") { ?>
                                        <option selected="selected" value="<?php echo $order_currency['currency_id']; ?>"><?php echo $order_currency['code']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $order_currency['currency_id']; ?>"><?php echo $order_currency['code']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_description; ?></label>
                                <div class="col-sm-10">
                                    <ul class="nav nav-tabs">
                                        <?php foreach ($languages as $language) { ?>
                                        <li><a href="#description-<?php echo $language['code']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></a></li>
                                        <?php }?>
                                    </ul>
                                    <div class="tab-content">
                                        <?php foreach ($languages as $language) { ?>
                                        <div id="description-<?php echo $language['code']; ?>" class="tab-pane">
                                            <textarea name="nextpay_description_<?php echo $language['language_id']; ?>" cols="50" rows="4" placeholder="<?php echo $entry_description; ?>" id="input-description-<?php echo $language['language_id']; ?>" class="form-control"><?php echo !empty(${'nextpay_description_' . $language['language_id']}) ? (${'nextpay_description_' . $language['language_id']}) : ''; ?></textarea>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-order-comment-confirm"><?php echo $entry_order_comment_confirm; ?></label>
                                <div class="col-sm-10">
                                    <ul class="nav nav-tabs">
                                        <?php foreach ($languages as $language) { ?>
                                        <li><a href="#comment-<?php echo $language['code']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></a></li>
                                        <?php }?>
                                    </ul>
                                    <div class="tab-content">
                                        <?php foreach ($languages as $language) { ?>
                                        <div id="comment-<?php echo $language['code']; ?>" class="tab-pane">
                                            <textarea name="nextpay_order_comment_confirm_<?php echo $language['language_id']; ?>" cols="50" rows="4" placeholder="<?php echo $entry_order_comment_confirm; ?>" id="input-order-comment-confirm-<?php echo $language['language_id']; ?>" class="form-control"><?php echo !empty(${'nextpay_order_comment_confirm_' . $language['language_id']}) ? (${'nextpay_order_comment_confirm_' . $language['language_id']}) : ''; ?></textarea>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                                <div class="col-sm-10">
                                    <select name="nextpay_order_status_id" id="input-order-status" class="form-control">
                                        <?php foreach ($order_statuses as $order_status) { ?>
                                        <?php if ($order_status['order_status_id'] == $nextpay_order_status_id) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                                <div class="col-sm-10">
                                    <select name="nextpay_status" id="input-status" class="form-control">
                                        <?php if ($nextpay_status) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php } else { ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="nextpay_sort_order" value="<?php echo $nextpay_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>