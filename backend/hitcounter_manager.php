<?php 

	//manages the api hit count by writing and reading from a file

	//updates the hit cound value by incrementing it by 1
	function updateHitCounter()
	{
		$count = getApiHitCount();
		$count = $count + 1;
		$myfile = fopen("hitcounter.txt", "w") or die("File Doesn't exist");
		$txt = $count;
		fwrite($myfile, $txt);
		fclose($myfile);
	}

	//returns the numeric value present in the hitcounter.txt file, which keeps track of the hit counts
	function getApiHitCount()
	{
		return file_get_contents("hitcounter.txt");
	}
 ?>