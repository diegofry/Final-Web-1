angular.module('registroApp',[]).controller('registroCtrl',function($scope, $http,$window){

	$scope.guardar = function(usuario){

		$http.post('ws/agregarUsuario', usuario)
			.then(function(succes){
				alert('usuario creado exitosamente!');
                $window.location.href = 'index.html';
				})
            .catch(function (error){
                alert('Este usuario/mail ya existe');
                $window.location.alert = 'registro.html';

            })
}
});
