<?php 

	//function to handle the get or post request and return appropriate json data
	function getJsonData()
	{
		require_once 'db_config.php';
		require_once 'constants.php';
		require_once 'db_functions.php';
		require_once 'pdf_creator.php';

		//connect to the dictionary database
		$db_server = connectDatabase($db_hostname,$db_database,$db_username,$db_password);

		//create an empty generic object to hold various data
		$obj = new stdClass();

		//check if the query parameter is received in  the request and fetch data from DB, else skip
	    if(isset($_REQUEST["query"]))
	    {
	    	$query = strtolower($_REQUEST["query"]);

	    	//if limit parameter is not received, then stop, else go ahead to fetch data
	    	if(isset($_REQUEST["limit"])  && !empty($_REQUEST["limit"]))
	        	$limit = strtolower($_REQUEST["limit"]);
		    else
		    	die("Oh Oh...missing the limit parameter in the api");

		    //if nextwordIndex parameter is not received, then stop, else go ahead to fetch data beginning from the index provided
		    if(isset($_REQUEST["nextwordIndex"])  && !empty($_REQUEST["nextwordIndex"]))
	        	$nextwordIndex = strtolower($_REQUEST["nextwordIndex"]);
		    else
		    	die("Oh Oh...missing the index of last word parameter in the api");

		    //if query parameter has no query text, then fetch data from beginning
	    	if($query == null)
			{
				$query_words_stmnt = $db_server->prepare("SELECT * FROM $db_tablename WHERE $db_col_id >= :nextwordIndex ORDER BY $db_col_id ASC LIMIT :datalimit");
			}else{
				//if limit parameter is not received, then stop, else go ahead to fetch data
				if($limit != null)
					$statement = "SELECT * FROM $db_tablename WHERE $db_col_word LIKE :query AND $db_col_id >= :nextwordIndex ORDER BY $db_col_id ASC LIMIT :datalimit";
				else
					die("Oh Oh...missing some parameters in the api");

				//inject the query text in the prepared sql statement
				$query_words_stmnt = $db_server->prepare($statement);
				$query_words_stmnt->bindValue(':query', "$query%");			
			}
			//inject the datalimit in the prepared sql statement
			$query_words_stmnt->bindValue(':datalimit', $limit);
			//inject the nextwordIndex in the prepared sql statement
			$query_words_stmnt->bindValue(':nextwordIndex', $nextwordIndex);
			//fetch the data from DB
			$query_words_stmnt->execute();	
			//create a words object in obj variable and initialise it with the data received from DB
			$obj->words = $query_words_stmnt->fetchAll(PDO::FETCH_ASSOC);			
	    }	

	    //if bookmarks parameter is received, then fetch bookmarks data from DB
		if(isset($_REQUEST["bookmarks"]))
		{
			//prepare statement to fetch the words that are bookmarked
			$query_bookmarks_stmnt = $db_server->prepare("SELECT $db_col_word,$db_bookmarks_tablename.$db_bookmarks_col_entry_id FROM $db_tablename INNER JOIN $db_bookmarks_tablename ON $db_bookmarks_col_entry_id=$db_tablename.$db_col_id ORDER BY $db_bookmarks_col_entry_id ASC");	
			//fetch the bookmarked words
			$query_bookmarks_stmnt->execute();
			//create a bookmarks object in obj variable and initialise it with the data received from DB
			$obj->bookmarks = $query_bookmarks_stmnt->fetchAll(PDO::FETCH_ASSOC);
		}		

		//if downloaddoc parameter is received, then create PDF and send back its link to download
		if(isset($_REQUEST["downloaddoc"]))
		{
			//prepare statement to fetch the words that are bookmarked
			$query_bookmarks_stmnt = $db_server->prepare("SELECT $db_col_word,$db_col_definition FROM $db_tablename INNER JOIN $db_bookmarks_tablename ON $db_bookmarks_col_entry_id=$db_tablename.$db_col_id ORDER BY $db_bookmarks_col_entry_id ASC");	
			//fetch the bookmarked words
			$query_bookmarks_stmnt->execute();
			//create PDF and send back its link to download
			return json_encode(createPDF($query_bookmarks_stmnt->fetchAll(PDO::FETCH_ASSOC)));
		}

		//get the updated api hit-count and put it in the obj object
		if(isset($_REQUEST["apihits"]))
			$obj->apihits = getApiHitCount();

		//disconnect with the DB server
		$db_server = null;

		//Json encode the obj object and return it
		return json_encode($obj);
	}
 ?>