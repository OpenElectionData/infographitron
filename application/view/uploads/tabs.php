<div class="container">
	<?php $this->renderFeedbackMessages(); ?>

	<p>Upload a background, graphic, or font to use in the creation of custom infographics. Assets can be made public and are available to other users or can remain private for your use only.</p>
	
	<ul class="nav nav-tabs" style="margin-bottom:20px">
		<li role='presentation'<?php if ($this->page == "backgrounds") { echo ' class="active" '; } ?>>
			<a href="<?php echo Config::get('URL'); ?>uploader/backgrounds">
				<span class='glyphicon glyphicon-picture' aria-hidden="true"></span> <?php echo TEXT::get('background_images'); ?>
			</a>
		</li>
		<li role='presentation'<?php if ($this->page == "graphics") { echo ' class="active" '; } ?>>
			<a href="<?php echo Config::get('URL'); ?>uploader/graphics"><span class='glyphicon glyphicon-star' aria-hidden="true"></span> <?php echo TEXT::get('graphics'); ?></a>
		</li>
		<li role='presentation'<?php if ($this->page == "fonts") { echo ' class="active" '; } ?>>
			<a href="<?php echo Config::get('URL'); ?>uploader/fonts"><span class='glyphicon glyphicon-font' aria-hidden="true"></span> <?php echo TEXT::get('fonts'); ?></a>
		</li>
	</ul>
</div>