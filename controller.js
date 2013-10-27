var app = angular.module('signup',[]);

app.controller('control', function control($scope,$http) {

    $scope.submit = function() {
        console.log("submit(),naam=" + $scope.naam + ",persons=" + $scope.persons + ",room=" + $scope.room);
        $scope.formdata = { naam: $scope.naam, persons: $scope.persons, room: $scope.room, remarks: $scope.remarks };
        $http({
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $scope.formdata,
            url: '/europrez/signup.php',
            transformRequest: function(obj) {
                var str = [];
                for(var p in obj)
                    str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                return str.join("&");
            }
        }).success(function(data, status) {
                $scope.handlePostSuccess(data, status);
        })
        .error(function() { console.log('error,status='+status)});
    }

    $scope.refreshdata = function() {
        console.log('refreshdata()');
        $http({
            method:'GET',
            url: 'listpersons.php'
        }).success(function(data,status) {
                $scope.participants = [];
            for (i=0; i < data.length-1; i++) {
                $scope.participants[i] = data[i];
            }
        }).error(function(data,status) {
            console.log("error refreshing data, http status:" + status);
        });
    }

    $scope.handlePostSuccess = function(data, status) {
        console.log('handlePostSuccess(' + data +',' + status +')');
        if (data.written) {
            delete $scope.formdata;
            delete $scope.naam;
            delete $scope.persons;
            delete $scope.room;
            delete $scope.remarks;
            $scope.refreshdata();
        } else {

        }
    }

    $scope.calctotal = function() {
        console.log("calctotal()");
        var result = 0;
        for (var i=0; i<$scope.participants.length; i++) {
            result += $scope.participants[i].persons;
        }
        return result;
    }

    $scope.participants = [];
    $scope.refreshdata();

});
