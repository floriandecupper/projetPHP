<?php
class Message_controller{
 
function beforeroute()
{
    if(!F3::get('SESSION.user_id')) {
        F3::reroute('/connexion'); 
    }
    $App=new App();
    $user=$App->get(F3::get('SESSION.user_id'),'pu_membre');
        $msgNonLus=$App->mget('pu_message','id_membre2=? AND lu=?', array($user->id,0));
    F3::set('msgNonLus',count($msgNonLus));
    F3::set('user',$user);
    F3::set('page_title','Messagerie');
 }
 
 function home()
 {
    $App=new App();
    $messages=$App->mget('pu_message', 'id_membre2=?',array(F3::get('user')->id), array('limit'=>'0,10', 'order'=>'date DESC'));
    F3::set('messages',$messages);
    $arrayOfPseudos=array();
    foreach($messages as $message) {
        $user0=$App->get($message->id_membre1,'pu_membre');
        array_push($arrayOfPseudos,transformToPseudo($user0->prenom,$user0->nom));
    }
    F3::set('pseudos',$arrayOfPseudos);
    echo Views::instance()->render('message/home.html');
 }
  function read(){
    $App=new App();
    $message=$App->get(F3::get('PARAMS.idmessage'), 'pu_message');
    if(F3::get('user')->id==$message->id_membre2) {
        $App->readMessage(F3::get('PARAMS.idmessage'));
    }
    $expediteur=$App->get($message->id_membre1, 'pu_membre');
    $destinataire=$App->get($message->id_membre2, 'pu_membre');
    $msgNonLus=$App->mget('pu_message','id_membre2=? AND lu=?', array(F3::get('user')->id,0));
    F3::mset(array(
        'message'=>$message,
        'msgNonLus'=>count($msgNonLus),
        'expediteur'=>$expediteur,
        'destinataire'=>$destinataire
    ));
    echo Views::instance()->render('message/read.html');
 }
 function send() {

    //Envoyer un message + mail au recepteur
    echo Views::instance()->render('member/show.html');
 }
 function erase() {
    //Effacer le message s'il existe et que c'est bien l'utilisateur qui a ce msg
 	$App=new App();
    $message=$App->get(F3::get('POST.idmessage'), 'pu_message');
    if($message!=FALSE) {
        $App->erase(F3::get('POST.idmessage'), 'pu_message');
    }
    // $message=$App->eraseMessage(F3::get('PARAMS.idmembre'), 'pu_message');
    F3::set('page_title',$user0->prenom);
    F3::set('user0',$user0);
    echo Views::instance()->render('member/show.html');
 }

    function nouveau()
    {
        if(F3::get('VERB')=='POST') 
        {
            if(strlen(F3::get('POST.titre'))<1) 
            {
                $erreur="Erreur : Le titre ne peut pas être vide.";
            }elseif(strlen(F3::get('POST.message'))<1) 
            {
                $erreur="Erreur : Le message ne peut pas être vide.";
            }elseif(!is_numeric(F3::get('POST.destinataire'))) 
            {
                F3::reroute('/messages');
            }else
            {
                $App=new App();
                $user0=$App->get(F3::get('POST.destinataire'),'pu_membre');
                if(!$user0) 
                {
                    $erreur="Erreur : L'utilisateur sélectionné n'est plus disponible.";
                }else
                {
                    $message=$App->add(array('titre'=>F3::get('POST.titre'), 'message'=>F3::get('POST.message'),'lu'=>0,'id_membre1'=>F3::get('user')->id,'id_membre2'=>F3::get('POST.destinataire')), 'pu_message');
                    F3::reroute('/message/'.$message->id);
                }
            }

            F3::mset(array(
                'form_titre'=>F3::get('POST.titre'),
                'form_message'=>F3::get('POST.message'),
                'erreur'=>$erreur
            ));

            echo Views::instance()->render('message/nouveau.html');
        }else
        {
            $App=new App();
            $user0=$App->get(F3::get('PARAMS.idmembre'), 'pu_membre');
            if(!$user0)
            {
                F3::reroute('/messages');
            }
            F3::set('user0',$user0);
            echo Views::instance()->render('message/nouveau.html');
        }
    }
}
?>