var dataApp = angular.module('dataApp', []);

dataApp.controller('dataCtrl', ['$scope', '$http', function ($scope, $http) {

    $scope.init= function(){
        $scope.sections = [];
        $scope.currtest = [];
        $http.get('data.php').success(function(data){
            $scope.currData = angular.fromJson(data);
            $scope.processCurr();
        });
    };

    $scope.processCurr = function(){
        //iterate through abbreviations
        var c = "";
        for(var i = 0; i < $scope.currData.Curricula.length; i++){
            c = $scope.currData.Curricula[i].CurriculumAbbreviation;
            $http.get('data.php?curr='+ c)
                .success(function(data){
                    $scope.sectionData = angular.fromJson(data);
                    $scope.processSections();
                })
                .error(function(data){
                })
        }
    };



    $scope.processSections = function(){
        for(var i in $scope.sectionData.Sections){
            $scope.sections.push($scope.sectionData.Sections[i].CurriculumAbbreviation.replace(' ', '%20') + ","
            + $scope.sectionData.Sections[i].CourseNumber + "/" + $scope.sectionData.Sections[i].SectionID);
        }
        $.post('sections.php', {"data":JSON.stringify($scope.sections)}, function(data, status){
           $scope.final = angular.fromJson(data).toString();
            console.log(data.length);
        });

        //$scope.send = angular.toJson($scope.sections);
/*        $http.post('sections.php', angular.toJson({"data": $scope.send}))
            .success(function(data){
                $scope.results = data;
            })*/
    }


}]);