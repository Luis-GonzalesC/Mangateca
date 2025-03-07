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

        require('util/conexion.php');//Importando la conexion php del servidor (BBDD)

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
                <a class ="btn btn-info" href="figuras/index.php">Figura</a>
        <?php }else{ ?>
                <a class ="btn btn-danger" href="usuarios/iniciar_sesion.php">Iniciar Sesión</a>
        <?php } ?>
        
        <h2>Listado de Figuras</h2>
        <?php
           $url = "https://api.jikan.moe/v4/manga";//Link de la conexion

           $curl = curl_init();//Iniciar la conexion
           curl_setopt($curl, CURLOPT_URL, $url); //Accedemos a la url
           curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//Cuando acceda, que me de ese fichero
           $respuesta = curl_exec($curl);//Ejecutamos
           curl_close($curl);//Cerramos el curl
           $datos = json_decode($respuesta, true);
           $mangas = $datos["data"];

        ?>
        <div class="row">
            <?php 
                foreach ($mangas as $manga) { ?>
                    <div class="col-3 card" style="width: 18rem;">
                        <img class="card-img-top" src="<?php echo $manga["images"]["jpg"]["image_url"];?>" alt="<?php echo $manga["titles"][0]["title"]?>">
                        <div class="card-body">
                            <p class="card-text"><?php echo $manga["titles"][0]["title"]?></p>
                        </div>
                    </div>
            <?php } ?>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>