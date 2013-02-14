<?php
class Member_controller{
 
 function __construct(){
    if(F3::get('SESSION.user')==NULL) {
        F3::reroute('/connexion'); 
    }
    F3::set('page_title','Espace Membre');
 }
 
 function home(){

    F3::set('user', F3::get('SESSION.user'));
    echo Views::instance()->render('member/home.html');
 }
 function show() {

 	$App=new App();
    $user0=$App->show(F3::get('PARAMS.idmembre'), 'pu_membre');
    $user=new User($user0->id,$user0->nom,$user0->prenom,$user0->points,$user0->mail, $user0->description, $user0->tags, $user0->date, $user0->ip, $user0->id_parrain);
    F3::set('page_title',$user->prenom());
    F3::set('user',$user);
    echo Views::instance()->render('header.html');
    echo Views::instance()->render('member/show.html');
 }
 
 function __destruct(){

 } 
}
?>