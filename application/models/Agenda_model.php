<?php defined('BASEPATH') OR exit('No direct script access allowed');
    require_once(APPPATH."third_party/email/class.phpmailer.php");
    require_once(APPPATH."third_party/email/class.smtp.php");

    class Agenda_model extends CI_Model {
        function __construct(){
            parent::__construct();
        }

        function enviar_email($email_receptor,$nombre_receptor, $mensaje) {
            $mail = new PHPMailer;
            try {
                $mail->isSMTP();
                $mail->SMTPDebug = 2; $mail->Host = 'hs1.ioh.network'; $mail->Port = 25; $mail->SMTPAuth = true;                     
                $mail->Username = "admin@corpofactperu-temporal.pe"; 
                $mail->Password = "d2Z24#xpG!E.";
                $mail->SMTPSecure = 'tls';

                $mail->setFrom($_SESSION["email"], "AGENDA - GRUPO LyL");
                $mail->addAddress($email_receptor, $nombre_receptor);
                $mail->Subject = "GRUPO LyL - Mesa de Partes Virtual";
                $mail->isHTML(true); $mail->CharSet = "utf-8";

                $mail->Body = $mensaje;

                $mail->smtpConnect = array(
                    'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                    )
                );

                $mail->SMTPSecure = false;
                $mail->SMTPAutoTLS = false;

                if(!$mail->send()){
                    $estado = 0;
                }else{
                    $estado = 1;
                }
            }catch(Exception $e) {
                $estado = 0;
            }
            
            return $estado;
        }


        // CORREO CUANDO EL CLIENTE ADJUNTA Y FINALIZA EL EVENTO
        function enviar_email_respuesta($email_receptor,$nombre_receptor, $mensaje, $nombrearchivo, $archivo) {
            $mail = new PHPMailer;
            try {
                $mail->isSMTP();
                $mail->SMTPDebug = 2; $mail->Host = 'hs1.ioh.network'; $mail->Port = 25; $mail->SMTPAuth = true;                     
                $mail->Username = "admin@corpofactperu-temporal.pe"; 
                $mail->Password = "d2Z24#xpG!E.";
                $mail->SMTPSecure = 'tls';

                $mail->setFrom($_SESSION["email"], "AGENDA - GRUPO LyL");
                $mail->addAddress($email_receptor, $nombre_receptor);
                $mail->Subject = "GRUPO LyL - Mesa de Partes Virtual";
                $mail->isHTML(true); $mail->CharSet = "utf-8";

                $mail->Body = $mensaje;

                $mail->addAttachment($archivo, $nombrearchivo);

                $mail->smtpConnect = array(
                    'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                    )
                );

                $mail->SMTPSecure = false;
                $mail->SMTPAutoTLS = false;

                if(!$mail->send()){
                    $estado = 0;
                }else{
                    $estado = 1;
                }
            }catch(Exception $e) {
                $estado = 0;
            }
            
            return $estado;
        }
    }
?>