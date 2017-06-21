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

        $sql = "SELECT asset_id, type, title, file, uploaded_by, viewable_by, created_date FROM assets WHERE type='fonts' AND viewable_by IN (-1, :user_id)";
        $query = $database->prepare($sql);
        $query->execute(array(':user_id' => Session::get('user_id')));

        // fetch() is the PDO method that gets a single result
        return $query->fetchAll();
	}
}