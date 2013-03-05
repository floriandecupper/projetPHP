<?php
require('lib/myLib/user.php');
require('lib/myLib/mail.php');

require('lib/myLib/paypal.php');
require('lib/myLib/functions.php');
$f3=require('lib/base.php');
require('app/Models/App.php');

$f3->config('config/globals.ini');
$f3->set('dB',new DB\SQL(
  'mysql:host='.F3::get('db_host').';port=3306;dbname='.F3::get('db_server'),
  F3::get('db_user'),
  F3::get('db_pw')));

$App=new App();
$reponse='';
if(F3::get('POST.id')) {
    $notifications=$App->mget('pu_notifications','id_membre=? AND lu=?',array(F3::get('POST.id'), 0),array('limit'=>'4', 'order'=>'date DESC'));
    foreach($notifications as $notification) {
        $notification=$App->set($notification->id,array('lu'=>'1'),'pu_notifications');
        if($notification->type=='proposition') {
            $proposition=$App->get($notification->id_objet,'pu_proposition');
            $annonce=$App->get($proposition->id_annonce,'pu_annonce');
            $user=$App->get($proposition->id_membre,'pu_membre');
            $reponse.=transformToText(F3::get('proposition_notification'),array(
            "<a href='".F3::get('site_url')."membre/".$user->id."'>".
            transformToPseudo($user->prenom,$user->nom).
            "</a>","<a href='".F3::get('site_url')."annonce/".$annonce->id."'>".
            $annonce->titre."</a><br /><hr /><br />"));

        }elseif($notification->type=='deal') {
            $annonce=$App->get($notification->id_objet,'pu_annonce');
            $proposition=$App->get($annonce->id_proposition,'pu_proposition');
            $user=$App->get($annonce->id_membre,'pu_membre');

            $reponse.=transformToText(F3::get('deal_notification'),array(
            "<a href='".F3::get('site_url')."membre/".$user->id."'>".
            transformToPseudo($user->prenom,$user->nom).
            "</a>","<a href='".F3::get('site_url')."annonce/".$annonce->id."'>".
            $annonce->titre."</a><br /><hr /><br />"));
        }elseif($notification->type=='avertissement') {

            $reponse.=F3::get('avertissement_notification')."<br /><hr /><br />";

        }elseif($notification->type=='valider') {
            $proposition=$App->get($notification->id_objet,'pu_proposition');
            $annonce=$App->get($proposition->id_annonce,'pu_annonce');
            $user=$App->get($proposition->id_membre,'pu_membre');

            $reponse.=transformToText(F3::get('valider_notification'),array(
                $proposition->prix,
                "<a href='".F3::get('site_url')."membre/".$user->id."'>".transformToPseudo($user->prenom,$user->nom)."</a>",
                "<a href='".F3::get('site_url')."annonce/".$annonce->id."'>".$annonce->titre."</a><br /><hr /><br />"
            ));
        }

    }
}elseif(F3::get('POST.nbrN')) {
    $notifications=$App->mget('pu_notifications','id_membre=? AND lu=?',array(F3::get('POST.nbrN'), 0),array('limit'=>'999'));
    if(!$notifications) {
        $reponse='0';
    }else
    {
        $reponse=count($notifications);
    }
}elseif(F3::get('POST.nbrM')) {
    $messages=$App->mget('pu_message','id_membre2=? AND lu=?',array(F3::get('POST.nbrM'), 0),array('limit'=>'999'));
    if(!$messages) {
        $reponse='0';
    }else{
        $reponse=count($messages);
    }
}
echo $reponse;
?>