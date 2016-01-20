// initialisation de l'application et déclaration des modules utilisé
var LarpManagerApp = angular.module("LarpManagerApp", ['ngResource','xeditable']);

// inclusion du controller Territoire
LarpManagerApp.factory('Territoire', ['$resource', function ($resource) {
		return $resource('/api/territoire/:territoireId', {territoireId: '@id'});
	}]);


// compatibilité avec Twig
LarpManagerApp.config(function($interpolateProvider){
	$interpolateProvider.startSymbol('{[{').endSymbol('}]}');
});

// défini le thème Bootstrap 3 pour xeditable
LarpManagerApp.run(function(editableOptions) {
	editableOptions.theme = 'bs3';
});