<?php
require('../lib/myLib/user.php');
require('../lib/myLib/mail.php');

require('../lib/myLib/paypal.php');
$f3=require('../lib/base.php');
require('../app/Models/App.php');

$f3->config('../config/globals.ini');
$f3->set('dB',new DB\SQL(
  'mysql:host='.F3::get('db_host').';port=3306;dbname='.F3::get('db_server'),
  F3::get('db_user'),
  F3::get('db_pw')));

// PayPal settings
$paypal_email = 'seb.sl_1361215186_biz@gmail.com';
$return_url = F3::get('site_url').'paypal/return';
$cancel_url = F3::get('site_url').'paypal/cancel';
$notify_url = F3::get('site_url').'paypal/notify.php';

$item_name = '10 Crédits';
$item_amount = 9.99;

// Include Functions

// Check if paypal request or response
if (isset($_POST["txn_id"]) && !isset($_POST["txn_type"])){
    // Response from Paypal

    // read the post from PayPal system and add 'cmd'
    $req = 'cmd=_notify-validate';
    foreach ($_POST as $key => $value) {
        $value = urlencode(stripslashes($value));
        $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
        $req .= "&$key=$value";
    }

    // assign posted variables to local variables
    $data['item_name']          = $_POST['item_name'];
    $data['item_number']        = $_POST['item_number'];
    $data['payment_status']     = $_POST['payment_status'];
    $data['payment_amount']     = $_POST['mc_gross'];
    $data['payment_currency']   = $_POST['mc_currency'];
    $data['txn_id']             = $_POST['txn_id'];
    $data['receiver_email']     = $_POST['receiver_email'];
    $data['payer_email']        = $_POST['payer_email'];
    $data['custom']             = $_POST['custom'];

    // post back to PayPal system to validate
    $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
    $header .= "Host: www.sandbox.paypal.com\r\n";
    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

    $fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
    if ($fp) {
        fputs ($fp, $header . $req);
        while (!feof($fp)) {
            $res = fgets ($fp, 1024);
            if (strcmp ($res, "VERIFIED") == 0) {
                    $App=new App();
                    $paiement=$App->addCredits($data['custom'],$data['payment_amount']);
                
            }else if (strcmp ($res, "INVALID") == 0) {
                    $contenu = "Payment Invalid";
                    $mail=new Mail("sebastien.carbain@gmail.com", F3::get('site_mail'), '', 'Test', $contenu);
                    $mail->send();
            }
        }

    fclose ($fp);
    }
}
$contenu = "Réponse FIN";
                     $mail=new Mail("sebastien.carbain@gmail.com", F3::get('site_mail'), '', 'Test', $contenu);
                     $mail->send();
?>