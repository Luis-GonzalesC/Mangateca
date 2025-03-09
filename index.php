<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Index de los Mangas</title>
    <?php
        error_reporting( E_ALL );
        ini_set( "display_errors", 1);

        require('util/conexion.php');//Importando la conexion php del servidor (BBDD)

        session_start(); //Para recuperar lo que sea iniciado porque no podemos acceder a ese valor
    ?>
    <link rel="stylesheet" href="estilos/inicio.css">
</head>
<body>
    <?php
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $titulo = $_POST["titulo"];
            $autor = $_POST["autor"];
            if($_POST["capitulos"] != '') $capitulos = $_POST["capitulos"];
            else $capitulos = 0;
            if($_POST["volumen"] != '') $volumen = $_POST["volumen"];
            else $volumen = 0;
            $score = $_POST["score"];
            $fecha = $_POST["fecha"];
            $imagen = $_POST["imagen"];

            $mi_usuario = $_SESSION['usuario'];

            if(isset($titulo) && isset($autor) && isset($capitulos) && isset($volumen) && isset($score) && isset($fecha) && isset($imagen)){

                //agregamos el manga a nuestra BBDD
                $sql = "INSERT INTO mangas (titulo, autor, capitulos, volumen, score, fecha_agregada, imagen) 
                            VALUES ('$titulo', '$autor', $capitulos, $volumen, $score, '$fecha', '$imagen')";
                $_conexion -> query($sql);

                //Insertamos en la tabla PERTENECE ambos ID
                $consulta_pertenece = "INSERT INTO pertenece (id_manga, id_coleccion) 
                    VALUES (
                        (SELECT id FROM mangas WHERE titulo = '$titulo'), /*Sacamos el id de manga filtrando por el titulo*/
                        (SELECT coleccion.id FROM coleccion JOIN usuarios ON coleccion.id_usuario = usuarios.id WHERE username = '$mi_usuario') /*Sacamos el id de colección haciendo una unión con la tabla de usuarios*/
                    )";
                $_conexion -> query($consulta_pertenece);
            }
        }
    ?>

    <div class="container">
        <?php
            if(isset($_SESSION["usuario"])){ ?>
                <h2>Bienvenid@ <?php echo $_SESSION["usuario"] ?></h2>
                <a class ="btn btn-danger" href="usuarios/cerrar_sesion.php">Cerrar Sesión</a> <br><br>
        <?php }else{ ?>
                <a class ="btn btn-danger" href="usuarios/iniciar_sesion.php">Iniciar Sesión</a>
        <?php } ?>
        <!-- Barra de navegación -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Inicio</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="favoritos/index.php">Favoritos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="colecciones/index.php">Coleccion</a>
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
        <h2>Listado de Mangas</h2>
        <?php
            if(!isset($_GET["page"])) $pagina = 1;
            else $pagina = $_GET["page"];

           $url = "https://api.jikan.moe/v4/manga?page=$pagina";//Link de la conexion

           $curl = curl_init();//Iniciar la conexion
           curl_setopt($curl, CURLOPT_URL, $url); //Accedemos a la url
           curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//Cuando acceda, que me de ese fichero
           $respuesta = curl_exec($curl);//Ejecutamos
           curl_close($curl);//Cerramos el curl
           $datos = json_decode($respuesta, true);
           $mangas = $datos;

           $existe_pagina = $mangas["pagination"]["has_next_page"];//Comprobar si existe una siguiente página o no (true o false)
        ?>
        <div class="row text-center">
            <?php
                foreach ($mangas["data"] as $manga) { ?>
                    <div class="col-3 card m-1" style="width: 19rem;">
                        <a href="mangas/index.php?id_manga=<?php echo $manga["mal_id"]?>&page=<?php echo $pagina ?>">
                            <img class="card-img-top" style="width: 255px; height: 350px" src="<?php echo $manga["images"]["jpg"]["image_url"];?>" alt="<?php echo $manga["titles"][0]["title"]?>">
                        </a>
                        <div class="card-body">
                            <h3 class="card-text"><?php echo $manga["titles"][0]["title"]?></h3>
                        </div>
                        <?php if(isset($_SESSION["usuario"])){ //Comprobando si mi usuario existe
                                $titulillo = $manga["titles"][0]["title"];
                                $sql = "SELECT titulo FROM mangas WHERE titulo = '$titulillo'";
                                $resultado = $_conexion -> query($sql);
                                $titulo = $resultado->fetch_array(); //Coger el valor de la consulta
                                if($titulo === null || $titulo === ''){ ?>
                                    <form action="" method="post">
                                        <input type="hidden" name ="titulo" value="<?php echo $manga["titles"][0]["title"]?>">
                                        <input type="hidden" name ="autor" value="<?php echo $manga["authors"][0]["name"]?>">
                                        <input type="hidden" name ="capitulos" value="<?php echo $manga["chapters"]?>">
                                        <input type="hidden" name ="volumen" value="<?php echo $manga["volumes"]?>">
                                        <input type="hidden" name ="score" value="<?php echo $manga["score"]?>">
                                        <input type="hidden" name ="fecha" value="<?php echo date("Y-m-d")?>">
                                        <input type="hidden" name ="imagen" value="<?php echo $manga["images"]["jpg"]["image_url"]?>">
                                        <input type="submit" value="Agregar">
                                    </form>
                        <?php   }
                            } ?>
                    </div>
            <?php } ?>
        </div>

        
        <div class="button-container">
        <?php 
            $siguiente = $pagina + 1;
            $atras = $pagina - 1;
            
            if($existe_pagina){ ?>
                <?php if($pagina != 1){ ?>
                    <a class="button-3d" href="index.php?page=<?php echo $atras ?>">
                        <div class="button-top">
                            <span class="material-icons">❮</span>
                        </div>
                        <div class="button-bottom"></div>
                        <div class="button-base"></div>
                    </a>
                <?php } ?>
                    <a class="button-3d" href="index.php?page=<?php echo $siguiente ?>">
                        <div class="button-top">
                            <span class="material-icons">❯</span>
                        </div>
                        <div class="button-bottom"></div>
                        <div class="button-base"></div>
                    </a>
        <?php
            } else{ ?>
                <a class="button-3d" href="index.php?page=<?php echo $atras ?>">
                    <div class="button-top">
                        <span class="material-icons">❮</span>
                    </div>
                    <div class="button-bottom"></div>
                    <div class="button-base"></div>
                </a>
        <?php
            }
        ?>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>