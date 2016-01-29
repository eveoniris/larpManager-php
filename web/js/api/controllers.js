
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
	 * Changer de langue principale
	 */
	$scope.updateLangue = function(langueId) {
		$scope.selected.langue_id = langueId;
		
		var arrayLength = $scope.availableLangues.length;
		for (var i = 0; i < arrayLength; i++ ) {
			if  ( $scope.availableLangues[i].id == langueId ) {
				$scope.selected.langue = $scope.availableLangues[i]
			}
		}
		$scope.selected.post();
	}
	
	/**
	 * Modifier les langues parlées
	 */
	$scope.updateLangueSecondaires = function(langueIdArray) {
		$scope.selected.langue_id_list = langueIdArray;
		$scope.selected.langues = [];
		var arrayLength = $scope.availableLangues.length;
		for (var i = 0; i < arrayLength; i++ ) {
			if  ( inArray($scope.availableLangues[i].id,langueIdArray) ) {
				$scope.selected.langues.push( $scope.availableLangues[i]);
			}
		}
		$scope.selected.post();
	}
	
	/**
	 * Modifier les importations
	 */
	$scope.updateImportations = function(importationIdArray) {
		$scope.selected.importation_id_list = importationIdArray;
		$scope.selected.importations = [];
		var arrayLength = $scope.availableRessources.length;
		for (var i = 0; i < arrayLength; i++ ) {
			if  ( inArray($scope.availableRessources[i].id,importationIdArray) ) {
				$scope.selected.importations.push( $scope.availableRessources[i]);
			}
		}
		$scope.selected.post();
	}
	
	/**
	 * Modifier les exportations
	 */
	$scope.updateExportations = function(exportationIdArray) {
		$scope.selected.exportation_id_list = exportationIdArray;
		$scope.selected.exportations = [];
		var arrayLength = $scope.availableRessources.length;
		for (var i = 0; i < arrayLength; i++ ) {
			if  ( inArray($scope.availableRessources[i].id,exportationIdArray) ) {
				$scope.selected.exportations.push( $scope.availableRessources[i]);
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
	
	/**
	 * Recupérer toutes les langues
	 */
	$scope.availableLangues = [];
	
	$scope.loadAvailableLangues = function() {
		return $scope.availableLangues.length ? null : Restangular.all('langue').getList().then(function(result) {
			var arrayLength = result.length;
			for (var i = 0; i < arrayLength; i++ ) {
				$scope.availableLangues.push(result[i].plain());
			}
		});
	}
	
	/**
	 * Récupérer toutes les ressources
	 */
	
	$scope.availableRessources = [];
	
	$scope.loadAvailableRessources = function() {
		return $scope.availableRessources.length ? null : Restangular.all('ressource').getList().then(function(result) {
			var arrayLength = result.length;
			for (var i = 0; i < arrayLength; i++ ) {
				$scope.availableRessources.push(result[i].plain());
			}
		});
	}
});

