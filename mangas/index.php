<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Index de los Mangas</title>
    <link rel="stylesheet" href="../estilos/mangas.css">
    <link rel="stylesheet" href="../estilos/inicio.css">
    <link rel="stylesheet" href="../estilos/boton_regresar.css">
    <?php
        error_reporting( E_ALL );
        ini_set( "display_errors", 1);

        require('../util/conexion.php');//Importando la conexion php del servidor (BBDD)

        session_start(); //Para recuperar lo que sea iniciado porque no podemos acceder a ese valor
    ?>
</head>
<body>
    <?php
        if(isset($_SESSION["usuario"])){
            $mi_usuario = $_SESSION['usuario'];
        }
    
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $mi_usuario = $_SESSION['usuario'];
            $titulo = $_POST["titulo"];

            if(isset($titulo) && isset($mi_usuario)){
                //Agregar a la tabla favoritos
                $consulta_favoritos = "INSERT INTO favoritos (id_usuario, id_manga) 
                    VALUES (
                        (SELECT id FROM usuarios WHERE username = '$mi_usuario'), /*Saco el ID del usuario*/
                        (SELECT id FROM mangas WHERE titulo = '$titulo') /*Saco el id de manga*/
                    )";
                $_conexion -> query($consulta_favoritos);
            }
        }
    ?>
    <div class="container">
        <?php 
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
                    <?php
                        if(isset($_SESSION["usuario"])){ ?>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="../favoritos/index.php">Favoritos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="../colecciones/index.php">Coleccion</a>
                            </li>
                    <?php } ?>
                    <?php
                        if(isset($_SESSION["usuario"])){ ?>
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
                    <?php } else {?>
                            <label class="popup" style="margin-left:72rem">
                                <input type="checkbox" />
                                <div tabindex="0" class="burger">
                                    <svg viewBox="0 0 24 24" fill="white" height="20" width="20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 2c2.757 0 5 2.243 5 5.001 0 2.756-2.243 5-5 5s-5-2.244-5-5c0-2.758 2.243-5.001 5-5.001zm0-2c-3.866 0-7 3.134-7 7.001 0 3.865 3.134 7 7 7s7-3.135 7-7c0-3.867-3.134-7.001-7-7.001zm6.369 13.353c-.497.498-1.057.931-1.658 1.302 2.872 1.874 4.378 5.083 4.972 7.346h-19.387c.572-2.29 2.058-5.503 4.973-7.358-.603-.374-1.162-.811-1.658-1.312-4.258 3.072-5.611 8.506-5.611 10.669h24c0-2.142-1.44-7.557-5.631-10.647z" ></path>
                                    </svg>
                                </div>
                                <nav class="popup-window">
                                    <legend>Usuario</legend>
                                    <ul>
                                        <li>
                                            <button>
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M19 4v6.406l-3.753 3.741-6.463-6.462 3.7-3.685h6.516zm2-2h-12.388l1.497 1.5-4.171 4.167 9.291 9.291 4.161-4.193 1.61 1.623v-12.388zm-5 4c.552 0 1 .449 1 1s-.448 1-1 1-1-.449-1-1 .448-1 1-1zm0-1c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm6.708.292l-.708.708v3.097l2-2.065-1.292-1.74zm-12.675 9.294l-1.414 1.414h-2.619v2h-2v2h-2v-2.17l5.636-5.626-1.417-1.407-6.219 6.203v5h6v-2h2v-2h2l1.729-1.729-1.696-1.685z"></path>
                                                </svg>
                                                <span><a href="../usuarios/iniciar_sesion.php">Iniciar Sesión</a></span>
                                            </button>
                                        </li>
                                        <li>
                                            <button>
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2.598 9h-1.055c1.482-4.638 5.83-8 10.957-8 6.347 0 11.5 5.153 11.5 11.5s-5.153 11.5-11.5 11.5c-5.127 0-9.475-3.362-10.957-8h1.055c1.443 4.076 5.334 7 9.902 7 5.795 0 10.5-4.705 10.5-10.5s-4.705-10.5-10.5-10.5c-4.568 0-8.459 2.923-9.902 7zm12.228 3l-4.604-3.747.666-.753 6.112 5-6.101 5-.679-.737 4.608-3.763h-14.828v-1h14.826z"></path>
                                                </svg>
                                                <span><a  href="../usuarios/registro.php">Registro</a></span>
                                            </button>
                                        </li>
                                    </ul>
                                </nav>
                            </label>
                    <?php } ?>
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
        <!-- From Uiverse.io by karthik092726122003 --> 
        <div class="styled-wrapper">
            <a class="button" href="../index.php?page=<?php echo $pagina?>">
                <div class="button-box">
                    <span class="button-elem">
                        <svg viewBox="0 0 24 24"xmlns="http://www.w3.org/2000/svg" class="arrow-icon">
                            <path fill="black" d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"></path>
                        </svg>
                    </span>
                    <span class="button-elem">
                        <svg fill="black" viewBox="0 0  24 24" xmlns="http://www.w3.org/2000/svg" class="arrow-icon">
                            <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"></path>
                        </svg>
                    </span>
                </div>
            </a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>