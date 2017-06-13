<div class="container">
    <h1><?php echo TEXT::get('your_infographics'); ?></h1>
    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>

        <?php if ($this->infographics) { ?>

                    <?php foreach($this->infographics as $key => $value) { ?>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong><?php echo $value->name; ?></strong></p>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="<?php echo Config::get('URL')."infographic/showInfographic?".$value->url; ?>" role="button" class="btn btn-default btn-xs" style="margin-left:10px;"><span class="glyphicon glyphicon-download" aria-hidden="true"></span> <?php echo TEXT::get("download"); ?></a>
                                        <a href="<?php echo Config::get('URL')."custom/?id=".$value->info_id; ?>" role="button" class="btn btn-default btn-xs" style="margin-left:10px;"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> <?php echo TEXT::get("customize"); ?></a>
                                        <a href="<?php echo Config::get('URL')."custom/delete?id=".$value->info_id; ?>" role="button" class="btn btn-danger btn-xs" style="margin-left:10px;"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo TEXT::get("delete"); ?></a>
                                        <br /><br />
                                        <strong><?php echo TEXT::get("created_on"); ?></strong> <?php echo $value->created_date; ?><br />
                                        <strong><?php echo TEXT::get("edited_on"); ?></strong> <?php echo $value->edited_date; ?>
                                    </div>
                                </div>
                                <img style="width:600px;height:600px" alt="" src="<?php echo Config::get('URL')."infographic/showInfographic?".$value->url; ?>">
                            </div>
                        </div>
                    <?php } ?>
            <?php } else { ?>
                <div>
                    <?php echo TEXT::get("no_infographics"); ?><br />
                    <a href="<?php echo Config::get('URL')."custom/"; ?>" role="button" class="btn btn-primary" style="margin-left:10px;"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> <?php echo TEXT::get("make_infographics"); ?></a>
                    </div>
            <?php } ?>
    </div>
</div>
