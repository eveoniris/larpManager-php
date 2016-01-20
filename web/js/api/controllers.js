/**
 * Controller Territoire
 * @param $scope
 * @param Territoire
 * @returns
 */
LarpManagerApp.controller('TerritoireController', function ($scope, Territoire) {
	
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
            resetForm();
        });
	};
	
	// Met à jour un territoire
	$scope.updateSelected = function() {
		var key = {id: $scope.selected.id};
		
		Territoire.save(key, $scope.selected, function (data) {
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
		$scope.inserted = {
				id: $scope.selected.chronologies.length +1,
				year : '',
				description : '',
				visibilite : ''
		};
		$scope.selected.chronologies.push($scope.inserted);
	};
	
	$scope.removeEvent = function(index) {
		$scope.selected.chronologies.splice(index, 1);
	};
});

