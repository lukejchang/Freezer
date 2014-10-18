/**
 * Created by JChang on 10/15/2014.
 */

var freezerApp = angular.module('freezerApp', []);

freezerApp.controller('indexCtrl', ['$scope', '$http', function ($scope, $http) {
    $scope.placeholders = [
        ['A A', '320', 'AD'],
        ['E E', '215', 'AB'],
        ['ENGL', '322', 'A'],
        ['ECON', '200', 'BD'],
        ['MUSIC', '303', 'AA']
    ];
    $scope.results = [];

    $scope.submit=function(){
        console.log(JSON.stringify($scope.results));
        for(var i = 0; i < $scope.results.length; i++){
            //if($scope.results[i].cur && $scope.results[i].number && $scope.results[i].section){
            if($scope.results[i] != null){
                var cur = $scope.results[i].cur.trim().toUpperCase();
                var num = $scope.results[i].number;
                var sec = $scope.results[i].section.trim().toUpperCase();
                var query = 'cur=' + cur + "&num=" + num + "&sec=" + sec;
                console.log(query);
                $http.get('time.php?' + query)
                    .success(function (data) {
                        $scope.add(Angular.fromJson(data));
                    })
                    .error(function (data) {
                        console.log(data);
                    })
            }
        }
    };
    $scope.add = function(data){
        console.log(JSON.stringify(data));
    }


}]);