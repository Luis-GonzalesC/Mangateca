<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index de favoritos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set( "display_errors", 1);

        require('../util/conexion.php');//Importando la conexion php del servidor (BBDD)

        session_start(); //Para recuperar lo que sea iniciado porque no podemos acceder a ese valor
    ?>
</head>
<body>
    <div class="container">
        <?php
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $id_manga = $_POST["id_manga"];
                //echo "<h1>$id_anime</h1>";
                /*$sql = "DELETE FROM productos WHERE id_producto = '$id_producto'";
                $_conexion -> query($sql);*/

                #1. Prepare
                $sql = $_conexion -> prepare("DELETE FROM favoritos WHERE id_manga = ?");

                #2. Binding
                $sql -> bind_param("i", $id_manga);

                #3. Execute
                $sql -> execute();
            }

            if(isset($_SESSION["usuario"])){ ?>
                <h2>Bienvenid@ <?php echo $_SESSION["usuario"] ?></h2>
                <a class ="btn btn-danger" href="../usuarios/cerrar_sesion.php">Cerrar Sesión</a> <br><br>
        <?php }else{ ?>
                <a class ="btn btn-danger" href="../usuarios/iniciar_sesion.php">Iniciar Sesión</a>
        <?php }

            $sql = "SELECT mangas.titulo, mangas.imagen, mangas.id FROM favoritos JOIN mangas ON favoritos.id_manga = mangas.id ORDER BY favoritos.id";
            $resultado = $_conexion -> query($sql); // => Devuelve un objeto

        ?>
        <!-- Barra de navegación -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">Inicio</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../colecciones/index.php">Coleccion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Dropdown</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <h2>Listado de Favoritos</h2>

        <table class="table table-striped">
            <thead class="table-primary">
                <tr>
                    <th>Nombre del Manga</th>
                    <th>Imagen</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while($fila = $resultado -> fetch_assoc()){
                        echo "<tr>";
                        echo "<td>". $fila["titulo"] ."</td>";?>
                        <td>
                            <img src="<?php echo $fila["imagen"] ?>" class="imagen">
                        </td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="id_manga" value="<?php echo $fila["id"] ?>">
                                <input class="btn btn-danger" type="submit" value="Borrar">
                            </form>
                        </td>
                <?php  echo "<tr>";
                    } ?>
            </tbody>
        </table>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>