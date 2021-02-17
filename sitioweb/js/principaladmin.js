angular.module('principaladminApp',[]).controller('principaladminCtrl',function($scope, $http,$window){

    if(localStorage.getItem("usuario") === null || localStorage.getItem("rol") == 2){
        
        $window.location= 'index.html';
    }
    else
    {

		$scope.foto = localStorage.getItem("foto");

        $http.get('ws/listar_usuarios').then(function(response){
            $scope.misUsuarios = response.data;
        });

        $http.get('ws/listar').then(function(response){
            $scope.misProductos = response.data;
        });

        $scope.logout = function () {
            localStorage.clear();
        }
        $scope.borraruser = function(id){
            var r,i,indice,usuarioSelected = null, usuarios = $scope.misUsuarios.length;
            for(i=0;i<usuarios;i++)
            {
                if(id == $scope.misUsuarios[i].id)
                {
                    usuarioSelected = $scope.misUsuarios[i];
                    indice = i;
                }
            }
            if(usuarioSelected !== null){
                r = confirm('¿Está seguro que desea eliminar al usuario  "'+ usuarioSelected.nombre_usuario + '" ?');
                if(r)
                {
                    $http.delete('ws/borrarusuario',{'data': usuarioSelected.id}).then(function(response){
                        $scope.misUsuarios.splice(indice,1);
                        });
                }
            }
        }

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
                        $scope.misProductos.splice(indice, 1);
                    });
                }
            }
        }

        $scope.haceradmin = function(id){
             var r,i, usuarioSelected, usuarios = $scope.misUsuarios.length;
            for(i=0;i<usuarios;i++)
            {
                if(id == $scope.misUsuarios[i].id && $scope.misUsuarios[i].type != 1)
                {           
                    usuarioSelected = $scope.misUsuarios[i];
                }
            
                else
                {
                    if(id == $scope.misUsuarios[i].id && $scope.misUsuarios[i].type == 1)
                    {
                        alert('El Usuario: "' +$scope.misUsuarios[i].nombre_usuario+'" ahora es un Administrador');
                    }
                }
            }
            r = confirm('¿Está seguro que desea convertir a  "'+ usuarioSelected.nombre_usuario + '" en Administraor?');
            if(r){
                $http.post('ws/haceradmin',{'id': usuarioSelected.id})
                    .then(function(response){
                    alert('El Usuario: "' +usuarioSelected.nombre_usuario+'" no es más un usuario');
                    $window.location= 'principaladmin.html';
                });
            } 
        }
       $scope.sacaradmin = function(id){
            var r,i, usuarioSelected, usuarios = $scope.misUsuarios.length;
            for(i=0;i<usuarios;i++)
            {
                if(id == $scope.misUsuarios[i].id && $scope.misUsuarios[i].type !=2)
                {           
                    usuarioSelected = $scope.misUsuarios[i];
                }
                else
                {
                    if(id == $scope.misUsuarios[i].id && $scope.misUsuarios[i].type ==2)
                    {
                        alert('El Usuario: "' +$scope.misUsuarios[i].nombre_usuario+'" no es más un Administrador');
                    }
                }
            }
            r = confirm('¿Está seguro que desea convertir a  "'+ usuarioSelected.nombre_usuario + '" en Usuario?');
            if(r){
                $http.post('ws/sacaradmin',{'id': usuarioSelected.id})
                    .then(function(response){
                    alert('El Usuario: "' +usuarioSelected.nombre_usuario+'" no es más un Administrador');
                    $window.location= 'principaladmin.html';
                });
            }  
        }
    }
});
