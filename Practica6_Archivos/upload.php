<?php
require_once "login_helper.php";
session_start();

if (!isset($_SESSION['user']) || !$_SESSION['user']['esAdmin']) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["archivo"])) {
    $targetDir = "uploads/";
    $nombre_archivo = isset($_POST['nombre_archivo']) ? $_POST['nombre_archivo'] : ''; 
    $targetFile = $targetDir . ($nombre_archivo !== '' ? $nombre_archivo . '.' . pathinfo($_FILES["archivo"]["name"], PATHINFO_EXTENSION) : basename($_FILES["archivo"]["name"]));
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if(!in_array($fileType, array("jpg", "jpeg", "png", "gif", "pdf"))) {
        echo "Solo se permiten archivos de imagen (JPG, JPEG, PNG, GIF) y archivos PDF.";
        $uploadOk = 0;
    }

    if (file_exists($targetFile)) {
        echo "El archivo ya existe.";
        $uploadOk = 0;
    }

    $maxFileSize = 5000000; 
    if ($_FILES["archivo"]["size"] > $maxFileSize) {
        echo "El archivo es demasiado grande.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $targetFile)) {
            echo "El archivo " . htmlspecialchars(basename($targetFile)) . " ha sido subido correctamente.";
            echo "<script>actualizarListaArchivos();</script>";
        } else {
            echo "Hubo un error al subir el archivo.";
        }
    }
}
?>
