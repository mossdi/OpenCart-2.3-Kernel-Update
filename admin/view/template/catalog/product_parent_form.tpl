<div id="popup-create-parent-product">
    <fieldset class="form-horizontal">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
            </div>
            <div class="panel-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                    <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
                    <li><a href="#tab-template" data-toggle="tab"><?php echo $tab_custom_template; ?></a></li>
                    <li><a href="#tab-related" data-toggle="tab"><?php echo $tab_related; ?></a></li>
                </ul>
                <form id="parent-product-form">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-general">
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
                                            <input type="text" name="category_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo $category_name_example; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
                                            <?php if (isset($error_name[$language['language_id']])) { ?>
                                            <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                                        <div class="col-sm-10">
                                            <textarea name="category_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['description'] : ''; ?></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-add-description<?php echo $language['language_id']; ?>"><?php echo $entry_add_description; ?></label>
                                        <div class="col-sm-10">
                                            <textarea name="category_description[<?php echo $language['language_id']; ?>][add_description]" placeholder="<?php echo $entry_add_description; ?>" id="input-add-description<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['add_description'] : ''; ?></textarea>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>

                                        <div class="col-sm-10">
                                            <input type="text" name="category_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                                            <?php if (isset($error_meta_title[$language['language_id']])) { ?>
                                            <div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                                        <div class="col-sm-10">
                                            <textarea name="category_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                                        <div class="col-sm-10">
                                            <textarea name="category_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-data">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-parent"><?php echo $entry_parent; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="path" value="" placeholder="<?php echo $entry_parent; ?>" id="input-parent" class="form-control" />
                                    <input type="hidden" name="parent_id" value="0" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                                <div class="col-sm-10">
                                    <div class="well well-sm" style="height: 150px; overflow: auto;">
                                        <div class="checkbox">
                                            <label>
                                                <?php if (in_array(0, $category_store)) { ?>
                                                <input type="checkbox" name="category_store[]" value="0" checked="checked" />
                                                <?php echo $text_default; ?>
                                                <?php } else { ?>
                                                <input type="checkbox" name="category_store[]" value="0" />
                                                <?php echo $text_default; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                        <?php foreach ($stores as $store) { ?>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" />
                                                <?php echo $store['name']; ?>
                                            </label>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-keyword"><span data-toggle="tooltip" title="<?php echo $help_keyword; ?>"><?php echo $entry_keyword; ?></span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="keyword" value="" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
                                    <input type="button" class="btn btn-primary" id="SEO_URL_GENERATOR" value="SEO URL GENERATOR" style="margin-top: 5px;" />
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-template">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-category-display"><?php echo $entry_template; ?></label>
                                <div class="col-sm-10">
                                    <select name="product_display" id="input-product-display" class="form-control">
                                        <?php if ($product_templates) { ?>
                                            <?php foreach ($product_templates as $template) { ?>
                                                <option value="<?php echo $template['name']; ?>"><?php echo $template['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                        <option value="0" selected><?php echo $text_disabled; ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-related">
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-category-display"><?php echo $tab_related; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="related" value="" placeholder="<?php echo $entry_related; ?>" id="input-related" class="form-control" />
                                    <div id="product-related" class="well well-sm" style="height: 150px; overflow: auto;">
                                        <?php foreach ($products_related as $product_related) { ?>
                                        <div id="product-related<?php echo $product_related['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_related['name']; ?>
                                            <input type="hidden" name="product_related[]" value="<?php echo $product_related['product_id']; ?>" />
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer text-right">
                <button type="button" id="btn-save" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>">
                    <i class="fa fa-save"></i>
                </button>
            </div>
        </div>
    </fieldset>
</div>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<script type="text/javascript" src="view/javascript/summernote/lang/summernote-ru-RU.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
<script type="text/javascript"><!--
    $('#language a:first').tab('show');

    $('#popup-create-parent-product').on('click', '#btn-save', function () {
        $.ajax({
            url: 'index.php?route=service/create_parent_product/create&token=<?php echo $token ?>',
            type: 'POST',
            data: { 'data': $('#parent-product-form').serialize() },
            dataType: 'json',
            beforeSend: function () {
                $('#btn-save').button('loading');
                $('.alert').remove();
            },
            success: function (json) {
                if (json['warning']) {
                    $('#btn-save').button('reset');
                } else {
                    location.reload();
                }
            }
        });
    });

    $('input[name=\'path\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    json.unshift({
                        category_id: 0,
                        name: '<?php echo $text_none; ?>'
                    });

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
            $('input[name=\'path\']').val(item['label']);
            $('input[name=\'parent_id\']').val(item['value']);
        }
    });

    $('input[name=\'related\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item['name'],
                            value: item['product_id']
                        }
                    }));
                }
            });
        },
        'select': function(item) {
            $('input[name=\'related\']').val('');

            $('#product-related' + item['value']).remove();

            $('#product-related').append('<div id="product-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_related[]" value="' + item['value'] + '" /></div>');
        }
    });

    $('#product-related').delegate('.fa-minus-circle', 'click', function() {
        $(this).parent().remove();
    });
//--></script>
<script type="text/javascript"><!--
    function getSeoUrlGenerator(seo_url_generator, autogenerator) {
        $.ajax({
            url: 'index.php?route=extension/module/seourlgenerator/seourlgenerateajax&token=<?php echo $token; ?>',
            type: 'post',
            dataType: 'html',
            data: 'autogenerator=' + autogenerator + '&id=<?php if (isset($_GET['category_id'])) { echo $_GET['category_id']; } else { echo "0"; } ?>&query_part=category_id&name=' + seo_url_generator,
            beforeSend: function() {
            },
            complete: function() {
            },
            success: function(response) {
                if (response != '') {
                    $("#input-keyword").val(response);
                }
            }
        });
    }
    
    $(document).ready(function() {
        var lastSeoUrl = $("#input-keyword").val();
        var configLanguageId = getConfigLanguageId();

        if (lastSeoUrl == '') {
            $( "input[name='category_description[" + configLanguageId + "][name]']" ).change(function() {
                getSeoUrlGenerator(this.value,0);
            });
        }
    });

    function getConfigLanguageId() {
        response = $.ajax({
            url: 'index.php?route=extension/module/seourlgenerator/getConfigLanguageId&token=<?php echo $token; ?>',
            type: 'post',
            dataType: 'html',
            data: '',
            async: false
        }).responseText;

        return response;
    }

    $( "#SEO_URL_GENERATOR" ).click(function() {
        var configLanguageId = getConfigLanguageId();
        var nameContent = $( "input[name='category_description[" + configLanguageId + "][name]']" ).val();
        if (nameContent == '') {
            alert('Сначала создайте название. Невозможно сгенерировать SEO URL без названия');
            return;
        } else {
            getSeoUrlGenerator(nameContent, 1);
        }
    });
//--></script>
