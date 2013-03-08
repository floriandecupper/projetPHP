<?php
class App_controller
{
    
    function beforeroute()
    {
        if (F3::get('SESSION.user_id')) {
            $App       = new App();
            $user      = $App->get(F3::get('SESSION.user_id'), 'pu_membre');
            $msgNonLus = $App->mget('pu_message', 'id_membre2=? AND lu=?', array(
                $user->id,
                0
            ));
            F3::mset(array(
                'msgNonLus' => count($msgNonLus),
                'user' => $user,
                'administrateur' => $user->administrateur
            ));
        }
        F3::set('page_title', 'Accueil');
    }
    function test() {
        echo Views::instance()->render('integration/messagerie.html');
    }
/**
Affichage Home
@return 
@param $userId int
**/
    function home()
    {
        $App   = new App();
        //Création tableau
        $array = array();
        $annonces=$App->exec('SELECT a.titre,a.id,a.description, a.date, a.categorie, a.id_membre,u.prenom AS uprenom, u.nom AS unom FROM pu_annonce AS a INNER JOIN pu_membre AS u ON a.id_membre = u.id WHERE a.etat=0 ORDER BY date DESC LIMIT 0,3');
        
         F3::set('annonces', $annonces);
        echo Views::instance()->render('accueil.html');
    }
    function zoom()
    {
        $App   = new App();
        echo '<img src="'.F3::get('site_url').'upload/'.F3::get('PARAMS.img').'" />';
    }
    
    function mentions() {
        F3::set('page_title', "Mentions légales");
        echo Views::instance()->render('mentions.html');
    }
    function cgu() {
        F3::set('page_title', "Conditions Générales d'utilisation");
        echo Views::instance()->render('cgu.html');
    }
    function apropos() {
        F3::set('page_title', 'A propos');
        echo Views::instance()->render('apropos.html');
    }
    function doc()
    {
        echo Views::instance()->render('userref.html');
    }
    
