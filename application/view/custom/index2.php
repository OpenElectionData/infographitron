<?

$b = null;
$g = null;
$t = array();
$t_f = array();
$t_c = array();
$t_s = array();
$t_x = array();
$t_y = array();
$g_xy = false;
$updatedQuery = null;

if($this->action == "create") {
	$this->query['id'] = null;
}

if(isset($this->query['b']) && $this->backgrounds[$this->query['b']] != "") {
	$b = $this->query['b'];
}
if(isset($this->query['g']) && is_array($this->query['g'])) {
	$g = $this->query['g'];
	$g_xy = true;
	foreach($this->query['g'] as $id=>$graphic) {
		if(isset($this->graphics[$this->query['g'][$id]]) && $this->graphics[$this->query['g'][$id]] != "") {
			$g[$id] = $this->query['g'][$id];
			$g_x[$id] = $this->query['g_x'][$id];
			$g_y[$id] = $this->query['g_y'][$id];
			if($g_x[$id]!="" && $g_y[$id]!="" && $g_xy == true) {
				$g_xy = true;
			} else {
				$g_xy = false;
			}
		}
	}
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
			
			echo "<div class=\"panel panel-"; if($this->step==1) echo "info"; else echo "default"; echo "\" style=\"margin-bottom:10px;\">";
				echo "<div class=\"panel-heading pointer\" role=\"tab\" data-toggle=\"collapse\" data-target=\"#collapse-step1\">";
					echo "<div class=\"row\">";
						echo "<div class=\"col-md-12\"><strong>".TEXT::get("set_background_image")."</strong></div>";
					echo "</div>";
				echo "</div>";
				echo "<ul class=\"list-group collapse"; if($this->step==1) echo " in"; echo "\" id=\"collapse-step1\">";
				if(is_array($this->backgrounds)) {
					foreach($this->backgrounds as $background_file=>$background_name) {
						echo "<li class=\"list-group-item\">";
							echo "<a href=\"".Config::get('URL')."custom/?";
							echo "f_n=".$this->query['f_n']."&id=".$this->query['id']."&b=".$background_file;
							if(is_array($g)) {
								foreach($g as $id=>$graphic2) {
									if($g[$id]!="") {
										echo "&g[".$id."]=".$g[$id]."&g_x[".$id."]=".$g_x[$id]."&g_y[".$id."]=".$g_y[$id]."";
									}
								}
							}
							if(is_array($t)) {
								foreach($t as $id=>$text) {
									if($t[$id]!="") {
										echo "&t[".$id."]=".$t[$id]."&t_f[".$id."]=".$t_f[$id]."&t_c[".$id."]=".$t_c[$id]."&t_s[".$id."]=".$t_s[$id]."&t_x[".$id."]=".$t_x[$id]."&t_y[".$id."]=".$t_y[$id]."";
									}
								}
							}
							echo "\"><img class=\"img-responsive\" src=\"".Config::get('URL')."uploads/backgrounds/".$background_file."\" alt=\"".$background_name."\"></a>";
							echo "<p class=\"text-center\" style=\"margin-top:10px\">".$background_name."</p>";
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
					foreach($this->graphics as $graphic_file=>$graphic_name) {
						echo "<li class=\"list-group-item\">";
							echo "<a href=\"".Config::get('URL')."custom/?";
							echo "f_n=".$this->query['f_n']."&id=".$this->query['id']."&b=".$b;
							$i=0;
							if(is_array($g)) {
								foreach($g as $id=>$graphic2) {
									if($g[$id]!="") {
										echo "&g[".$id."]=".$g[$id]."&g_x[".$id."]=".$g_x[$id]."&g_y[".$id."]=".$g_y[$id]."";
										$i++;
									}
								}
							}
							echo "&g[".$i."]=".$graphic_file."&g_x[".$i."]=&g_y[".$i."]=";
							if(is_array($t)) {
								foreach($t as $id=>$text) {
									if($t[$id]!="") {
										echo "&t[".$id."]=".$t[$id]."&t_f[".$id."]=".$t_f[$id]."&t_c[".$id."]=".$t_c[$id]."&t_s[".$id."]=".$t_s[$id]."&t_x[".$id."]=".$t_x[$id]."&t_y[".$id."]=".$t_y[$id]."";
									}
								}
							}
							echo "\"><img class=\"img-responsive center-block\" src=\"".Config::get('URL')."uploads/graphics/".$graphic_file."\" alt=\"".$graphic_name."\"></a>";
							echo "<p class=\"text-center\" style=\"margin-top:10px\">".$graphic_name."</p>";
						echo "</li>";
					}
				}
				echo "</ul>";
			echo "</div>";

			echo "<form method=\"post\" action=\"".Config::get('URL')."custom/updateURL/\">";
				echo "<div class=\"panel panel-"; if($this->step==3) echo "info"; else echo "default"; echo "\" style=\"margin-bottom:10px;\">";
					echo "<div class=\"panel-heading pointer\" role=\"tab\" data-toggle=\"collapse\" data-target=\"#collapse-step3\">";
						echo "<div class=\"row\">";
							echo "<div class=\"col-md-12\"><strong>".TEXT::get("adjust_graphic")."</strong></div>";
						echo "</div>";
					echo "</div>";
					echo "<input type=\"hidden\" name=\"f_n\" value=\"".$this->query['f_n']."\">";
					echo "<input type=\"hidden\" name=\"b\" value=\"".$b."\">";
					if(is_array($t)) {
						foreach($t as $id=>$text) {
							echo "<input type=\"hidden\" name=\"t[".$id."]\" value=\"".$t[$id]."\">";
							echo "<input type=\"hidden\" name=\"t_f[".$id."]\" value=\"".$t_f[$id]."\">";
							echo "<input type=\"hidden\" name=\"t_c[".$id."]\" value=\"".$t_c[$id]."\">";
							echo "<input type=\"hidden\" name=\"t_s[".$id."]\" value=\"".$t_s[$id]."\">";
							echo "<input type=\"hidden\" name=\"t_x[".$id."]\" value=\"".$t_x[$id]."\">";
							echo "<input type=\"hidden\" name=\"t_y[".$id."]\" value=\"".$t_y[$id]."\">";
						}
					}
					echo "<ul class=\"list-group collapse"; if($this->step==3) echo " in"; echo "\" id=\"collapse-step3\">";
						if(is_array($g)) {
							foreach($g as $id=>$graphic) {
								echo "<li class=\"list-group-item\">";
									echo "<div class=\"row\">";
										echo "<div class=\"col-md-9\">";
											echo "<img class=\"img-responsive center-block\" src=\"".Config::get('URL')."uploads/graphics/".$g[$id]."\" alt=\"".$this->graphics[$g[$id]]."\">";
										echo "</div>";
										echo "<div class=\"col-md-3\">";
											echo "<a href=\"".Config::get('URL')."custom/?";
											echo "f_n=".$this->query['f_n']."&b=".$b;
											$i=0;
											if(is_array($g)) {
												foreach($g as $id2=>$graphic2) {
													if($g[$id2]!="" && $id!=$id2) {
														echo "&g[".$i."]=".$g[$id2]."&g_x[".$i."]=".$g_x[$id2]."&g_y[".$i."]=".$g_y[$id2]."";
														$i++;
													}
												}
											}
											if(is_array($t)) {
												foreach($t as $id2=>$text) {
													if($t[$id2]!="") {
														echo "&t[".$id2."]=".$t[$id2]."&t_f[".$id2."]=".$t_f[$id2]."&t_c[".$id2."]=".$t_c[$id2]."&t_s[".$id2."]=".$t_s[$id2]."&t_x[".$id2."]=".$t_x[$id2]."&t_y[".$id2."]=".$t_y[$id2]."";
													}
												}
											}
											echo "\" role=\"button\" class=\"btn btn-danger btn-xs\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></a>";
										echo "</div>";
									echo "</div>";
									echo "<input type=\"hidden\" name=\"g[".$id."]\" value=\"".$g[$id]."\">";
									echo "<div class=\"row\" style=\"margin-top:20px;\">";
										echo "<div class=\"col-md-6\" style=\"padding-right:2px;\">";
											echo "<div class=\"form-group\">";
												echo "<input type=\"range\" min=\"0\" max=\"600\" class=\"form-control input-sm\" name=\"g_x[".$id."]\" id=\"g_x[".$id."]\" placeholder=\"".TEXT::get("position_x")."\" value=\"".$g_x[$id]."\">";
											echo "</div>";
										echo "</div>";
										echo "<div class=\"col-md-6\" style=\"padding-left:2px;\">";
											echo "<div class=\"form-group\">";
												echo "<input type=\"text\" class=\"form-control input-sm\" name=\"g_y[".$id."]\" id=\"g_y[".$id."]\" placeholder=\"".TEXT::get("position_y")."\" value=\"".$g_y[$id]."\">";
											echo "</div>";
										echo "</div>";
									echo "</div>";
								echo "</li>";
							}
						}
						echo "<li class=\"list-group-item bg-lightgrey\">";
							echo "<button class=\"btn btn-primary\" type=\"submit\">".TEXT::get("set")."</button>";
						echo "</li>";
					echo "</ul>";
			echo "</div>";
			echo "</form>";

			echo "<form method=\"get\" action=\"".Config::get('URL')."custom/\">";
				echo "<input type='hidden' name='id' value='".$this->query['id']."' />";
				echo "<div class=\"panel panel-"; if($this->step==4) echo "info"; else echo "default"; echo "\" style=\"margin-bottom:10px;\">";
					echo "<div class=\"panel-heading pointer\" role=\"tab\" data-toggle=\"collapse\" data-target=\"#collapse-step4\">";
						echo "<div class=\"row\">";
							echo "<div class=\"col-md-12\"><strong>".TEXT::get("add_description")."</strong></div>";
						echo "</div>";
					echo "</div>";
					echo "<input type=\"hidden\" name=\"b\" value=\"".$b."\">";
					if(is_array($g)) {
						foreach($g as $id=>$graphic) {
							echo "<input type=\"hidden\" name=\"g[".$id."]\" value=\"".$g[$id]."\">";
							echo "<input type=\"hidden\" name=\"g_x[".$id."]\" value=\"".$g_x[$id]."\">";
							echo "<input type=\"hidden\" name=\"g_y[".$id."]\" value=\"".$g_y[$id]."\">";
						}
					}
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
												echo "<select name=\"t_f[".$id."]\" id=\"t_f[".$id."]\" class=\"form-control input-sm\">";
													echo "<option value=\"\">".TEXT::get("font")."</option>";
													if(is_array($this->fonts)) {
														foreach($this->fonts as $font_file=>$font_name) {
															echo "<option value=\"".$font_file."\"";
															if($font_file==$t_f[$id]) {
																echo " selected";
															}
															echo ">".$font_name."</option>";
														}
													}
												echo "</select>";
											echo "</div>";
										echo "</div>";
										echo "<div class=\"col-md-4\" style=\"padding-left:4px;padding-right:4px;\">";
											echo "<div class=\"form-group\">";
												echo "<input type=\"text\" class=\"form-control input-sm\" name=\"t_c[".$id."]\" id=\"t_c[".$id."]\" placeholder=\"".TEXT::get("color")."\" value=\"".$t_c[$id]."\">";
											echo "</div>";
										echo "</div>";
										echo "<div class=\"col-md-3\" style=\"padding-left:0px;\">";
											echo "<div class=\"form-group\">";
												echo "<input type=\"text\" class=\"form-control input-sm\" name=\"t_s[".$id."]\" id=\"t_s[".$id."]\" placeholder=\"".TEXT::get("size")."\" value=\"".$t_s[$id]."\">";
											echo "</div>";
										echo "</div>";
									echo "</div>";
									echo "<div class=\"row\">";
										echo "<div class=\"col-md-6\" style=\"padding-right:2px;\">";
											echo "<div class=\"form-group\">";
												echo "<input type=\"text\" class=\"form-control input-sm\" name=\"t_x[".$id."]\" id=\"t_x[".$id."]\" placeholder=\"".TEXT::get("position_x")."\" value=\"".$t_x[$id]."\">";
											echo "</div>";
										echo "</div>";
										echo "<div class=\"col-md-6\" style=\"padding-left:2px;\">";
											echo "<div class=\"form-group\">";
												echo "<input type=\"text\" class=\"form-control input-sm\" name=\"t_y[".$id."]\" id=\"t_y[".$id."]\" placeholder=\"".TEXT::get("position_y")."\" value=\"".$t_y[$id]."\">";
											echo "</div>";
										echo "</div>";
									echo "</div>";
								echo "</li>";
								$i++;
							}
						}
						$id = $i - 1;
						echo "<li class=\"list-group-item\">";
							echo "<div class=\"form-group\">";
								echo "<label for=\"t[".$id."]\">".TEXT::get("new_line")."</label>";
								echo "<input class=\"form-control input-sm\" type=\"text\" name=\"t[".$id."]\" id=\"t[".$id."]\" placeholder=\"Enter text\">";
							echo "</div>";
							echo "<div class=\"row\">";
								echo "<div class=\"col-md-5\" style=\"padding-right:0px;\">";
									echo "<div class=\"form-group\">";
										echo "<select name=\"t_f[".$id."]\" id=\"t_f[".$id."]\" class=\"form-control input-sm\">";
											echo "<option value=\"\">".TEXT::get("font")."</option>";
											if(is_array($this->fonts)) {
												foreach($this->fonts as $font_file=>$font_name) {
													echo "<option value=\"".$font_file."\">".$font_name."</option>";
												}
											}
										echo "</select>";
									echo "</div>";
								echo "</div>";
								echo "<div class=\"col-md-4\" style=\"padding-left:4px;padding-right:4px;\">";
									echo "<div class=\"form-group\">";
										echo "<input type=\"text\" class=\"form-control input-sm\" name=\"t_c[".$id."]\" id=\"t_c[".$id."]\" placeholder=\"".TEXT::get("color")."\">";
									echo "</div>";
								echo "</div>";
								echo "<div class=\"col-md-3\" style=\"padding-left:0px;\">";
									echo "<div class=\"form-group\">";
										echo "<input type=\"text\" class=\"form-control input-sm\" name=\"t_s[".$id."]\" id=\"t_s[".$id."]\" placeholder=\"".TEXT::get("size")."\">";
									echo "</div>";
								echo "</div>";
							echo "</div>";
							echo "<div class=\"row\">";
								echo "<div class=\"col-md-6\" style=\"padding-right:2px;\">";
									echo "<div class=\"form-group\">";
										echo "<input type=\"text\" class=\"form-control input-sm\" name=\"t_x[".$id."]\" id=\"t_x[".$id."]\" placeholder=\"".TEXT::get("position_x")."\">";
									echo "</div>";
								echo "</div>";
								echo "<div class=\"col-md-6\" style=\"padding-left:2px;\">";
									echo "<div class=\"form-group\">";
										echo "<input type=\"text\" class=\"form-control input-sm\" name=\"t_y[".$id."]\" id=\"t_y[".$id."]\" placeholder=\"".TEXT::get("position_y")."\">";
									echo "</div>";
								echo "</div>";
							echo "</div>";
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
		echo "<div class=\"col-sm-9\">";
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
				if($this->step > 1) {
					$query="f_n=".$this->query['f_n']."&b=".$b;
					if(is_array($g)) {
						foreach($g as $id=>$graphic) {
							if($g[$id]!="") {
								$query.="&g[".$id."]=".$g[$id]."&g_x[".$id."]=".$g_x[$id]."&g_y[".$id."]=".$g_y[$id]."";
							}
						}
					}
					if(is_array($t)) {
						foreach($t as $id=>$text) {
							if($t[$id]!="") {
								$query.="&t[".$id."]=".$t[$id]."&t_f[".$id."]=".$t_f[$id]."&t_c[".$id."]=".$t_c[$id]."&t_s[".$id."]=".$t_s[$id]."&t_x[".$id."]=".$t_x[$id]."&t_y[".$id."]=".$t_y[$id]."";
							}
						}
					}
					$updatedQuery = $query;
					echo "<div class=\"panel panel-default\">";
						echo "<div class=\"panel-body\">";
							echo "<div class=\"row\">";
								echo "<div class=\"col-md-6\">";
									echo "<p><strong>".$this->query['f_n']."</strong></p>";
								echo "</div>";
								echo "<div class=\"col-md-6 text-right\">";
									echo "<a href=\"".Config::get('URL')."infographic/showInfographic?".$query."\" role=\"button\" class=\"btn btn-default btn-xs\"><span class=\"glyphicon glyphicon-download\" aria-hidden=\"true\"></span> ".TEXT::get("download")."</a>";
								echo "</div>";
							echo "</div>";
							echo "<img class=\"img-responsive center-block\" style=\"width:600px;height:600px\" alt=\"\" src=\"".Config::get('URL')."infographic/showInfographic?".$query."\">";
						echo "</div>";
					echo "</div>";
				}
		echo "</div>";
	echo "</div>";
	// Save or Delete Options
	echo "<div class='row'>";
		echo "<div class='panel panel-default'>";
			echo "<form method=\"post\" action=\"".Config::get('URL')."custom/".$this->action."\">";
				echo "<input type='hidden' name='id' value='".$this->query['id']."' />";
				echo "<input type='hidden' name='url' value='".$updatedQuery."' />";
				echo "<button type='submit' class='btn btn-primary'>Save Infographic</button>";
			echo "</form>";
		echo "</div>";
	echo "</div>";
echo "</div>";

?>