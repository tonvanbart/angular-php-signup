var app = angular.module('signup',[]);

app.controller('control', function control($scope,$http) {
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
        $scope.formdata = { naam: $scope.naam, persons: $scope.persons, room: $scope.room, remarks: $scope.remarks };
        $http({
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            transformRequest: function(obj) {
                var str = [];
                for(var p in obj)
                    str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                return str.join("&");
            },

            data: $scope.formdata,
            url: '/europrez/signup.php'
        }).success(function() { console.log('success')})
            .error(function() { console.log('error')});
//        $http({
//            method: 'POST',
//            url: 'europrez/signup.php',
//            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
//            transformRequest: function(obj) {
//                var str = [];
//                for(var p in obj)
//                    str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
//                return str.join("&");
//            },
//            data: ""
//        }).success(function() {});
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
