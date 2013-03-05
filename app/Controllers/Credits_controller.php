<?php
class Credits_controller{
 
function beforeroute(){
    if(!F3::get('SESSION.user_id')) {
        F3::reroute('/connexion'); 
    }
    F3::set('page_title','Crédits');
    $App=new App();
    $user=$App->get(F3::get('SESSION.user_id'), 'pu_membre');
    $msgNonLus=$App->mget('pu_message','id_membre2=? AND lu=?', array($user->id,0));
    F3::mset(array(
        'msgNonLus'=>count($msgNonLus),
        'user'=>$user,
        'administrateur'=>$user->administrateur
    ));
 }
 
 function home(){
 	echo Views::instance()->render('member/credits.html');
 }
 function add(){
 	

 }
 function paypal() {
 	$contenu = "Lancement de fonction";
    $mail=new Mail("sebastien.carbain@gmail.com", F3::get('site_mail'), '', 'Test', $contenu);
    $mail->send();
 	$paypal_email = 'seb.sl_1361215186_biz@gmail.com';
	$return_url = F3::get('site_url').'paypal/return';
	$cancel_url = F3::get('site_url').'paypal/cancel';
	$notify_url = F3::get('site_url').'paypal/notification';
    // Response from Paypal

    // read the post from PayPal system and add 'cmd'
    $req = 'cmd=_notify-validate';
    foreach ($_POST as $key => $value) {
        $value = urlencode(stripslashes($value));
        $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
        $req .= "&$key=$value";
    }
	$contenu = $req;
    $mail=new Mail("sebastien.carbain@gmail.com", F3::get('site_mail'), '', 'Test', $contenu);
    $mail->send();
    // assign posted variables to local variables
    $data['item_name']          = F3::get('POST.item_name');
    $data['item_number']        = F3::get('POST.item_number');
    $data['payment_status']     = F3::get('POST.payment_status');
    $data['payment_amount']     = F3::get('POST.mc_gross');
    $data['payment_currency']   = F3::get('POST.mc_currency');
    $data['txn_id']             = F3::get('POST.txn_id');
    $data['receiver_email']     = F3::get('POST.receiver_email');
    $data['payer_email']        = F3::get('POST.payer_email');
    $data['custom']             = F3::get('POST.custom');

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
            		$contenu = "Paiement OK";
    $mail=new Mail("sebastien.carbain@gmail.com", F3::get('site_mail'), '', 'Test', $contenu);
    $mail->send();
                    $App=new App();
                    $paiement=$App->add(array(
                        'id_membre'=>$data['custom'],
                        'montant'=>$data['payment_amount']
                        ),'pu_achat');
                    $user=$App->get($data['custom'],'pu_membre');
                    $App->set($user->id, array('points'=>$user->points+10), 'pu_membre');
                
            }else if (strcmp ($res, "INVALID") == 0) {
                    $contenu = "Payment Invalid";
                    $mail=new Mail("sebastien.carbain@gmail.com", F3::get('site_mail'), '', 'Test', $contenu);
                    $mail->send();
            }
        }

    fclose ($fp);
    }
 }
}
?>
