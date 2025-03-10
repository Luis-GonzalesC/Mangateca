<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colecciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../estilos/inicio.css">
    <link rel="stylesheet" href="../estilos/coleccion.css">
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
        if(isset($_SESSION["usuario"])){
            $mi_usuario = $_SESSION['usuario'];
        }

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
                        <div class="user">
                            <li class="nav-item">
                                <button id="btn-message" class="button-message">
                                    <div class="content-avatar">
                                        <div class="status-user"></div>
                                        <div class="avatar">
                                            <svg class="user-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12,12.5c-3.04,0-5.5,1.73-5.5,3.5s2.46,3.5,5.5,3.5,5.5-1.73,5.5-3.5-2.46-3.5-5.5-3.5Zm0-.5c1.66,0,3-1.34,3-3s-1.34-3-3-3-3,1.34-3,3,1.34,3,3,3Z"></path></svg>
                                        </div>
                                    </div>
                                    <div class="notice-content">
                                        <div class="lable-message"><?php echo $mi_usuario ?></div>
                                        <div class="user-id"><a href="../usuarios/cerrar_sesion.php">Cerrar Sesión</a></div>
                                    </div>
                                </button>
                            </li>
                        </div>
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
                        <div class="row mb-2">
                            <div class="col offset-2">
                                <form action="" method="post">
                                    <input type="hidden" name ="id_manga" value="<?php echo $fila["id"]?>">
                                    <button class="delete-button">
                                        <svg class="delete-svgIcon" viewBox="0 0 448 512">
                                            <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <div class="col">
                                <a class="edit-button" href="../mangas/editar_manga.php?id_manga=<?php echo $fila["id"]?>">
                                    <svg class="edit-svgIcon" viewBox="0 0 512 512">
                                        <path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
            <?php } ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>