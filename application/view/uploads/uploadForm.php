<div class="container">
	<?php $this->renderFeedbackMessages(); ?>

	<div class="panel panel-default" style="margin-top:40px;">
		<div class="panel-heading">
			<h3 class="panel-title">
				<?
					if($this->page=="backgrounds") echo TEXT::get("upload_a_background");
					if($this->page=="graphics") echo TEXT::get("upload_a_graphic");
					if($this->page=="fonts") echo TEXT::get("upload_a_font");
				?>
			</h3>
		</div>
		<div class="panel-body bg-lightgrey">
			<form method="post" action="<?php echo Config::get('URL');?>uploader/upload" enctype="multipart/form-data">
				<input type="hidden" name="assetType" value="<?php echo $this->page; ?>"/>
				<div class="form-group">
					<label for="file"><?php echo TEXT::get("file"); ?></label>
					<input type="file" id="file" name="file[]" multiple>
					<p class="help-block small">
					<?
						if($this->page=="backgrounds") echo TEXT::get("help_file_background");
						if($this->page=="graphics") echo TEXT::get("help_file_graphic");
						if($this->page=="fonts") echo TEXT::get("help_file_font");
					?>
					</p>
				</div>
				<div class="fileInputInfo" id="file0">
					<div class="form-group">
						<label for="filename"><?php echo TEXT::get("file_name"); ?></label>
						<input type="text" class="form-control filename" id="filename" placeholder="" name="filename[]">
						<p class="help-block small"><?php echo TEXT::get("help_filename"); ?></p>
					</div>
					<?php if($this->page == "graphics") { ?>
						<div class="form-group">
							<label for="tags"><?php echo TEXT::get("file_tags"); ?></label><br />
							<?php foreach($this->tags as $tag) { ?>
								<label class="checkbox-inline">
							  		<input type="checkbox" name="tags[][]" class="tags" value="<?php echo $tag; ?>"> <?php echo $tag; ?>
								</label>
							<?php } ?>
						</div>
					<?php } ?>
					<div class="form-group">
						<label for="permissions"><?php echo TEXT::get("permissions"); ?></label>
						<select name="permissions[]" class="form-control input-sm permissions">
							<option value="private">Private</option>
							<option value="public">Public</option>
						</select>
					</div>
				</div>
				<div class="multipleFiles-container"></div>
				<button class="btn btn-primary" type="submit"><?php echo TEXT::get("submit"); ?></button>
			</form>
		</div>
	</div>
</div>

<script>
function makeFileList() {
	// //get the input and UL list
	// var input = document.getElementById('file');
	// var list = document.getElementById('fileList');

	// //empty list for now...
	// while (list.hasChildNodes()) {
	// 	list.removeChild(ul.firstChild);
	// }

	// //for every file...
	// for (var x = 0; x < input.files.length; x++) {
	// 	//add to list
	// 	var li = document.createElement('li');
	// 	li.innerHTML = 'File ' + (x + 1) + ':  ' + input.files[x].name;
	// 	list.append(li);
	// }
}
</script>