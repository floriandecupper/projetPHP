<?php
class Admin_controller{
 
function beforeroute(){
    if(!F3::get('SESSION.user_id')) {
      F3::reroute('/connexion'); 
    }
      $App=new App();
      $user=$App->get(F3::get('SESSION.user_id'), 'pu_membre');
      if($user->administrateur==0) {
        F3::reroute('/'); 
      }

    
    F3::set('page_title','Administration');
 }
 
 function home(){
    $App=new App();

    $users=$App->mget('pu_membre');
    $annonces = $App->mget('pu_annonce');
    F3::set('annonces',$annonces);F3::set('users',$users);F3::set('user',$App->get(F3::get('SESSION.user_id'), 'pu_membre'));
    echo Views::instance()->render('admin/home.html');
 }
  function categorie()
  {
    $App=new App();
    if(F3::get('VERB')=='POST') 
    {
      if(F3::get('POST.categorie')) 
      {
        $categorie=$App->add(array('nom'=>F3::get('POST.categorie')),'pu_categorie');
        F3::set('info',"La catégorie ".$categorie->nom."a bien été ajouté.");
      }elseif(F3::get('POST.categories')) 
      {
        foreach(F3::get('POST.categories') as $categorie) {
          $App->erase($categorie,'pu_categorie');  
        }
      }
      F3::set('info',"Les catégories sélectionnées ont été supprimé");
    }
      
    $categories=$App->mget('pu_categorie', 'id>?', array(0), array('order'=>'nom ASC'));
    $nbrAnnonces=array();
    foreach($categories as $categorie) {
      $nbrAnnonces[]=count($App->mget('pu_annonce','categorie=?', array($categorie->nom)));
    }
    F3::mset(array(
      'categories'=>$categories,
      'user'=>$App->get(F3::get('SESSION.user_id'), 'pu_membre'),
      'nbrAnnonces'=>$nbrAnnonces
      ));
    echo Views::instance()->render('admin/categorie.html');
  }
  function annonce()
  {
    $App=new App();
    if(F3::get('VERB')=='POST') 
    {
      if(F3::get('POST.annonce')) 
      {
        echo F3::get('POST.annonce');
        $annonce=$App->get(F3::get('POST.annonce'), 'pu_annonce');
        if($annonce) {
          $annonce->copyFrom('POST');
          $App->update($annonce);
          F3::reroute('/annonce/'.$annonce->id); 
        }else{
          //ERREUR
        }
      }elseif(F3::get('POST.supprimer')) 
      {
        $annonce=$App->get(F3::get('POST.supprimer'), 'pu_annonce');
        if($annonce) {
          $App->erase($annonce->id,'pu_annonce');
        }
        // F3::set('info',"L'annonce ".$annonce->titre." a été supprimé");
        F3::reroute('/flux'); 
      }
      
    }
  }
  function membre()
  {
    $App=new App();
    if(F3::get('VERB')=='POST') 
    {
      if(F3::get('POST.membre')) 
      {
        $membre=$App->get(F3::get('POST.membre'), 'pu_membre');
        if($membre) {
          if(F3::get('POST.tags')!='') {
            $App->tags(F3::get('POST.tags'));
          }
          $membre->copyFrom('POST');
          $App->update($membre);
          
        }else{
          //ERREUR
        }
        F3::reroute('/membre/'.$membre->id); 
      }elseif(F3::get('POST.supprimer')) 
      {
        $membre=$App->get(F3::get('POST.supprimer'), 'pu_membre');
        if($membre) {
          $App->erase($membre->id,'pu_membre');
          //Envoyer un mail
        }
        F3::reroute('/membre/'.$membre->id); 
      }elseif(F3::get('POST.notification')) 
      {
        $notification=$App->add(array(
                    'id_membre'=>F3::get('POST.notification'),
                    'type'=>'avertissement',
                    'id_objet'=>0,
                    'lu'=>0
                ), 'pu_notifications');
      }
      F3::reroute('/membre/'.F3::get('POST.notification')); 
    }
  }

}
?>