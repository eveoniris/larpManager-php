angular.module('LarpManager', ['ngResource'])
	.factory('TerritoireEvent', ['$resource', 
	  function ($resource) {
			return $resource('/api/territoire/:territoireId/event/:eventId', {territoireId: '@territoireId', eventId: '@id'});
	}]
	).config(function($interpolateProvider){
		$interpolateProvider.startSymbol('{[{').endSymbol('}]}');
	});