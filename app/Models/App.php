<?php
class App extends Prefab{
  
  function __construct(){
      
  }
  // function locationDetails($id=null){
  //   //return F3::get('dB')->exec('select * from location limit 1');
  //   $location=new DB\SQL\Mapper(F3::get('dB'),'pu_annonce');
  //   if(!$id){
  //     return $location->load();
  //   }
  //   return $location->load(array('id=?',$id));
  // }
  
  // function locationPictures($idLocation){
  //   //F3::get('dB')->exec('select * from pictures where idLocation='.$idLocation);
  //   $pictures=new DB\SQL\Mapper(F3::get('dB'),'pu_membre');
  //   return $pictures->find(array('idLocation=?',$idLocation));
  // }
  
  // function next($id){  
  //   return F3::get('dB')->exec("select id, titre from pu_annonce where id =(select min(id) from pu_annonce where id > ".$id.")");
  // }
  
  // function prev($id){
  //   return F3::get('dB')->exec("select id, titre from pu_annonce where id =(select max(id) from pu_annonce where id < ".$id.")");
  // }
  
  function addMember($pseudo, $password, $mail) {

  }
  
  function __destruct(){

  }
}
?>