<?php
class Account_controller{
 
 function __construct(){
    if(F3::get('SESSION.user_id')==NULL) {
        F3::reroute('/connexion'); 
    }
    F3::set('page_title','Mon Compte');
 }
 
 function home(){
 	$App=new App();
    $user=$App->getUser(F3::get('SESSION.user_id'));
    F3::set('user', $user);
    echo Views::instance()->render('member/mon-compte.html');
 }
 
 function __destruct(){

 } 
}
?>