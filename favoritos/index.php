<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index de favoritos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../estilos/inicio.css">
    <link rel="stylesheet" href="../estilos/favoritos.css">
    <link rel="stylesheet" href="../estilos/boton_dia_noche.css">
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
    <script>
        function cambiar () {
            let padre = document.querySelector("html");
            if (padre.getAttribute("data-bs-theme") == "dark") padre.setAttribute("data-bs-theme", "light");
            else if (padre.getAttribute("data-bs-theme") == "light") padre.setAttribute("data-bs-theme", "dark");
        }
    </script>
    <style>
        .encabezado{
            --bs-table-bg: #FFFFE0;
        }
        th {
            --bs-table-color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="contenedor">
            <div class="toggle">
                <input type="checkbox" onclick="cambiar()">
                <span class="button"></span>
                <span class="label">☼</span>
            </div>
        </div>
        <?php
            if(isset($_SESSION["usuario"])){
                $mi_usuario = $_SESSION['usuario'];
            }

            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $id_manga = $_POST["id_manga"];

                #1. Prepare
                $sql = $_conexion -> prepare("DELETE FROM favoritos WHERE id_manga = ?");

                #2. Binding
                $sql -> bind_param("i", $id_manga);

                #3. Execute
                $sql -> execute();
            }

            $mi_usuario = $_SESSION["usuario"];
            $sql = "SELECT mangas.titulo, mangas.imagen, mangas.id FROM favoritos 
                JOIN mangas ON favoritos.id_manga = mangas.id
                WHERE favoritos.id_usuario = (SELECT id FROM usuarios WHERE username = '$mi_usuario')
                ORDER BY favoritos.id";
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

        <h2>Listado de Favoritos</h2>

        <table class="table table-striped text-center align-middle">
            <thead class="encabezado">
                <tr>
                    <th>Nombre del Manga</th>
                    <th>Imagen</th>
                    <th>Boton Borrar</th>
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
                                <!-- From Uiverse.io by vinodjangid07 --> 
                                <button class="bin-button offset-5">
                                    <svg xmlns="http://www.w3.org/2000/svg"fill="none" viewBox="0 0 39 7" class="bin-top">
                                        <line stroke-width="4" stroke="white" y2="5" x2="39" y1="5"></line>
                                        <line stroke-width="3" stroke="white" y2="1.5" x2="26.0357" y1="1.5" x1="12"></line>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 33 39" class="bin-bottom">
                                        <mask fill="white" id="path-1-inside-1_8_19">
                                            <path d="M0 0H33V35C33 37.2091 31.2091 39 29 39H4C1.79086 39 0 37.2091 0 35V0Z"></path>
                                        </mask>
                                        <path mask="url(#path-1-inside-1_8_19)" fill="white" d="M0 0H33H0ZM37 35C37 39.4183 33.4183 43 29 43H4C-0.418278 43 -4 39.4183 -4 35H4H29H37ZM4 43C-0.418278 43 -4 39.4183 -4 35V0H4V35V43ZM37 0V35C37 39.4183 33.4183 43 29 43V35V0H37Z"></path>
                                        <path stroke-width="4" stroke="white" d="M12 6L12 29"></path>
                                        <path stroke-width="4" stroke="white" d="M21 6V29"></path>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 89 80" class="garbage">
                                        <path fill="white" d="M20.5 10.5L37.5 15.5L42.5 11.5L51.5 12.5L68.75 0L72 11.5L79.5 12.5H88.5L87 22L68.75 31.5L75.5066 25L86 26L87 35.5L77.5 48L70.5 49.5L80 50L77.5 71.5L63.5 58.5L53.5 68.5L65.5 70.5L45.5 73L35.5 79.5L28 67L16 63L12 51.5L0 48L16 25L22.5 17L20.5 10.5Z"></path>
                                    </svg>
                                </button>

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