<?php
if(file_exists('../config.php'))
{
    require_once('../config.php');
}
else
{
    die("No existe archivo de configuracion para la base de datos");
}

$link = mysqli_connect(DBHOST, DBUSER, DBPASS, DBBASE);

if ($link !== false)
{
  mysqli_set_charset($link, "utf8");
  switch ($_GET['accion'])
  {
    case 'agregarUsuario':
    $data = json_decode(file_get_contents("php://input") , true);
    $nombre_usuario = mysqli_real_escape_string($link, $data['nombre_usuario']);
    $apellido = mysqli_real_escape_string($link, $data['apellido']);
    $telefono = mysqli_real_escape_string($link, $data['telefono']);
    $localidad = mysqli_real_escape_string($link, $data['localidad']);
    $email = mysqli_real_escape_string($link, $data['email']);
    $password = mysqli_real_escape_string($link, $data['password']);
    $existUser = mysqli_query($link, "SELECT * FROM usuarios WHERE nombre_usuario = '$nombre_usuario'");
    $existEmail = mysqli_query($link, "SELECT * FROM usuarios WHERE email = '$email'");
    $rowUser = mysqli_num_rows($existUser);
    $rowEmail = mysqli_num_rows($existEmail);
    if ($rowUser >= 1 || $rowEmail >= 1 || $rowUser >= 1 && $rowEmail >= 1)
    {
      header('Status: 400 Bad Request', true, 400);
      $respuesta = ["error" => "Ya existe un usuario con ese nombre", ];
    }
    else
    {
      mysqli_query($link, "INSERT INTO usuarios (nombre_usuario, email, password, apellido, telefono, localidad, type, foto)
       VALUES ('$nombre_usuario','$email','$password','$apellido','$telefono','$localidad', 2,'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png')");
      $data['id'] = mysqli_insert_id($link);
      header('Status: 200 OK', true, 200);
      $respuesta = ["succes" => "se creo correctamente", ];
    }

    header('Content-Type: application/json');
    print json_encode($respuesta);
    break;

    case 'login':
    $data = json_decode(file_get_contents("php://input") , true);
    $nombre_usuario = mysqli_real_escape_string($link, $data['nombre_usuario']);
    $password = mysqli_real_escape_string($link, $data['password']);
    $existUser = mysqli_query($link, "SELECT * FROM usuarios WHERE nombre_usuario = '$nombre_usuario' and password = '$password'and type = 2");
    $existAdmin = mysqli_query($link, "SELECT * FROM usuarios WHERE nombre_usuario = '$nombre_usuario' and password = '$password'and type = 1");
    $rowUser = mysqli_num_rows($existUser);
    $rowAdmin = mysqli_num_rows($existAdmin);
    if ($rowUser >= 1)
    {
      session_start();
      $_SESSION['name'] = '$nombre_usuario';
      header('Status: 200 OK', true, 200);
      $usuarios = [];
      $resultado_usuarios = mysqli_query($link, "SELECT nombre_usuario, type, id, apellido, telefono, localidad, email, foto FROM usuarios where nombre_usuario = '$nombre_usuario'");
      while ($fila_usuarios = mysqli_fetch_assoc($resultado_usuarios))
      {
        $usuario = ['nombre_usuario' => $fila_usuarios['nombre_usuario'], 'type' => $fila_usuarios['type'], 'id' => $fila_usuarios['id'], 'apellido' => $fila_usuarios['apellido'], 'telefono' => $fila_usuarios['telefono'], 'localidad' => $fila_usuarios['localidad'], 'email' => $fila_usuarios['email'], 'foto' => $fila_usuarios['foto']];
        $usuarios[] = $usuario;
      }
    }
    else
      if ($rowAdmin >= 1)
      {
        session_start();
        $_SESSION['name'] = '$nombre_usuario';
        header('Status: 200 OK', true, 200);
        $usuarios = [];
        $resultado_usuarios = mysqli_query($link, "SELECT nombre_usuario, type, id, apellido, telefono, localidad, email, foto FROM usuarios where nombre_usuario = '$nombre_usuario'");
        while ($fila_usuarios = mysqli_fetch_assoc($resultado_usuarios))
        {
          $usuario = ['nombre_usuario' => $fila_usuarios['nombre_usuario'], 'type' => $fila_usuarios['type'], 'id' => $fila_usuarios['id'], 'apellido' => $fila_usuarios['apellido'], 'telefono' => $fila_usuarios['telefono'], 'localidad' => $fila_usuarios['localidad'], 'email' => $fila_usuarios['email'], 'foto' => $fila_usuarios['foto']];
          $usuarios[] = $usuario;
        }
      }
      else
      {
        header('Status: 400 Bad Request', true, 400);
        $respuesta = ["error" => "error", ];
      }

      header('Content-Type: application/json');
      mysqli_free_result($resultado_usuarios);
      print json_encode($usuarios);
      break;

      case 'haceradmin':
      $usuarioId = json_decode(file_get_contents("php://input") , true);
      $id = isset($usuarioId['id']) ? $usuarioId['id'] : '';
      mysqli_query($link, "UPDATE usuarios
       SET type = 1
       WHERE id = $id AND type<>1");
      break;

      case 'sacaradmin':
      $usuarioId = json_decode(file_get_contents("php://input") , true);
      $id = isset($usuarioId['id']) ? $usuarioId['id'] : '';
      mysqli_query($link, "UPDATE usuarios
       SET type = 2
       WHERE id = $id AND type<>2");
      break;

      case 'borrarusuario':
      $usuarioId = json_decode(file_get_contents("php://input") , true);
      $id = $usuarioId + 0;
      mysqli_query($link, "DELETE FROM usuarios
       WHERE id = $id");
      break;

      case 'agregar':
      $producto = json_decode(file_get_contents("php://input") , true);
      $titulo = mysqli_real_escape_string($link, $producto['titulo']);
      $descripcion = mysqli_real_escape_string($link, $producto['descripcion']);
      $usuarioid = mysqli_real_escape_string($link, $producto['usuarioid']);
      $nombre_usuario = mysqli_real_escape_string($link, $producto['nombre_usuario']);
      $precio = mysqli_real_escape_string($link, $producto['precio']);
      mysqli_query($link, "INSERT INTO productos (titulo, descripcion, usuarioid, nombre_usuario, precio)
       VALUES ('$titulo','$descripcion','$usuarioid','$nombre_usuario','$precio')");
      $producto['id'] = mysqli_insert_id($link);
      print json_encode($producto);
      break;

      case 'comentar':
      $comentario = json_decode(file_get_contents("php://input") , true);
      $productoId = mysqli_real_escape_string($link, $comentario['productoId']);
      $nombreUsuario = mysqli_real_escape_string($link, $comentario['nombreUsuario']);
      $comentario = mysqli_real_escape_string($link, $comentario['comentario']);
      mysqli_query($link, "INSERT INTO comentarios (producto_id, nombre_usuario, comentario)
       VALUES ('$productoId','$nombreUsuario','$comentario')");
      $comentario['id'] = mysqli_insert_id($link);
      print json_encode($comentario);
      break;

      case 'borrar':
      $productoId = json_decode(file_get_contents("php://input") , true);
      $id = $productoId + 0;
      mysqli_query($link, "DELETE FROM productos
       WHERE id = $id");
      mysqli_query($link, "DELETE FROM comentarios
       WHERE producto_id = $id");
      break;

      case 'actualizar':
      $producto = json_decode(file_get_contents("php://input") , true);
      $id = $producto['id'] + 0;
      $titulo = mysqli_real_escape_string($link, $producto['titulo']);
      $descripcion = mysqli_real_escape_string($link, $producto['descripcion']);
      mysqli_query($link, "UPDATE productos
       SET titulo = '$titulo',
       descripcion = '$descripcion',                 
       WHERE id = $id");
      break;

      case 'listar_usuarios':
      $usuarios = [];
      $resultado_usuarios = mysqli_query($link, "SELECT nombre_usuario, type, id, apellido, telefono, localidad,email, foto  FROM usuarios");
      while ($fila_usuarios = mysqli_fetch_assoc($resultado_usuarios))
      {
        $usuario = ['nombre_usuario' => $fila_usuarios['nombre_usuario'], 'type' => $fila_usuarios['type'], 'id' => $fila_usuarios['id'], 'apellido' => $fila_usuarios['apellido'], 'telefono' => $fila_usuarios['telefono'], 'localidad' => $fila_usuarios['localidad'], 'email' => $fila_usuarios['email'], 'foto' => $fila_usuarios['foto']];
        $usuarios[] = $usuario;
      }

      mysqli_free_result($resultado_usuarios);
      print json_encode($usuarios);
      break;

      case 'listarProductoUsuario':
      $usuarioid = json_decode(file_get_contents("php://input") , true);
      $usuarioid = $usuarioid['usuarioid'] + 0;
      $productos = [];
      $resultado = mysqli_query($link, "SELECT  titulo, id
        FROM productos
        WHERE usuarioid = $usuarioid");
      while ($fila = mysqli_fetch_assoc($resultado))
      {
        $producto = ['titulo' => $fila['titulo'], 'id' => $fila['id']];
        $productos[] = $producto;
      }
      mysqli_free_result($resultado);
      print json_encode($productos);
      break;

      case 'listar':
      $productos = [];
      $resultado = array();
      $queryproducto = mysqli_query($link, "SELECT productos.id as id, titulo, descripcion, productos.nombre_usuario as nombre_usuario, usuarios.foto as foto, precio FROM productos INNER JOIN usuarios on productos.usuarioid = usuarios.id");
      while ($rowproducto = mysqli_fetch_array($queryproducto))
      {
        $idproducto = $rowproducto['id'];
        $queryComentarios = mysqli_query($link, "SELECT comentario, nombre_usuario from comentarios where producto_id = $idproducto ");
        $comentarios = [];
        while ($rowComentario = mysqli_fetch_array($queryComentarios))
        {
          $comentarios[] = $rowComentario;
          $idproducto = $rowproducto['id'];
        }

        $resultado[] = (object)array(
            'titulo' => $rowproducto['titulo'],
            'id' => $rowproducto['id'],
            'descripcion' => $rowproducto['descripcion'],
            'nombre_usuario' => $rowproducto['nombre_usuario'],
            'foto' => $rowproducto['foto'],
            'precio' => $rowproducto['precio'],
            'comentario' => $comentarios,
        );
      }
      print json_encode($resultado);
      break;

      case'cargarImagen':
        $datos = [];
        $datos = json_decode(file_get_contents("php://input") , true);
        $id = isset($datos['id']) ? $datos['id'] : '';
        $url = $datos['url'];

        mysqli_query($link, "UPDATE usuarios
          SET foto = '$url'
          WHERE id = $id");
        break;
    
      mysqli_close($link);
    }
  }
  ?>