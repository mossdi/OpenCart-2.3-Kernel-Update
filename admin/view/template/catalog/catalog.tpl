<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-category" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-catalog" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-keyword"><?php echo $entry_keyword; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-catalog-display"><?php echo $entry_catalog_display; ?></label>
                        <div class="col-sm-10">
                            <select name="catalog_display" id="input-catalog-display" class="form-control">
                                <?php if ($catalog_templates) { ?>
                                <?php foreach ($catalog_templates as $template) { ?>
                                <option <?php echo $template['name'] == $catalog_display ? 'selected' : false; ?> value="<?php echo $template['name']; ?>"><?php echo $template['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                                <option <?php  echo !$catalog_display ? 'selected' : false; ?> value="0" ><?php echo $text_default; ?></option>
                            </select>
                        </div>
                    </div>
                    <ul class="nav nav-tabs" id="language">
                        <?php foreach ($languages as $language) { ?>
                        <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                        <?php } ?>
                    </ul>
                    <div class="tab-content">
                        <?php foreach ($languages as $language) { ?>
                        <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-meta-h1<?php echo $language['language_id']; ?>"><?php echo $entry_meta_h1; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="catalog_description[<?php echo $language['language_id']; ?>][meta_h1]" value="<?php echo isset($catalog_description[$language['language_id']]) ? $catalog_description[$language['language_id']]['meta_h1'] : ''; ?>" placeholder="<?php echo $entry_meta_h1; ?>" id="input-meta-h1<?php echo $language['language_id']; ?>" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                                <div class="col-sm-10">
                                    <textarea name="catalog_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($catalog_description[$language['language_id']]) ? $catalog_description[$language['language_id']]['description'] : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="catalog_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($catalog_description[$language['language_id']]) ? $catalog_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                                    <?php if (isset($error_meta_title[$language['language_id']])) { ?>
                                    <div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                                <div class="col-sm-10">
                                    <textarea name="catalog_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($catalog_description[$language['language_id']]) ? $catalog_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                                <div class="col-sm-10">
                                    <textarea name="catalog_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($catalog_description[$language['language_id']]) ? $catalog_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
<script type="text/javascript">
    $('#language a:first').tab('show');
</script>
<?php echo $footer; ?>