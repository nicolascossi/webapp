<?php
// Conexión a la base de datos
try {
    $db = new PDO('sqlite:big6users.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error al conectarse a la base de datos: " . $e->getMessage());
}

// Función para agregar un usuario a la base de datos
function agregarUsuario($nombre, $email, $contraseña) {
    global $db;
    try {
        $stmt = $db->prepare("INSERT INTO usuarios (nombre, email, contraseña) VALUES (:nombre, :email, :contraseña)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':contraseña', $contraseña);
        $stmt->execute();
        return true;
    } catch(PDOException $e) {
        return false;
    }
}

// Función para verificar las credenciales del usuario durante el inicio de sesión
function iniciarSesion($email, $contraseña) {
    global $db;
    try {
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {
            return $usuario;
        } else {
            return null;
        }
    } catch(PDOException $e) {
        return null;
    }
}

// Ejemplo de uso para agregar un usuario
$nombre = "Juan";
$email = "juan@example.com";
$contraseña = password_hash("password123", PASSWORD_DEFAULT); // Guarda la contraseña de manera segura en la base de datos

if(agregarUsuario($nombre, $email, $contraseña)) {
    echo "Usuario registrado exitosamente.";
} else {
    echo "Error al registrar el usuario.";
}

// Ejemplo de uso para iniciar sesión
$email_login = "juan@example.com";
$contraseña_login = "password123";

$usuario = iniciarSesion($email_login, $contraseña_login);
if ($usuario) {
    echo "Inicio de sesión exitoso. Bienvenido, " . $usuario['nombre'];
} else {
    echo "Inicio de sesión fallido. Verifique su correo electrónico y contraseña.";
}

// Cerrar la conexión a la base de datos
$db = null;
?>



