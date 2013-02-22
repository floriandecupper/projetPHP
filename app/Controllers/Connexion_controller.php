<?php
class Connexion_controller{
 
 function __construct(){
    F3::set('page_title','Connexion');
    
 }
 
 function home(){
    if(F3::get('SESSION.user_id')!=NULL) { 
        F3::reroute('/membre'); 
    }
    echo Views::instance()->render('connexion/home.html');
 }
 
  
  function submit(){
    $erreur='';
    $App=new App();
    $result=$App->connexion(F3::get('POST.mail'), md5(F3::get('POST.password')));
    if(is_integer($result)) 
    {
        if($result==0){
            F3::set('erreur',"Erreur : Les identifiants sont incorrects.");
            echo Views::instance()->render('connexion/home.html');
        }elseif($result==1) 
        {
            F3::set('erreur',"Erreur : Vous avez dépassé le nombre de tentatives, merci de réessayer plus tard.");
            echo Views::instance()->render('connexion/home.html');
        }
    }else
    {
        $user0=$result;
        F3::set('SESSION.user_id',$user0->id);
        F3::set('SESSION.user_mail',$user0->mail);
        F3::set('user',$user0);
        F3::reroute('/membre');
    }
  }
 function oubli(){
    echo Views::instance()->render('connexion/oubli.html');
 }
   function oubli_submit(){
    $erreur='';
    $App=new App();
    $user0=$App->oubli(F3::get('POST.mail'));
    echo Views::instance()->render('connexion/oubli2.html');
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
 function deconnexion(){
    F3::clear( 'SESSION' );
    F3::reroute('/'); 
 }
 function __destruct(){

 } 
}
?>