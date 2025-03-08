<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Index de la Biblioteca</title>
    <?php
        error_reporting( E_ALL );
        ini_set( "display_errors", 1);

        require('../util/conexion.php');//Importando la conexion php del servidor (BBDD)

        session_start(); //Para recuperar lo que sea iniciado porque no podemos acceder a ese valor
    ?>
    <style>
        .imagen{
            width: 100px;
            height: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
            if(isset($_SESSION["usuario"])){ ?>
                <h2>Bienvenid@ <?php echo $_SESSION["usuario"] ?></h2>
                <a class ="btn btn-danger" href="usuarios/cerrar_sesion.php">Cerrar Sesión</a> <br><br>
                <a class ="btn btn-info" href="colecciones/coleccion.php">Coleccion</a>
        <?php }else{ ?>
                <a class ="btn btn-danger" href="usuarios/iniciar_sesion.php">Iniciar Sesión</a>
        <?php }
            //Recibiendo el ID del manga
            $id_manga = $_GET["id_manga"];

            $url = "https://api.jikan.moe/v4/manga/$id_manga";//Link de la conexion

            $curl = curl_init();//Iniciar la conexion
            curl_setopt($curl, CURLOPT_URL, $url); //Accedemos a la url
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//Cuando acceda, que me de ese fichero
            $respuesta = curl_exec($curl);//Ejecutamos
            curl_close($curl);//Cerramos el curl
            $datos = json_decode($respuesta, true);
            $manga = $datos["data"];
        ?>

        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">Inicio</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
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

        <div class="card mb-3 p-5" style="max-width: auto;">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="<?php echo $manga["images"]["jpg"]["image_url"] ?>" class="img-fluid rounded-start" alt="<?php echo $manga["titles"][0]["title"]?>">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h2 class="card-title"><?php echo $manga["titles"][0]["title"]?></h2>
                        <p class="card-text"><?php echo $manga["synopsis"]?></p>
                        <p class="card-text"><small class="text-body-secondary"><?php echo $manga["published"]["string"]?></small></p>
                    </div>
                </div>
            </div>

            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">Autor</h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary"><?php echo $manga["authors"][0]["name"]?></h6>
                    <p class="card-text">Publicación: <?php echo $manga["serializations"][0]["name"]?></p>
                    <a href="#" class="card-link">Another link</a>
                </div>
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>