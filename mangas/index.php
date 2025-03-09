<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Index de los Mangas</title>
    <link rel="stylesheet" href="../estilos/mangas.css">
    <?php
        error_reporting( E_ALL );
        ini_set( "display_errors", 1);

        require('../util/conexion.php');//Importando la conexion php del servidor (BBDD)

        session_start(); //Para recuperar lo que sea iniciado porque no podemos acceder a ese valor
    ?>
</head>
<body>
    <?php
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $mi_usuario = $_SESSION['usuario'];
            $titulo = $_POST["titulo"];

            if(isset($titulo) && isset($mi_usuario)){
                //Saco el ID del usuario
                $consulta_usuario = "SELECT id FROM usuarios WHERE username = '$mi_usuario'";
                $id_usuario = $_conexion -> query($consulta_usuario);
                $resultado1 = $id_usuario->fetch_array()[0]; //Coger el valor de la consulta
                //Saco el id de manga
                $consulta_manga = "SELECT id FROM mangas WHERE titulo = '$titulo'";
                $id_manga = $_conexion -> query($consulta_manga);
                $resultado2 = $id_manga->fetch_array()[0]; //Coger el valor de la consulta

                //Insertamos en la tabla pertenece ambos ID
                $consulta_favoritos = "INSERT INTO favoritos (id_usuario, id_manga) VALUES ($resultado1, $resultado2)";
                $_conexion -> query($consulta_favoritos);
            }
        }
    ?>
    <div class="container">
        <?php
            if(isset($_SESSION["usuario"])){ ?>
                <h2>Bienvenid@ <?php echo $_SESSION["usuario"] ?></h2>
                <a class ="btn btn-danger" href="../usuarios/cerrar_sesion.php">Cerrar Sesión</a> <br><br>
                <a class ="btn btn-info" href="colecciones/coleccion.php">Coleccion</a>
        <?php }else{ ?>
                <a class ="btn btn-danger" href="../usuarios/iniciar_sesion.php">Iniciar Sesión</a>
        <?php }
            //Recibiendo el ID del manga
            $id_manga = $_GET["id_manga"];
            $pagina = $_GET["page"];


            $url = "https://api.jikan.moe/v4/manga/$id_manga";//Link de la conexion

            $curl = curl_init();//Iniciar la conexion
            curl_setopt($curl, CURLOPT_URL, $url); //Accedemos a la url
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//Cuando acceda, que me de ese fichero
            $respuesta = curl_exec($curl);//Ejecutamos
            curl_close($curl);//Cerramos el curl
            $datos = json_decode($respuesta, true);
            $manga = $datos["data"];
        ?>
        <!-- Barra de navegación-->
        <nav class="mt-2 navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">Inicio</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../favoritos/index.php">Favoritos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../index.php?page=<?php echo $pagina ?>">Regresar</a>
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
        <!-- Presentación de cada manga-->
        <div class="card mb-3 p-5" style="max-width: auto;">
            <div class="row g-0">
                <div class="col offset-8">
                    <h1>Score: <?php echo $manga["score"]?></h1>
                    <?php //SI no estás registrado no puedes agregar a favoritos
                        if(isset($_SESSION["usuario"])){ //Comprobado si el usuario existe
                            $titulillo = $manga["titles"][0]["title"];
                            $sql = "SELECT titulo FROM mangas WHERE titulo = '$titulillo'";
                            $resultado = $_conexion -> query($sql);
                            $titulo = $resultado->fetch_array(); //Coger el valor de la consulta 
                            if($titulo != null || $titulo != ''){ //Comprando si el título está vacio y no está agregado a los mangas
                                $sql = "SELECT favoritos.id_manga FROM favoritos JOIN mangas ON favoritos.id_manga = mangas.id WHERE titulo = '$titulillo'";
                                $resultado = $_conexion -> query($sql);
                                $id_manga = $resultado->fetch_array(); //Coger el valor de la consulta 
                                if($id_manga == '' || $id_manga == null){ //Comprobando si el manga está YA agregado a favoritos?>
                                    <form action="" method="post">
                                        <input type="hidden" name ="titulo" value="<?php echo $manga["titles"][0]["title"]?>">
                                        <input type="submit" value="Agregar a Favoritos">
                                    </form>
                    <?php       }else echo "<input type='submit' disabled value='Agregado a Favoritos'>"; 
                            }
                        } ?>
                </div>
                <div class="col-md-4">
                    <img src="<?php echo $manga["images"]["jpg"]["image_url"] ?>" class="img-fluid rounded-start" alt="<?php echo $manga["titles"][0]["title"]?>">
                    <?php //SI no estás registrado no puedes puntuar
                        if(isset($_SESSION["usuario"])){ ?>
                            <div class="radio-input me-5">
                                <input value="value-1" name="value-radio" id="value-1" type="radio" class="star s1"/>
                                <input value="value-2" name="value-radio" id="value-2" type="radio" class="star s2"/>
                                <input value="value-3" name="value-radio" id="value-3" type="radio" class="star s3"/>
                                <input value="value-4" name="value-radio" id="value-4" type="radio" class="star s4"/>
                                <input value="value-5" name="value-radio" id="value-5" type="radio" class="star s5"/>
                            </div>
                    <?php } ?>
                </div>

                <div class="col-md-8">
                    <div class="card-body">
                        <h2 class="card-title"><?php echo $manga["titles"][0]["title"]?></h2>
                        <p class="card-text"><?php echo $manga["synopsis"]?></p>
                        <p class="card-text"><small class="text-body-secondary"><?php echo $manga["published"]["string"]?></small></p>
                    </div>
                </div>
            </div>
            <!-- Presentación del autor-->
            <div class="row mt-5">
                <div class="col-4 card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Author</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary"><?php echo $manga["authors"][0]["name"]?></h6>
                        <p class="card-text">Serialization: <?php echo $manga["serializations"][0]["name"]?></p>
                        <a href="<?php echo $manga["serializations"][0]["url"]?>" target="_blank" class="card-link">Published Manga</a>
                    </div>
                </div>
                <div class="col-6 offset-6 card" style="width: 18rem;">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Rank: <?php echo $manga["rank"]?></li>
                        <li class="list-group-item">Popularity: <?php echo $manga["popularity"]?></li>
                        <li class="list-group-item">Chapters: <?php echo $manga["chapters"]?></li>
                        <li class="list-group-item">Volumes: <?php echo $manga["volumes"]?></li>
                        <li class="list-group-item">Favorites: <?php echo $manga["favorites"]?></li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>