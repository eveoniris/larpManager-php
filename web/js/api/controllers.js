
LarpManagerApp.controller('ChronologieController', function ($scope, Restangular) {
	
	Restangular.all('chronologie').getList().then(function(result) {
        $scope.events = result;
    });
	
	$scope.removeEvent = function(event) {
		event.remove().then( function() {
			console.log('Evenement retiré');
		},
		function() {
			console.log('Error retrait evenement');
		});
	}
});


/**
 * Controller Territoire
 * @param $scope
 * @param Territoire
 * @returns
 */
LarpManagerApp.controller('TerritoireController', function ($scope,  $filter, Restangular) {
	
	$scope.EventVisiblite = [
	        {value: 'PRIVATE', text:'PRIVATE'},
	        {value: 'PUBLIC', text:'PUBLIC'},
	        {value: 'GROUPE_MEMBER', text:'GROUPE_MEMBER'}
	        ];
		
	/**
	 * Rafraichir la liste des territoires
	 */
	$scope.refresh = function()	{
		$scope.selected = undefined;
		Restangular.all('territoire').getList().then(function(result) {
	        $scope.territoires = result;
	    });
	}
	
	/**
	 * Créer un nouveau territoire
	 */
	$scope.new = function() {
		newTerritoire = Restangular.restangularizeElement(null, {}, "territoire",null);
		$scope.territoires.push(newTerritoire);
		$scope.selectTerritoire(newTerritoire);
	}
	
	/**
	 * Selection d'un territoire dans la liste
	 */
	$scope.selectTerritoire = function(territoire) {
		$scope.selected = territoire;
	}
	
	/**
	 * Sauvegarde d'un territoire
	 */
	$scope.saveTerritoire = function(territoire) {
		territoire.post();
	}
	
	/**
	 * Sauvegarde d'un événement appartenant à un territoire
	 */
	$scope.updateEvent = function(event)  {
		event.territoire_id = $scope.selected.id;
		e = Restangular.restangularizeElement(null, event, "chronologies",null);
		e.save().then(function(result) {
			console.log(result);	
		});
	}
	
	/**
	 * Suppression d'un événement appartenant à un territoire
	 */
	$scope.removeEvent = function(event) {
		e = Restangular.restangularizeElement(null, event, "chronologies",null);
		e.remove().then(function(result) {
			$scope.selected.chronologies.splice(_.indexOf($scope.selected.chronologies,event), 1);
			console.log(result);
		});
	}
	
	/**
	 * Formulaire d'ajout d'un événement
	 */
	$scope.addEvent = function() {
		$scope.insertMode = true;
		$scope.inserted = {
				id: '',
				year : '',
				description : '',
				visibilite : ''
		};
		$scope.selected.chronologies.push($scope.inserted);
	}
	
	/**
	 * Changer de religion principale
	 */
	$scope.updateReligion = function(religionId) {
		$scope.selected.religion_id = religionId;
		
		var arrayLength = $scope.availableReligions.length;
		for (var i = 0; i < arrayLength; i++ ) {
			if  ( $scope.availableReligions[i].id == religionId ) {
				$scope.selected.religion = $scope.availableReligions[i]
			}
		}
		$scope.selected.post();
	}
	
	/**
	 * Met à jour la liste des religions secondaires
	 */
	$scope.updateReligionSecondaires = function(religionIdArray) {
		$scope.selected.religion_id_list = religionIdArray;
		$scope.selected.religions = [];
		var arrayLength = $scope.availableReligions.length;
		for (var i = 0; i < arrayLength; i++ ) {
			if  ( inArray($scope.availableReligions[i].id,religionIdArray) ) {
				$scope.selected.religions.push( $scope.availableReligions[i]);
			}
		}
		$scope.selected.post();
	}
	
	/**
	 *  récupérer tous les territoires
	 */
	$scope.refresh();
	
	
	/**
	 * Récupérer toutes les religions
	 */
	$scope.availableReligions = [];
	
	$scope.loadAvailableReligions = function() {
		return $scope.availableReligions.length ? null : Restangular.all('religion').getList().then(function(result) {
			var arrayLength = result.length;
			for (var i = 0; i < arrayLength; i++ ) {
				$scope.availableReligions.push(result[i].plain());
			}
			
		});
	}
});

