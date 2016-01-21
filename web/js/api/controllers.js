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
LarpManagerApp.controller('TerritoireController', function ($scope, Restangular) {
	
	$scope.selected = null;
	
	Restangular.all('territoire').getList().then(function(result) {
        $scope.territoires = result;
    });
	
	/**
	 * Selection
	 */
	$scope.selectTerritoire = function(territoire) {
		territoire.all('chronologie').getList().then( function(result) {
			territoire.chronologies = result;
			$scope.selected = territoire;
		});
	}
	
	/**
	 * Sauvegarde d'un territoire
	 */
	$scope.saveTerritoire = function(territoire) {
		territoire.post();
	}
	
	
	$scope.removeEvent = function(event) {
		event.remove();
	}
	/*var baseTeritoires = Restangular.all('territoire');
	
	baseTerritoires.getList().then(function(territoires) {
		$scope.AllTerritoires = territoires;
	});
	
	$scope.territoires = null;
	
	$scope.getAllTerritoires = function() {
		Territoire.query().$promise.then(
			function(data) {
				$scope.territoires = data;
			},
			function(error){
				console.log('getAllTerritoire', error);
			} 
		)
	}
	
	$scope.createTerritoire = function(territoire) {
		Territoire.save(territoire).$promise.then(
			function(data) {
				$scope.getAllTerritoires();
			},
			function(error) {
				console.log("createTerritoire", error);
			}
		)
	}
	
	$scope.updateTerritoire = function(territoire) {
		Territoire.update({id: territoire.id}, territoire).$promise.then(
			function(data) {
				$scope.getAllTerritoires();
			},
			function(error) {
				console.log("updateTerritoire", error);
			}
		)
	}
	
	$scope.getTerritoire = function(id) {
		Territoire.get({id:id}).promise.then(
			function(data)  {
				console.log(data);
				$scope.territoire = data;
			},
			function(error) {
				console.log("getTerritoire", error);
			}
		)
	}
	
	$scope.deleteTerritoire = function(id) {
		Territoire.delete({id: id}).$promise.then(
			function(data) {
				$scope.getAllTerritoires();
			},
			function(error) {
				console.log("territoire delete ERROR", error);
			}
		)
	}
	
	
	$scope.selectTerritoire = function(territoire) {
		$scope.territoire = territoire;
	}
	
	$scope.saveTerritoire = function(territoire) {
		territoire._id !== undefined ? $scope.updateTerritoire(territoire) : $scope.createTerritoire(territoire);
	}
	
	$scope.newTerritoire = function() {
		$scope.territoire = {};
	}
	
	$scope.getAllTerritoires();
	*/
	
	
	/*
	
	$scope.selected = undefined;
	$scope.isEnabled = false;
	
	// remet à zero le formulaire
	var resetForm = function () {
		$scope.addMode = false;
		$scope.selected = undefined;
		
		$scope.nom = undefined;
		$scope.description = undefined;
		$scope.capitale = undefined;
		$scope.politique = undefined;
		$scope.dirigeant = undefined;
		$scope.population = undefined;
		$scope.symbole = undefined;
		$scope.tech_level = undefined;
		$scope.type_racial = undefined;
		$scope.inspiration = undefined;
		$scope.armes_predilection = undefined;
		$scope.vetements = undefined;
		$scope.noms_masculin = undefined;
		$scope.noms_feminin = undefined;
		$scope.frontieres = undefined;
		$scope.groupes = undefined;
		$scope.langue = undefined;
		$scope.religion = undefined;
		$scope.chronologies = undefined;
	};
	
    $scope.territoires = Territoire.query();
    $scope.addMode = true;
    
    // Ajoute un territoire
	$scope.add = function() {
		var key = {};
        var value = {
        		nom: $scope.nom,
        		description: $scope.description,
        		capitale: $scope.capitale,
        		politique: $scope.politique,
        		dirigeant: $scope.dirigeant,
        		population: $scope.population,
        		symbole: $scope.symbole,
        		tech_level: $scope.tech_level,
        		type_racial: $scope.type_racial,
        		inspiration: $scope.inspiration,
        		armes_predilection: $scope.armes_predilection,
        		vetements: $scope.vetements,
        		noms_masculin: $scope.noms_masculin,
        		noms_feminin: $scope.noms_feminin,
        		frontieres: $scope.frontieres,
        		groupes: $scope.groupes,
        		langue: $scope.langue,
        		religion: $scope.religion,
        		chronologies: $scope.chronologies
        	};
 
        Territoire.save(key, value, function (data) {
            $scope.territoires.push(data);
            resetForm();
        });
	};
	
	// Met à jour un territoire
	$scope.update = function(item) {
		var key = {id: item.id};
		
		Territoire.save(key, item, function (data) {
			$scope.selected = data;
        });
	};
	
	// Rafraichi la liste des territoires
	$scope.refresh = function() {
        $scope.territoires = Territoire.query();
        resetForm();
	};
	
	// Supprime un territoire
	$scope.delete = function() {
        Territoire.delete({id: id}, function () {
            $scope.territoires.splice(index, 1);
            resetForm();
        });
	};
	
	// Selectionne un territoire
	$scope.select = function(item) {
		$scope.selected = item;
        $scope.addMode = false;
	};
	
	// Annule l'action
	$scope.cancel = function() {
		resetForm();
	};
	
	
	
	
	
	
	// Ajoute un événement à la chronologie du territoire
	$scope.addEvent = function() {
		$scope.insertMode = true;
		$scope.inserted = {
				id: $scope.selected.chronologies.length +1,
				year : '',
				description : '',
				visibilite : ''
		};
		$scope.selected.chronologies.push($scope.inserted);
	};
	
	// Retire un événement de la chronologie et envoi la requête http
	$scope.removeEvent = function(index, event) {
		$scope.selected.chronologies.splice(index, 1);
	};
	
	// Met à jour un événement de la chronologie et envoi la requête http
	$scope.updateEvent = function(item) {
		var key = {id: item.id};
		
		// création d'un événement
		if ( $scope.insertMode == true ) 
		{
			// POST /event
		}
		else // mise à jour d'un événement
		{
			// POST /event/{id}
		}
		
	};
	*/
});

