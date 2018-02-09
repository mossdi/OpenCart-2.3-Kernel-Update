<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right" id="control-buttons">
                <button type="submit" form="form" data-toggle="tooltip" data-placement="bottom" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $button_cancel; ?>" class="btn btn-warning"><i class="fa fa-reply"></i></a>
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
        <div class="panel panel-default alert-helper">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
                    <ul class="nav nav-tabs" role="tablist" id="revtabs">
                        <li class="active"><a href="#tab_settings" role="tab" data-toggle="tab"><?php echo $text_settings; ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_settings">
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $text_status; ?></label>
                                <div class="col-sm-2">
                                    <label class="radio-inline">
                                        <input type="radio" name="popup_window_setting[modal_status]" value="1" <?php if ($popup_window_setting['modal_status']) { echo 'checked'; } ?> /> <?php echo $text_enabled; ?>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="popup_window_setting[modal_status]" value="0" <?php if (!$popup_window_setting['modal_status']) { echo 'checked';} ?> /> <?php echo $text_disabled; ?>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $text_vision; ?><span data-toggle="tooltip" title="<?php echo $text_vision_help; ?>"></span></label>
                                <div class="col-sm-3">
                                    <input type="text" name="popup_window_setting[modal_time]" value="<?php echo $popup_window_setting['modal_time']; ?>" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $text_header; ?></label>
                                <div class="col-sm-3">
                                    <input type="text" name="popup_window_setting[modal_header]" value="<?php echo $popup_window_setting['modal_header']; ?>" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $text_text; ?></label>
                                <div class="col-sm-10">
                                    <textarea name="popup_window_setting[modal_text]" id="input-modal-text" class="form-control summernote"><?php echo isset($popup_window_setting['modal_text']) ? $popup_window_setting['modal_text'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
<?php echo $footer; ?>
