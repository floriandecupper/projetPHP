<?php
class App_controller{
 
function beforeroute(){
    if(F3::get('SESSION.user_id')) {
      $App=new App();
      $user=$App->get(F3::get('SESSION.user_id'), 'pu_membre');
          $msgNonLus=$App->mget('pu_message','id_membre2=? AND lu=?', array($user->id,0));
    F3::set('msgNonLus',count($msgNonLus));
      F3::set('user', $user);
    }
    F3::set('page_title','Accueil');
 }
 
 function home(){
    $App=new App();

    F3::set('annonces',$App->mget('pu_annonce','etat=?',array('0'),array('order'=>'date DESC','limit'=>'0,100')));
    echo Views::instance()->render('accueil.html');
 }
 
  
  function doc(){
    echo Views::instance()->render('userref.html');
  }
 
}
?>