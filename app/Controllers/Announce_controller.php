<?php
class Announce_controller{
 
 function __construct(){
    if(F3::get('SESSION.user')==NULL) {
        F3::reroute('/connexion'); 
    }
    F3::set('page_title','Accueil');
 }
 
 function home(){
    F3::set('page_title','Annonces');
    $id=F3::get('PARAMS.id');
    #récupération de la location
    $App=new App();
    // $location=$App->locationDetails($id);
    F3::set('location','$location');
    
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
    
    echo Views::instance()->render('accueil.html');
 }
  function add(){
    F3::set('page_title','Créer une annonce');
    F3::set('user', F3::get('SESSION.user'));
    echo Views::instance()->render('annonces/new.html');
 }
function submit() {
    $App=new App();
    F3::set('form_titre',F3::get('POST.titre'));
    F3::set('form_prix',F3::get('POST.prix'));
    F3::set('form_description',F3::get('POST.description'));
    if(strlen(F3::get('POST.titre'))<2) 
    {
        F3::set('erreur',"Erreur : Le titre doit contenir au moins 2 caractères.");
        echo Views::instance()->render('annonces/new.html');
    }elseif(strlen(F3::get('POST.description'))<20) 
    {
        F3::set('erreur',"Erreur : La description doit contenir au moins 20 caractères.");
        echo Views::instance()->render('annonces/new.html');
    }elseif(!is_numeric(F3::get('POST.prix'))) 
    {
        F3::set('erreur',"Erreur : Le prix n'est pas correcte.");
        echo Views::instance()->render('annonces/new.html');
    }else{
        
        $user=F3::get('SESSION.user');
        $annonce=$App->addAnnounce(F3::get('POST.titre'), F3::get('POST.description'), F3::get('POST.prix'), $user->id());
        F3::reroute('/annonce/'.$annonce->id);
    }
}
function own() {
//afficher ses annonces
}  

function show() {
    $App=new App();
    $annonce=$App->show(F3::get('PARAMS.idannonce'), 'pu_annonce');
    F3::set('page_title',$annonce->titre);
    F3::set('annonce',$annonce);
    $user0=$App->getUser($annonce->id_membre);
    $user=new User($user0->id,$user0->nom,$user0->prenom,$user0->points,$user0->mail, $user0->description);
    F3::set('user',$user);
    echo Views::instance()->render('annonces/show.html');
}  
  function doc(){
    echo Views::instance()->render('userref.html');
  }
 
 function __destruct(){

 } 
}
?>