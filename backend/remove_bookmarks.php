<?php 

	//function to remove the id of the word that is received in the api parameter, in the bookmarks database
	function removeBookmark()
	{
		require_once 'db_config.php';
		require_once 'constants.php';
		require_once 'db_functions.php';

		//connect to the database
		$db_server = connectDatabase($db_hostname,$db_database,$db_username,$db_password);

		//if only an id parameter is recieved then delete it from the table
		if(isset($_REQUEST["id"]))
		{
			$id = strtolower($_REQUEST["id"]);			
			$query_deletebookmark_stmnt = $db_server->prepare("DELETE FROM $db_bookmarks_tablename WHERE $db_bookmarks_col_entry_id = :id");
			$query_deletebookmark_stmnt->bindValue(":id",$id);
			$query_deletebookmark_stmnt->execute();
		}

		//if clearBookmarks parameter is received, then delete the entire contents of the bookmarks table
		if(isset($_REQUEST["clearBookmarks"]))
		{
			$query_deletebookmark_stmnt = $db_server->prepare("DELETE FROM $db_bookmarks_tablename");
			$query_deletebookmark_stmnt->execute();
		}
		$db_server = null;
	}
 ?>