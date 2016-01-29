// pour netoyer les chaines
angular.module('htmlToPlaintext', []).
	filter('htmlToPlaintext', function() {
		return function(text) {
			return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
		};
	}
);

// initialisation de l'application et déclaration des modules utilisé
var LarpManagerApp = angular.module("LarpManagerApp", ['restangular','xeditable','htmlToPlaintext','textAngular']);

// interprétation des balises html
LarpManagerApp.filter('unsafe', function ($sce) {
	return function(val) {
        return $sce.trustAsHtml(val);
    };
});

// configuration de restangular
LarpManagerApp.config(function(RestangularProvider) {
	RestangularProvider.setBaseUrl('/api');	
});

// compatibilité avec Twig
LarpManagerApp.config(function($interpolateProvider){
	$interpolateProvider.startSymbol('{[{').endSymbol('}]}');
});

// défini le thème Bootstrap 3 pour xeditable
LarpManagerApp.run(function(editableOptions) {
	editableOptions.theme = 'bs3';
});

function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}