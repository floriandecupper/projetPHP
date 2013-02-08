<?php
class Connexion_controller{
 
 function __construct(){
    F3::set('page_title','Connexion');
 }
 
 function home(){
    if(F3::get('SESSION.user')!=NULL) { 
        F3::reroute('/membre'); 
    }
    echo Views::instance()->render('connexion/home.html');
 }
 
  
  function submit(){
    $erreur='';
    $App=new App();
    $user0=$App->connexion(F3::get('POST.mail'), md5(F3::get('POST.password')));
    if(!$user0) 
    {
        F3::set('erreur',"Erreur d'identification : Impossible de vous connecter.");
        echo Views::instance()->render('connexion/home.html');
    }else 
    {
        $user=new User($user0->nom,$user0->prenom,$user0->points,$user0->mail);
        F3::set('SESSION.user',$user);
        F3::set('user',$user);
        F3::reroute('/membre');
    }
  }
 
 function __destruct(){

 } 
}
?>