<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-last_modified" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
                <ul class="nav nav-tabs" id="error_tabs">
                    <li class="active"><a href="#tab-settings" data-toggle="tab"><?php echo $tab_settings; ?></a></li>
                    <li><a href="#tab-help" data-toggle="tab"><?php echo $tab_help; ?></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-settings">
                        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-last_modified" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                                <div class="col-sm-10">
                                    <select name="last_modified_enable" id="input-status" class="form-control">
                                        <?php if ($last_modified_enable) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php } else { ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <fieldset">
                                <legend class="legendStyle">Товары</legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-product"><?php echo $entry_product; ?></label>
                                    <div class="col-sm-10">
                                        <select name="last_modified_product" id="input-product" class="form-control">
                                            <?php if ($last_modified_product) { ?>
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
                                    <label class="col-sm-2 control-label" for="input-product-module"><?php echo $entry_changes_modules; ?></label>
                                    <div class="col-sm-10">
                                        <select name="last_modified_product_module" id="input-category-module" class="form-control">
                                            <?php if ($last_modified_product_module) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0"><?php echo $text_disabled; ?></option>
                                            <?php } else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset">
                                <legend class="legendStyle">Категории</legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-category"><?php echo $entry_category; ?></label>
                                    <div class="col-sm-10">
                                        <select name="last_modified_category" id="input-category" class="form-control">
                                            <?php if ($last_modified_category) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0"><?php echo $text_disabled; ?></option>
                                            <?php } else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="form-text">
                                            <?php echo $text_also_manufacturer; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-category-module"><?php echo $entry_changes_modules; ?></label>
                                    <div class="col-sm-10">
                                        <select name="last_modified_category_module" id="input-category-module" class="form-control">
                                            <?php if ($last_modified_category_module) { ?>
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
                                    <label class="col-sm-2 control-label" for="input-category-module"><?php echo $entry_changes_product; ?></label>
                                    <div class="col-sm-10">
                                        <select name="last_modified_category_product" id="input-category-module" class="form-control">
                                            <?php if ($last_modified_category_product) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0"><?php echo $text_disabled; ?></option>
                                            <?php } else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-home"><?php echo $entry_home; ?></label>
                                <div class="col-sm-10">
                                    <select name="last_modified_home" id="input-home" class="form-control">
                                        <?php if ($last_modified_home) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php } else { ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <fieldset">
                                <legend class="legendStyle">Информационные статьи</legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-information"><?php echo $entry_information; ?></label>
                                    <div class="col-sm-10">
                                        <select name="last_modified_information" id="input-home" class="form-control">
                                            <?php if ($last_modified_information) { ?>
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
                                    <label class="col-sm-2 control-label" for="input-category-module"><?php echo $entry_changes_modules; ?></label>
                                    <div class="col-sm-10">
                                        <select name="last_modified_category_module" id="input-category-module" class="form-control">
                                            <?php if ($last_modified_category_module) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0"><?php echo $text_disabled; ?></option>
                                            <?php } else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-cache"><?php echo $entry_caching; ?></label>
                                <div class="col-sm-10">
                                    <select name="last_modified_caching" id="input-cache" class="form-control">
                                        <?php if ($last_modified_caching) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php } else { ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="block-expire">
                                <label class="col-sm-2 control-label" for="input-expire"><?php echo $entry_expires; ?></label>
                                <div class="col-sm-10">
                                    <input name="last_modified_expires" placeholder="<?php echo $entry_expires; ?>" id="input-expire" class="form-control" value="<?php echo $last_modified_expires; ?>">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="tab-help">
                        <div class="container">
                            <h3 style="color:red">Благодарю вас за выбор модуля!</h3>
                            <p>Если у вас возникла проблема с работой модуля в этом контексте, то вы всегда можете запросить бесплатную техническую поддержку по адресу покупки модуля.</p>
                            <h3>Назначение модуля</h3>
                            <p>Модуль предназначен для управления заголовками ответа сервера</p>
                            <p>
                                <strong>Last Modified</strong> - Заголовок Last-Modified - время последнего изменения документа
                            </p>
                            <p>
                                <strong>if-modified-since</strong> – это запрос клиента(браузера или ПС) к вашему серверу, в нем клиент спрашивает: &quot;не изменилась ли страница с моего последнего визита?&quot;
                            </p>
                            <h4 class="text-primary">Особенности модуля</h4>
                            <ul>
                                <li>Применять в товарах - настройка позволяющая отправлять заголовок на странице товаров</li>
                                <li>Применять в категориях, производителе - настройка позволяющая отправлять заголовок на странице категории или производителя</li>
                                <li>Применять в статьях - настройка позволяющая отправлять заголовок на странице странице</li>
                                <li>Применять на главной - настройка позволяющая отправлять заголовок для главной страницы</li>
                                <li>-----</li>
                                <li><span class="text-danger">*</span> Учитывать изменения в модулях - настройка позволяющая учитывать время изменения модуля, находящегося на странице (категории, или производителя)</li>
                                <li><span class="text-danger">*</span> Учитывать изменения товаров - настройка позволяющая учитывать время изменения товаров из категории или из производителя</li>
                            </ul>
                            <ul>
                                <li>Отсылать заголовок Cache-Control - Директивы Cache-Control определяют, где, как и на какое время может быть кеширован ресурс.</li>
                                <li>Время для Expires (в минутах) - Время жизни кеша в браузере (по умолчанию 1 минута)</li>
                            </ul>
                            <div>
                                <span class="text-danger">*</span>Необходимость настройки - при изменении какого-либо товара принадлежащего категории, или редактировании схемы страницы, соответственно меняется и сам контент, что
                                и приводит к изменению времени модификации страницы
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#input-cache').on('change', function(){
        change_expire();
    });
    function change_expire() {
        if (parseInt($('#input-cache').val())) {
            $('#block-expire').show();
        } else {
            $('#block-expire').hide();
        }
    }
    change_expire();
</script>
<?php echo $footer; ?>