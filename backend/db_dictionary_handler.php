<?php
	
	//This provides the various apis that can be used to fetch from or send data to the DBs
	//This handles the operations based on the request type

	// Headers to allow external API calls; and to tackle browser's client-side security policy towards script execution.
	header('Access-Control-Allow-Origin: *');
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Access-Control-Allow-Methods: GET, POST, PUT');	

	require_once "hitcounter_manager.php";

	//check the request type and perform operations accordingly
    if($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'POST')
	{
		//update the api hit-counter each time when this api is called
		updateHitCounter();
		//get the requested data and return it in json format
		require_once 'client_read_request.php';
		echo getJsonData();
	}else if($_SERVER['REQUEST_METHOD'] == 'PUT'){
		//add the requested word to the bookmarks list by adding the received id in the bookmarks table
		require_once 'add_bookmark.php';
		addBookmark();
	}else if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
		//remove the requested word from the bookmarks list by removing the received id in the bookmarks table
		require_once 'remove_bookmarks.php';
		removeBookmark();
	}    
 ?>