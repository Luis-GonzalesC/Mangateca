<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Manga</title>
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
    <div class="container">
        <?php
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $tmp_score = $_POST["score"];
                $tmp_fecha = $_POST["fecha"];

                if($tmp_score != ''){
                    if(filter_var($tmp_score, FILTER_VALIDATE_FLOAT) !== FALSE){
                        if($tmp_score >= 0 && $tmp_score <= 10){
                            $score = $tmp_score;
                        } else $err_score = "La puntuación debe ser evaluada entre 0 y 10";
                    } else $err_score = "La puntuación tiene que ser un número";
                }else $err_score = "La puntuación es obligatoria";

                if($tmp_fecha != ''){
                    $fecha = $tmp_fecha;
                }else $err_fecha_agregada = "La fecha es obligatoria";

                if(isset($score) && isset($fecha)){
                    $id_manga = $_POST["id_manga"];

                    #1. Prepare
                    $sql = $_conexion -> prepare("UPDATE mangas SET
                            score = ?,
                            fecha_agregada = ?
                            WHERE id = ?");
                            
                    #2. Binding
                    $sql -> bind_param("isi", $score, $fecha, $id_manga);
                    #3. Execute
                    $sql -> execute();

                    echo "<div class='col-4 alert alert-success'>SE HA ACTUALIZADO</div>";
                }
            }

            $id_manga = $_GET["id_manga"];

            #1. Prepare
            $sql = $_conexion -> prepare("SELECT * FROM mangas WHERE id = ?");

            #2. Binding
            $sql -> bind_param("i", $id_manga);

            #3. Execute
            $sql -> execute();

            #4. Retrieve
            $resultado = $sql -> get_result();
            $manga = $resultado -> fetch_assoc();
        ?>
        <h1>ESTELAAAAAAAAAAAAAAAA</h1>
        <form class="col-4" action="" method="post">
            <div class="mb-3">
                    <label class="form-label">ID manga</label>
                    <input disabled class="form-control" type="text" value="<?php echo $manga["id"]?>">
                    <input type="hidden" name="id_manga" value="<?php echo $manga["id"] ?>">
            </div>

            <div class="mb-3">
                    <label class="form-label">Nombre del manga</label>
                    <input disabled class="form-control" type="text" name="titulo" value="<?php echo $manga["titulo"]?>">
            </div>

            <div class="mb-3">
                    <label class="form-label">Autor del manga</label>
                    <input disabled class="form-control" type="text" name="autor" value="<?php echo $manga["autor"]?>">
            </div>
            <div class="mb-3">
                    <label class="form-label">Capitulos del manga</label>
                    <input disabled class="form-control" type="text" name="capitulos" value="<?php echo $manga["capitulos"]?>">                    
            </div>
            <div class="mb-3">
                    <label class="form-label">Tomo del manga</label>
                    <input disabled class="form-control" type="text" name="volumen" value="<?php echo $manga["volumen"]?>">
            </div>
            <div class="mb-3">
                    <label class="form-label">Puntuación del manga</label>
                    <?php if(isset($err_score)) echo "<div class='alert alert-danger'>$err_score</div>"?>
                    <input class="form-control" type="text" name="score" value="<?php echo $manga["score"]?>">
            </div>
            <div class="mb-3">
                    <label class="form-label">Fecha agregada del manga</label>
                    <?php if(isset($err_fecha_agregada)) echo "<div class='alert alert-danger'>$err_fecha_agregada</div>"?>
                    <input class="form-control" type="date" name="fecha" value="<?php echo $manga["fecha_agregada"]?>">
            </div>
            <div class="mb-3">
                    <input class="btn btn-primary" type="submit" value="Modificar">
                    <a class="btn btn-secondary" href="../colecciones/index.php">Regresar</a>
            </div>

        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>