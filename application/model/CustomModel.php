<?php

/**
 * Class CustomModel
 *
 */
class CustomModel
{
	/**
	 * Processes custom infographic URL
	 */
	public static function processCustomInfographic($query)
	{

		// Check if we're editing an existing infographic
		if(isset($query[1])) {
			$query = CustomModel::parseQuery($query[1]);

			// Check if we're accessing from an ID and pull the correct URL
			if(isset($query['id']) && $query['id'] != "") {
				$infographic = CustomModel::getInfographic($query['id']);
				$query = CustomModel::parseQuery($infographic->url);
				$query['id'] = $infographic->info_id;
				$query['url'] = $infographic->url;
			}
		}

		if(!isset($query['id'])) {
			$result = CustomModel::getDefaultTemplate();
			if($result) {
				$query = CustomModel::parseQuery($result->default_template);
				$query['url'] = $result->default_template;
			}
			else {
				$query['f_n'] = "custom_infographic";
			}
		}

		return $query;
		
	}

	/**
	 * Determine step of process user is on
	 */
	public static function trackStepProgress($query, $step = 1)
	{
		if(isset($query['id']) || isset($query['f_n'])) {
			if(isset($query['g']) && $query['g'][0] != "") {
				$step = 3;
			}
			elseif(isset($query['b'])) {
				$step = 2;
			}
		}
		return $step;
	}

	private static function parseQuery($query) {
		parse_str($query, $arguments);
		return $arguments;
	}

	/**
	 * Determines what action we're rendering, creating or editing
	 */
	public static function determineAction($query)
	{

		// Check if we're editing an existing infographic
		if(isset($query[1])) {
			$query = CustomModel::parseQuery($query[1]);

			// Check if we're accessing from an ID and pull the correct URL
			if(isset($query['id']) && $query['id'] != "") {
				$action = "edit";
			}
			else {
				$action = "create";
			}
		}
		else {
			$action = "create";
		}

		return $action;
		
	}


	/**
     * Get all infographics created by the user
     * @return array an array with several objects (the results)
     */
    public static function getAllInfographics()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT info_id, user_id, url, name, created_date, edited_date FROM infographics WHERE user_id = :user_id";
        $query = $database->prepare($sql);
        $query->execute(array(':user_id' => Session::get('user_id')));

        // fetchAll() is the PDO method that gets all result rows
        return $query->fetchAll();
    }

    /**
     * Get a single infographic
     * @param int $info_id id of the specific infographic
     * @return object a single object (the result)
     */
    public static function getInfographic($info_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT info_id, user_id, url, name, created_date, edited_date FROM infographics WHERE info_id = :info_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':info_id' => $info_id));

        // fetch() is the PDO method that gets a single result
        return $query->fetch();
    }

    /**
     * Set a infographic (create a new one)
     * @param string $url URL that will be stored
     * @return bool feedback (was the note created properly ?)
     */
    public static function createInfographic($url, $name)
    {
        if (!$url || strlen($url) == 0) {
            Session::add('feedback_negative', Text::get('FEEDBACK_INFOGRAPHIC_CREATION_FAILED'));
            return false;
        }

        // Format Dates
        $date = date("Y-m-d H:i:s", time());

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO infographics (url, user_id, name, created_date, edited_date) VALUES (:url, :user_id, :name, :dateFormatted, :dateFormatted)";
        $query = $database->prepare($sql);
        $query->execute(array(':url' => $url, ':user_id' => Session::get('user_id'), ':name' => $name, ':dateFormatted' => $date));

        if ($query->rowCount() == 1) {
            return $database->lastInsertId();
        }

        // default return
        Session::add('feedback_negative', Text::get('FEEDBACK_INFOGRAPHIC_CREATION_FAILED'));
        return false;
    }

    /**
     * Update an existing note
     * @param int $info_id id of the specific note
     * @param string $url new text of the specific note
     * @return bool feedback (was the update successful ?)
     */
    public static function updateInfographic($info_id, $url, $name)
    {
        if (!$info_id || !$url) {
            return false;
        }

        // Format Dates
        $date = date("Y-m-d H:i:s", time());

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE infographics SET url = :url, name = :name, edited_date = :dateFormatted WHERE info_id = :info_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':info_id' => $info_id, ':url' => $url, ':name' => $name, ':dateFormatted' => $date));

        if ($query->rowCount() == 1) {
            return true;
        }

        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_EDITING_FAILED'));
        return false;
    }

    /**
     * Delete a specific infographic
     * @param int $info_id id of the note
     * @return bool feedback (was the note deleted properly ?)
     */
    public static function deleteInfographic($info_id)
    {
        if (!$info_id) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "DELETE FROM infographics WHERE info_id = :info_id AND user_id = :user_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':info_id' => $info_id, ':user_id' => Session::get('user_id')));

        if ($query->rowCount() == 1) {
            return true;
        }

        // default return
        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_DELETION_FAILED'));
        return false;
    }

    /**
     * Update default template for user
     * @param int $info_id id of the specific note
     * @param string $url new text of the specific note
     * @return bool feedback (was the update successful ?)
     */
    public static function saveDefaultTemplate($url)
    {
        if (!$url) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE users SET default_template = :url WHERE user_id = :user_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':url' => $url, ':user_id' => Session::get('user_id')));

        if ($query->rowCount() == 1) {
            return true;
        }

        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_EDITING_FAILED'));
        return false;
    }

    /**
     * Get default template
     * @return object a single object (the result)
     */
    public static function getDefaultTemplate()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT default_template FROM users WHERE user_id = :user_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':user_id' => Session::get('user_id')));

        // fetch() is the PDO method that gets a single result
        return $query->fetch();
    }

    /**
     * Get default template
     * @return object a single object (the result)
     */
    public static function deleteDefaultTemplate()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE users SET `default_template` = '' WHERE user_id = :user_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':user_id' => Session::get('user_id')));

        if ($query->rowCount() == 1) {
            return true;
        }

        // default return
        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_DELETION_FAILED'));
        return false;
    }
}
