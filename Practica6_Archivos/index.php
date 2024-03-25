<?php
require_once "login_helper.php";
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador de Archivos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h2>Bienvenido, <?php echo $user['nombre']; ?></h2>
    </header>
    <p>Lista de archivos</p>
    <table>
        <tr>
            <th>Nombre del Archivo</th>
            <th>Tamaño (KB)</th>
            <?php if ($user['esAdmin']): ?>
            <th>Borrar</th>
            <?php endif; ?>
        </tr>
        <?php   
            $directorio = "uploads/";
            
            if (is_dir($directorio)) {
                if ($gestor = opendir($directorio)) {
                    while (($archivo = readdir($gestor)) !== false) {
                        if ($archivo != "." && $archivo != "..") {
                            $rutaArchivo = $directorio . $archivo;
                            $tamañoArchivo = round(filesize($rutaArchivo) / 1024, 2); 
                            echo "<tr>";
                            echo "<td><a href='" . $directorio . $archivo . "' target='_blank'>" . $archivo . "</a></td>";
                            echo "<td>" . $tamañoArchivo . "</td>";
                            if ($user['esAdmin']) {
                                echo "<td><button onclick='borrarArchivo(\"" . $archivo . "\")'>Borrar</button></td>";
                            }
                            echo "</tr>";
                        }
                    }
                    closedir($gestor);
                }
            }

        ?>

    </table>
    <?php if ($user['esAdmin']): ?>
    <form id="uploadForm" enctype="multipart/form-data">
        <label for="nombre_archivo">Ingresa nombre del Archivo:</label>
        <input type="text" id="nombre_archivo" name="nombre_archivo">
        <input type="file" id="archivo" name="archivo">
        <button type="submit">Subir Archivo</button>
    </form>
    <div id="mensaje"></div>
    <?php endif; ?>

    <div class="cerrar_s">
        <form id="logoutForm" action="logout.php" method="post">
            <input type="submit" value="Cerrar Sesión" onclick="return confirmarCerrarSesion()">
        </form>
    </div>

    <script>
        function confirmarCerrarSesion() {
            return confirm("¿Estás seguro que deseas cerrar sesión?");
        }
    </script>

    <script>
    // SUBIR ARCHIVOS               AJAX
    document.getElementById('uploadForm').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'upload.php', true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                document.getElementById('mensaje').innerHTML = xhr.responseText;
                actualizarListaArchivos();
            } else {
                document.getElementById('mensaje').innerHTML = 'Error al subir el archivo.';
            }
        };
        xhr.send(formData);
    });

    function actualizarListaArchivos() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'index.php', true); 
        xhr.onload = function () {
            if (xhr.status === 200) {
                var tabla = document.createElement('div');
                tabla.innerHTML = xhr.responseText;
                var nuevaTabla = tabla.querySelector('table');
                document.querySelector('table').parentNode.replaceChild(nuevaTabla, document.querySelector('table'));
            } else {
                console.log('Error al actualizar la lista de archivos.');
            }
        };
        xhr.send();
    }

    function borrarArchivo(nombreArchivo) {
        if (confirm("¿Está seguro que desea borrar " + nombreArchivo + "?")) {
            var xhr = new XMLHttpRequest();
            
            xhr.onload = function () {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);
                    actualizarListaArchivos();
                } else {
                    console.error('Error al borrar el archivo.');
                }
            };

            xhr.open('GET', 'borrar.php?archivo=' + encodeURIComponent(nombreArchivo), true);
            xhr.send();
        }
    }

</script>

</body>
</html>
