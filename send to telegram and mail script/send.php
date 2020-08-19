<?php
require_once('PHPMailer.php');

// $adminEmail = 'itop.web.dew.4@gmail.com';
$adminEmail = 'zakaz.myasovo@gmail.com';
$phone          = $_POST['phone'];
$phone2          = $_POST['phone2'];
$mess           = $_POST['message'];
$mess2           = $_POST['message2'];
$withask    = $_POST['footform'];
$name          = $_POST['name'];
$name2          = $_POST['name2'];
$order          = json_decode($_POST['order']);
$order2          = json_decode($_POST['order']);
$token = "1180006585:AAEp8_IRuAP-mLroEl3tHZY3W01jpqDjnMA";
$telegram_admin_id = "-1001150839966";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = new PHPMailer();
$email->CharSet = 'UTF-8';

foreach ($order2 as $products){
    $prodName  = htmlspecialchars($products -> name);
    $ms .= "%0A";
    $ms .= "Название: ".$products -> name."%0A";
    $ms .= "Цена: ".$products -> price." грн/кг %0A";
    $ms .= "Количество: ".$products -> count." кг %0A";
}

$arr = array(
    'Имя пользователя: ' => $name,
    'Телефон: ' => $phone,
    'Адрес доставки: ' => $mess,
    'Заказ: %0A' => $ms
  );

  foreach($arr as $key => $value) {
    $txt .= "<b>".$key."</b> ".$value."%0A";
  };

//   $sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$telegram_admin_id}&parse_mode=html&text={$txt}","r");

  if ($order2) {
    $sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$telegram_admin_id}&parse_mode=html&text={$txt}","r");
  }

try{

     $email->SetFrom('zakaz.myasovo@gmail.com', 'Myasovo');
//     $email->SetFrom('itop.web.dew.4@gmail.com', 'Myasovo');


    $email->AddAddress( $adminEmail );
    // $email->AddAddress( 'itop.web.dew.4@gmail.com' );
    $email->AddBCC('follow@itop.media');
    $email->isHTML(true);


    if ($withask) {
        $email->Subject   =  'Просят перезвонить!';
        $msg = <<<HERE
        Имя: {$name2}<br />
        Телефон: {$phone2}<br />
        Сообщение: {$mess2}<br />
HERE;

    } else {
        $email->Subject   =  'Новый заказ';
        $msg = <<<HERE
        Имя: {$name}<br />
        Телефон: {$phone}<br />
        Адрес доставки: {$mess}<br />
        Заказ: <br />
HERE;
        foreach ($order as $product){
            $msg .= "Название: ".$product -> name."<br>";
            $msg .= "Цена: ".$product -> price." грн/кг <br>";
            $msg .= "Количество: ".$product -> count." кг <br>";
            $msg .= '<hr>';
        }
    }

    $email->Body = $msg;

    $email->Send();

}catch(Exception $e){
    //echo "Message could not be sent. Mailer Error: {$email->ErrorInfo}";
}
echo json_encode(1);