<div class="m-4">
    <h2>Lista de proyectos</h2>
    <?php
    require_once 'models/p.php';

    /*$email = $_SESSION['email'];

    $query = mysqli_query($con, "SELECT id FROM users WHERE email = '$email'");

    $result = mysqli_num_rows($query);
    if ($result > 0) {

        $data = mysqli_fetch_array($query);
    }*/
    ?>

    <a href="views/createProject.php?id=<?php echo $data["id"]; ?>" class="btnNew pr-4">Crear Nuevo Proyecto</a>


    <a href="views/myProjects.php" class="btnNeutral">Mis Proyectos</a>
    <br>
    <hr>
    <br>



    <?php

    //require '../models/conection.php';        

    $sql = "SELECT p.id AS idP, p.titulo, p.descripcion, c.descripcion AS categorias, u.nombres, u.apellidos 
                    FROM proyectos p 
                    LEFT JOIN usuarios u ON p.usuarios_id = u.id
                    INNER JOIN categorias c ON p.categorias_id = c.id
                    ORDER BY p.id DESC";

    $result = mysqli_query($con, $sql);
    ?>
    </table>

    <?php foreach ($result as $data) : ?>
        <div class="d-flex justify-content-center">
            <div class="card " style="width:95%; margin-bottom: 20px;">
                <div class="card-body">

                    <h4 class="card-title"><?php echo $data["titulo"]; ?></h4>
                    <p class="card-text"><?php echo $data["descripcion"]; ?></p>
                    <p class="card-text"><?php echo $data["categorias"]; ?></p>

                    <div class="row">
                        <button type="button" class="btn btn-warning ml-3 mr-4 ">Me Gusta</button>
                        <button type="button" class="btn btn-info">Agregar a Favoritos</button>
                    </div>
                    <br>
                    <div class="card-footer">
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <input type="hidden" name="idU" id="" value="1">
                            <input type="hidden" name="idP" id="record-<?php echo $data["idP"]; ?>" value="<?php echo $data["idP"]; ?>">
                            <div class="form-group">
                                <input type="text" class="form-control enviar-btn" placeholder="Escribe un comentario" name="comentario" id="comentario-<?php echo $data["idP"]; ?>" required>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Comentar</button>
                        </form>
                        <br>
                        <div class="card-footer">
                            <label><strong>Comentarios:</strong></label>
                            <?php
                            $sql = "SELECT * 
                                    FROM detalle 
                                     WHERE proyectos_id = 33
                                    ORDER BY id DESC";
                            $com = mysqli_query($con, $sql);
                            foreach ($com as $data) :
                            ?>
                                <div>
                                    <p><?php echo $data['comentario'] ?></p>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    <?php endforeach ?>

</div>


<?php
require 'models/comments.php';

if (isset($_POST['submit'])) {
    $idP = $_POST['idP'];
    $idU = $_POST['idU'];
    $comentario = $_POST['comentario'];
    $created = date("Y-m-d H:i:s");
    $modified = date("Y-m-d H:i:s");

    $comment = new comments();
    $comment->comment($idP, $idU, $comentario, $created, $modified);
}
?>


<script>
    // PARA EVITAR REENVIO DE FORMULARIO
    if (window.history.replaceState) { // verificamos disponibilidad
        window.history.replaceState(null, null, window.location.href);
    }

    $(document).ready(function() {

        $(".enviar-btn").keypress(function(event) {

            if (event.which == 13) {

                var getpID = $(this).parent().attr('id').replace('record-', '');

                var comentario = $("#comentario-" + getpID).val();
                var publicacion = getpID;

                var dataString = '&comentario=' + comentario + '&publicacion=' + publicacion;

                $.ajax({
                    type: "POST",
                    url: "../models/commentsAdd.php",
                    data: dataString,
                    success: function() {
                        $('#nuevocomentario' + getpID).append('<div class="box-comment"><div class="comment-text">' + comentario + '</div></div>');
                    }
                });
                return false;
            }
        });

    });
</script>