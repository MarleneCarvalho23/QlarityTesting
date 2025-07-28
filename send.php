<?php
// Verifica que sea un POST real
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recibe los datos del formulario
    $nombre = htmlspecialchars(trim($_POST["nombre"]));
    $apellido = htmlspecialchars(trim($_POST["apellido"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $mensaje = htmlspecialchars(trim($_POST["mensaje"]));

    // Validación básica
    if (empty($nombre) || empty($apellido) || empty($email) || empty($mensaje)) {
        echo "ERROR: Faltan campos obligatorios.";
        exit;
    }

    // Destino
    $to = "proyectos@qlaritytesting.com";
    $subject = "Nuevo mensaje desde QlarityTesting.com";
    $body = "👤 Nombre: $nombre $apellido\n📧 Email: $email\n\n💬 Mensaje:\n$mensaje";

    // Cabeceras
    $headers = "From: QlarityTesting <no-reply@qlaritytesting.com>\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Envío
    if (mail($to, $subject, $body, $headers)) {
        echo "OK";
    } else {
        echo "ERROR: No se pudo enviar el mensaje.";
    }

} else {
    echo "ERROR: Acceso inválido.";
}
?>
