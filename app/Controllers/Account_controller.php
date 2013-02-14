<?php
class Account_controller{
 
 function __construct(){
    if(F3::get('SESSION.user')==NULL) {
        F3::reroute('/connexion'); 
    }
    F3::set('page_title','Mon Compte');
 }
 
 function home(){
    F3::set('user', F3::get('SESSION.user'));
    echo Views::instance()->render('member/mon-compte.html');
 }
 
 function __destruct(){

 } 
}
?>