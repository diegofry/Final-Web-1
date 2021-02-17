angular.module('cargaApp',[]).controller('cargaCtrl',function($scope, $http,$window){
	if(localStorage.getItem("usuario") == null || localStorage.getItem("rol") == 1){
		$window.location= 'index.html';
	}
	else{
		$scope.logout = function () {
			localStorage.clear();
		}

		$scope.foto = localStorage.getItem("foto");
		$http.get('ws/listar').then(function(response){
			$scope.misProductos = response.data;
		});
		
		var persistirProducto = function (producto)
		{
			if('id' in producto) 
				$http.post('ws/actualizar', producto).then(function(response){
					var i, qProductos = $scope.misProductos.length;
					for(i=0;i<qProductos;i++)
						if(producto.id == $scope.misProductos[i].id)
						{
							$scope.misProductos[i] = JSON.parse(JSON.stringify(producto)); 
							break;
						}
						return true;
					});
			else
				$scope.producto.nombre_usuario = localStorage.getItem("usuario");
			$scope.producto.usuarioid = localStorage.getItem("id");
			$http.post('ws/agregar', producto)
			.then(function(response){
				$scope.misProductos.push(response.data);
				alert("cargado con exito");
				$window.location= 'principal.html';
				return true;
			});
			return false;
		}

		$scope.guardar = function(){
			persistirProducto($scope.producto);
			$scope.editando = false;
			delete $scope.producto;
		}
	}
});