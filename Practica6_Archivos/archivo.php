<?php

require_once "config.php";

$nombre = filter_input(INPUT_GET, "nombre");
if (!$nombre) {
    http_response_code(400);
    exit();
}

$rutaArchivo = DIR_UPLOAD . $nombre;
if (!file_exists($rutaArchivo)) {
    http_response_code(404);
    exit();
}

$tamaño = filesize($rutaArchivo);
$extension = strtolower(pathinfo($rutaArchivo, PATHINFO_EXTENSION));

$contentType = array_key_exists($extension, $CONTENT_TYPES_EXT) ? 
    $CONTENT_TYPES_EXT[$extension] : $CONTENT_TYPES_EXT["bin"];

header("Content-Type: $contentType");
header("Content-Disposition: inline; filename=\"$nombre\"");
header("Content-Length: $tamaño");

readfile($rutaArchivo);
