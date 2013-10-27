var app = angular.module('signup',[]);

app.controller('control', function control($scope) {
    $scope.participants = [
        {
            'name': 'Ton van Bart',
            'persons':3,
            'room':'double'
        },
        {
            'name': 'Ingrid Schmidt',
            'persons':2,
            'room':'share'
        },
        {
            'name': 'Darryl Richman',
            'persons':1,
            'room':'single'
        }
    ];

    $scope.submit = function() {
        console.log("submit(),naam=" + $scope.naam + ",persons=" + $scope.persons + ",room=" + $scope.room);
        $http({method: 'POST', })
    }

    $scope.calctotal = function() {
        console.log("calctotal()");
        var result = 0;
        for (var i=0; i<$scope.participants.length; i++) {
            result += $scope.participants[i].persons;
        }
        return result;
    }

});
