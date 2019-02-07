<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-theme-dm-project" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-theme-dm-project" class="form-horizontal">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#main" data-toggle="tab"><?php echo $tab_general; ?></a>
                        </li>
                        <li>
                            <a href="#products" data-toggle="tab"><?php echo $tab_product; ?></a>
                        </li>
                        <li>
                            <a href="#filters" data-toggle="tab"><?php echo $tab_filter; ?></a>
                        </li>
                        <li>
                            <a href="#fast-view" data-toggle="tab"><?php echo $tab_fast_view; ?></a>
                        </li>
                        <li>
                            <a href="#images" data-toggle="tab"><?php echo $tab_image; ?></a>
                        </li>
                        <li>
                            <a href="#map" data-toggle="tab"><?php echo $tab_map; ?></a>
                        </li>
                        <li>
                            <a href="#payment_details" data-toggle="tab"><?php echo $tab_payment_details; ?></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="main" class="tab-pane fade active in">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-theme-status"><?php echo $entry_status; ?></label>
                                    <div class="col-sm-10">
                                        <select name="dm_project_status" id="input-theme-status" class="form-control">
                                            <?php if ($dm_project_status) { ?>
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
                                    <label class="col-sm-2 control-label" for="input-directory"><?php echo $entry_directory; ?></span></label>
                                    <div class="col-sm-10">
                                        <span class="form-control">dm_project</span>
                                        <input name="dm_project_directory" type="hidden" value="dm_project">
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div id="products" class="tab-pane fade">
                            <fieldset>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-catalog-limit"><span data-toggle="tooltip" title="<?php echo $help_product_limit; ?>"><?php echo $entry_product_limit; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="dm_project_product_limit" value="<?php echo $dm_project_product_limit; ?>" placeholder="<?php echo $entry_product_limit; ?>" id="input-catalog-limit" class="form-control" />
                                        <?php if ($error_product_limit) { ?>
                                        <div class="text-danger"><?php echo $error_product_limit; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-description-limit"><span data-toggle="tooltip" title="<?php echo $help_product_description_length; ?>"><?php echo $entry_product_description_length; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="dm_project_product_description_length" value="<?php echo $dm_project_product_description_length; ?>" placeholder="<?php echo $entry_product_description_length; ?>" id="input-description-limit" class="form-control" />
                                        <?php if ($error_product_description_length) { ?>
                                        <div class="text-danger"><?php echo $error_product_description_length; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-attribute-required"><?php echo $entry_attribute_required; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="attribute_required" value="" placeholder="<?php echo $entry_attribute_required; ?>" id="input-attribute-required" class="form-control" />
                                        <div id="attribute-required" class="well well-sm" style="height: 150px; overflow: auto;">
                                            <?php foreach ($attributes_required as $attribute) { ?>
                                            <div id="attribute-required<?php echo $attribute['attribute_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $attribute['name']; ?>
                                                <input type="hidden" name="dm_project_attributes_required[]" value="<?php echo $attribute['attribute_id']; ?>" />
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div id="filters" class="tab-pane fade">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-attribute-filter"><?php echo $entry_attribute_filters; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="attribute_filter" value="" placeholder="<?php echo $entry_attribute_filters; ?>" id="input-attribute-filter" class="form-control" />
                                        <div id="attribute-filter" class="well well-sm" style="height: 150px; overflow: auto;">
                                            <?php foreach ($attribute_filters as $attribute_filter) { ?>
                                            <div id="attribute-filter<?php echo $attribute_filter['attribute_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $attribute_filter['name']; ?>
                                                <input type="hidden" name="dm_project_attribute_filters[]" value="<?php echo $attribute_filter['attribute_id']; ?>" />
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-attribute-filter-explode"><?php echo $entry_attribute_filters_explode; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="attribute_filter_explode" value="" placeholder="<?php echo $entry_attribute_filters_explode; ?>" id="input-attribute-filter-explode" class="form-control" />
                                        <div id="attribute-filter-explode" class="well well-sm" style="height: 150px; overflow: auto;">
                                            <?php foreach ($attribute_filters_explode as $attribute_filter) { ?>
                                            <div id="attribute-filter-explode<?php echo $attribute_filter['attribute_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $attribute_filter['name']; ?>
                                                <input type="hidden" name="dm_project_attribute_filters_explode[]" value="<?php echo $attribute_filter['attribute_id']; ?>" />
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div id="fast-view" class="tab-pane fade">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-fast-view-status"><?php echo $entry_status; ?></label>
                                    <div class="col-sm-10">
                                        <select name="dm_project_popup_view_data[status]" id="input-fast-view-status" class="form-control">
                                            <?php if ($dm_project_popup_view_data['status']) { ?>
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
                                    <label class="col-sm-2 control-label" for="input-heading"><?php echo $entry_heading; ?></label>
                                    <div class="col-sm-10">
                                        <select name="dm_project_popup_view_data[heading]" id="input-heading" class="form-control">
                                            <?php if ($dm_project_popup_view_data['heading']) { ?>
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
                                    <label class="col-sm-2 control-label" for="input-no-stock"><?php echo $entry_no_stock_info; ?></label>
                                    <div class="col-sm-10">
                                        <select name="dm_project_popup_view_data[no_stock]" id="input-no-stock" class="form-control">
                                            <?php if ($dm_project_popup_view_data['no_stock']) { ?>
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
                                    <label class="col-sm-2 control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
                                    <div class="col-sm-10">
                                        <select name="dm_project_popup_view_data[quantity]" id="input-quantity" class="form-control">
                                            <?php if ($dm_project_popup_view_data['quantity']) { ?>
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
                                    <label class="col-sm-2 control-label" for="input-specification"><?php echo $entry_specification_tab; ?></label>
                                    <div class="col-sm-10">
                                        <select name="dm_project_popup_view_data[specification]" id="input-specification" class="form-control">
                                            <?php if ($dm_project_popup_view_data['specification']) { ?>
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
                                    <label class="col-sm-2 control-label" for="input-wishlist"><?php echo $entry_wishlist; ?></label>
                                    <div class="col-sm-10">
                                        <select name="dm_project_popup_view_data[wishlist]" id="input-wishlist" class="form-control">
                                            <?php if ($dm_project_popup_view_data['wishlist']) { ?>
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
                                    <label class="col-sm-2 control-label" for="input-compare"><?php echo $entry_compare; ?></label>
                                    <div class="col-sm-10">
                                        <select name="dm_project_popup_view_data[compare]" id="input-compare" class="form-control">
                                            <?php if ($dm_project_popup_view_data['compare']) { ?>
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
                                    <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_description_tab; ?></label>
                                    <div class="col-sm-10">
                                        <select name="dm_project_popup_view_data[description]" id="input-description" class="form-control">
                                            <?php if ($dm_project_popup_view_data['description']) { ?>
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
                                    <label class="col-sm-2 control-label" for="input-description_max"><?php echo $entry_description_max; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="dm_project_popup_view_data[description_max]" value="<?php echo $dm_project_popup_view_data['description_max']; ?>" id="input-description_max" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
                                    <div class="col-sm-10">
                                        <select name="dm_project_popup_view_data[image]" id="input-image" class="form-control">
                                            <?php if ($dm_project_popup_view_data['image']) { ?>
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
                                    <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_width; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="dm_project_popup_view_data[image_width]" value="<?php echo $dm_project_popup_view_data['image_width']; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-height"><?php echo $entry_height; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="dm_project_popup_view_data[image_height]" value="<?php echo $dm_project_popup_view_data['image_height']; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-additional_image"><?php echo $entry_additional_image; ?></label>
                                    <div class="col-sm-10">
                                        <select name="dm_project_popup_view_data[additional_image]" id="input-additional_image" class="form-control">
                                            <?php if ($dm_project_popup_view_data['additional_image']) { ?>
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
                                    <label class="col-sm-2 control-label" for="input-additional_width"><?php echo $entry_additional_width; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="dm_project_popup_view_data[image_additional_width]" value="<?php echo $dm_project_popup_view_data['image_additional_width']; ?>" placeholder="<?php echo $entry_additional_width; ?>" id="input-additional_width" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-additional_height"><?php echo $entry_additional_height; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="dm_project_popup_view_data[image_additional_height]" value="<?php echo $dm_project_popup_view_data['image_additional_height']; ?>" placeholder="<?php echo $entry_additional_height; ?>" id="input-additional_height" class="form-control" />
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div id="images" class="tab-pane fade">
                            <fieldset>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-image-category-width"><?php echo $entry_image_category; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_image_category_width" value="<?php echo $dm_project_image_category_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-category-width" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_image_category_height" value="<?php echo $dm_project_image_category_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_image_category) { ?>
                                        <div class="text-danger"><?php echo $error_image_category; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-image-thumb-width"><?php echo $entry_image_thumb; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_image_thumb_width" value="<?php echo $dm_project_image_thumb_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-thumb-width" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_image_thumb_height" value="<?php echo $dm_project_image_thumb_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_image_thumb) { ?>
                                        <div class="text-danger"><?php echo $error_image_thumb; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-manufacturer-image-thumb-width"><?php echo $entry_manufacturer_image_thumb; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_manufacturer_image_thumb_width" value="<?php echo $dm_project_manufacturer_image_thumb_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-manufacturer-image-thumb-width" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_manufacturer_image_thumb_height" value="<?php echo $dm_project_manufacturer_image_thumb_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_manufacturer_image_thumb) { ?>
                                        <div class="text-danger"><?php echo $error_manufacturer_image_thumb; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-image-popup-width"><?php echo $entry_image_popup; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_image_popup_width" value="<?php echo $dm_project_image_popup_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-popup-width" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_image_popup_height" value="<?php echo $dm_project_image_popup_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_image_popup) { ?>
                                        <div class="text-danger"><?php echo $error_image_popup; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-image-product-width"><?php echo $entry_image_product; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_image_product_width" value="<?php echo $dm_project_image_product_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-product-width" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_image_product_height" value="<?php echo $dm_project_image_product_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_image_product) { ?>
                                        <div class="text-danger"><?php echo $error_image_product; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-manufacturer-image-product-width"><?php echo $entry_manufacturer_image_product; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_manufacturer_image_product_width" value="<?php echo $dm_project_manufacturer_image_product_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-manufacturer-image-product-width" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_manufacturer_image_product_height" value="<?php echo $dm_project_manufacturer_image_product_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_manufacturer_image_product) { ?>
                                        <div class="text-danger"><?php echo $error_manufacturer_image_product; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-filter-manufacturer-image-width"><?php echo $entry_filter_manufacturer_image; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_filter_manufacturer_image_width" value="<?php echo $dm_project_filter_manufacturer_image_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-filter-manufacturer-image-width" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_filter_manufacturer_image_height" value="<?php echo $dm_project_filter_manufacturer_image_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_filter_manufacturer_image) { ?>
                                        <div class="text-danger"><?php echo $error_filter_manufacturer_image; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-image-additional-width"><?php echo $entry_image_additional; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_image_additional_width" value="<?php echo $dm_project_image_additional_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-additional-width" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_image_additional_height" value="<?php echo $dm_project_image_additional_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_image_additional) { ?>
                                        <div class="text-danger"><?php echo $error_image_additional; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-image-related"><?php echo $entry_image_related; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_image_related_width" value="<?php echo $dm_project_image_related_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-related" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_image_related_height" value="<?php echo $dm_project_image_related_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_image_related) { ?>
                                        <div class="text-danger"><?php echo $error_image_related; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-image-compare"><?php echo $entry_image_compare; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_image_compare_width" value="<?php echo $dm_project_image_compare_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-compare" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_image_compare_height" value="<?php echo $dm_project_image_compare_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_image_compare) { ?>
                                        <div class="text-danger"><?php echo $error_image_compare; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-image-wishlist"><?php echo $entry_image_wishlist; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_image_wishlist_width" value="<?php echo $dm_project_image_wishlist_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-wishlist" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_image_wishlist_height" value="<?php echo $dm_project_image_wishlist_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_image_wishlist) { ?>
                                        <div class="text-danger"><?php echo $error_image_wishlist; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-image-cart"><?php echo $entry_image_cart; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_image_cart_width" value="<?php echo $dm_project_image_cart_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-cart" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_image_cart_height" value="<?php echo $dm_project_image_cart_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_image_cart) { ?>
                                        <div class="text-danger"><?php echo $error_image_cart; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-image-location"><?php echo $entry_image_location; ?></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_image_location_width" value="<?php echo $dm_project_image_location_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-location" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="dm_project_image_location_height" value="<?php echo $dm_project_image_location_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_image_location) { ?>
                                        <div class="text-danger"><?php echo $error_image_location; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div id="map" class="tab-pane fade">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-map-status"><?php echo $entry_status; ?></label>
                                    <div class="col-sm-10">
                                        <select name="dm_project_map_data[status]" id="input-map-status" class="form-control">
                                            <?php if ($dm_project_map_data['status']) { ?>
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
                                    <label class="col-sm-2 control-label" for="input-map-api-key"><?php echo $entry_map_api_key; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="dm_project_map_data[api_key]" value="<?php echo isset($dm_project_map_data['api_key']) ? $dm_project_map_data['api_key'] : ''; ?>" placeholder="<?php echo $entry_map_api_key; ?>" id="input-map-api-key" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-map-longitude"><?php echo $entry_map_longitude; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="dm_project_map_data[longitude]" value="<?php echo isset($dm_project_map_data['longitude']) ? $dm_project_map_data['longitude'] : ''; ?>" placeholder="<?php echo $entry_map_longitude; ?>" id="input-map-longitude" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-map-latitude"><?php echo $entry_map_latitude; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="dm_project_map_data[latitude]" value="<?php echo isset($dm_project_map_data['latitude']) ? $dm_project_map_data['latitude'] : ''; ?>" placeholder="<?php echo $entry_map_latitude; ?>" id="input-map-latitude" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-map-zoom"><?php echo $entry_map_zoom; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="dm_project_map_data[zoom]" value="<?php echo isset($dm_project_map_data['zoom']) ? $dm_project_map_data['zoom'] : ''; ?>" placeholder="<?php echo $entry_map_zoom; ?>" id="input-map-zoom" class="form-control" />
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div id="payment_details" class="tab-pane fade">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-add-attachment"><?php echo $entry_add_attachment; ?></label>
                                <div class="col-sm-10">
                                    <select name="dm_project_payment_details[add_attachment]" id="input-add-attachment" class="form-control">
                                        <?php if (!empty($dm_project_payment_details['add_attachment']) && $dm_project_payment_details['add_attachment']) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php } else { ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-payment-receiver-name"><?php echo $entry_payment_receiver_name; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="dm_project_payment_details[payment_receiver_name]" value="<?php echo !empty($dm_project_payment_details['payment_receiver_name']) ? $dm_project_payment_details['payment_receiver_name'] : ''; ?>" placeholder="<?php echo $entry_payment_receiver_name; ?>" id="input-payment-receiver-name" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-payment-receiver-inn"><?php echo $entry_payment_receiver_inn; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="dm_project_payment_details[payment_receiver_inn]" value="<?php echo !empty($dm_project_payment_details['payment_receiver_inn']) ? $dm_project_payment_details['payment_receiver_inn'] : ''; ?>" placeholder="<?php echo $entry_payment_receiver_inn; ?>" id="input-payment-receiver-inn" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-payment-receiver-account"><?php echo $entry_payment_receiver_account; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="dm_project_payment_details[payment_receiver_account]" value="<?php echo !empty($dm_project_payment_details['payment_receiver_account']) ? $dm_project_payment_details['payment_receiver_account'] : ''; ?>" placeholder="<?php echo $entry_payment_receiver_account; ?>" id="input-payment-receiver-account" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-payment-receiver-bank-name"><?php echo $entry_payment_receiver_bank_name; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="dm_project_payment_details[payment_receiver_bank_name]" value="<?php echo !empty($dm_project_payment_details['payment_receiver_bank_name']) ? $dm_project_payment_details['payment_receiver_bank_name'] : ''; ?>" placeholder="<?php echo $entry_payment_receiver_bank_name; ?>" id="input-payment-receiver-bank-name" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-payment-receiver-bank-bic"><?php echo $entry_payment_receiver_bank_bic; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="dm_project_payment_details[payment_receiver_bank_bic]" value="<?php echo !empty($dm_project_payment_details['payment_receiver_bank_bic']) ? $dm_project_payment_details['payment_receiver_bank_bic'] : ''; ?>" placeholder="<?php echo $entry_payment_receiver_bank_bic; ?>" id="input-payment-receiver-bank-bic" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-payment-receiver-bank-cor-acct"><?php echo $entry_payment_receiver_bank_cor_acct; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="dm_project_payment_details[payment_receiver_bank_cor_acct]" value="<?php echo !empty($dm_project_payment_details['payment_receiver_bank_cor_acct']) ? $dm_project_payment_details['payment_receiver_bank_cor_acct'] : ''; ?>" placeholder="<?php echo $entry_payment_receiver_bank_cor_acct; ?>" id="input-payment-receiver-bank-cor-acct" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-payment-receiver-kpp"><?php echo $entry_payment_receiver_kpp; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="dm_project_payment_details[payment_receiver_kpp]" value="<?php echo !empty($dm_project_payment_details['payment_receiver_kpp']) ? $dm_project_payment_details['payment_receiver_kpp'] : ''; ?>" placeholder="<?php echo $entry_payment_receiver_kpp; ?>" id="input-payment-receiver-kpp" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-payment-receiver-address"><?php echo $entry_payment_receiver_address; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="dm_project_payment_details[payment_receiver_address]" value="<?php echo !empty($dm_project_payment_details['payment_receiver_address']) ? $dm_project_payment_details['payment_receiver_address'] : ''; ?>" placeholder="<?php echo $entry_payment_receiver_address; ?>" id="input-payment-receiver-address" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
$('input[name=\'attribute_filter\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        category: item.attribute_group,
                        label:    item.name,
                        value:    item.attribute_id
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'attribute_filter\']').val('');
        $('#attribute-filter' + item['value']).remove();
        $('#attribute-filter').append('<div id="attribute-filter' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="dm_project_attribute_filters[]" value="' + item['value'] + '" /></div>');
    }
});
$('#attribute-filter').delegate('.fa-minus-circle', 'click', function() {
    $(this).parent().remove();
});

$('input[name=\'attribute_filter_explode\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        category: item.attribute_group,
                        label:    item.name,
                        value:    item.attribute_id
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'attribute_filter_explode\']').val('');
        $('#attribute-filter-explode' + item['value']).remove();
        $('#attribute-filter-explode').append('<div id="attribute-filter-explode' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="dm_project_attribute_filters_explode[]" value="' + item['value'] + '" /></div>');
    }
});
$('#attribute-filter-explode').delegate('.fa-minus-circle', 'click', function() {
    $(this).parent().remove();
});

$('input[name=\'attribute_required\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        category: item.attribute_group,
                        label:    item.name,
                        value:    item.attribute_id
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'attribute_required\']').val('');
        $('#attribute-required' + item['value']).remove();
        $('#attribute-required').append('<div id="attribute-required' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="dm_project_attributes_required[]" value="' + item['value'] + '" /></div>');
    }
});
$('#attribute-required').delegate('.fa-minus-circle', 'click', function() {
    $(this).parent().remove();
});
//--></script>
<?php echo $footer; ?>
