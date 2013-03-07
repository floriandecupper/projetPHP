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
    
    function home()
    {
        $App   = new App();
        //Création tableau
        $array = array();
        
        if (F3::get('SESSION.user_id')) {
            $tags = explode(',', F3::get('user')->tags);
            foreach ($tags as $value) {
                $annonces = $App->search($value, 'pu_annonce');
                foreach ($annonces as $annonce) {
                    if (array_key_exists($annonce->id, $array)) {
                        $array[$annonce->id] = $array[$annonce->id] + 1;
                    } else {
                        $array[$annonce->id] = 1;
                    }
                    
                }
                
                
            }
            $annonces = array();
            foreach ($array as $key => $nbr) {
                $annonces[] = $App->get($key, 'pu_annonce');
            }
        } else {
            if(F3::get('PARAMS.idparrain')) {

                if($App->get(F3::get('PARAMS.idparrain'),'pu_membre')!=false) {
                    F3::set('SESSION.id_parrain',F3::get('PARAMS.idparrain'));
                }
            }
            $annonces = $App->mget('pu_annonce', 'etat=?', array(
                0
            ));
        }
        //Recherche pour chaque mot clé dans les annonces, à chaque fois trouvé, on teste si cette annonce est déjà stockée, si oui on ajoute le tag dans un tableau multidimensionnel, sinon on ajoute cette annonce au tableau
        F3::set('annonces', $annonces);
        echo Views::instance()->render('accueil.html');
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
            'order',
            'tag ASC'
        ));
        $json_data = array();
        header('Content-type: application/json');
        foreach ($tags as $tag) {
            $json_data[] = $tag->tag;
        }
        echo json_encode($json_data);
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
        $notifications = $App->mget('pu_notifications', 'id_membre=? AND lu=?', array(
            F3::get('user')->id,
            0
        ), array(
            'order' => 'date DESC'
        ));
        if (!$notifications) {
            $notifications = $App->mget('pu_notifications', 'id_membre=? AND lu=?', array(
                F3::get('user')->id,
                1
            ), array(
                'limit' => 5,
                'order' => 'date DESC'
            ));
        }
        F3::set('notifications', $notifications);
        echo Views::instance()->render('notifications.html');
        
        
    }
    
}
?>