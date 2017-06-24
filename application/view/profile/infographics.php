<div class="container">
    <h1><?php echo TEXT::get('your_infographics'); ?></h1>
    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>

        <?php if ($this->infographics) { ?>
        <form method="post" action="<?php echo Config::get('URL');?>custom/bulkEdit">
            <div class="row">
                <?php 
                foreach($this->infographics as $key => $value) {

                    switch($value->approval_state) {
                        case 'approved':
                            $panelClass = "panel-success";
                            $buttonClass = "btn-success";
                            break;
                        case 'denied':
                            $panelClass = "panel-danger";
                            $buttonClass = "btn-danger";
                            break;
                        case 'pending':
                        default:
                            $panelClass = "panel-warning";
                            $buttonClass = "btn-warning";
                            break;
                    } 



                ?>
                    <div class="col-xs-12 col-md-4">
                        <div class="panel panel-default <?php echo $panelClass; ?>">
                            <div class="panel-heading">
                                <strong><?php echo $value->name; ?></strong>
                                <div class="pull-right"><label><input type="checkbox" name="selectedInfographics[]" value="<?php echo $value->info_id; ?>" aria-label="<?php echo $value->name; ?>" /></label></div>
                            </div>
                            <div class="panel-body">
                                <img style="width:100%;height: auto;" alt="" src="<?php echo Config::get('URL')."infographic/showInfographic?".$value->url; ?>"><br />
                                <strong><?php echo TEXT::get("created_on"); ?></strong> <?php echo $value->created_date; ?><br />
                                <strong><?php echo TEXT::get("edited_on"); ?></strong> <?php echo $value->edited_date; ?>
                            </div>
                            <div class="panel-footer">
                                <button type="button" class="btn <?php echo $buttonClass; ?> btn-xs"><?php echo ucfirst($value->approval_state); ?></button>
                                <?php if($value->approval_state == "approved") { ?>
                                <a href="<?php echo Config::get('URL')."infographic/showInfographic?".$value->url; ?>" role="button" class="btn btn-default btn-xs" style="margin-left:10px;"><span class="glyphicon glyphicon-download" aria-hidden="true"></span> <?php echo TEXT::get("download"); ?></a>
                                <?php } ?>
                                <a href="<?php echo Config::get('URL')."custom/?id=".$value->info_id; ?>" role="button" class="btn btn-default btn-xs" style="margin-left:10px;"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> <?php echo TEXT::get("edit"); ?></a>
                                <a href="<?php echo Config::get('URL')."custom/delete?id=".$value->info_id; ?>" role="button" class="btn btn-danger btn-xs" style="margin-left:10px;"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo TEXT::get("delete"); ?></a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="row col-xs-12 pull-right text-right">
                <select class="form-control" name="action" style="display:inline-block;width:auto;">
                  <option value="approve">Approve</option>
                  <option value="deny">Deny</option>
                  <option value="pending">Pending</option>
                </select>
                <button type='submit' class='btn btn-primary'>Submit</button>
            </div>
        </form>
            <?php } else { ?>
                <div>
                    <?php echo TEXT::get("no_infographics"); ?><br />
                    <a href="<?php echo Config::get('URL')."custom/"; ?>" role="button" class="btn btn-primary" style="margin-left:10px;"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> <?php echo TEXT::get("make_infographics"); ?></a>
                    </div>
            <?php } ?>
    </div>
</div>
