<?

$b = null;
$g = null;
$g_xy = false;
$t = array();
$t_f = array();
$t_c = array();
$t_s = array();
$t_x = array();
$t_y = array();

// If we're loading from an ID, load an image
if(isset($this->query['url']) && $this->query['url'] != "") {
	$image = "<img class='img-responsive center-block' style='width:600px;height:600px' src='".Config::get('URL')."infographic/showInfographic?".$this->query['url']."' />";
}
else {
	$image = "";
	$this->query['url'] = null;
}

// If we don't have a name, give a default value
if(!isset($this->query['f_n'])) {
	$this->query['f_n'] = 'custom_infographic';
}

// If the ID isn't loading, check to see if we have one
if(!isset($this->query['id']) && Request::get('id') != "") {
	$this->query['id'] = Request::get('id');
}

if($this->action == "create") {
	$this->query['id'] = null;
}

if(isset($this->query['b']) && $this->backgrounds[$this->query['b']] != "") {
	$b = $this->query['b'];
}
if(isset($this->query['g']) && is_array($this->query['g'])) {
	$g = $this->query['g'];
	$g_x = $this->query['g_x'];
	$g_y = $this->query['g_y'];

	$g_xy = true;
}

if(isset($this->query['t']) && is_array($this->query['t'])) {
	$t = $this->query['t'];
	$t = array_filter($t, 'strlen'); // Remove any empty keys
	foreach($t as $id => $text) {
		if($t[$id] != "") {
			$t[$id] = $t[$id];
			$t_f[$id]=$this->query['t_f'][$id];
			$t_c[$id]=$this->query['t_c'][$id];
			$t_s[$id]=$this->query['t_s'][$id];
			$t_x[$id]=$this->query['t_x'][$id];
			$t_y[$id]=$this->query['t_y'][$id];
		}
	}
}

if($g_xy == true) {
	$this->step = 4;
}

