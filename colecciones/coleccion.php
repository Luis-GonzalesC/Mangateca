<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coleccion de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set( "display_errors", 1);

        require('../util/conexion.php');//Importando la conexion php del servidor (BBDD)

        session_start(); //Para recuperar lo que sea iniciado porque no podemos acceder a ese valor
    ?>
    <style>
        .select{
            width: 50rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
            function depurar(string $entrada) : string{
                $salida = htmlspecialchars($entrada);
                return $salida;
            }

            $sql = "SELECT * FROM usuarios";
            $resultado = $_conexion -> query($sql);

            $usuarios = [];
            while($fila = $resultado -> fetch_assoc()){
                array_push($usuarios, $fila["username"]);    
            }

            if($_SERVER["REQUEST_METHOD"] == "POST"){
                if(isset($_POST["usuario"])) $tmp_usuario = depurar($_POST["usuario"]);
                else $tmp_usuario = "";

                if($tmp_usuario != ''){
                    if(in_array($tmp_usuario, $usuarios)) $usuario = $tmp_usuario;
                    else $err_usuario = "El usuario no existe";
                }else $err_usuario = "El usuario es obligatorio";
            }
        ?>

        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label">Usuarios</label>
                <?php if(isset($err_usuario)) echo "<div class='alert alert-danger'>$err_usuario</div>"?>
                <select class="form-select select" name="usuario">
                    <option value="" selected disabled hidden>---ELIGE UN USUARIO---</option>
                    <?php
                        foreach ($usuarios as $usuario) { ?>
                            <option value="<?php echo $usuario ?>">
                                <?php echo $usuario ?>
                            </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <input class="btn btn-primary" type="submit" value="Mostrar">
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>