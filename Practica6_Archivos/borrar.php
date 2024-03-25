<?php
if (isset($_GET['archivo'])) {
    $archivo = $_GET['archivo'];
    
    $directorio = "uploads/";

    $rutaArchivo = $directorio . $archivo;
    if (file_exists($rutaArchivo)) {
        unlink($rutaArchivo); // Eliminar el archivo
        echo "El archivo '" . $archivo . "' ha sido eliminado correctamente.";
    } else {
        echo "El archivo '" . $archivo . "' no existe.";
    }
} else {
    echo "No se proporcionó un nombre de archivo.";
}
?>
