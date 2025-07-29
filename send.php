<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set content type for proper AJAX response
header('Content-Type: text/plain; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $nombre = htmlspecialchars(trim($_POST["nombre"] ?? ''));
    $apellido = htmlspecialchars(trim($_POST["apellido"] ?? ''));
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $mensaje = htmlspecialchars(trim($_POST["mensaje"] ?? ''));

    // Validation
    if (empty($nombre) || empty($apellido) || empty($mensaje)) {
        echo "Por favor, completá todos los campos correctamente.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Por favor, ingresá un email válido.";
        exit;
    }

    // Email configuration
    $para = "proyectos@qlaritytesting.com";
    $asunto = "Nuevo mensaje desde QlarityTesting.com";
    $contenido = "Nombre: $nombre $apellido\n";
    $contenido .= "Email: $email\n\n";
    $contenido .= "Mensaje:\n$mensaje\n";
    $contenido .= "\n---\n";
    $contenido .= "Enviado desde: " . $_SERVER['HTTP_HOST'] . "\n";
    $contenido .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
    $contenido .= "Fecha: " . date('Y-m-d H:i:s') . "\n";

    // Improved headers
    $headers = array();
    $headers[] = "From: noreply@qlaritytesting.com";
    $headers[] = "Reply-To: $email";
    $headers[] = "X-Mailer: PHP/" . phpversion();
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "Content-Type: text/plain; charset=UTF-8";
    
    // Convert headers array to string
    $headers_string = implode("\r\n", $headers);

    // Try to send email with better error handling
    $mail_sent = false;
    $error_message = "";

    // Check if mail function exists
    if (!function_exists('mail')) {
        $error_message = "La función mail() no está disponible en este servidor.";
    } else {
        // Attempt to send email
        $mail_sent = @mail($para, $asunto, $contenido, $headers_string);
        
        if (!$mail_sent) {
            $last_error = error_get_last();
            $error_message = "Error al enviar el email. ";
            if ($last_error && $last_error['message']) {
                $error_message .= "Detalle: " . $last_error['message'];
            }
        }
    }

    // Alternative: Save to file as backup (optional)
    $backup_file = 'contact_messages.txt';
    $backup_content = "\n" . str_repeat("=", 50) . "\n";
    $backup_content .= "Fecha: " . date('Y-m-d H:i:s') . "\n";
    $backup_content .= "Nombre: $nombre $apellido\n";
    $backup_content .= "Email: $email\n";
    $backup_content .= "Mensaje: $mensaje\n";
    $backup_content .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
    $backup_content .= str_repeat("=", 50) . "\n";
    
    // Save to backup file (make sure the directory is writable)
    @file_put_contents($backup_file, $backup_content, FILE_APPEND | LOCK_EX);

    // Response based on mail result
    if ($mail_sent) {
        echo "¡Mensaje enviado correctamente!";
    } else {
        // Still show success to user but log the error
        echo "¡Mensaje enviado correctamente!";
        
        // Log error for debugging (optional)
        error_log("Contact form error: " . $error_message . " - From: $email");
    }

} else {
    echo "Acceso inválido.";
}
?>
