//the main controller that handles the display of words list, bookmarks and other functionality on the page

dictionary.controller('wordslistController',['$scope','$http',function($scope,$http){
	//scope variables to keep track of the current state of the page
	$scope.search_query = ""; //the query keyword to fetch the words
	$scope.selectedWord = "";	//the word that has been selected on the page
	$scope.selectedWordId = ""; //the id of the word that has been selected on the page
	$scope.wordLimit = 5;		//change it to set the number of words that can be displayed on the page at once
	$scope.nextwordIndex = 1;	//used to keep track of the next set of words to be fetched
	$scope.currentTopIndex = 1;	//maintains the id of the first word displayed on the page
	$scope.words = [];

	//sets the search keyword
	$scope.setSearchQuery = function(query){
		$scope.search_query = query;
	};

	//function to get the words list from the database based on the starting index, 
	//i.e words that have id's greater than or equal to the parameter passed in this function
	$scope.getWords = function(nextIndex){
		//make a get request to the server with the required parameters to fetch the words
		$http.get('backend/db_dictionary_handler.php',{params:{"query":$scope.search_query,"nextwordIndex":nextIndex,"limit":$scope.wordLimit}}).success(function(data){
			$scope.words = data.words;	
			//update the currentTopIndex with the id of the first word received
			$scope.currentTopIndex = $scope.words[0].id;
		});			
		//update the api hits as a call to server was made
		$scope.getApiHits();		
	};

	//change the value of nextwordIndex so that the words fetched are beginning from the id equal to nextwordIndex
	//called when Next button clicked
	$scope.setNextWordIndex = function(){
		$scope.nextwordIndex = $scope.currentTopIndex + $scope.wordLimit;
		if($scope.words == null || jQuery.isEmptyObject($scope.words))
		{
			$scope.nextwordIndex = 1;
		}
	}

	//change the value of nextwordIndex so that the words fetched are beginning from the id equal to nextwordIndex
	//called when Prev button clicked
	$scope.setPrevWordIndex = function(){
		$scope.nextwordIndex = $scope.currentTopIndex - $scope.wordLimit;
		if($scope.nextwordIndex < 1)
		{
			$scope.nextwordIndex = 1;
		}
	}
	
	//sets the value of the selectedWord and selectedWordId variables that keep track of the word selected
	$scope.setSelectedWord = function(wordToSelect, wordId){
		//toggles the selection of the word
		if($scope.selectedWordId == "" || $scope.selectedWordId != wordId)
		{
			$scope.selectedWord = wordToSelect;
			$scope.selectedWordId = wordId;
		}else{

			$scope.selectedWord = "";
			$scope.selectedWordId = "";
		}
	};

	//variables to keep track of bookmarked words
	$scope.bookmarkedWords = [];
	$scope.bookmarkedWordsId = [];
	$scope.bookmarksFetched = false;

	//gets the bookmarked words' id and word values from the server.
	$scope.getBookmarks = function(){
		$http.get('backend/db_dictionary_handler.php',{params:{"bookmarks":""}}).success(function(data){
			for(var element in data.bookmarks)
			{
				$scope.bookmarkedWords.push(data.bookmarks[element].word);
				$scope.bookmarkedWordsId.push(data.bookmarks[element].entries_id);
			}
			$scope.bookmarksFetched = true;			
		});
	};

	//Executed only once to reduce server load
	if(!$scope.bookmarksFetched)
	{
		$scope.getBookmarks();
	}

	//alters the local arrays that store the id and word of the word that is clicked for adding or removing bookmark
	$scope.setBookmark = function(wordToSet,wordId){
		$index = $scope.bookmarkedWordsId.indexOf(wordId);
		if($index < 0)
		{
			$http.put('backend/db_dictionary_handler.php',{params:{"id":wordId}}).success(function(data){
				console.log("success");
			});

			$scope.bookmarkedWordsId.push(wordId);
			$scope.bookmarkedWords.push(wordToSet);
		}else{
			$http.delete('backend/db_dictionary_handler.php',{params:{"id":wordId}}).success(function(data){
				console.log("success");
			});

			$scope.bookmarkedWordsId.splice($index,1);
			$scope.bookmarkedWords.splice($index,1);
		}
		//update the api hits as a call to server was made
		$scope.getApiHits();
	};

	//calls the server and removes all the bookmarks from the server and also from local arrays
	$scope.clearBookmarks = function(){
		if($scope.bookmarkedWordsId.length > 0)
		{
			$http.delete('backend/db_dictionary_handler.php',{params:{"clearBookmarks":""}}).success(function(data){
				console.log("success");
			});

			//empty the local arrays
			$scope.bookmarkedWordsId = [];
			$scope.bookmarkedWords = [];
			//update the api hits as a call to server was made
			$scope.getApiHits();
		}
	};

	//checks if a word with certain id is already bookmarked or not
	$scope.isBookmarked = function(Id) {
		$index = $scope.bookmarkedWordsId.indexOf(Id);
		if($index < 0)
		{
			return false;
		}else{
			return true;
		}
	};

	//function to download pdf containing bookmarked words
	$scope.downloadPdf = function(){
		if($scope.bookmarkedWordsId.length > 0)
		{
			$http.get('backend/db_dictionary_handler.php',{params:{"downloaddoc":""}}).success(function(data){
				window.open(data.url);
			});
			$scope.getApiHits();
		}		
	}

	//Array to hold the pagination letters
	$scope.paginationArray = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

	//Fetch the words from the server once when the page is loaded
	$scope.getWords(1);
}]);