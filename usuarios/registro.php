<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../estilos/iniciar_sesion.css">
    <?php
        error_reporting( E_ALL );
        ini_set( "display_errors", 1);

        require('../util/conexion.php');//Importando la conexion php del servidor (BBDD)
    ?>
</head>
<body>
    <?php
        function depurar(string $entrada) : string{
            $salida = htmlspecialchars($entrada);
            return $salida;
        }

        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $tmp_usuario = depurar($_POST["usuario"]);
            $tmp_contrasena = depurar($_POST["contrasena"]);
            $tmp_correo = depurar($_POST["correo"]);

            $sql = $_conexion -> prepare("SELECT * FROM usuarios WHERE username = ?");

            $sql -> bind_param("s", $tmp_usuario);

            $sql -> execute();

            $resultado = $sql -> get_result();

            if($resultado -> num_rows == 0){//Compruebo si ya existe el usuario
                if($tmp_usuario != ''){
                    if(strlen($tmp_usuario) >= 3 && strlen($tmp_usuario) <= 20){
                        $patron = "/^[a-zA-Z0-9]+$/";
                        if(!preg_match($patron, $tmp_usuario)) $err_usuario = "El nombre de usuario no cumple con el patrón, solo puede tener letras y números";
                        else $usuario = $tmp_usuario;
                    }else $err_usuario = "El nombre de usuario debe tener entre 3 y 20 caracteres";
                }else $err_usuario = "El nombre de usuario es obligatorio";
            }else $err_registro = "EL USUARIO YA EXISTE INGRESE OTRO NOMBRE";

            if($tmp_contrasena != ''){
                if(strlen($tmp_contrasena) >= 8 && strlen($tmp_contrasena) <= 40){
                    $patron = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/";
                    if(!preg_match($patron, $tmp_contrasena)) $err_contrasena = "La contraseña debe tener letras en mayúsculas y minúsculas, algún número y puede tener caracteres especiales";
                    else $contrasena = $tmp_contrasena;
                }else $err_contrasena = "La contraseña debe tener entre 8 y 40 carácteres";
            }else $err_contrasena = "La contraseña es obligatoria";

            if($tmp_correo != ''){
                if(strlen($tmp_correo) >= 10 && strlen($tmp_correo) <= 40){
                    $patron = "/^\b[\w\.-]+@[\w\.-]+\.\w{2,4}\b$/";
                    if(!preg_match($patron, $tmp_correo)) $err_correo = "El correo no cumple el patrón adecuado";
                    else $correo = $tmp_correo;
                }else $err_correo = "El correo debe tener entre 10 y 40 carácteres";
            }else $err_correo = "El correo es obligatorio";

            if(isset($usuario) && isset($contrasena) && isset($correo)){
                $contrasena_cifrada = password_hash($contrasena, PASSWORD_DEFAULT);

                $sql = $_conexion -> prepare("INSERT INTO usuarios (username, correo, contrasenia) VALUES (?,?,?)");

                $sql -> bind_param("sss", $usuario, $correo, $contrasena_cifrada);

                $sql -> execute();

                //Tomando el ID del usuario para agregarlo en su colección correspondiente
                $consulta_usuario = "SELECT id FROM usuarios WHERE username='$usuario'";
                $id_usuario = $_conexion -> query($consulta_usuario);
                $resultado = $id_usuario->fetch_array()[0]; //Coger el valor de la consulta
                //Agregando el usuario a la colección
                $consulta_coleccion = "INSERT INTO coleccion (id_usuario) VALUES ($resultado)";
                $_conexion -> query($consulta_coleccion);
            }
            
        }
    ?>
    <div class="container">
        <form class="form_main" action="" method="post">
            <p class="heading">Registrarse</p>
            <?php if(isset($err_registro)) echo "<span class='error'>$err_registro</span>"?>
            <div class="inputContainer">
                <svg viewBox="0 0 16 16" fill="#2e2e2e" height="16" width="16" xmlns="http://www.w3.org/2000/svg" class="inputIcon">
                    <path d="M13.106 7.222c0-2.967-2.249-5.032-5.482-5.032-3.35 0-5.646 2.318-5.646 5.702 0 3.493 2.235 5.708 5.762 5.708.862 0 1.689-.123 2.304-.335v-.862c-.43.199-1.354.328-2.29.328-2.926 0-4.813-1.88-4.813-4.798 0-2.844 1.921-4.881 4.594-4.881 2.735 0 4.608 1.688 4.608 4.156 0 1.682-.554 2.769-1.416 2.769-.492 0-.772-.28-.772-.76V5.206H8.923v.834h-.11c-.266-.595-.881-.964-1.6-.964-1.4 0-2.378 1.162-2.378 2.823 0 1.737.957 2.906 2.379 2.906.8 0 1.415-.39 1.709-1.087h.11c.081.67.703 1.148 1.503 1.148 1.572 0 2.57-1.415 2.57-3.643zm-7.177.704c0-1.197.54-1.907 1.456-1.907.93 0 1.524.738 1.524 1.907S8.308 9.84 7.371 9.84c-.895 0-1.442-.725-1.442-1.914z"></path>
                </svg>
                <input placeholder="Username" id="username" class="inputField" type="text" name="usuario">
                <?php if(isset($err_usuario)) echo "<span class='error'>$err_usuario</span>"?>

            </div>
            
            <div class="inputContainer">
                <svg viewBox="0 0 16 16" fill="#2e2e2e" height="16" width="16" xmlns="http://www.w3.org/2000/svg" class="inputIcon">
                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"></path>
                </svg>
                <input placeholder="Password" id="password" class="inputField" type="password" name="contrasena">
                <?php if(isset($err_contrasena)) echo "<span class='error'>$err_contrasena</span>"?>
            </div>

            <div class="inputContainer">
                <svg viewBox="0 0 16 16" fill="#2e2e2e" height="16" width="16" xmlns="http://www.w3.org/2000/svg" class="inputIcon">
                    <path d="M13.106 7.222c0-2.967-2.249-5.032-5.482-5.032-3.35 0-5.646 2.318-5.646 5.702 0 3.493 2.235 5.708 5.762 5.708.862 0 1.689-.123 2.304-.335v-.862c-.43.199-1.354.328-2.29.328-2.926 0-4.813-1.88-4.813-4.798 0-2.844 1.921-4.881 4.594-4.881 2.735 0 4.608 1.688 4.608 4.156 0 1.682-.554 2.769-1.416 2.769-.492 0-.772-.28-.772-.76V5.206H8.923v.834h-.11c-.266-.595-.881-.964-1.6-.964-1.4 0-2.378 1.162-2.378 2.823 0 1.737.957 2.906 2.379 2.906.8 0 1.415-.39 1.709-1.087h.11c.081.67.703 1.148 1.503 1.148 1.572 0 2.57-1.415 2.57-3.643zm-7.177.704c0-1.197.54-1.907 1.456-1.907.93 0 1.524.738 1.524 1.907S8.308 9.84 7.371 9.84c-.895 0-1.442-.725-1.442-1.914z"></path>
                </svg>
                <input placeholder="Email" id="correo" class="inputField" type="email" name="correo">
                <?php if(isset($err_correo)) echo "<span class='error'>$err_correo</span>"?>
            </div>
            
            <button id="button">Registrarse</button>
                <div class="signupContainer">
                    <p>Ya estás registrado?</p>
                    <a href="iniciar_sesion.php">Iniciar Sesión</a>
                    <a href="../index.php">Página Principal</a>

                </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>