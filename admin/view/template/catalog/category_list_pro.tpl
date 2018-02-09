<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a> <a href="<?php echo $repair; ?>" data-toggle="tooltip" title="<?php echo $button_rebuild; ?>" class="btn btn-default"><i class="fa fa-refresh"></i></a>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_enable; ?>" class="btn btn-default" onclick="$('#form-category').attr('action', '<?php echo $enabled; ?>').submit()"><i class="fa fa-play"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_disable; ?>" class="btn btn-default" onclick="$('#form-category').attr('action', '<?php echo $disabled; ?>').submit()"><i class="fa fa-pause"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-category').submit() : false;"><i class="fa fa-trash-o"></i></button>
            </div>
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
        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-category">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                <td class="text-center"><?php echo $column_name; ?></td>
                                <td class="text-center"><?php echo $column_parent; ?></td>
                                <td class="text-center"><?php echo $column_templates; ?></td>
                                <td class="text-center"><?php echo $column_attributes; ?></td>
                                <td class="text-center"><?php echo $column_sort_order; ?></td>
                                <td class="text-center"><?php echo $column_status; ?></td>
                                <td class="text-center"><?php echo $column_action; ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($categories) { ?>
                            <?php foreach ($categories as $category) { ?>
                            <tr>
                                <td class="text-center"><?php if (in_array($category['category_id'], $selected)) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>" />
                                    <?php } ?></td>
                                <?php if ($category['href']) { ?>
                                <td class="left">
                                    <div style="<?php echo $category['indent']; ?>">
                                        <a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
                                    </div>
                                </td>
                                <?php } else { ?>
                                <td class="left">
                                    <div style="<?php echo $category['indent']; ?>">
                                        <div class="row">
                                            <div class="col-xs-12"><?php echo $category['name']; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <?php } ?>
                                <td class="text-center">
                                    <?php if ($category['template_product']) { ?>
                                    <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                    <?php } ?>
                                </td>
                                <td class="text-center">
                                    <div class="row">
                                        <?php if (!$category['template_product'] || $category['template_product'] && $category['template_category']) { ?>
                                        <div class="col-xs-12">
                                            <strong><?php echo $text_category_template; ?>:</strong> <?php echo $category['template_category'] ? '<span class="custom">' . $category['template_category'] . '</span>' : $text_by_default; ?>
                                        </div>
                                        <div class="col-xs-12">
                                            <strong><?php echo $text_products_template; ?>:</strong> <?php echo $category['template_products'] ? '<span class="custom">' . $category['template_products'] . '</span>' : $text_by_default; ?>
                                        </div>
                                        <?php } ?>
                                        <?php if ($category['template_product']) { ?>
                                        <div class="col-xs-12"><strong><?php echo $text_product_template; ?>:</strong> <span class="custom"><?php echo $category['template_product']; ?></span></div>
                                        <div class="col-xs-12">
                                            <strong><?php echo $text_variants_template; ?>:</strong> <?php echo $category['template_variants'] ? '<span class="custom">' . $category['template_variants'] . '</span>' : $text_by_default; ?>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <strong><?php echo $text_attribute_groups; ?>:</strong> <?php echo $category['attribute_groups'] ? '<span class="custom">' . $category['attribute_groups'] . '</span>' : $text_disabled; ?>
                                        </div>
                                        <div class="col-xs-12">
                                            <strong><?php echo $text_attribute_display; ?>:</strong> <?php echo $category['attribute_display'] ? '<span class="custom">' . $category['attribute_display'] . '</span>' : $text_disabled; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center"><?php echo $category['sort_order']; ?></td>
                                <td class="text-center"><?php echo $category['status'] == 1 ? $text_status_on : $text_status_off; ?></td>
                                <td nowrap class="text-center">
                                    <a target="_blank" href="<?php echo $category['href_shop']; ?>" data-toggle="tooltip" title="<?php echo $button_shop; ?>" class="btn btn-success"><i class="fa fa-eye"></i></a>
                                    <a href="<?php echo $category['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </form>
                <div class="row">
                    <div class="col-sm-12 text-right"><?php echo $results; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    span.custom {
        color:#8fbb6c;
        font-weight: bold;
    }
</style>
<?php echo $footer; ?>
