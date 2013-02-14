<?php
class App_controller{
 
 function __construct(){
    if(F3::get('SESSION.user')!=NULL) {
       F3::set('user', F3::get('SESSION.user'));
    }
    F3::set('page_title','Accueil');
 }
 
 function home(){
    $App=new App();
    F3::set('annonces',$App->getAnnounces());
    echo Views::instance()->render('accueil.html');
 }
 
  
  function doc(){
    echo Views::instance()->render('userref.html');
  }
 
 function __destruct(){

 } 
}
?>