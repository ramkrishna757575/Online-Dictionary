<?php 

	//a generic function that can be used to connect to any database and obtain the handle to it
	function connectDatabase($db_hostname,$db_database,$db_username,$db_password)
	{		
		try{
			$db_server=new PDO('mysql:host='.$db_hostname.';dbname='.$db_database,$db_username,$db_password);
			// Optional PDO attributes
		     $db_server->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
		     $db_server->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			die("Oh snap...something went wrong in connecting to database :\n" . $e->getMessage());
		}
		return $db_server;
	}
 ?>