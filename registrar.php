<?php
if (isset($_POST['nombreUsuarioRegistro']) && isset($_POST['correoElectronico']) && isset($_POST['password'])) {
    $nameUser = $_POST['nombreUsuarioRegistro'];
    $email = $_POST['correoElectronico'];
    $password = $_POST['password'];
    $repeatPassword = $_POST['repeatPassword'];

    if (strlen($nameUser) <= 4 || strlen($email) <= 4 || strlen($password) <= 4 || $password != $repeatPassword) {
        header("Location: Index.php");
        exit(); // Terminamos la ejecución del script después de redireccionar
    } else {
        session_start();
        require "bbdd.php";
        $conexion = conectar("localhost", "root", "", "eventzoneg");
        $_SESSION['nameUser'] = $nameUser;
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;

        // VERIFICAMOS SI INTRODUJO UNA IMAGEN Y LA GUARDAMOS
        if (isset($_FILES['foto-perfil']) && !empty($_FILES['foto-perfil']['tmp_name'])) {
            $directorioGuardar = 'uploads/';
            $archivo = $_FILES['foto-perfil'];
            $nombreArchivo = $archivo['name'];
            $rutaImagen = $directorioGuardar . $nombreArchivo;

            // Guardar la imagen en la carpeta de uploads
            if (move_uploaded_file($archivo['tmp_name'], $rutaImagen)) {
                // INSERTAMOS EL USUARIO CON EL NOMBRE DE LA IMAGEN EN LA BASE DE DATOS
                RegistrarUsuario($conexion, $nameUser, $email, $password, $nombreArchivo);
                $_SESSION['foto-perfil'] = $nombreArchivo;
            } else {
                echo "Error al subir la imagen.";
            }
        } else {
            // Si no se subió ninguna imagen, insertamos el usuario con NULL en el campo de imagen
            RegistrarUsuario($conexion, $nameUser, $email, $password, NULL);
        }
        //---------------------------------------------------------
        header("Location:Index.php");
        exit();
    }
} else {
    echo '<p> No han llegado los datos correctamente.</p>';
}

/*
<?php
//echo '<p>'.$nameUser.'</p>';
//echo '<p>'.$email.'</p>';
//echo '<p>'.$password.'</p>';
//echo '<p>'.$repeatPassword.'</p>';
//echo '<img src="data:image/jpeg;base64,' . base64_encode($contenidoImagen) . '" />';
//-------------------------------------------------------------
session_start();
require "bbdd.php";
$conexion = conectar("localhost", "root", "", "eventzoneg");
$_SESSION['nameUser'] = $nameUser;
$_SESSION['email'] = $email;
$_SESSION['password'] = $password;
// VERIFICAMOS SI INTRODUJO UNA IMAGEN E INSERTAMOS EL USUARIO CON LA IMAGEN
if (isset($_FILES['foto-perfil']) && !empty($_FILES['foto-perfil']['tmp_name'])) {
    $archivo = $_FILES['foto-perfil'];
    $contenidoImagen = file_get_contents($archivo['tmp_name']);
    // Codificar la imagen en base64
    $imagenCodificada = base64_encode($contenidoImagen);

    // INSERTAMOS EL USUARIO CON LA IMAGEN -----------------------------------
    RegistrarUsuario($conexion, $nameUser, $email, $password, $imagenCodificada);
    $_SESSION['foto-perfil'] = $imagenCodificada;

    // SI NO INSERTO UNA IMAGEN INSERTAMOS EL USUARIO CON LA IMAGEN EN NULL
    } else {
    // INSERTAMOS EL USUARIO SIN LA IMAGEN -----------------------------------
        RegistrarUsuario($conexion, $nameUser, $email, $password, NULL);
    }

    //---------------------------------------------------------
        header("Location:Index.php");
        exit();
    }
} else {
    echo '<p> No han llegado los datos correctamente.</p>';
}
*/
?>