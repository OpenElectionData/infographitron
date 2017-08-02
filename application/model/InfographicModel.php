<?php

/**
 * Class InfographicModel
 *
 */
class InfographicModel
{
	/**
	 * Generates the captcha, "returns" a real image, this is why there is header('Content-type: image/jpeg')
	 * Note: This is a very special method, as this is echoes out binary data.
	 */
	public static function generateAndShowInfographic($query)
	{

		$infographics = new Infographics;

		// Get the title of the infographic so we can save it as the name
		$title = $infographics->getTitle($query);

		// render an image showing the characters (=the captcha)
		header("Content-Type: image/png");
		header("Content-Disposition: attachment; filename=\"".$title.".png\""); 
		$infographics->get($query);
	}

	public static function zipInfographics($filename, $firstline)
	{
		// Pass $filename and $firstline to core to generate zip file
		$infographics = new Infographics;
		$infographics->zip($filename, $firstline);
	}

	public static function generateBackgrounds() {
		$infographics = new Infographics;
		return $infographics->getBackgrounds();
	}

	public static function generateGraphics($getInfo = false) {
		if($getInfo) {
			return InfographicModel::getGraphics();
		}
		else {
			$infographics = new Infographics;
			return $infographics->getGraphics();
		}
	}

	public static function generateFonts() {
		$infographics = new Infographics;
		return $infographics->getFonts();
	}

	// Get background images viewable to user
	public static function getBackgrounds() {
		$database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT asset_id, type, title, file, uploaded_by, viewable_by, created_date FROM assets WHERE type='backgrounds' AND viewable_by IN (-1, :user_id)";
        $query = $database->prepare($sql);
        $query->execute(array(':user_id' => Session::get('user_id')));

        // fetch() is the PDO method that gets a single result
        return $query->fetchAll();
	}

	// Get graphics images viewable to user
	public static function getGraphics() {
		$database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT asset_id, type, title, tags, file, uploaded_by, viewable_by, created_date FROM assets WHERE type='graphics' AND viewable_by IN (-1, :user_id)";
        $query = $database->prepare($sql);
        $query->execute(array(':user_id' => Session::get('user_id')));

        // fetch() is the PDO method that gets a single result
        return $query->fetchAll();
	}

	// Get fonts images viewable to user
	public static function getFonts() {
		$database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT asset_id, type, title, file, preview, uploaded_by, viewable_by, created_date FROM assets WHERE type='fonts' AND viewable_by IN (-1, :user_id)";
        $query = $database->prepare($sql);
        $query->execute(array(':user_id' => Session::get('user_id')));

        // fetch() is the PDO method that gets a single result
        return $query->fetchAll();
	}

	/**
	 * Download infographic in CSV template
	 * @param  array $query URL of infographic
	 * @return file        CSV file
	 */
	public static function downloadCSV($query)
	{
		parse_str($query, $arguments);

		// Header & Background
		$header_row = array("Name", "Background");
		$content_row = array($arguments['f_n'], $arguments['b']);

		// Loop through graphics
		if(isset($arguments['g'])) {
			$graphicsCount = count($arguments['g']) - 1;
			for($i = 0; $i <= $graphicsCount; $i++) {
				array_push($header_row, "Type", "Image", "X", "Y");
				array_push($content_row, "1", $arguments['g'][$i], $arguments['g_x'][$i], $arguments['g_y'][$i]);
			}
		}

		// Loop through text
		if(isset($arguments['t'])) {
			$textCount = count($arguments['t']) - 1;
			for($i = 0; $i <= $textCount; $i++) {
				array_push($header_row, "Type", "Text", "Font", "Color", "Size", "X", "Y");
				array_push($content_row, "2", $arguments['t'][$i], $arguments['t_f'][$i], $arguments['t_c'][$i], $arguments['t_s'][$i], $arguments['t_x'][$i], $arguments['t_y'][$i]);
			}
		}

		$output = fopen("php://output",'w') or die("Can't open php://output");
		header("Content-Type:application/csv"); 
		header("Content-Disposition:attachment;filename=infographics.csv"); 
		fputcsv($output, $header_row);
		fputcsv($output, $content_row);
		fclose($output) or die("Can't close php://output");
	}

	/**
	 * Download multiple infographics into CSV template
	 * @param  array $urls URLS of infographics
	 * @return file        CSV file
	 */
	public static function downloadCSVBulk($urls)
	{

		$graphicsMax = 0;
		$graphicFields = 4;
		$textMax = 0;
		$textFields = 7;
		$contents = array();
		$header_row = array("Name", "Background");

		foreach($urls as $url => $value) {
			parse_str($value->url, $arguments);

			// Name & Background
			$content_row['name'] = array($arguments['f_n'], $arguments['b']);
			$content_row['graphics'] = array();
			$content_row['text'] = array();

			// Loop through graphics
			if(isset($arguments['g'])) {
				$graphicsCount = count($arguments['g']);
				$arguments['g'] = array_values($arguments['g']);
				$arguments['g_x'] = array_values($arguments['g_x']);
				$arguments['g_y'] = array_values($arguments['g_y']);

				if($graphicsCount >= $graphicsMax) {
					$graphicsMax = $graphicsCount;
				}
				for($i = 0; $i <= ($graphicsCount - 1); $i++) {
					array_push($content_row['graphics'], "1", $arguments['g'][$i], $arguments['g_x'][$i], $arguments['g_y'][$i]);
				}
			}

			// Loop through text
			if(isset($arguments['t'])) {
				$textCount = count($arguments['t']);
				$arguments['t'] = array_values($arguments['t']);
				$arguments['t_x'] = array_values($arguments['t_x']);
				$arguments['t_y'] = array_values($arguments['t_y']);
				$arguments['t_f'] = array_values($arguments['t_f']);
				$arguments['t_c'] = array_values($arguments['t_c']);
				$arguments['t_s'] = array_values($arguments['t_s']);
				if($textCount >= $textMax) {
					$textMax = $textCount;
				}
				for($i = 0; $i <= $textCount - 1; $i++) {
					array_push($content_row['text'], "2", $arguments['t'][$i], $arguments['t_f'][$i], $arguments['t_c'][$i], $arguments['t_s'][$i], $arguments['t_x'][$i], $arguments['t_y'][$i]);
				}
			}

			$contents[] = $content_row;
		}


		for($i = 1; $i <= $graphicsMax; $i++) {
			array_push($header_row, "Type", "Image", "X", "Y");
		}

		for($i = 1; $i <= $textMax; $i++) {
			array_push($header_row, "Type", "Text", "Font", "Color", "Size", "X", "Y");
		}

		$output = fopen("php://output",'w') or die("Can't open php://output");
		header("Content-Type:application/csv"); 
		header("Content-Disposition:attachment;filename=infographics.csv"); 
		fputcsv($output, $header_row);
		
		foreach($contents as $content) {
			$graphicsCount = count($content['graphics']);
			$graphicsTotal = $graphicsMax * $graphicFields;

			if($graphicsCount != ($graphicsTotal - 1)) {
				$graphics = array_pad($content['graphics'], $graphicsTotal, "");
			}
			else {
				$graphics = $content['graphics'];
			}

			$textCount = count($content['text']);
			$textTotal = $textMax * $textFields;

			if($textCount != ($textTotal - 1)) {
				$text = array_pad($content['text'], $textTotal, "");
			}
			else {
				$text = $content['text'];
			}

			fputcsv($output, array_merge($content['name'], $graphics, $text));
		}
		fclose($output) or die("Can't close php://output");
	}
}