echo "<div class=\"container\">";
	echo "<div class=\"row\">";
		echo "<div class=\"col-sm-3\">";
			echo '<form id="make-submit">';
				// Hidden inputs for background image
				echo "<input type='hidden' name='b' value='".$b."' />";
			
				echo "<div class=\"panel panel-"; if($this->step==1) echo "info"; else echo "default"; echo "\" style=\"margin-bottom:10px;\">";
					echo "<div class=\"panel-heading pointer\" role=\"tab\" data-toggle=\"collapse\" data-target=\"#collapse-step1\">";
						echo "<div class=\"row\">";
							echo "<div class=\"col-md-12\"><strong>".TEXT::get("set_background_image")."</strong></div>";
						echo "</div>";
					echo "</div>";
					echo "<ul class=\"list-group collapse"; if($this->step==1) echo " in"; echo "\" id=\"collapse-step1\">";
					if(is_array($this->backgrounds)) {
						foreach($this->backgrounds as $file => $title) {
							echo "<li class=\"list-group-item\">";
								echo "<img class=\"img-responsive imgFormSubmit\" src=\"".Config::get('URL')."uploads/backgrounds/".$file."\" alt=\"".$title."\" data-background-id='".$file."'>";
								echo "<p class=\"text-center\" style=\"margin-top:10px\">".$title."</p>";
							echo "</li>";
						}
					}
					echo "</ul>";
				echo "</div>";
			
			echo "<div class=\"panel panel-"; if($this->step==2) echo "info"; else echo "default"; echo "\" style=\"margin-bottom:10px;\">";
				echo "<div class=\"panel-heading pointer\" role=\"tab\" data-toggle=\"collapse\" data-target=\"#collapse-step2\">";
					echo "<div class=\"row\">";
						echo "<div class=\"col-md-12\"><strong>".TEXT::get("insert_graphics")."</strong></div>";
					echo "</div>";
				echo "</div>";
				echo "<ul class=\"list-group collapse"; if($this->step==2) echo " in"; echo "\" id=\"collapse-step2\">";
				if(is_array($this->graphics)) {
					foreach($this->graphics as $index => $value) {
						$tags = explode(",",$value->tags);
						echo "<li class=\"list-group-item\">";
							echo "<img class=\"img-responsive center-block imgFormSubmit\" src=\"".Config::get('URL')."uploads/graphics/".$value->file."\" alt=\"".$value->title."\" data-graphic-id='".$value->file."'>";
							echo "<p class=\"text-center\" style=\"margin-top:10px\">".$value->title."</p>";
							foreach($tags as $tag) {
								echo '<span class="label label-primary">'.$tag.'</span>';
							}
						echo "</li>";
					}
				}
				echo "</ul>";
			echo "</div>";

			// Adjust Graphics Positioning
				echo "<div class=\"panel panel-"; if($this->step==3) echo "info"; else echo "default"; echo "\" style=\"margin-bottom:10px;\">";
					echo "<div class=\"panel-heading pointer\" role=\"tab\" data-toggle=\"collapse\" data-target=\"#collapse-step3\">";
						echo "<div class=\"row\">";
							echo "<div class=\"col-md-12\"><strong>".TEXT::get("adjust_graphic")."</strong></div>";
						echo "</div>";
					echo "</div>";
					echo "<ul class=\"list-group collapse"; if($this->step==3) echo " in"; echo "\" id=\"collapse-step3\">";
					echo "</ul>";
				echo "</div>";

				// Add Text Descriptions
				echo "<div class=\"panel panel-"; if($this->step==4) echo "info"; else echo "default"; echo "\" style=\"margin-bottom:10px;\">";
					echo "<div class=\"panel-heading pointer\" role=\"tab\" data-toggle=\"collapse\" data-target=\"#collapse-step4\">";
						echo "<div class=\"row\">";
							echo "<div class=\"col-md-12\"><strong>".TEXT::get("add_description")."</strong></div>";
						echo "</div>";
					echo "</div>";

					echo "<ul class=\"list-group collapse"; if($this->step==4) echo " in"; echo "\" id=\"collapse-step4\">";
						if(is_array($t)) {
							$i = 1;
							foreach($t as $id=>$text) {
								echo "<li class=\"list-group-item\">";
									echo "<div class=\"form-group\">";
										echo "<label for=\"t[".$id."]\">".TEXT::get("line")." ".$i."</label>";
										echo "<input class=\"form-control input-sm\" type=\"text\" name=\"t[".$id."]\" id=\"t[".$id."]\" placeholder=\"Enter text\" value=\"".$t[$id]."\">";
									echo "</div>";
									echo "<div class=\"row\">";
										echo "<div class=\"col-md-5\" style=\"padding-right:0px;\">";
											echo "<div class=\"form-group\">";
												echo "<label for=\"t_f[".$id."]\">".TEXT::get("font")."</label>";
												echo "<select name=\"t_f[".$id."]\" id=\"t_f[".$id."]\" class=\"form-control input-sm\">";
													echo "<option value=\"\" disabled>".TEXT::get("font")."</option>";
													if(is_array($this->fonts)) {
														foreach($this->fonts as $file => $title) {
															echo "<option value=\"".$file."\"";
															if($title == $t_f[$id]) {
																echo " selected";
															}
															echo ">".$title."</option>";
														}
													}
												echo "</select>";
											echo "</div>";
										echo "</div>";
										echo "<div class=\"col-md-4\" style=\"padding-left:4px;padding-right:4px;\">";
											echo "<div class=\"form-group\">";
												echo "<label for=\"t_c[".$id."]\">".TEXT::get("color")."</label>";
												echo "<input type=\"color\" class=\"form-control input-sm\" name=\"t_c[".$id."]\" id=\"t_c[".$id."]\" placeholder=\"".TEXT::get("color")."\" value=\"".$t_c[$id]."\">";
											echo "</div>";
										echo "</div>";
										echo "<div class=\"col-md-3\" style=\"padding-left:0px;\">";
											echo "<div class=\"form-group\">";
												echo "<label for=\"t_s[".$id."]\">".TEXT::get("size")."</label>";
												echo "<input type=\"text\" class=\"form-control input-sm\" name=\"t_s[".$id."]\" id=\"t_s[".$id."]\" placeholder=\"".TEXT::get("size")."\" value=\"".$t_s[$id]."\">";
											echo "</div>";
										echo "</div>";
									echo "</div>";
									echo "<div class=\"row\">";
										echo "<div class=\"col-md-6\" style=\"padding-right:2px;\">";
											echo "<div class=\"form-group\">";
												echo "<label for=\"t_x[".$id."]\">".TEXT::get("position_x")."</label>";
												echo "<input type=\"range\" min='0' max='600' class=\"form-control input-sm\" name=\"t_x[".$id."]\" id=\"t_x[".$id."]\" placeholder=\"".TEXT::get("position_x")."\" value=\"".$t_x[$id]."\">";
											echo "</div>";
										echo "</div>";
										echo "<div class=\"col-md-6\" style=\"padding-left:2px;\">";
											echo "<div class=\"form-group\">";
												echo "<label for=\"t_y[".$id."]\">".TEXT::get("position_y")."</label>";
												echo "<input type=\"range\" min='0' max='600' class=\"form-control input-sm\" name=\"t_y[".$id."]\" id=\"t_y[".$id."]\" placeholder=\"".TEXT::get("position_y")."\" value=\"".$t_y[$id]."\">";
											echo "</div>";
										echo "</div>";
									echo "</div>";
								echo "</li>";
								$i++;
							}
						}
						$id = $i - 1;
						echo "<li class=\"list-group-item textContainer\">";
							echo "<div class=\"form-group\">";
								echo "<label for=\"t[".$id."]\">".TEXT::get("new_line")."</label>";
								echo "<input class=\"form-control input-sm\" type=\"text\" name=\"t[".$id."]\" id=\"t[".$id."]\" placeholder=\"Enter text\">";
							echo "</div>";
							echo "<div class=\"row\">";
								echo "<div class=\"col-md-5\" style=\"padding-right:0px;\">";
									echo "<div class=\"form-group\">";
										echo "<label for=\"t_f[".$id."]\">".TEXT::get("font")."</label>";
										echo "<select name=\"t_f[".$id."]\" id=\"t_f[".$id."]\" class=\"form-control input-sm\">";
											echo "<option value=\"\" disabled>".TEXT::get("font")."</option>";
											if(is_array($this->fonts)) {
												foreach($this->fonts as $file => $title) {
													if($title == "Open Sans") {
														$selected = " selected";
													}
													else {
														$selected = "";
													}
													echo "<option value=\"".$file."\"".$selected.">".$title."</option>";
												}
											}
										echo "</select>";
									echo "</div>";
								echo "</div>";
								echo "<div class=\"col-md-4\" style=\"padding-left:4px;padding-right:4px;\">";
									echo "<div class=\"form-group\">";
										echo "<label for=\"t_c[".$id."]\">".TEXT::get("color")."</label>";
										echo "<input type=\"color\" class=\"form-control input-sm\" name=\"t_c[".$id."]\" id=\"t_c[".$id."]\" placeholder=\"".TEXT::get("color")."\" value='#000000'>";
									echo "</div>";
								echo "</div>";
								echo "<div class=\"col-md-3\" style=\"padding-left:0px;\">";
									echo "<div class=\"form-group\">";
										echo "<label for=\"t_s[".$id."]\">".TEXT::get("size")."</label>";
										echo "<input type=\"text\" class=\"form-control input-sm\" name=\"t_s[".$id."]\" id=\"t_s[".$id."]\" placeholder=\"".TEXT::get("size")."\" value='18'>";
									echo "</div>";
								echo "</div>";
							echo "</div>";
							echo "<div class=\"row\">";
								echo "<div class=\"col-md-6\" style=\"padding-right:2px;\">";
									echo "<div class=\"form-group\">";
										echo "<label for=\"t_x[".$id."]\">".TEXT::get("position_x")."</label>";
										echo "<input type=\"range\" min='0' max='600' class=\"form-control input-sm\" name=\"t_x[".$id."]\" id=\"t_x[".$id."]\" placeholder=\"".TEXT::get("position_x")."\" value='550'>";
									echo "</div>";
								echo "</div>";
								echo "<div class=\"col-md-6\" style=\"padding-left:2px;\">";
									echo "<div class=\"form-group\">";
										echo "<label for=\"t_y[".$id."]\">".TEXT::get("position_y")."</label>";
										echo "<input type=\"range\" min='0' max='600' class=\"form-control input-sm\" name=\"t_y[".$id."]\" id=\"t_y[".$id."]\" placeholder=\"".TEXT::get("position_y")."\" value='300'>";
									echo "</div>";
								echo "</div>";
							echo "</div>";
						echo "</li>";
						echo "<li class=\"list-group-item bg-lightgrey\">";
							echo "<a role='button' class='btn btn-success btn-xs addNew' style='float:right;'><span class='glyphicon glyphicon-plus' aria=hidden='true'></span></a>";
							echo "<button class=\"btn btn-primary\">".TEXT::get("updateText")."</button>";
						echo "</li>";
						echo "<li class=\"list-group-item\">";
								echo "<label for=\"f_n\">".TEXT::get("file_name")."</label>";
								echo "<input type=\"text\" class=\"form-control input-sm\" name=\"f_n\" id=\"f_n\" placeholder=\"".TEXT::get("enter_file_name")."\" value=\"".$this->query["f_n"]."\">";
						echo "</li>";
						echo "<li class=\"list-group-item bg-lightgrey\">";
							echo "<button class=\"btn btn-primary\" type=\"submit\">".TEXT::get("render")."</button>";
						echo "</li>";
					echo "</ul>";
			echo "</div>";
			echo "</form>";
		echo "</div>";
		echo '<div class="col-sm-9 make-main">';
				switch($this->step) {
					case 1;
						echo "<div class=\"alert alert-info\" role=\"alert\">".TEXT::get("step1_select_a_background")."</div>";
						break;
					case 2;
						echo "<div class=\"alert alert-info\" role=\"alert\">".TEXT::get("step2_add_a_graphic")."</div>";
						break;
					case 3;
						echo "<div class=\"alert alert-info\" role=\"alert\">".TEXT::get("step3_position_graphic")."</div>";
						break;
					case 4;
						echo "<div class=\"alert alert-info\" role=\"alert\">".TEXT::get("step4_adjust_text")."</div>";
						break;
				}
					echo "<div class=\"panel panel-default\">";
						echo "<div class=\"panel-body\">";
							echo "<div class=\"row\">";
								echo "<div class=\"col-md-6\">";
									echo "<p><strong id='infographicTitle'>".$this->query['f_n']."</strong></p>";
								echo "</div>";
								echo "<div class=\"col-md-6 text-right\">";
									echo "<a href=\"".Config::get('URL')."infographic/showInfographic?".$this->query['url']."\" role=\"button\" class=\"btn btn-default btn-xs downloadLink\"><span class=\"glyphicon glyphicon-download\" aria-hidden=\"true\"></span> ".TEXT::get("download")."</a>";
								echo "</div>";
							echo "</div>";
							echo "<div id='imgContainer'>".$image."</div>";
						echo "</div>";
					echo "</div>";
				
		echo "</div>";
	echo "</div>";
	// Save or Delete Options
	echo "<div class='row'>";
		echo "<div class='customInfographicFormContainer'>";
			echo "<form method=\"post\" class='customInfographicForm' action=\"".Config::get('URL')."custom/".$this->action."\">";
				echo "<input type='hidden' name='id' value='".$this->query['id']."' />";
				echo "<input type='hidden' name='f_n' value='".$this->query['f_n']."' />";
				echo "<input type='hidden' name='url' value='".$this->query['url']."' />";
				echo "<button type='submit' class='btn btn-primary'>Save Infographic</button>";
			echo "</form>";
			echo "<form method=\"post\" class='customInfographicForm' action=\"".Config::get('URL')."custom/duplicate\">";
				echo "<input type='hidden' name='id' value='".$this->query['id']."' />";
				echo "<input type='hidden' name='f_n' value='".$this->query['f_n']."' />";
				echo "<input type='hidden' name='url' value='".$this->query['url']."' />";
				echo "<button type='submit' class='btn btn-default'>Duplicate Infographic</button>";
			echo "</form>";
			echo "<form method=\"post\" class='customInfographicForm' action=\"".Config::get('URL')."custom/saveDefault\">";
				echo "<input type='hidden' name='id' value='".$this->query['id']."' />";
				echo "<input type='hidden' name='f_n' value='".$this->query['f_n']."' />";
				echo "<input type='hidden' name='url' value='".$this->query['url']."' />";
				echo "<button type='submit' class='btn btn-default'>Save as Default Template</button>";
			echo "</form>";
		echo "</div>";
	echo "</div>";
echo "</div>";

?>