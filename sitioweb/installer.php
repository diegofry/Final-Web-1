<?php
	if(file_exists('config.php'))
	{
		require_once 'config.php';

		if(@mysqli_connect(DBHOST, DBUSER, DBPASS, DBBASE))
		{	
	    	header( 'Location: index.html' );
	    	exit();
		}
	}

	if(isset($_GET["usrBD"]))
	{
		header( 'Location: index.html' ) ;

		$usrBD = trim(strval($_GET["usrBD"]));
		$passBD = trim(strval($_GET["passBD"]));
		$hostBD = trim(strval($_GET["hostBD"]));
		$nameBD = trim(strval($_GET["nameBD"]));

		$myfile = fopen("config.php", "w") or die("No se puede abrir el archivo!");

		$renglon1 = '<?php'.PHP_EOL;
		$renglon2 = 'define("DBUSER", "'.$usrBD.'");'.PHP_EOL;
		$renglon3 = 'define("DBPASS", "'.$passBD.'");'.PHP_EOL;
		$renglon4 = 'define("DBHOST", "'.$hostBD.'");'.PHP_EOL;
		$renglon5 = 'define("DBBASE", "'.$nameBD.'");'.PHP_EOL;
		$renglon6 = '?>';
		
		fwrite($myfile, $renglon1);
		fwrite($myfile, $renglon2);
		fwrite($myfile, $renglon3);
		fwrite($myfile, $renglon4);
		fwrite($myfile, $renglon5);
		fwrite($myfile, $renglon6);
		fclose($myfile);

		$link = mysqli_connect($hostBD, $usrBD, $passBD, $nameBD);

		$crearComentarios = "CREATE TABLE `comentarios` (
							  `id` int(11) NOT NULL,
							  `producto_id` text COLLATE utf8_spanish_ci NOT NULL,
							  `comentario` text COLLATE utf8_spanish_ci NOT NULL,
							  `nombre_usuario` text COLLATE utf8_spanish_ci NOT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";

		$crearProductos = "CREATE TABLE `productos` (
						  `id` int(10) UNSIGNED NOT NULL,
						  `titulo` text COLLATE utf8_spanish_ci NOT NULL,
						  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
						  `precio` decimal(10,2) NOT NULL,
						  `usuarioid` int(11) DEFAULT NULL,
						  `nombre_usuario` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
						  `comentario_id` int(11) DEFAULT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";

		$crearUsuarios = "CREATE TABLE `usuarios` (
						  `id` int(10) UNSIGNED NOT NULL,
						  `nombre_usuario` text COLLATE utf8_spanish_ci NOT NULL,
						  `password` text COLLATE utf8_spanish_ci NOT NULL,
						  `email` text COLLATE utf8_spanish_ci NOT NULL,
						  `type` int(11) DEFAULT NULL,
						  `localidad` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
						  `apellido` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
						  `telefono` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
						  `foto` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";

		$primaryKeyComentarios = "ALTER TABLE `comentarios`
										ADD PRIMARY KEY (`id`);";
		$primaryKeyProductos = "ALTER TABLE `productos`
									ADD PRIMARY KEY (`id`);";
		$primaryKeyUsuarios = "ALTER TABLE `usuarios`
									ADD PRIMARY KEY (`id`);";

			$autoIncrementComentarios = "ALTER TABLE `comentarios`
									MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;";
			$autoIncrementProductos = "ALTER TABLE `productos`
									MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;";
			$autoIncrementUsuarios = "ALTER TABLE `usuarios`
									MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;";

			$agregarAdmin = "INSERT INTO `usuarios` (`nombre_usuario`, `password`, `email`, `type`, `localidad`, `apellido`, `telefono`, `foto`) VALUES ('admin', 'admin', 'admin@admin', '1', NULL, NULL, NULL, 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png');";

			mysqli_set_charset($link, "utf8");

		mysqli_query($link, $crearComentarios);
		mysqli_query($link, $crearProductos);
		mysqli_query($link, $crearUsuarios);
		mysqli_query($link, $primaryKeyComentarios);
		mysqli_query($link, $primaryKeyProductos);
		mysqli_query($link, $primaryKeyUsuarios);
		mysqli_query($link, $autoIncrementComentarios);
		mysqli_query($link, $autoIncrementProductos);
		mysqli_query($link, $autoIncrementUsuarios);
		mysqli_query($link, $agregarAdmin);

		mysqli_close($link);

		exit();
	}

?>
<html>
<head>
	<title>Instalador del sitio</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<div class="container">
		<h3 class="panel-title">Instalador del sitio</h3>
		<form action="installer.php" method="get">
		<div class="form-group">
			<label class="label">Usuario de la BD</label><br>
			<input class="" type="text" name="usrBD">
		</div>
		<div class="form-group">
			<label class="label">Contraseña de la BD</label><br>
			<input class="" type="text" name="passBD">
		</div>
		<div class="form-group">
			<label class="label">Host de la BD</label><br>
			<input class="" type="text" name="hostBD">
		</div>
		<div class="form-group">
			<label class="label">Nombre de la BD</label><br>
			<input class="" type="text" name="nameBD">
		</div>
		<input class="btn btn-info" type="submit" name="submitBtn">
	</form>
	</div>
</body>
</html>