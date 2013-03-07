<?php
class Credits_controller{
 
function beforeroute(){
    if(!F3::get('SESSION.user_id')) {
        F3::reroute('/connexion'); 
    }
    F3::set('page_title','Crédits');
    $App=new App();
    $user=$App->get(F3::get('SESSION.user_id'), 'pu_membre');
    $msgNonLus=$App->mget('pu_message','id_membre2=? AND lu=?', array($user->id,0));
    F3::mset(array(
        'msgNonLus'=>count($msgNonLus),
        'user'=>$user,
        'administrateur'=>$user->administrateur
    ));
 }
 
 function home(){
 	echo Views::instance()->render('member/credits.html');
 }
 function add(){
 	

 }
}
?>
