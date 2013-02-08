<?php
class Signup_controller{
 
 function __construct(){
    F3::set('page_title','Inscription');
 }
 
 function home(){
    echo Views::instance()->render('signup/home.html');
 }
 
  
  function submit()
  {
    $erreur='';
    $regex='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';  
    F3::set('form_mail',F3::get('POST.mail'));
    F3::set('form_prenom',F3::get('POST.prenom'));
    F3::set('form_nom',F3::get('POST.nom'));
    F3::set('form_description',F3::get('POST.description'));
    if(!preg_match($regex,F3::get('POST.mail'))) // Vérifie si l'email n'est pas valide
    {
        F3::set('erreur',"Erreur : L'adresse E-Mail n'est pas valide.");
        echo Views::instance()->render('signup/home.html');
    }elseif(strlen(F3::get('POST.prenom'))<2) 
    {
        F3::set('erreur',"Erreur : Votre prénom doit contenir au moins 2 caractères.");
        echo Views::instance()->render('signup/home.html');
    }elseif(strlen(F3::get('POST.nom'))<2) 
    {
        F3::set('erreur',"Erreur : Votre nom doit contenir au moins 2 caractères.");
        echo Views::instance()->render('signup/home.html');
    }elseif(F3::get('POST.password1')!=F3::get('POST.password2')) 
    {
        F3::set('erreur',"Erreur : Les deux mots de passe ne correspondent pas.");
        echo Views::instance()->render('signup/home.html');
    }else
    {
        $App=new App();
        $user0=$App->addUser(F3::get('POST.mail'), F3::get('POST.password1'), F3::get('POST.prenom'), F3::get('POST.nom'), F3::get('POST.description'));
        $user=new User($user0->nom,$user0->prenom,$user0->points,$user0->mail);
        F3::set('SESSION.user',$user);
        F3::set('user',$user);
        echo Views::instance()->render('signup/submit.html');
    }
  }
 
 function __destruct(){

 } 
}
?>