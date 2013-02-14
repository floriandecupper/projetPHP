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
        $user=new User($user0->id,$user0->nom,$user0->prenom,$user0->points,$user0->mail, $user0->description, $user0->tags, $user0->date, $user0->ip, $user0->id_parrain);
        F3::set('SESSION.user',$user);
        F3::set('user',$user);
        F3::reroute('/membre');
    }
  }

    function verification(){
        $App=new App();
        $test=$App->verification(F3::get('PARAMS.idmembre'), F3::get('PARAMS.activation'));
        if($test) {
            F3::set('erreur',"Votre compte a bien été validé.");
            echo Views::instance()->render('connexion/home.html');
        }else {
            F3::set('erreur',"Une erreur est survenue, votre compte n'a pas été validé.");
            echo Views::instance()->render('connexion/home.html');
        }
        
  }
 
 function __destruct(){

 } 
}
?>