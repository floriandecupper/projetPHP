<?php
class Message_controller{
 
 function __construct(){
    if(F3::get('SESSION.user_id')==NULL) {
        F3::reroute('/connexion'); 
    }
    $App=new App();
    $user=$App->getUser(F3::get('SESSION.user_id'));
        $msgNonLus=$App->gets('pu_message','id_membre2=? AND lu=?', array($user->id,0));
    F3::set('msgNonLus',count($msgNonLus));
    F3::set('user',$user);
    F3::set('page_title','Messagerie');
 }
 
 function home(){
    $App=new App();
    $messages=$App->gets('pu_message', 'id_membre2=?',array(F3::get('user')->id), array('limit'=>'0,10', 'order'=>'date DESC'));
    F3::set('messages',$messages);
    $arrayOfPseudos=array();
    foreach($messages as $message) {
        $user0=$App->getUser($message->id_membre1);
        array_push($arrayOfPseudos,transformToPseudo($user0->prenom,$user0->nom));
    }
    F3::set('pseudos',$arrayOfPseudos);
    echo Views::instance()->render('message/home.html');
 }
  function read(){
    $App=new App();
    $message=$App->get(F3::get('PARAMS.idmessage'), 'pu_message');
    $App->readMessage(F3::get('PARAMS.idmessage'));
    F3::set('message',$message);
    $user0=$App->getUser($message->id_membre1);
        $msgNonLus=$App->gets('pu_message','id_membre2=? AND lu=?', array(F3::get('user')->id,0));
    F3::set('msgNonLus',count($msgNonLus));
    F3::set('user0',$user0);
    echo Views::instance()->render('message/read.html');
 }
 function send() {

    //Envoyer un message + mail au recepteur
    echo Views::instance()->render('member/show.html');
 }
 function erase() {
    //Effacer le message s'il existe et que c'est bien l'utilisateur qui a ce msg
 	$App=new App();
    $message=$App->get(F3::get('POST.idmessage'), 'pu_message');
    if($message!=FALSE) {
        $App->erase(F3::get('POST.idmessage'), 'pu_message');
    }
    // $message=$App->eraseMessage(F3::get('PARAMS.idmembre'), 'pu_message');
    F3::set('page_title',$user0->prenom);
    F3::set('user0',$user0);
    echo Views::instance()->render('member/show.html');
 }
 
 function __destruct(){

 } 
}
?>