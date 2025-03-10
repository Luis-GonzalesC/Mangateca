<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="estilos/boton_dia_noche.css">
    <title>Index de los Mangas</title>
    <?php
        error_reporting( E_ALL );
        ini_set( "display_errors", 1);

        require('util/conexion.php');//Importando la conexion php del servidor (BBDD)

        session_start(); //Para recuperar lo que sea iniciado porque no podemos acceder a ese valor
    ?>
    <link rel="stylesheet" href="estilos/inicio.css">
    <script>
        function cambiar () {
            let padre = document.querySelector("html");
            if (padre.getAttribute("data-bs-theme") == "dark") padre.setAttribute("data-bs-theme", "light");
            else if (padre.getAttribute("data-bs-theme") == "light") padre.setAttribute("data-bs-theme", "dark");
        }
    </script>
</head>
<body>
    <?php
        if(isset($_SESSION["usuario"])){
            $mi_usuario = $_SESSION['usuario'];
        }
        
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
        <div class="contenedor">
            <div class="toggle">
                <input type="checkbox" onclick="cambiar()">
                <span class="button"></span>
                <span class="label">☼</span>
            </div>
        </div>
        <!-- Barra de navegación -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Inicio</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php
                        if(isset($_SESSION["usuario"])){ ?>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="favoritos/index.php">Favoritos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="colecciones/index.php">Coleccion</a>
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
                                            <div class="user-id"><a href="usuarios/cerrar_sesion.php">Cerrar Sesión</a></div>
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
                                                <span><a href="usuarios/iniciar_sesion.php">Iniciar Sesión</a></span>
                                            </button>
                                        </li>
                                        <li>
                                            <button>
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2.598 9h-1.055c1.482-4.638 5.83-8 10.957-8 6.347 0 11.5 5.153 11.5 11.5s-5.153 11.5-11.5 11.5c-5.127 0-9.475-3.362-10.957-8h1.055c1.443 4.076 5.334 7 9.902 7 5.795 0 10.5-4.705 10.5-10.5s-4.705-10.5-10.5-10.5c-4.568 0-8.459 2.923-9.902 7zm12.228 3l-4.604-3.747.666-.753 6.112 5-6.101 5-.679-.737 4.608-3.763h-14.828v-1h14.826z"></path>
                                                </svg>
                                                <span><a  href="usuarios/registro.php">Registro</a></span>
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
                                        <button title="Add" class="cssbuttons-io-button mb-3">
                                            <svg height="25" width="25" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z" fill="currentColor"></path>
                                            </svg>
                                            <span>Add</span>
                                        </button>
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