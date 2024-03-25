<?php

$USERS = [
    [
        "id" => 1,
        "username" => "admin",  // contraseña en texto claro => Admin1234
        "nombre" => "Administrador",
        "passwordEncrypted" => "17D94FA6D235D3B338FC3A696C64A186852ADEDF8A0BE87D1F62227C1192EEB345B717F228278100D690FFFEC5E48D15809E8829F6CAFD3325F3AB09E72248B0",
        "passwordSalt" => "8362C1F926583CAC4A8C617AA1D33CE23F8652C1E5406443D31D0C41808F3835",
        "esAdmin" => true
    ],
    [
        "id" => 2,
        "username" => "user01",  // contraseña en texto claro => user01
        "nombre" => "Usuario 01",
        "passwordEncrypted" => "406D7E2E07B2BBEA0F931B93897600ED4C4D41DFEE46EC02E7316C9CE0E5D82DBA14160AB296A243B6BD2FE2A0179367B45A68B40869172864C996F65FB4D4D0",
        "passwordSalt" => "BCBEFB53EC209EB5F557347C970D87C649E90F9591EA4441A1B378F5A75EBA2C",
        "esAdmin" => false
    ]
];

/**
 * Función para autenticar al usuario por su nombre de usuario y contraseña.
 * Si la autenticación falla, devuelve false; si es exitosa, devuelve un array asociativo con los datos del usuario.
 */
function autenticar($username, $password) {

    // Si no se proporcionan los parámetros, devuelve false.
    if (!$username || !$password) {
        return false;
    }

    global $USERS;
    $user = NULL;

    // Busca al usuario por su nombre de usuario.
    foreach ($USERS as $u) {
        if ($username == $u["username"]) {
            $user = $u;
            break;
        }
    }

    // Si el usuario no se encuentra, devuelve false.
    if (!$user) {
        return false;
    }

    // Calcula el hash del password junto con el salt.
    $passwordMasSalt = $password . $user["passwordSalt"];
    $passwordEncriptado = strtoupper(hash("sha512", $passwordMasSalt));

    // Compara el hash del password proporcionado con el hash almacenado.
    if ($passwordEncriptado != $user["passwordEncrypted"]) {
        return false;
    }

    // Autenticación exitosa, devuelve los datos del usuario.
    return [
        "id" => $user["id"],
        "username" => $user["username"],
        "nombre" => $user["nombre"],
        "esAdmin" => $user["esAdmin"]
    ];
}
?>
