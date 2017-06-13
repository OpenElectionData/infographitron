<?php

class Infographics
{
    private static $infographics;
    private static $backgrounds;
    private static $graphics;
    private static $fonts;

    public function get($query)
    {
	    // If we don't have a query, exit
	    if (!$query) {
		    return null;
	    }

	    // create array with URL parts in $url
        $arguments = $this->splitQuery($query);

        // Load Backgrounds, Graphics, & Fonts
        $backgrounds = $this->getBackgrounds();
        $graphics = $this->getGraphics();
        $fonts = $this->getFonts();

	    
	    if(isset($arguments['b']) && $backgrounds[$arguments["b"]]!="") {
			$b=$arguments["b"];
		} else {
			$b="background_white.png";
		}

		$im=@imagecreatefrompng("./uploads/backgrounds/".$b);

		if(isset($arguments['g']) && is_array($arguments["g"])) {
			foreach($arguments["g"] as $id=>$graphic) {
				
				if($graphic != "") {
					$g[$id]=$arguments["g"][$id];
					if($arguments["g_x"][$id]>="0" && $arguments["g_x"][$id]<="1000") {
						$g_x[$id]=$arguments["g_x"][$id];
					} else {
						$g_x[$id]="30";
					}
					if($arguments["g_y"][$id]>="0" && $arguments["g_y"][$id]<="1000") {
						$g_y[$id]=$arguments["g_y"][$id];
					} else {
						$g_y[$id]=$arguments["g_y"]="60";
					}
				}
			}
		}
		if(isset($arguments['t']) && is_array($arguments["t"])) {
			foreach($arguments["t"] as $id=>$text) {
				if($arguments["t"][$id]!="") {
					$t[$id]=$arguments["t"][$id];
					if($fonts[$arguments["t_f"][$id]]!="") {
						$t_f[$id]="./uploads/fonts/".$arguments["t_f"][$id];
					} else {
						$t_f[$id]="./uploads/fonts/arial.ttf";
					}
					if($arguments["t_c"][$id]!="") {
						$c="";
						$c= $this->hex2RGB($arguments["t_c"][$id]);
						if(is_array($c)) {
							$t_c[$id]=imagecolorallocate($im, $c["red"], $c["green"], $c["blue"]);
						} else {
							$t_c[$id]=imagecolorallocate($im, 0,0,0);
						}
					} else {
						$t_c[$id]=imagecolorallocate($im, 0,0,0);
					}
					if($arguments["t_s"][$id]>"0" && $arguments["t_s"][$id]<="1000") {
						$t_s[$id]=$arguments["t_s"][$id];
					} else {
						$t_s[$id]="18";
					}
					if($arguments["t_x"][$id]>="0" && $arguments["t_x"][$id]<="1000") {
						$t_x[$id]=$arguments["t_x"][$id];
					} else {
						$t_x[$id]="20";
					}
					if($arguments["t_y"][$id]>="0" && $arguments["t_y"][$id]<="1000") {
						$t_y[$id]=$arguments["t_y"][$id];
					} else {
						$t_y[$id]="20";
					}
				}
			}
		}
		if(isset($arguments['g']) && is_array($arguments["g"])) {
			foreach($arguments["g"] as $id=>$graphic) {
				if($graphic!="") {
					list($g_w[$id], $g_h[$id]) = getimagesize("./uploads/graphics/".$g[$id]);
					imagecopy($im, imagecreatefrompng("./uploads/graphics/".$g[$id]), $g_x[$id], $g_y[$id], 0, 0, $g_w[$id], $g_h[$id]);
				}
			}
		}
		if(isset($arguments['t']) && is_array($arguments["t"])) {
			foreach($arguments["t"] as $id=>$text) {
				if($t[$id]!="") {
					imagettftext($im, $t_s[$id], 0, $t_x[$id], $t_y[$id], $t_c[$id], $t_f[$id], $t[$id]);
				}
			}
		}

		$image = imagepng($im);
		// return $image;

		
		// imagedestroy($im);

        // return $query;
    }

    public function zip($filename, $firstline) {
    	if (class_exists("ZipArchive")) {
			if(file_exists("tmp/".$filename)) {
				$handle = @fopen("tmp/".$filename,"r");
				if($handle!==false) {
					$i=0;
					$csv=array();
					while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
						$csv[$i]=$data;
						$i++;
					}
					fclose($handle);
					$zipname = "tmp/".$filename.".zip";
					$zip = new ZipArchive;
					$zip->open($zipname, ZipArchive::CREATE);
					
					if(is_array($csv)) {
						$n=0;
						$p=0;
						$query="";
						foreach($csv as $n=>$row) {
							if($firstline!="ignore" || $n>0) {
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
											$query.="&t[".$i_t."]=".urlencode($value)."";
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
								$zip->addFromString($row[0].".png", file_get_contents(Config::get('URL')."infographic/showInfographic?".$query));
							}
						}
					}
					$zip->close();

					header("Content-Type: application/zip");
					header("Content-disposition: attachment; filename=infographitron.zip");
					header("Content-Length: ".filesize($zipname));
					readfile($zipname);
				} else {
					echo "File read error.";
				}
			} else {
				echo "File does not excist.";
			}
		} else {
			echo "PHP ZipArchive class is not available.";
		}
    }

    /**
     * Get and split the query
     */
    private function splitQuery($query)
    {	
    	parse_str($query, $arguments);

        // Return our array of arguments
        return $arguments;
    }

    /**
     * Get and split the query
     */
    public function getTitle($query)
    {
    	
    	parse_str($query, $arguments);

        // Return our array of arguments
        return $arguments['f_n'];
    }

    public function getBackgrounds() {
    	// load config file (this is only done once per application lifecycle)
        if (!self::$backgrounds) {
        	$array = array();
        	// Load in assets
        	$backgrounds = InfographicModel::getBackgrounds();
        	foreach($backgrounds as $index => $value) {
        		$array[$value->file] = $value->title;
        	}
        	self::$backgrounds = $array;
        }
        return self::$backgrounds;
    }

    public function getGraphics() {
    	// load config file (this is only done once per application lifecycle)
        if (!self::$graphics) {
        	$array = array();
        	// Load in assets
        	$graphics = InfographicModel::getGraphics();
        	foreach($graphics as $index => $value) {
        		$array[$value->file] = $value->title;
        	}
        	self::$graphics = $array;
        }
        return self::$graphics;
    }

    public function getFonts() {
    	// load config file (this is only done once per application lifecycle)
        if (!self::$fonts) {
        	$array = array();
        	// Load in assets
        	$fonts = InfographicModel::getFonts();
        	foreach($fonts as $index => $value) {
        		$array[$value->file] = $value->title;
        	}
        	self::$fonts = $array;
        }
        return self::$fonts;
    }

    private function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
		$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr);
		$rgbArray = array();
		if (strlen($hexStr) == 6) {
			$colorVal = hexdec($hexStr);
			$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
			$rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
			$rgbArray['blue'] = 0xFF & $colorVal;
		} elseif (strlen($hexStr) == 3) {
			$rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
			$rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
			$rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
		} else {
			return false;
		}
		return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray;
	}

}
