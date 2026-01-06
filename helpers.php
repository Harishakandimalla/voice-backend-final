<?php
require_once 'config.php';
require_once 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function sendEmail($to,$name,$subject,$html){
  $mail=new PHPMailer(true);
  try{
    $mail->isSMTP();
    $mail->Host=SMTP_HOST; $mail->SMTPAuth=true;
    $mail->Username=SMTP_USER; $mail->Password=SMTP_PASS;
    $mail->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS; $mail->Port=SMTP_PORT;
    $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
    $mail->addAddress($to,$name);
    $mail->isHTML(true); $mail->Subject=$subject; $mail->Body=$html;
    $mail->send(); return true;
  }catch(Exception $e){ return false; }
}

function generateOTP($digits=6){ $min=(int)pow(10,$digits-1); $max=(int)(pow(10,$digits)-1); return (string)random_int($min,$max); }
function generateRandomToken($len=64){ return bin2hex(random_bytes((int)ceil($len/2))); }
function jwtCreate($payload,$expSeconds=3600){
  $time=time(); $token=['iat'=>$time,'iss'=>JWT_ISSUER,'exp'=>$time+$expSeconds,'data'=>$payload]; return JWT::encode($token, JWT_SECRET,'HS256');
}
function jwtVerifyFromHeader(){
  $headers=function_exists('apache_request_headers')?apache_request_headers():$_SERVER;
  $auth=$headers['Authorization']??$headers['authorization']??null;
  if(!$auth) return null;
  if(!preg_match('/Bearer\s(\S+)/',$auth,$m)) return null;
  try{ $decoded=JWT::decode($m[1], new Key(JWT_SECRET,'HS256')); return $decoded->data ?? null; }catch(Exception $e){ return null; }
}
function readInput(){ $input=$_POST; if(empty($input)){ $raw=file_get_contents('php://input'); $json=json_decode($raw,true); if(is_array($json)) $input=$json; } return $input; }
