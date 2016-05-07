<?php 

	//function to add the id of the word that is received in the api parameter, in the bookmarks database
	function addBookmark()
	{
		require_once 'db_config.php';
		require_once 'constants.php';
		require_once 'db_functions.php';

		//get the json request recieved
		$raw_json = file_get_contents("php://input");
		//decode the json request to get the request data
		$_REQUEST = json_decode($raw_json,true);
		//if we recieved an id parameter, then we add it to the bookmarks database
		if(isset($_REQUEST["params"]["id"]))
		{
			$id = strtolower($_REQUEST["params"]["id"]);
			$db_server = connectDatabase($db_hostname,$db_database,$db_username,$db_password);
			$query_addbookmark_stmnt = $db_server->prepare("INSERT INTO $db_bookmarks_tablename (entries_id) VALUES (:id)");
			$query_addbookmark_stmnt->bindValue(":id",$id);	
			$query_addbookmark_stmnt->execute();
			$db_server = null;
		}
	}
 ?>