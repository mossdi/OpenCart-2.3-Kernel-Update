<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a></div>
      <h1><i class="fa fa-search"></i> <?php echo $heading_title; ?></h1>
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
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="module_shoputils_antispam_contact_status" id="input-status" class="form-control">
                <option value="0"<?php echo !$module_shoputils_antispam_contact_status ? ' selected="selected"' : '';?>><?php echo $text_disabled; ?></option>
                <option value="1"<?php echo $module_shoputils_antispam_contact_status ? ' selected="selected"' : '';?>><?php echo $text_enabled; ?></option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-log"><?php echo $entry_log; ?></label>
            <div class="col-sm-8">
              <input type="hidden" name="module_shoputils_antispam_contact_log_filename" value="<?php echo $log_filename ?>" />
              <select name="module_shoputils_antispam_contact_log" id="input-log" class="form-control">
                <?php foreach ($logs as $key => $value) { ?>
                <?php if ($key == $module_shoputils_antispam_contact_log) { ?>
                <option value="<?php echo $key; ?>"
                    selected="selected"><?php echo $value; ?></option>
                <?php } else { ?>
                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
              <span class="help-block"><?php echo $help_log; ?></span>
            </div>
            <div class="col-sm-2">
              <a class="btn btn-success" id="button-download" href="<?php echo $download; ?>"><i class="fa fa-download"></i> <?php echo $button_download; ?></a>
              <a class="btn btn-danger" id="button-clear" data-loading-text="<?php echo $text_loading; ?>"><i class="fa fa-eraser"></i> <?php echo $button_clear; ?></a>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_log_file; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 300px; overflow: auto;">
                <pre id="pre-log" style="font-size:11px; min-height: 280px;"><?php foreach ($log_lines as $log_line) {echo $log_line;} ?></pre>
              </div>
              <span class="help-block"><?php echo $help_log_file; ?></span>
            </div>
          </div>
        </form>
        <div style="padding: 15px 15px; border:1px solid #ccc; margin-top: 15px; box-shadow:0 0px 5px rgba(0,0,0,0.1);"><?php echo $text_copyright; ?></div>
      </div><!-- </div class="panel-body"> -->
    </div><!-- </div class="panel panel-default"> -->
  </div><!-- </div class="container-fluid"> -->
</div><!-- </div id="content"> -->

<script type="text/javascript"><!--
  $('#button-clear').on('click', function() {
    if (confirm('<?php echo $text_confirm; ?>')){
      $.ajax({
        url: '<?php echo $clear_log; ?>',
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
          $('#button-clear').button('loading');
        },
        complete: function() {
          $('#button-clear').button('reset');
        },
        success: function(json) {
          $('.alert-success, .alert-danger').remove();
                
          if (json['error']) {
            $('#content > .container-fluid').before('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            $('.alert-danger').fadeIn('slow');
          }
          
          if (json['success']) {
                    $('#content > .container-fluid').before('<div class="alert alert-success" style="display: none;"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            
            $('#pre-log').empty();
            $('.alert-success').fadeIn('slow');
          }

          $('html, body').animate({ scrollTop: 0 }, 'slow'); 
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
  });
//--></script>
<?php echo $footer; ?>