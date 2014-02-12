var app = angular.module('signup',['ngBootstrap']);

app.controller('control', function control($scope,$http) {

    $scope.submit = function() {
        console.log("submit(),naam=" + $scope.naam + ",persons=" + $scope.persons + ",room=" + $scope.room + ",extra=" + $scope.extra);
        $scope.processing = true;
        $scope.formdata = { naam: $scope.naam, persons: $scope.persons, room: $scope.room, extra: $scope.extra, remarks: $scope.remarks };
        $http({
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'},
            data: $scope.formdata,
            url: 'signup.php',
            transformRequest: function(obj) {
                var str = [];
                for(var p in obj)
                    str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                return str.join("&");
            }
        }).success(function(data, status) {
                $scope.handlePostSuccess(data, status);
        })
        .error(function() {
                $scope.processing = false;
                console.log('error posting form, status='+status)
        });
    }

    $scope.refreshdata = function() {
        console.log('refreshdata()');
        $scope.participants = [];
        $http({
            method:'GET',
            url: 'listpersons.php'
        }).success(function(data,status) {
            $scope.participants = [];
            for (var i=0; i < data.length; i++) {
                $scope.participants[i] = data[i];
            }
        }).error(function(data,status) {
            console.log("error refreshing data, http status:" + status);
        });
    }

    $scope.handlePostSuccess = function(data, status) {
        $scope.processing = false;
        console.log('handlePostSuccess(' + data +',' + status +')');
        console.log('scope.naam='+$scope.naam+',$scope[naam]='+$scope['naam']);
        delete $scope.errornaam;
        delete $scope.errorpersons;
        delete $scope.errorroom;
        if (data.written) {
            delete $scope.formdata;
            delete $scope.naam;
            delete $scope.persons;
            delete $scope.room;
            delete $scope.remarks;
            $scope.extra = 'none';
            $scope.refreshdata();
        } else {
            for (var i=0; i<data.missing.length; i++) {
                $scope['error'+data.missing[i]] = "This is a required field";
            }
            if (data.notnum.length > 0) {
                $scope.errorpersons = "Please enter a number";
            }
        }
    }

    $scope.calctotal = function() {
        console.log("calctotal()");
        var result = 0;
        for (var i=0; i<$scope.participants.length; i++) {
            result += parseInt($scope.participants[i].persons);
        }
        return result;
    }

    $scope.extras = ['none','before','after','before and after'];
    $scope.rooms = ['single room','will share a room','double or family room'];
    $scope.refreshdata();
    $scope.extra = 'none';
    $scope.processing = false;

});
