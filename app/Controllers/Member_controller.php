<?php
class Member_controller{
 
 function __construct(){
    if(F3::get('SESSION.user')==NULL) {
        F3::reroute('/connexion'); 
    }
    F3::set('page_title','Espace Membre');
 }
 
 function home(){
    // $id=F3::get('PARAMS.id');
    #récupération de la location
    // $App=new App();
    // $location=$App->locationDetails($id);
    
    // F3::set('position',Views::instance()->toJson($location,array('lat'=>'lat','lng'=>'lng')));
    
    
    #récupération des images de la location
    // $pictures=$App->locationPictures($location->id);
    // $json=Views::instance()->toJson($pictures,array('image'=>'src'));
    // F3::set('pictures',$json);
    
    // $next=$App->next($location->id);
    // $prev=$App->prev($location->id);
    

    // $p=$prev?$prev[0]['id'].'-'.$prev[0]['title']:'';
    // $n=$next?$next[0]['id'].'-'.$next[0]['title']:'';

    // F3::set('prev',$p);
    // F3::set('next',$n);
    F3::set('user', F3::get('SESSION.user'));
    echo Views::instance()->render('member/home.html');
 }
 
 
 function __destruct(){

 } 
}
?>