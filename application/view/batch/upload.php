<?
		$tabs=array();
		$tabs["upload"]="upload";
		$tabs["feed"]="feed";

		// if(isset($tabs[$uri[2]])) {
		// 	$tab=$uri[2];
		// } else {
		// 	$tab="upload";
		// }
		
		$tab = "upload";

		if(isset($_FILES['file']) && is_uploaded_file($_FILES["file"]["tmp_name"])) {
			$tmp_filename = sha1_file($_FILES["file"]["tmp_name"]);
			copy($_FILES["file"]["tmp_name"], "tmp/".$tmp_filename);
			$filename=$_FILES["file"]["tmp_name"];
		} else {
			$filename=Request::post("feed");
			$handle = @fopen($filename,"r");
			if($handle!==false) {
				$tmp_filename = sha1_file($filename);
				file_put_contents("tmp/".$tmp_filename, $handle);
			}
		}

		if(Request::post("submit") != "" && Request::post("firstline") == "ignore") {
			$firstline="ignore";
		} else {
			$firstline="";
		}

echo "<div class=\"container\">";
	echo "<ul class=\"nav nav-tabs\" style=\"margin-bottom:20px\">";
	if(is_array($tabs)) {
		foreach($tabs as $tab_slug=>$tab_name) {
			echo "<li role=\"presentation\"";
			if($tab_slug==$tab) echo " class=\"active\"";
			echo "><a href=\"".Config::get('URL')."batch/".$tab_slug."/\"><span class=\"glyphicon glyphicon-".Icons::get($tab_slug)."\" aria-hidden=\"true\"></span> ".TEXT::get($tab_name)."</a></li>";
		}
	}
	echo "</ul>";
	?>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo TEXT::get("upload_a_file"); ?></h3>
		</div>
		<div class="panel-body bg-lightgrey">
			<form method="post" action="<?php echo Config::get('URL'); ?>batch/upload/" enctype="multipart/form-data">
				<div class="form-group">
					<label for="file"><?php echo TEXT::get("file_name"); ?></label>
					<input type="file" name="file" id="file">
					<p class="help-block small"><?php echo TEXT::get("help_file_batch"); ?></p>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="firstline" value="ignore" <?php if($firstline=="ignore") { echo "checked"; } ?>> <?php echo TEXT::get("ignore_first_line"); ?>
					</label>
				</div>
				<button class="btn btn-primary" type="submit" name="submit" value="submit"><?php echo TEXT::get("process"); ?></button>
			</form>
		</div>
	</div>

	<?

	if(Request::post("submit")!="") {
		$handle = @fopen("tmp/".$tmp_filename,"r");
		if($handle!==false) {
			$i=0;
			$csv=array();
			while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
				$csv[$i]=$data;
				$i++;
			}
			fclose($handle);
			//echo "<pre>"; print_r($csv); echo "</pre>";
			if(is_array($csv)) {
				if (class_exists("ZipArchive")) {
					echo "<div class=\"text-right\" style=\"margin-bottom:20px;\">";
						echo "<a href=\"".Config::get('URL')."infographic/zip?filename=".$tmp_filename."&firstline=".$firstline."\" role=\"button\" class=\"btn btn-default btn\" style=\"margin-left:10px;\"><span class=\"glyphicon glyphicon-save\" aria-hidden=\"true\"></span> ".TEXT::get("download_all_graphics")."</a>";
					echo "</div>";
				}
				$n=0;
				$p=0;
				$query="";
				foreach($csv as $n => $row) {
					if($firstline != "ignore" || $n > 0) {
						$query="f_n=".$row[0]."&b=".$row[1];
						$i_g=0;
						$i_t=0;

						$type = false;

						foreach(array_slice($row, 2) as $id=>$value) {

							if($type==false) {
								if($value==1) {
									$type=1;
								}
								if($value==2) {
									$type=2;
								}
								$p=0;
							}
							if($type==1) {
								if($p==1) {
									$query.="&g[".$i_g."]=".$value."";
								}
								if($p==2) {
									$query.="&g_x[".$i_g."]=".$value."";
								}
								if($p==3) {
									$query.="&g_y[".$i_g."]=".$value."";
									$type=false;
									$i_g++;
								}
								$p++;
							}
							if($type==2) {
								if($p==1) {
									$query.="&t[".$i_t."]=".$value."";
								}
								if($p==2) {
									$query.="&t_f[".$i_t."]=".$value."";
								}
								if($p==3) {
									$query.="&t_c[".$i_t."]=".$value."";
								}
								if($p==4) {
									$query.="&t_s[".$i_t."]=".$value."";
								}
								if($p==5) {
									$query.="&t_x[".$i_t."]=".$value."";
								}
								if($p==6) {
									$query.="&t_y[".$i_t."]=".$value."";
									$type=false;
									$i_t++;
								}
								$p++;
							}
						}

						//echo $query;
						echo "<div class=\"panel panel-default\">";
							echo "<div class=\"panel-body\">";
								echo "<div class=\"row\">";
									echo "<div class=\"col-md-6\">";
										echo "<p><strong>".$row[0]."</strong></p>";
									echo "</div>";
									echo "<div class=\"col-md-6 text-right\">";
										echo "<a href=\"".Config::get('URL')."infographic/showInfographic?".$query."\" role=\"button\" class=\"btn btn-default btn-xs\" style=\"margin-left:10px;\"><span class=\"glyphicon glyphicon-download\" aria-hidden=\"true\"></span> ".TEXT::get("download")."</a>";
										echo "<a href=\"".Config::get('URL')."/custom/?".$query."\" role=\"button\" class=\"btn btn-default btn-xs\" style=\"margin-left:10px;\"><span class=\"glyphicon glyphicon-cog\" aria-hidden=\"true\"></span> ".TEXT::get("customize")."</a>";
									echo "</div>";
								echo "</div>";
								echo "<img style=\"width:600px;height:600px\" alt=\"\" src=\"".Config::get('URL')."infographic/showInfographic?".$query."\">";
							echo "</div>";
						echo "</div>";
					}
				}
				if (class_exists("ZipArchive")) {
					echo "<div class=\"text-right\" style=\"margin-top:20px;\">";
						echo "<a href=\"".Config::get('URL')."infographic/zip?filename=".$tmp_filename."&firstline=".$firstline."\" role=\"button\" class=\"btn btn-default btn\" style=\"margin-left:10px;\"><span class=\"glyphicon glyphicon-save\" aria-hidden=\"true\"></span> ".TEXT::get("download_all_graphics")."</a>";
					echo "</div>";
				}
			}
		} else{
			
			echo "<div class=\"alert alert-danger\" role=\"alert\">".TEXT::get("cant_process_file")."</div>";
			
		}
	}

echo "</div>";

?>