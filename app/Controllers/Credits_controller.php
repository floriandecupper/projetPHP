<?php
class Credits_controller{
 
 function __construct(){
    if(F3::get('SESSION.user_id')==NULL) {
        F3::reroute('/connexion'); 
    }
    F3::set('page_title','Crédits');
    $App=new App();
    $user=$App->getUser(F3::get('SESSION.user_id'));
    $msgNonLus=$App->gets('pu_message','id_membre2=? AND lu=?', array($user->id,0));
    F3::set('msgNonLus',count($msgNonLus));
    F3::set('user', $user);
 }
 
 function home(){
 	echo Views::instance()->render('member/credits.html');
 }
 function add(){
 	

 }
 function __destruct(){

 } 
}
?>
