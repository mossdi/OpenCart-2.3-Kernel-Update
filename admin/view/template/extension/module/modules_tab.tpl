<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-product-tab" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $button_save; ?></button>
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
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product-tab" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                            <?php if ($error_name) { ?>
                            <div class="text-danger"><?php echo $error_name; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" ><?php echo $entry_animatetabs; ?></label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <?php if ($animatetabsshow) { ?>
                                <input type="radio" name="animatetabsshow" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                                <?php } else { ?>
                                <input type="radio" name="animatetabsshow" value="1" />
                                <?php echo $text_yes; ?>
                                <?php } ?>
                            </label>
                            <label class="radio-inline">
                                <?php if (!$animatetabsshow) { ?>
                                <input type="radio" name="animatetabsshow" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                                <?php } else { ?>
                                <input type="radio" name="animatetabsshow" value="0" />
                                <?php echo $text_no; ?>
                                <?php } ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-products-display"><?php echo $entry_template_products; ?></label>
                        <div class="col-sm-10">
                            <select name="products_display" id="input-products-display" class="form-control">
                                <?php if ($products_templates) { ?>
                                <?php foreach ($products_templates as $template) { ?>
                                <option <?php echo $template['name'] == $products_display ? 'selected' : false; ?> value="<?php echo $template['name']; ?>"><?php echo $template['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                                <option <?php  echo !$products_display ? 'selected' : false; ?> value="0" ><?php echo $text_by_default; ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" ><?php echo $entry_special; ?></label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <?php if ($special_products) { ?>
                                <input type="radio" name="special_products" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                                <?php } else { ?>
                                <input type="radio" name="special_products" value="1" />
                                <?php echo $text_yes; ?>
                                <?php } ?>
                            </label>
                            <label class="radio-inline">
                                <?php if (!$special_products) { ?>
                                <input type="radio" name="special_products" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                                <?php } else { ?>
                                <input type="radio" name="special_products" value="0" />
                                <?php echo $text_no; ?>
                                <?php } ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" ><?php echo $entry_latest; ?></label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <?php if ($latest_products) { ?>
                                <input type="radio" name="latest_products" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                                <?php } else { ?>
                                <input type="radio" name="latest_products" value="1" />
                                <?php echo $text_yes; ?>
                                <?php } ?>
                            </label>
                            <label class="radio-inline">
                                <?php if (!$latest_products) { ?>
                                <input type="radio" name="latest_products" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                                <?php } else { ?>
                                <input type="radio" name="latest_products" value="0" />
                                <?php echo $text_no; ?>
                                <?php } ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" ><?php echo $entry_bestseller; ?></label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <?php if ($bestseller_products) { ?>
                                <input type="radio" name="bestseller_products" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                                <?php } else { ?>
                                <input type="radio" name="bestseller_products" value="1" />
                                <?php echo $text_yes; ?>
                                <?php } ?>
                            </label>
                            <label class="radio-inline">
                                <?php if (!$bestseller_products) { ?>
                                <input type="radio" name="bestseller_products" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                                <?php } else { ?>
                                <input type="radio" name="bestseller_products" value="0" />
                                <?php echo $text_no; ?>
                                <?php } ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" ><?php echo $entry_mostviewed; ?></label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <?php if ($mostviewed_products) { ?>
                                <input type="radio" name="mostviewed_products" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                                <?php } else { ?>
                                <input type="radio" name="mostviewed_products" value="1" />
                                <?php echo $text_yes; ?>
                                <?php } ?>
                            </label>
                            <label class="radio-inline">
                                <?php if (!$mostviewed_products) { ?>
                                <input type="radio" name="mostviewed_products" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                                <?php } else { ?>
                                <input type="radio" name="mostviewed_products" value="0" />
                                <?php echo $text_no; ?>
                                <?php } ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" ><?php echo $entry_featured; ?></label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <?php if ($featured_products) { ?>
                                <input type="radio" name="featured_products" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                                <?php } else { ?>
                                <input type="radio" name="featured_products" value="1" />
                                <?php echo $text_yes; ?>
                                <?php } ?>
                            </label>
                            <label class="radio-inline">
                                <?php if (!$featured_products) { ?>
                                <input type="radio" name="featured_products" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                                <?php } else { ?>
                                <input type="radio" name="featured_products" value="0" />
                                <?php echo $text_no; ?>
                                <?php } ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-parent-product"><?php echo $entry_product; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="parent_product" value="" placeholder="<?php echo $entry_product; ?>" id="input-parent-product" class="form-control" />
                            <div id="parent-product" class="well well-sm" style="height: 150px; overflow: auto;">
                                <?php foreach ($parent_products as $parent_product) { ?>
                                <div id="parent-product-<?php echo $parent_product['id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $parent_product['name']; ?>
                                    <input type="hidden" name="parent_product[]" value="<?php echo $parent_product['id']; ?>" />
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-limit"><?php echo $entry_limit; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="limit" value="<?php echo $limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                            <select name="status" id="input-status" class="form-control">
                                <?php if ($status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
    // Category
    $('input[name=\'parent_product\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item['name'],
                            value: item['category_id']
                        }
                    }));
                }
            });
        },
        'select': function(item) {
            $('input[name=\'parent_product\']').val('');

            $('#parent-product-' + item['value']).remove();

            $('#parent-product').append('<div id="parent-product-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="parent_product[]" value="' + item['value'] + '" /></div>');
        }
    });

    $('#parent-product').delegate('.fa-minus-circle', 'click', function() {
        $(this).parent().remove();
    });
//--></script>
<?php echo $footer; ?>