<?php
class App extends Prefab{
  
  function __construct(){
      
  }
  //
  //Users
  //
  function get($id,$table) {
    //Renvoyer l'objet de la bd en fonction de l'id et de la table renvoyée
    //OU si n'existe pas, FALSE
    $objet=new DB\SQL\Mapper(F3::get('dB'),$table);


    $objet->load(array('id=?',$id));
    if(!$objet->dry()){
      return $objet;
    }else{
      return false;
    }
  }  
  function exec($sql) {
    echo $sql.'<br />';
    $db=F3::get('dB');
    return $db->exec($sql);
    
  }
  function set($id,$array,$table) {
    //Renvoyer l'objet de la bd en fonction de l'id et de la table renvoyée
    //OU si n'existe pas, FALSE
    $objet=new DB\SQL\Mapper(F3::get('dB'),$table);
    $objet->load(array('id=?',$id));
    if(!$objet->dry()){
      foreach($array as $id=>$value) 
      {
        $objet->$id=$value;
        $objet->update();
      }
      return $objet;
    }else{
      return false;
    }
  }
  function update($objet) {
    $objet->update();
  }
  function add($array,$table) {
    $objet=new DB\SQL\Mapper(F3::get('dB'),$table);
    foreach($array as $id=>$value) 
    {
      $objet->$id=$value;
    }
    if($table=='pu_notifications') {
      $objet->date=time();
    }elseif($table!='pu_tags' && $table!='pu_categorie') 
    {
      $objet->date=time();
      $objet->ip=F3::get('SERVER.REMOTE_ADDR');
    }
    $objet->save();
    return $objet;
  }
  function tags($tags){
    $tags = transformToTags($tags);
    $tags=explode(',',$tags);
    foreach($tags as $tag) {
      echo $tag;
      $isset=$this->mget('pu_tags','tag=?',array($tag), array());
      if(!$isset) {
        $this->add(array('tag'=>$tag),'pu_tags');
      }
    }
  }
  //   function addCredits($id, $amount) 
  // {

  //   $paiement=new DB\SQL\Mapper(F3::get('dB'),'pu_achat');
  //   $paiement->id_membre=$id;
  //   $paiement->montant=$amount;
  //   $paiement->date=time();
  //   $paiement->ip=F3::get('SERVER.REMOTE_ADDR');
  //   $paiement->save();
  //   $user=new DB\SQL\Mapper(F3::get('dB'),'pu_membre');
  //   $user->load(array('id=?',$id));
  //   if(!$user->dry()){
  //     $user->points+=10;
  //     $user->update();
  //   }
  //   return $paiement;
  // }
  
  //   function readMessage($id) {
    //   $message=new DB\SQL\Mapper(F3::get('dB'),'pu_message');
    //   $message->load(array('id=?',$id));
    //   $message->lu=1;
    //   $message->update();
  //   }
  function search($tag,$table) {
      $objet=new DB\SQL\Mapper(F3::get('dB'),$table);
      $result=$objet->select('id', 'tags LIKE "%'.$tag.'%"');
      return $result;
  }
  
  function mget($table,$query='id>?',$vals=array(0), $param=array('order'=>'date DESC','limit'=>'1000')) {
    // if($vars!=array()) {
      $conditions=$vals;
      array_unshift($conditions,$query);
      $pu_table=new DB\SQL\Mapper(F3::get('dB'),$table);
      $objets=$pu_table->find($conditions, $param);
      if(count($objets)==0) {
        return false;
      }
      return $objets;
    //Renvoyer l'objet de la bd en fonction de l'id et de la table renvoyée
    //OU si n'existe pas, FALSE
    
  }
    
  function erase($id,$table) {
    //efface l'objet de la bd en fonction de l'id et de la table renvoyée
    $objet=new DB\SQL\Mapper(F3::get('dB'),$table);
    $objet->load(array('id=?',$id));
    if(!$objet->dry()){
      $db=F3::get('dB');
      $db->begin();
      $db->exec('DELETE FROM '.$table.' WHERE id='.$id);
      $db->commit();
      return true;
    }else{
      return false;
    }
  }
  
  function connexion($mail, $password) 
  {

    $user=new DB\SQL\Mapper(F3::get('dB'),'pu_membre');
    $user->load(array('mail=? AND password=?',$mail, $password));
    $pu_connexion=new DB\SQL\Mapper(F3::get('dB'),'pu_connexion');
    $date=time() - 3600;
    
    $connexion0=$pu_connexion->find(array('mail=? AND date>? AND valid=?',$mail, $date, 0));
    if(count($connexion0)>4) {
      return 1;
    }else {
        $connexion=new DB\SQL\Mapper(F3::get('dB'),'pu_connexion');
        $connexion->mail=$mail;
        $connexion->date=time();
        $connexion->ip=$_SERVER['REMOTE_ADDR'];
      if(!$user->dry()){
        if($user->activation==1) {
          $connexion->valid=1;
          $connexion->save();
          return $user;
        }else{
          return 2;
        }
      }else{
        $connexion->valid=0;
        $connexion->save();
        return 0;
      }
    }
  }
    function oubli($mail) 
  {
    $user=new DB\SQL\Mapper(F3::get('dB'),'pu_membre');
    $user->load(array('mail=?',$mail));
    $chars = "abcdefghijkmnopqrstuvwxyz023456789";

    srand((double)microtime()*1000000);

    $i = 0;

    $pass = '' ;

    while ($i <= 7) {

        $num = rand() % 33;

        $tmp = substr($chars, $num, 1);

        $pass = $pass . $tmp;

        $i++;

    }

    $user->password=md5($pass);
    $user->update();
    $contenu = "<p>Bonjour ".$user->prenom.",<br /><br />Vous venez de faire la demande pour recevoir un nouveau mot de passe sur ".F3::get('site_nom').". <br /><br />Nouveau mot de passe : ".$pass."<br /><br />Cordialement,<br />L'équipe de ".F3::get('site_nom');
    $mail=new Mail($mail, F3::get('site_mail'), '', 'Réinitialisation de votre mot de passe sur '.F3::get('site_nom'), $contenu);
    $mail->send();
    return $user;
  }
  // function getUser($id) 
  // {
  //   $user=new DB\SQL\Mapper(F3::get('dB'),'pu_membre');
  //   $user->load(array('id=?',$id));
  //   if(!$user->dry()){
  //     return $user;
  //   }
  //   return false;
  // }
    function verification($id, $activation) 
  {
    $user=new DB\SQL\Mapper(F3::get('dB'),'pu_membre');
    $user->load(array('id=? AND activation=?',$id, $activation));
    if(!$user->dry()){
      $user->activation=1;
      $user->update();
      return true;
    }
    return false;
    
  }
  //
  //Annonces
  //
  // function getAnnounces($nbr1=0, $nbr2=10) {
  //   $db=new DB\SQL\Mapper(F3::get('dB'),'pu_annonce');
  //   $annonce=$db->find(array('etat=?',0),array('order'=>'date DESC','limit'=>''.$nbr1.','.$nbr2.''));
  //   return $annonce;
  // }
  function signaler($id, $type, $message, $id_membre=0) {
    $signalement=new DB\SQL\Mapper(F3::get('dB'),'pu_signalement');
    $signalement->id_annonce=$id;
    $signalement->id_membre=$id_membre;
    $signalement->message=$message;
    $signalement->ip=$_SERVER["REMOTE_ADDR"];
    $signalement->date=time();
    $signalement->save();
    return $signalement;
  }
  function __destruct(){

  }
}
?>