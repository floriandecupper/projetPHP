<?php
class App extends Prefab{
  
  function __construct(){
      
  }
  function addUser($mail, $password, $prenom, $nom, $description) 
  {
    $user=new DB\SQL\Mapper(F3::get('dB'),'pu_membre');
    $user->nom=$nom;
    $user->prenom=$prenom;
    $user->password=md5($password);
    $user->mail=$mail;
    $user->description=$description;
    $user->points=F3::get('start_points');
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
  function __destruct(){

  }
}
?>