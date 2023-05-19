<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

Class Email {

    public $nombre;
    public $email;
    public $token;

    public function __construct($nombre, $email, $token)
    {
        $this->email = $email;          //  Asignamos el valor del atributo $email en el constructor
        $this->nombre = $nombre;        //  Asignamos el valor del atributo $nombre en el constructor
        $this->token = $token;          //  Asignamos el valor del atributo $token en el constructor
    }

    public function enviarConfirmacion() {

        // Crear el objeto de email
        $mail = new PHPMailer();                                    // Instanciamos un objeto, el email que vamos a enviar, que será de la clase PHPMailer
        $mail->isSMTP();                                            // Indicamos el protocolo de envío de emails que vamos a utilizar
        $mail->Host = 'sandbox.smtp.mailtrap.io';                   // Indicamos el Host o dominio que vamos a utilizar
        $mail->SMTPAuth = true;                                     // Indicamos que nos vamos a autenticar con usuario y password  
        $mail->Port = 2525;                                         // Indicamos el puerto al que nos vamos a conectar
        $mail->Username = '467259e2a8cc28';                         // Indicamos nuestra credencia de nombre de usuario
        $mail->Password = '139977d3ad0899';                         // Indicamos nuestra credencial de constraseña

        $mail->setFrom('cuentas@appsalon.com');                     // Con setFrom() indicamos quien envia el email
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');  // Con addAddress() indicamos el destinatario del email (dirección de email, Dominio desde el que se envia)
        $mail->Subject = 'Confirma tu cuenta';                      // Es lo primero que el usuario va a leer cuando le llegue un email a su inbox

        // Set HTML
        $mail->isHTML(TRUE);                                        // Indicamos que vamos a utilizar HTML. Necesario para poder agregar el html en nuestro email.
        $mail->CharSet = 'UTF-8';                                   // Indicamos el tipo de caracteres que utilizaremos en nuestro HTML. Necesario para poder agregar el html en nuestro email

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta en App
        Salon, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;                                   // Inyectamos al email que enviaremos, en el atributo Body, que respresenta el cuerpo del mensaje, el html de nuestro
                                                                    // mensaje, el cual está en $contenido

        // Enviar el mail
        $mail->send();                                              // Metodo con el que enviamos el email
    }

    public function EnviarInstrucciones() {

        // Crear el objeto de email
        $mail = new PHPMailer();                                    // Instanciamos un objeto, el email que vamos a enviar, que será de la clase PHPMailer
        $mail->isSMTP();                                            // Indicamos el protocolo de envío de emails que vamos a utilizar
        $mail->Host = 'sandbox.smtp.mailtrap.io';                   // Indicamos el Host o dominio que vamos a utilizar
        $mail->SMTPAuth = true;                                     // Indicamos que nos vamos a autenticar con usuario y password  
        $mail->Port = 2525;                                         // Indicamos el puerto al que nos vamos a conectar
        $mail->Username = '467259e2a8cc28';                         // Indicamos nuestra credencia de nombre de usuario
        $mail->Password = '139977d3ad0899';                         // Indicamos nuestra credencial de constraseña

        $mail->setFrom('cuentas@appsalon.com');                     // Con setFrom() indicamos quien envia el email
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');  // Con addAddress() indicamos el destinatario del email (dirección de email, Dominio desde el que se envia)
        $mail->Subject = 'Restablece tu password';                  // Es lo primero que el usuario va a leer cuando le llegue un email a su inbox

        // Set HTML
        $mail->isHTML(TRUE);                                        // Indicamos que vamos a utilizar HTML. Necesario para poder agregar el html en nuestro email.
        $mail->CharSet = 'UTF-8';                                   // Indicamos el tipo de caracteres que utilizaremos en nuestro HTML. Necesario para poder agregar el html en nuestro email

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has solicitado
        reestablecer tu password, sigue el siguiente enlace para hacerlo</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/recuperar?token=" . $this->token . "'>Restablecer Password</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;                                   // Inyectamos al email que enviaremos, en el atributo Body, que respresenta el cuerpo del mensaje, el html de nuestro
                                                                    // mensaje, el cual está en $contenido

        // Enviar el mail
        $mail->send();                                              // Metodo con el que enviamos el email

    }
}