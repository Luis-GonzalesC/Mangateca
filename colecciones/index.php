<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colecciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set( "display_errors", 1);

        require('../util/conexion.php');//Importando la conexion php del servidor (BBDD)

        session_start(); //Para recuperar lo que sea iniciado porque no podemos acceder a ese valor
        if(!isset($_SESSION["usuario"])){
            header("location: ../usuarios/iniciar_sesion.php");
            exit;
        }
    ?>
</head>
<body>
    <?php
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $id_manga = $_POST["id_manga"];

            #1. Prepare
            $sql = $_conexion -> prepare("DELETE FROM mangas where id = ?");

            #2. Binding
            $sql -> bind_param("i", $id_manga);

            #3. Execute
            $sql -> execute();
        }
    ?>

    <div class="container">
        <?php
            if(isset($_SESSION["usuario"])){ ?>
                <h2>Bienvenid@ <?php echo $_SESSION["usuario"] ?></h2>
                <a class ="btn btn-danger" href="../usuarios/cerrar_sesion.php">Cerrar Sesión</a> <br><br>
        <?php }else{ ?>
                <a class ="btn btn-danger" href="../usuarios/iniciar_sesion.php">Iniciar Sesión</a>
        <?php }
            $mi_usuario = $_SESSION["usuario"];
            $sql = "SELECT mangas.titulo, mangas.id, mangas.imagen, mangas.score, mangas.fecha_agregada FROM pertenece
                    JOIN mangas ON pertenece.id_manga = mangas.id
                    WHERE pertenece.id_coleccion = 
                        (SELECT id FROM coleccion WHERE id_usuario = /*Sacamos la ID de colección*/
                            (SELECT id FROM usuarios WHERE username = '$mi_usuario') /*Sacamos el ID del usuario LOGUEADO*/
                        )
                    ORDER BY mangas.titulo";
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
                    <?php
                        if(isset($_SESSION["usuario"])){ ?>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="../favoritos/index.php">Favoritos</a>
                            </li>
                    <?php } ?>
                        
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

        <div class="row text-center mt-5">
            <h1>Lista de Colección</h1>
            <?php
                while($fila = $resultado -> fetch_assoc()){ ?>
                    <div class="col-3 card m-1" style="width: 19rem;">
                        <img class="card-img-top" src="<?php echo $fila["imagen"]?>" alt="<?php echo $fila["titulo"]?>">
                        <div class="card-body">
                            <h3 class="card-text"><?php echo $fila["titulo"]?></h3>
                            <p class="card-subtitle mb-2 text-body-secondary">Puntuación: <?php echo $fila["score"]?></p>
                            <p class="card-subtitle mb-2 text-body-secondary">Fecha agregada: <?php echo $fila["fecha_agregada"]?></p>

                        </div>
                        <div class="col">
                            <form action="" method="post">
                                <input type="hidden" name ="id_manga" value="<?php echo $fila["id"]?>">
                                <input class="btn btn-danger" type="submit" value="Borrar Colección">
                            </form>
                            <a class="btn btn-primary mt-2 mb-2" href="../mangas/editar_manga.php?id_manga=<?php echo $fila["id"]?>">Editar Colección</a>
                        </div>
                    </div>
            <?php } ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>