angular.module('perfilApp',[]).controller('perfilCtrl',function($scope, $http,$window){
    if(localStorage.getItem("usuario") == null){
        $window.location= 'index.html';
    }
    else {
        $scope.nombre = localStorage.getItem("usuario");
        $scope.apellido = localStorage.getItem("apellido");
        $scope.telefono = localStorage.getItem("telefono");
        $scope.email = localStorage.getItem("email");
        $scope.localidad = localStorage.getItem("localidad");
        $scope.foto = localStorage.getItem("foto");
        $scope.idUsr = localStorage.getItem("id");
        $scope.usuarioid = localStorage.getItem("id");
        $http.post('ws/listarProductoUsuario',{'usuarioid': $scope.usuarioid}).then(function(response){
            $scope.misProductos = response.data;
        });

        $scope.borrar = function(id){
            var i,indice , productoSelected = null, qProductos = $scope.misProductos.length;
            for(i=0;i<qProductos;i++){
                if(id == $scope.misProductos[i].id)
                {
                    productoSelected = $scope.misProductos[i];
                    indice = i;
                }
            }
            if(productoSelected !== null)
            {
                var r = confirm('¿Desea elimianar a "'+ productoSelected.titulo + '"?');
                if(r){
                    
                    $http.delete('ws/borrar',{'data': productoSelected.id}).then(function(response){
                        console.log($scope.misProductos.length);
                        $scope.misProductos.splice(indice, 1);
                        console.log($scope.misProductos.length);
                    });
                }
            }
        }

            $scope.cargarImagen = function(idUsuario){

                $http.post('ws/cargarImagen',{'id': idUsuario, 'url':$scope.urlImagen})
                .then(function(response){
                    alert("La imagen se a cargado con éxito");
                })
                .catch(function(response){
                    alert("Ha ocurrido un error al cargar la imagen");
                });
                localStorage.setItem("foto", $scope.urlImagen);
            }
        }
    })

