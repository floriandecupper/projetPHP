<?php
class App extends Prefab{
  
  function __construct(){
      
  }
  //
  //Users
  //
  function addUser($mail, $password, $prenom, $nom, $description) 
  {
    $user=new DB\SQL\Mapper(F3::get('dB'),'pu_membre');
    $user->nom=$nom;
    $user->prenom=$prenom;
    $user->password=md5($password);
    $user->mail=$mail;
    $user->description=$description;
    $user->points=F3::get('start_points');
    $user->activation=rand(1,9999999999);
    $user->save();
    return $user;
  }
  function connexion($mail, $password) 
  {
    $db=new DB\SQL\Mapper(F3::get('dB'),'pu_membre');
    $user=$db->load(array('mail=? AND password=?',$mail, $password));
    if($user!=NULL){
      return $user;
    }else{
      return false;
    }
  }
  function getUser($id) 
  {
    $db=new DB\SQL\Mapper(F3::get('dB'),'pu_membre');
    $user=$db->load(array('id=?',$id));
    if($user!=NULL){
      return $user;
    }else{
      return false;
    }
  }
    function verification($id, $activation) 
  {
    $db=new DB\SQL\Mapper(F3::get('dB'),'pu_membre');
    $user=$db->load(array('id=? AND activation=?',$id, $activation));

    if($user!=NULL){
      var_dump($user->activation);
      $user->activation=1;
      var_dump($user->activation);
      $user->update();
      var_dump($user->activation);
      return true;
    }else{
      return false;
    }
  }
  //
  //Annonces
  //
  function addAnnounce($titre, $description, $prix, $id_user) 
  {
    $annonce=new DB\SQL\Mapper(F3::get('dB'),'pu_annonce');
    $annonce->titre=$titre;
    $annonce->id_membre=$id_user;
    $annonce->description=nl2br($description);
    $annonce->prix=$prix;
    $annonce->etat=0;
    $annonce->date=time();
    $annonce->save();
    return $annonce;
  }
  function getAnnounces($nbr1=0, $nbr2=10) {
    $db=new DB\SQL\Mapper(F3::get('dB'),'pu_annonce');
    $annonce=$db->find(array('etat=?',0),array('order'=>'date DESC','limit'=>''.$nbr1.','.$nbr2.''));
    return $annonce;
  }
  function show($id,$table) {
    $db=new DB\SQL\Mapper(F3::get('dB'),$table);
    $annonce=$db->load(array('id=?',$id));
    return $annonce;
  }

  function __destruct(){

  }
}
?>