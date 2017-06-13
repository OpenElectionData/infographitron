<div class="container">
    <h1><?php echo TEXT::get('profile_title'); ?></h1>

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>

        <div><strong><?php echo TEXT::get('username'); ?>:</strong> <?= $this->user_name; ?></div>
        <div><strong><?php echo TEXT::get('email'); ?>:</strong> <?= $this->user_email; ?></div>
        <br />

        <?php if($this->default_template->default_template != "") { ?>
	        <div class="panel panel-default">
	            <div class="panel-body">
	                <div class="row">
	                    <div class="col-md-6">
	                        <p><strong><?php echo TEXT::get('default_template'); ?></strong></p>
	                    </div>
	                    <div class="col-md-6 text-right">
	                        <a href="<?php echo Config::get('URL')."infographic/showInfographic?".$this->default_template->default_template; ?>" role="button" class="btn btn-default btn-xs" style="margin-left:10px;"><span class="glyphicon glyphicon-download" aria-hidden="true"></span> <?php echo TEXT::get("download"); ?></a>
	                        <a href="<?php echo Config::get('URL')."custom/"; ?>" role="button" class="btn btn-default btn-xs" style="margin-left:10px;"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> <?php echo TEXT::get("customize"); ?></a>
	                        <a href="<?php echo Config::get('URL')."custom/deleteDefault"; ?>" role="button" class="btn btn-danger btn-xs" style="margin-left:10px;"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo TEXT::get("delete_default"); ?></a>
	                        <br /><br />
	                    </div>
	                </div>
	                <img style="width:600px;height:600px" alt="" src="<?php echo Config::get('URL')."infographic/showInfographic?".$this->default_template->default_template; ?>">
	            </div>
	        </div>
	    <?php } ?>

</div>
