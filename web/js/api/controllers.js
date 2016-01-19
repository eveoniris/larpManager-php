function TerritoireEventController($scope, TerritoireEvent) {

    var currentResource;
    var resetForm = function () {
        $scope.addMode = true;
        
        $scope.territoire = undefined;
        $scope.year = undefined;
        $scope.month = undefined;
        $scope.day = undefined;
        $scope.description = undefined;
        $scope.visibilite = undefined;
        
        $scope.selectedIndex = undefined;
    }
 
    $scope.events = TerritoireEvent.query();
    $scope.addMode = true;
 
    $scope.add = function () {
        var key = {};
        var value = {territoire: $scope.territoire, year: $scope.year, month : $scope.month, day: $scope.day, description: $scope.description, visibilite: $scope.visibilite}
 
        Event.save(key, value, function (data) {
            $scope.events.push(data);
            resetForm();
        });
    };
 
    $scope.update = function () {
        var key = {id: currentResource.id};
        var value = {territoire: $scope.territoire, year: $scope.year, month : $scope.month, day: $scope.day, description: $scope.description, visibilite: $scope.visibilite}
        Event.save(key, value, function (data) {
            currentResource.territoire = data.territoire;
            currentResource.year = data.year;
            currentResource.month = data.month;
            currentResource.day = data.day;
            currentResource.description = data.description;
            currentResource.visibilite = data.visibilite;
            
            resetForm();
        });
    }
 
    $scope.refresh = function () {
        $scope.events = Event.query();
        resetForm();
    };
 
    $scope.deleteEvent = function (index, id) {
        Event.delete({id: id}, function () {
            $scope.events.splice(index, 1);
            resetForm();
        });
    };
 
    $scope.selectEvent = function (index) {
        currentResource = $scope.events[index];
        $scope.addMode = false;
        $scope.territoire = data.territoire;
        $scope.year = data.year;
        $scope.month = data.month;
        $scope.day = data.day;
        $scope.description = data.description;
        $scope.visibilite = data.visibilite;
    }
 
    $scope.cancel = function () {
        resetForm();
    }
}