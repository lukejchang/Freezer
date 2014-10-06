var dataApp = angular.module('dataApp', []);

dataApp.controller('dataCtrl', ['$scope', '$http', function ($scope, $http) {

    $scope.init= function(){
        $scope.sections = [];
        $http.get('data.php').success(function(data){
            $scope.currData = angular.fromJson(data);
            $scope.processCurr();
        });
    };

    $scope.processCurr = function(){
        //iterate through abbreviations
        for(var curr in $scope.currData.Curricula){
            $http.get('data.php?curr'+ $scope.currData.Curricula[curr].CurriculumAbbreviation)
                .success(function(data){
                    $scope.sectionData = angular.fromJson(data);
                    $scope.processSections();
                });
        }
    };



    $scope.processSections = function(){
        for(var i in $scope.sectionData.Sections){
            $scope.sections.push({"Course": $scope.sectionData.Sections[i].CurriculumAbbreviation + " "
            + $scope.sectionData.Sections[i].CourseNumber, "Section": $scope.sectionData.Sections[i].SectionID});
        }
    }


}]);