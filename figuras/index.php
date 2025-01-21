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
        ?>
        
        <h2>Listado de Figuras</h2>
        <?php
            $sql =  "SELECT * FROM figuras";
            $resultado = $_conexion -> query($sql); // => Devuelve un objeto

        ?>

        <a class="btn btn-primary" href="nueva_figura.php">Nueva Figura</a>

        <table class="table table-striped">
            <thead class="table-primary">
                <tr>
                    <th>Nombre de la Figura</th>
                    <th>Nombre del Anime</th>
                    <th>Tipo de Figura</th>
                    <th>Precio</th>
                    <th>Imagen</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while($fila = $resultado -> fetch_assoc()){
                        echo "<tr>";
                        echo "<td>". $fila["nombre_figura"] ."</td>";
                        echo "<td>". $fila["nombre_anime"] ."</td>";
                        echo "<td>". $fila["tipo_figura"] ."</td>";
                        echo "<td>". $fila["precio"] ."</td>"; ?>
                        <td>
                            <img src="<?php echo $fila["imagen"] ?>" class="imagen">
                        </td>
                        <td>
                            <a class="btn btn-primary" href="editar_figura.php?id=<?php echo $fila["id"] ?>">EDITAR</a>
                        </td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="id" value="<?php echo $fila["id"] ?>">
                                <input class="btn btn-danger" type="submit" value="Borrar">
                            </form>
                        </td>
                <?php   echo "</tr>";
                    } ?>
            </tbody>
        </table>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>