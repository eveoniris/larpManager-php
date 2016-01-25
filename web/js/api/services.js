// initialisation de l'application et déclaration des modules utilisé
var LarpManagerApp = angular.module("LarpManagerApp", ['restangular','xeditable']);

// configuration de restangular
LarpManagerApp.config(function(RestangularProvider) {
	RestangularProvider.setBaseUrl('/larp-manager-php/api');	
});

// compatibilité avec Twig
LarpManagerApp.config(function($interpolateProvider){
	$interpolateProvider.startSymbol('{[{').endSymbol('}]}');
});

// défini le thème Bootstrap 3 pour xeditable
LarpManagerApp.run(function(editableOptions) {
	editableOptions.theme = 'bs3';
});
