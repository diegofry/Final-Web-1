angular.module('principalApp',[]).controller('principalCtrl',function($scope, $http,$window){

	if(localStorage.getItem("usuario") == null || localStorage.getItem("rol") == 1){
        $window.location= 'index.html';
    }
    else{

        $scope.nombre = localStorage.getItem("usuario");
        $scope.apellido = localStorage.getItem("apellido");
        $scope.telefono = localStorage.getItem("telefono");
        $scope.email = localStorage.getItem("email");
        $scope.localidad = localStorage.getItem("localidad");
        $scope.foto = localStorage.getItem("foto");
        $scope.usuarioid = localStorage.getItem("id");
        $http.post('ws/listarProductosUsuario',{'usuarioid': $scope.usuarioid}).then(function(response){
            $scope.misProductos = response.data;
        });

        $scope.comentar = function(productoId, comentario){
            var comentario = {
                'productoId':productoId,
                'comentario': comentario,
                'nombreUsuario':$scope.nombre
            }  

            $http.post('ws/comentar', comentario)
            .then(function(succes){
               $window.location= 'principal.html';
           })
            .catch(function (error){
                alert('Algo salio mal' +" " + error );
            })
        }

        $http.get('ws/listar').then(function(response){
            $scope.misProductos = response.data;
        });
    }
    $scope.logout = function () {
        localStorage.clear();
    }
});



