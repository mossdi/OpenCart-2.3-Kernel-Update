<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-landing" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-landing" class="form-horizontal">
          <fieldset>
            <ul class="nav nav-tabs" id="language">
              <?php foreach ($languages as $language) { ?>
              <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
              <?php } ?>
            </ul>
            <div class="tab-content">
              <?php foreach ($languages as $language) { ?>
              <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="landing_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($landing_description[$language['language_id']]) ? $landing_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
                    <?php if (isset($error_name[$language['language_id']])) { ?>
                    <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <?php } ?>
            </div>
          </fieldset>
          <fieldset>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-category"><?php echo $entry_category; ?></label>
              <div class="col-sm-10">
                <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
                <div id="category" class="well well-sm" style="height: 60px; overflow: auto;">
                  <?php if ($category) { ?>
                  <div id="category<?php echo $category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $category['name']; ?>
                    <input type="hidden" name="category_id" value="<?php echo $category['category_id']; ?>" />
                  </div>
                  <?php } ?>
                </div>
                <?php if ($error_category) { ?>
                <div class="text-danger"><?php echo $error_category; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-attribute"><?php echo $entry_attribute; ?></label>
              <div class="col-sm-10">
                <select name="attribute_id" id="input-attribute" class="form-control">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($attributes as $attribute) { ?>
                    <?php if ($attribute['attribute_id'] == $attribute_id) { ?>
                      <option selected value="<?php echo $attribute['attribute_id']; ?>"><?php echo $attribute['attribute_name']; ?></option>
                    <?php } else { ?>
                      <option value="<?php echo $attribute['attribute_id']; ?>"><?php echo $attribute['attribute_name']; ?></option>
                    <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-attribute"><?php echo $entry_manufacturer; ?></label>
              <div class="col-sm-10">
                <select name="manufacturer_id" id="input-manufacturer" class="form-control">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($manufacturers as $manufacturer) { ?>
                    <?php if ($manufacturer['manufacturer_id'] == $manufacturer_id) { ?>
                      <option selected value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
                    <?php } else { ?>
                      <option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
                    <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  //Main Category
  $('input[name=\'category\']').autocomplete({
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
      $('input[name=\'category\']').val('');

      $('#category' + item['value']).remove();

      $('#category').html('<div id="category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="category_id" value="' + item['value'] + '" /></div>');
    }
  });

  $('#category').delegate('.fa-minus-circle', 'click', function() {
    $(this).parent().remove();
  });

  $('#language a:first').tab('show');
</script>
<?php echo $footer; ?>