    function tags()
    {
        $App = new App();
        
        $tags      = $App->mget('pu_tags', 'id>?', array(
            0
        ), array(
            'order'=>
            'tag ASC'
        ));
        $json_data = array();
        header('Content-type: application/json');
        foreach ($tags as $tag) {
            $json_data[] = $tag->tag;
        }
        echo json_encode($json_data);
    }
    function api() {
        $App=new App();
        $reponse='';
        if(F3::get('POST.nbrN')) {
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
    }
    function contact()
    {
        $App = new App();
        
        if (F3::get('VERB') == 'POST') {
            $erreur = '';
            $regex  = '#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
            
            if (!preg_match($regex, F3::get('POST.mail'))) // Vérifie si l'email n'est pas valide
                {
                $erreur = "Erreur : L'adresse E-Mail n'est pas valide.";
            } elseif (strlen(F3::get('POST.pseudo')) < 2) {
                $erreur = "Erreur : Votre pseudo doit contenir au moins 2 caractères.";
            } elseif (strlen(F3::get('POST.titre')) < 2) {
                $erreur = "Erreur : Votre message doit comporter un titre.";
            } elseif (strlen(F3::get('POST.message')) < 20) {
                $erreur = "Erreur : Votre message doit contenir 20 caractères au minimum.";
            } else {
                $dispo_jours = '';
                if (is_array(F3::get('POST.dispo_jours'))) {
                    $dispo_jours = implode(",", F3::get('POST.dispo_jours'));
                }
                if (F3::get('POST.tags') != '') {
                    $App->tags(F3::get('POST.tags'));
                }
                $contact = $App->add(array(
                    'mail' => F3::get('POST.mail'),
                    'pseudo' => md5(F3::get('POST.pseudo')),
                    'titre' => F3::get('POST.titre'),
                    'message' => F3::get('POST.message')
                ), 'pu_contact');
                $contenu = "<p>Bonjour " . F3::get('POST.pseudo') . ",<br /><br />Nous avons bien receptionné votre message et nous nous efforcerons d'y répondre dans les plus brefs délais.<br /><br />Cordialement,<br />L'équipe de " . F3::get('site_nom');
                $mail    = new Mail(F3::get('POST.mail'), F3::get('site_mail'), '', 'Demande de contact avec ' . F3::get('site_nom'), $contenu);
                $mail->send();
                
                F3::reroute('/?info=contact');
                
            }
            // Effet ricochet :
            F3::mset(array(
                'form_mail' => F3::get('POST.mail'),
                'form_titre' => F3::get('POST.titre'),
                'form_message' => F3::get('POST.message'),
                'form_pseudo' => F3::get('POST.pseudo'),
                'erreur' => $erreur
            ));
            
            echo Views::instance()->render('contact.html');
            
        } else {
            echo Views::instance()->render('contact.html');
        }
    }
    function parrainage()
    {
        $App      = new App();
        $filleuls = $App->mget('pu_membre', 'id_parrain=?', array(
            F3::get('user')->id
        ), array(
            'order' => 'date DESC'
        ));
        
        F3::set('filleuls', $filleuls);
        echo Views::instance()->render('parrainage.html');
    }
    
    function notifications()
    {
        $App           = new App();
        $notifications0 = $App->mget('pu_notifications', 'id_membre=? AND lu=?', array(
            F3::get('user')->id,
            0
        ), array(
            'order' => 'date DESC','limit'=>'4'
        ));
        $notifArray=array();
        if($notifications0!=false) {
            foreach($notifications0 as $notification0) {
                $App->set($notification0->id,array('lu'=>'1'),'pu_notifications');
                if($notification0->type=='proposition') {

                    $notification=$App->exec('SELECT n.*, p.*, u.nom AS unom, u.prenom AS uprenom, a.titre AS atitre
                                        FROM pu_notifications AS n
                                        INNER JOIN pu_proposition AS p ON n.id_objet= p.id
                                        INNER JOIN pu_annonce AS a ON p.id_annonce = a.id
                                        INNER JOIN pu_membre AS u ON p.id_membre=u.id
                                        WHERE n.lu=0 AND n.id_membre='.F3::get('user')->id);
                     $reponse=transformToText(F3::get('proposition_notification'),array(
                    "<a href='".F3::get('site_url')."membre/".$notification['id_membre']."'>".
                    transformToPseudo($notification['uprenom'],$notification['unom']).
                    "</a>","<a href='".F3::get('site_url')."annonce/".$notification['id_annonce']."'>".
                    $notification['atitre']."</a><br /><hr /><br />"));
                    
                }elseif($notification0->type=='deal') {

                    $annonce=$App->get($notification0->id_objet,'pu_annonce');
                    $proposition=$App->get($annonce->id_proposition,'pu_proposition');
                    $user=$App->get($annonce->id_membre,'pu_membre');

                    $reponse=transformToText(F3::get('deal_notification'),array(
                    "<a href='".F3::get('site_url')."membre/".$user->id."'>".
                    transformToPseudo($user->prenom,$user->nom).
                    "</a>","<a href='".$site_url."annonce/".$annonce->id."'>".
                    $annonce->titre."</a><br /><hr /><br />"));
                    
                }elseif($notification0->type=='avertissement') {

                    $reponse=F3::get('avertissement_notification')."<br /><hr /><br />";

                }elseif($notification0->type=='valider') {
                    $proposition=$App->get($notification0->id_objet,'pu_proposition');
                    $annonce=$App->get($proposition->id_annonce,'pu_annonce');
                    $user=$App->get($proposition->id_membre,'pu_membre');

                    $reponse=transformToText(F3::get('valider_notification'),array(
                        $proposition->prix,
                        "<a href='".$site_url."membre/".$user->id."'>".transformToPseudo($user->prenom,$user->nom)."</a>",
                        "<a href='".$site_url."annonce/".$annonce->id."'>".$annonce->titre."</a><br /><hr /><br />"
                    ));
                }elseif($notification0->type=='paiement') {
                    $reponse= "Votre paiement a bien été enregistré, les crédits ont été ajouté à votre compte.<br /><hr /><br />";
                }
                $notifArray[]=$reponse;
            }
        }
        F3::set('notifications', $notifArray);
        echo Views::instance()->render('notifications.html');
        
        
    }
    
}
?>