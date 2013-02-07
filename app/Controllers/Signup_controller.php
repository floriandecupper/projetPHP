<?php
class Signup_controller{
 
 function __construct(){
  
 }
 
 function home(){
    $id=F3::get('PARAMS.id');
    #récupération de la location
    $App=new App();
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
    
    echo Views::instance()->render('signup/home.html');
 }
 
  
  function submit(){
    $erreur='';
    if(F3::get('POST.password1')!=F3::get('POST.password2')) 
    {
        F3::set('erreur',"Erreur : Les deux mots de passe ne correspondent pas.");
        echo Views::instance()->render('signup/home.html');
    }else 
    {
        F3::set('pseudo',F3::get('POST.pseudo'));
        echo Views::instance()->render('signup/submit.html');
    }
  }
 
 function __destruct(){

 } 
}
?>