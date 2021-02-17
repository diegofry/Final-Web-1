angular.module('loginApp',[]).controller('loginCtrl',function($scope, $http,$window){

	$scope.log = function(usuario){
		$http.post('ws/login', usuario)
            .then(function(response){
                var data = response.data;
                for (var i=0;i<data.length;i++) {
                    data = data[i];
                }
                if(data.type == 2){
                    $window.location= 'principal.html';
                    localStorage.setItem("usuario", data.nombre_usuario);
                    localStorage.setItem("apellido", data.apellido);
                    localStorage.setItem("telefono", data.telefono);
                    localStorage.setItem("email", data.email);
                    localStorage.setItem("localidad", data.localidad);
					localStorage.setItem("foto", data.foto);
                    localStorage.setItem("rol", data.type);
                    localStorage.setItem("id", data.id);
                }
                if(data.type == 1){
                    window.location= 'principaladmin.html';
                    localStorage.setItem("usuario", data.nombre_usuario);
                    localStorage.setItem("apellido", data.apellido);
                    localStorage.setItem("telefono", data.telefono);
                    localStorage.setItem("email", data.email);
                    localStorage.setItem("localidad", data.localidad);
					localStorage.setItem("foto", data.foto);
                    localStorage.setItem("rol", data.type);
                    localStorage.setItem("id", data.id);
                }
            })
            .catch(function (response){
                alert('El usuario o la contraseña son incorrectos');
            })
    }
});