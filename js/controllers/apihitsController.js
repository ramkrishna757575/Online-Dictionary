// controller to get the apihits from the server

dictionary.controller('apihitsController',['$scope','$http',function($scope,$http){
	
	$scope.hits = parseInt(0);	//variable to keep track of apihits
	$scope.isApiHitsNotFetched = true;	//variable to check if apiHits fetched for the first time, when page loaded

	//function to get api hit-count form  the server
	$scope.getApiHits = function(){
		//make a get request with the apihits parameter to get the api hit-count from the server
		$http.get('backend/db_dictionary_handler.php',{params:{"apihits":''}}).success(function(data){
			if($scope.isApiHitsNotFetched)
			{
				$scope.hits = data.apihits;
				$scope.isApiHitsNotFetched = false;
			}else{
				$scope.hits = parseInt($scope.hits) + parseInt(1);
			}						
		});
		console.log($scope.hits);
	};
	//called for the first time the page is loaded
}]);