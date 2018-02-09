<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-autoseotitle" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-autoseotitle" class="form-horizontal">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-settings" data-toggle="tab"><i class="fa fa-cog"></i> <?php echo $tab_settings; ?></a></li>
						<li><a href="#tab-help" data-toggle="tab"><i class="fa fa-comment"></i> <?php echo $tab_help; ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab-settings">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-autoseotitle_enable"><?php echo $entry_status; ?></label>
								<div class="col-sm-10">
									<?php $checked = ($autoseotitle_enable)? 'checked="checked"':''; ?>
									<label class="switcher" title="<?php echo $entry_status; ?>">
										<input name="autoseotitle_enable" value="1" type="checkbox" <?php echo $checked; ?>>
										<span><span></span></span></label>
								</div>
							</div>
							<fieldset><legend><?php echo $text_legend_title; ?></legend>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-autoseotitle_rewrite"><?php echo $entry_rewrite; ?></label>
									<div class="col-sm-10">
										<?php $checked = ($autoseotitle_rewrite)? 'checked="checked"':''; ?>
										<label class="switcher" title="<?php echo $entry_rewrite; ?>">
											<input name="autoseotitle_rewrite" value="1" type="checkbox" <?php echo $checked; ?>>
											<span><span></span></span></label>
										<div><?php echo $text_rewrite_help; ?></div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-autoseotitle_product"><?php echo $entry_product; ?></label>
									<?php
									if (preg_match_all('#\[(.+?)\]#',$allowed_product_patterns,$matches)) {
										foreach ($matches[0] as $match) {
											$allowed_product_patterns = str_replace(
											$match ,
											'<button type="button" class="btn btn-default btn-xs" data-id="input-autoseotitle_product-{lang_id}" data-insert-pattern="' . $match . '"><i class="fa fa-plus-circle"></i> ' . $match . '</button>',$allowed_product_patterns);
										}
									}
									?>
									<div class="col-sm-10">
										<?php foreach ($languages as $language) { ?>
										<div class="input-group">
											<span class="input-group-addon"><img title="<?php echo $language['name']; ?>" src="<?php echo $language['image']; ?>"></span>
											<input type="text" class="form-control" id="input-autoseotitle_product-<?php echo $language['language_id']; ?>"
												   name="autoseotitle_product[<?php echo $language['language_id']; ?>]"
												   value="<?php echo isset($autoseotitle_product[$language['language_id']])?$autoseotitle_product[$language['language_id']]:''; ?>" >
										</div>
										<p><small><?php echo $text_allowed_patern;?> <?php echo str_replace('{lang_id}', $language['language_id'],$allowed_product_patterns); ?></small></p>
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-autoseotitle_category"><?php echo $entry_category; ?></label>
									<?php
									if (preg_match_all('#\[(.+?)\]#',$allowed_category_patterns,$matches)) {
										foreach ($matches[0] as $match) {
											$allowed_category_patterns = str_replace(
											$match ,
											'<button type="button" class="btn btn-default btn-xs" data-id="input-autoseotitle_category-{lang_id}" data-insert-pattern="' . $match . '"><i class="fa fa-plus-circle"></i> ' . $match . '</button>',$allowed_category_patterns);
										}
									}
									?>
									<div class="col-sm-10">
										<?php foreach ($languages as $language) { ?>
										<div class="input-group">
											<span class="input-group-addon"><img title="<?php echo $language['name']; ?>" src="<?php echo $language['image']; ?>"></span>
											<input type="text" class="form-control" id="input-autoseotitle_category-<?php echo $language['language_id']; ?>"
												   name="autoseotitle_category[<?php echo $language['language_id']; ?>]"
												   value="<?php echo isset($autoseotitle_category[$language['language_id']])?$autoseotitle_category[$language['language_id']]:''; ?>" >
										</div>
										<p><small><?php echo $text_allowed_patern;?> <?php echo str_replace('{lang_id}', $language['language_id'],$allowed_category_patterns); ?></small></p>
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-autoseotitle_manufacturer"><?php echo $entry_manufacturer; ?></label>
									<?php
									if (preg_match_all('#\[(.+?)\]#',$allowed_manufacturer_patterns,$matches)) {
										foreach ($matches[0] as $match) {
											$allowed_manufacturer_patterns = str_replace(
											$match ,
											'<button type="button" class="btn btn-default btn-xs" data-id="input-autoseotitle_manufacturer-{lang_id}" data-insert-pattern="' . $match . '"><i class="fa fa-plus-circle"></i> ' . $match . '</button>',$allowed_manufacturer_patterns);
										}
									}
									?>
									<div class="col-sm-10">
										<?php foreach ($languages as $language) { ?>
										<div class="input-group">
											<span class="input-group-addon"><img title="<?php echo $language['name']; ?>" src="<?php echo $language['image']; ?>"></span>
											<input type="text" class="form-control" id="input-autoseotitle_manufacturer-<?php echo $language['language_id']; ?>"
												   name="autoseotitle_manufacturer[<?php echo $language['language_id']; ?>]"
												   value="<?php echo isset($autoseotitle_manufacturer[$language['language_id']])?$autoseotitle_manufacturer[$language['language_id']]:''; ?>" >
										</div>
										<p><small><?php echo $text_allowed_patern;?> <?php echo str_replace('{lang_id}', $language['language_id'],$allowed_manufacturer_patterns); ?></small></p>
										<?php } ?>
									</div>
								</div>
							</fieldset>
							<fieldset><legend><?php echo $text_legend_description; ?></legend>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-autoseotitle_descr_rewrite"><?php echo $entry_descr_rewrite; ?></label>
									<div class="col-sm-10">
										<?php $checked = ($autoseotitle_descr_rewrite)? 'checked="checked"':''; ?>
										<label class="switcher" title="<?php echo $entry_descr_rewrite; ?>">
											<input name="autoseotitle_descr_rewrite" value="1" type="checkbox" <?php echo $checked; ?>>
											<span><span></span></span></label>
										<div><?php echo $text_rewrite_help; ?></div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-autoseotitle_descr_product"><?php echo $entry_product; ?></label>
									<?php
									if (preg_match_all('#\[(.+?)\]#',$allowed_descr_product_patterns,$matches)) {
										foreach ($matches[0] as $match) {
											$allowed_descr_product_patterns = str_replace(
											$match ,
											'<button type="button" class="btn btn-default btn-xs" data-id="input-autoseotitle_descr_product-{lang_id}" data-insert-pattern="' . $match . '"><i class="fa fa-plus-circle"></i> ' . $match . '</button>',$allowed_descr_product_patterns);
										}
									}
									?>
									<div class="col-sm-10">
										<?php foreach ($languages as $language) { ?>
										<div class="input-group">
											<span class="input-group-addon"><img title="<?php echo $language['name']; ?>" src="<?php echo $language['image']; ?>"></span>
											<input type="text" class="form-control" id="input-autoseotitle_descr_product-<?php echo $language['language_id']; ?>"
												   name="autoseotitle_descr_product[<?php echo $language['language_id']; ?>]"
												   value="<?php echo isset($autoseotitle_descr_product[$language['language_id']])?$autoseotitle_descr_product[$language['language_id']]:''; ?>" >
										</div>
										<p><small><?php echo $text_allowed_patern;?> <?php echo str_replace('{lang_id}', $language['language_id'],$allowed_descr_product_patterns); ?></small></p>
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-autoseotitle_descr_category"><?php echo $entry_category; ?></label>
									<?php
									if (preg_match_all('#\[(.+?)\]#',$allowed_descr_category_patterns,$matches)) {
										foreach ($matches[0] as $match) {
											$allowed_descr_category_patterns = str_replace(
											$match ,
											'<button type="button" class="btn btn-default btn-xs" data-id="input-autoseotitle_descr_category-{lang_id}" data-insert-pattern="' . $match . '"><i class="fa fa-plus-circle"></i> ' . $match . '</button>',$allowed_descr_category_patterns);
										}
									}
									?>
									<div class="col-sm-10">
										<?php foreach ($languages as $language) { ?>
										<div class="input-group">
											<span class="input-group-addon"><img title="<?php echo $language['name']; ?>" src="<?php echo $language['image']; ?>"></span>
											<input type="text" class="form-control" id="input-autoseotitle_descr_category-<?php echo $language['language_id']; ?>"
												   name="autoseotitle_descr_category[<?php echo $language['language_id']; ?>]"
												   value="<?php echo isset($autoseotitle_descr_category[$language['language_id']])?$autoseotitle_descr_category[$language['language_id']]:''; ?>" >
										</div>
										<p><small><?php echo $text_allowed_patern;?> <?php echo str_replace('{lang_id}', $language['language_id'],$allowed_descr_category_patterns); ?></small></p>
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-autoseotitle_descr_manufacturer"><?php echo $entry_manufacturer; ?></label>
									<?php
									if (preg_match_all('#\[(.+?)\]#',$allowed_descr_manufacturer_patterns,$matches)) {
										foreach ($matches[0] as $match) {
											$allowed_descr_manufacturer_patterns = str_replace(
											$match ,
											'<button type="button" class="btn btn-default btn-xs" data-id="input-autoseotitle_descr_manufacturer-{lang_id}" data-insert-pattern="' . $match . '"><i class="fa fa-plus-circle"></i> ' . $match . '</button>',$allowed_descr_manufacturer_patterns);
										}
									}
									?>
									<div class="col-sm-10">
										<?php foreach ($languages as $language) { ?>
										<div class="input-group">
											<span class="input-group-addon"><img title="<?php echo $language['name']; ?>" src="<?php echo $language['image']; ?>"></span>
											<input type="text" class="form-control" id="input-autoseotitle_descr_manufacturer-<?php echo $language['language_id']; ?>"
												   name="autoseotitle_descr_manufacturer[<?php echo $language['language_id']; ?>]"
												   value="<?php echo isset($autoseotitle_descr_manufacturer[$language['language_id']])?$autoseotitle_descr_manufacturer[$language['language_id']]:''; ?>" >
										</div>
										<p><small><?php echo $text_allowed_patern;?> <?php echo str_replace('{lang_id}', $language['language_id'],$allowed_descr_manufacturer_patterns); ?></small></p>
										<?php } ?>
									</div>
								</div>
							</fieldset>
							<fieldset><legend><?php echo $text_legend_keywords; ?></legend>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-autoseotitle_keyw_rewrite"><?php echo $entry_keyw_rewrite; ?></label>
									<div class="col-sm-10">
										<?php $checked = ($autoseotitle_keyw_rewrite)? 'checked="checked"':''; ?>
										<label class="switcher" title="<?php echo $entry_keyw_rewrite; ?>">
											<input name="autoseotitle_keyw_rewrite" value="1" type="checkbox" <?php echo $checked; ?>>
											<span><span></span></span></label>
										<div><?php echo $text_rewrite_help; ?></div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-autoseotitle_keyw_product"><?php echo $entry_product; ?></label>
									<?php
									if (preg_match_all('#\[(.+?)\]#',$allowed_keyw_product_patterns,$matches)) {
										foreach ($matches[0] as $match) {
											$allowed_keyw_product_patterns = str_replace(
											$match ,
											'<button type="button" class="btn btn-default btn-xs" data-id="input-autoseotitle_keyw_product-{lang_id}" data-insert-pattern="' . $match . '"><i class="fa fa-plus-circle"></i> ' . $match . '</button>',$allowed_keyw_product_patterns);
										}
									}
									?>
									<div class="col-sm-10">
										<?php foreach ($languages as $language) { ?>
										<div class="input-group">
											<span class="input-group-addon"><img title="<?php echo $language['name']; ?>" src="<?php echo $language['image']; ?>"></span>
											<input type="text" class="form-control" id="input-autoseotitle_keyw_product-<?php echo $language['language_id']; ?>"
												   name="autoseotitle_keyw_product[<?php echo $language['language_id']; ?>]"
												   value="<?php echo isset($autoseotitle_keyw_product[$language['language_id']])?$autoseotitle_keyw_product[$language['language_id']]:''; ?>" >
										</div>
										<p><small><?php echo $text_allowed_patern;?> <?php echo str_replace('{lang_id}', $language['language_id'],$allowed_keyw_product_patterns); ?></small></p>
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-autoseotitle_keyw_category"><?php echo $entry_category; ?></label>
									<?php
									if (preg_match_all('#\[(.+?)\]#',$allowed_keyw_category_patterns,$matches)) {
										foreach ($matches[0] as $match) {
											$allowed_keyw_category_patterns = str_replace(
											$match ,
											'<button type="button" class="btn btn-default btn-xs" data-id="input-autoseotitle_keyw_category-{lang_id}" data-insert-pattern="' . $match . '"><i class="fa fa-plus-circle"></i> ' . $match . '</button>',$allowed_keyw_category_patterns);
										}
									}
									?>
									<div class="col-sm-10">
										<?php foreach ($languages as $language) { ?>
										<div class="input-group">
											<span class="input-group-addon"><img title="<?php echo $language['name']; ?>" src="<?php echo $language['image']; ?>"></span>
											<input type="text" class="form-control" id="input-autoseotitle_keyw_category-<?php echo $language['language_id']; ?>"
												   name="autoseotitle_keyw_category[<?php echo $language['language_id']; ?>]"
												   value="<?php echo isset($autoseotitle_keyw_category[$language['language_id']])?$autoseotitle_keyw_category[$language['language_id']]:''; ?>" >
										</div>
										<p><small><?php echo $text_allowed_patern;?> <?php echo str_replace('{lang_id}', $language['language_id'],$allowed_keyw_category_patterns); ?></small></p>
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-autoseotitle_keyw_manufacturer"><?php echo $entry_manufacturer; ?></label>
									<?php
									if (preg_match_all('#\[(.+?)\]#',$allowed_keyw_manufacturer_patterns,$matches)) {
										foreach ($matches[0] as $match) {
											$allowed_keyw_manufacturer_patterns = str_replace(
											$match ,
											'<button type="button" class="btn btn-default btn-xs" data-id="input-autoseotitle_keyw_manufacturer-{lang_id}" data-insert-pattern="' . $match . '"><i class="fa fa-plus-circle"></i> ' . $match . '</button>',$allowed_keyw_manufacturer_patterns);
										}
									}
									?>
									<div class="col-sm-10">
										<?php foreach ($languages as $language) { ?>
										<div class="input-group">
											<span class="input-group-addon"><img title="<?php echo $language['name']; ?>" src="<?php echo $language['image']; ?>"></span>
											<input type="text" class="form-control" id="input-autoseotitle_keyw_manufacturer-<?php echo $language['language_id']; ?>"
												   name="autoseotitle_keyw_manufacturer[<?php echo $language['language_id']; ?>]"
												   value="<?php echo isset($autoseotitle_keyw_manufacturer[$language['language_id']])?$autoseotitle_keyw_manufacturer[$language['language_id']]:''; ?>" >
										</div>
										<p><small><?php echo $text_allowed_patern;?> <?php echo str_replace('{lang_id}', $language['language_id'],$allowed_keyw_manufacturer_patterns); ?></small></p>
										<?php } ?>
									</div>
								</div>
							</fieldset>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-autoseotitle_page"><?php echo $entry_page; ?></label>
								<div class="col-sm-10">
									<?php foreach ($languages as $language) { ?>
									<div class="input-group">
										<span class="input-group-addon"><img title="<?php echo $language['name']; ?>" src="<?php echo $language['image']; ?>"></span>
										<input type="text" class="form-control" id="input-autoseotitle_page-<?php echo $language['language_id']; ?>"
											   name="autoseotitle_page[<?php echo $language['language_id']; ?>]"
											   value="<?php echo isset($autoseotitle_page[$language['language_id']])?$autoseotitle_page[$language['language_id']]:''; ?>" >
									</div>
									<p><small><?php echo $text_allowed_patern;?>
											<button type="button" class="btn btn-default btn-xs" data-id="input-autoseotitle_page-<?php echo $language['language_id'];?>" data-insert-pattern="[page_num]"><i class="fa fa-plus-circle"></i> [page_num]</button></small></p>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-help">
							<?php echo $text_support; ?>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<style>
	label.switcher input[type="checkbox"] {display:none}
	label.switcher input[type="checkbox"] + span {position:relative;display:inline-block;vertical-align:middle;width:36px;height:17px;margin:0 5px 0 0;background:#ccc;border:solid 1px #999;border-radius:10px;box-shadow:inset 0 1px 2px #999;cursor:pointer;transition:all ease-in-out .2s;}
	label.switcher input[type="checkbox"]:checked + span {background:#8fbb6c;border:solid 1px #7da35e;}
	label.switcher input[type="checkbox"]:checked + span span {right:0;left:auto}
	label.switcher span span{position:absolute;background:white;height:17px;width:17px;display:inlaine-box;left:0;top:-1px;border-radius:50%}
</style>
<script>
    $('[data-insert-pattern]').on('click', function() {
        var data_id = $(this).attr('data-id');
        var data_insert_pattern = $(this).attr('data-insert-pattern');
        $('#' + data_id).val($('#'+ data_id).val() + data_insert_pattern);
    });
</script>
<?php echo $footer; ?>