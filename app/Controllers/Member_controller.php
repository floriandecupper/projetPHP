<?php
class Member_controller{
 
 function __construct(){
    if(F3::get('SESSION.user_id')==NULL) {
        F3::reroute('/connexion'); 
    }
    $App=new App();
    $user=$App->getUser(F3::get('SESSION.user_id'));
    $msgNonLus=$App->gets('pu_message','id_membre2=? AND lu=?', array($user->id,0));
    F3::set('msgNonLus',count($msgNonLus));
    F3::set('user',$user);
    F3::set('page_title','Espace Membre');
 }
 
 function home(){
    echo Views::instance()->render('member/home.html');
 }
  function monCompte(){
    F3::set('page_title','Mon Compte');
    echo Views::instance()->render('member/mon-compte.html');
 }
 function monCompte_submit() {
    $regex='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
    $mail=F3::get('POST.mail');
    $password=F3::get('POST.password1');
    if(!preg_match($regex,$mail)) // Vérifie si l'email n'est pas valide
    {
        F3::set('erreur',"Erreur : L'adresse E-Mail n'est pas valide.");
        $mail=F3::get('user')->mail;
    }
    if(($password!=F3::get('POST.password2'))) 
    {
        F3::set('erreur',"Erreur : Les deux mots de passe ne correspondent pas.");
        $password=F3::get('user')->password;
    }
    if($password=='') {
        $password=F3::get('user')->password;
    }
        $App=new App();
        $user=$App->changeUser(F3::get('user')->id, $mail, $password, F3::get('POST.description'));
        F3::set('page_title','Mon Compte');
        F3::set('user0',$user);
        echo Views::instance()->render('member/show.html');
 }
 function show() {

 	$App=new App();
    $user0=$App->show(F3::get('PARAMS.idmembre'), 'pu_membre');
    F3::set('page_title',$user0->prenom);
    F3::set('user0',$user0);
    echo Views::instance()->render('member/show.html');
 }
 
 function __destruct(){

 } 
}
?>