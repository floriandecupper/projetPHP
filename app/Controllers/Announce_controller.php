<?php
class Announce_controller{
 
    function beforeroute()
    {
        if(!F3::get('SESSION.user_id')) 
        {
            F3::reroute('/connexion'); 
        }
        F3::set('page_title','Accueil');
        if(F3::get('SESSION.user_id')!=NULL) 
        {
            $App=new App();
            $user=$App->get(F3::get('SESSION.user_id'), 'pu_membre');
            $msgNonLus=$App->mget('pu_message','id_membre2=? AND lu=?', array($user->id,0));

            F3::mset(array(
                'msgNonLus'=>count($msgNonLus),
                'user'=>$user,
                'administrateur'=>$user->administrateur
            ));
        }
     }
     
     function home(){

        F3::set('page_title','Annonces');
        $id=F3::get('PARAMS.id');
        $App=new App();
        echo Views::instance()->render('accueil.html');

     }
    function ajouter(){

        F3::set('page_title','Créer une annonce');
        $App=new App();
        
        $pictures=array();
        for($i=1;$i<=4;$i++) 
        {
            if (F3::get('FILES.photo'+$i))
            {
                if(F3::get('FILES.photo'+$i+'.error') > 0) 
                {
                    $erreur =F3::get('FILES.photo'+$i+'.error');
                    set('erreur', $erreur);
                }else{
                    $pictures[]='test';
                }
            }
        }
        if(F3::get('VERB')=='POST') 
        {
            print_r(F3::get('FILES'));
            // echo $_FILES["photo1"]["name"];
        //     if(strlen(F3::get('POST.titre'))<2) 
        //     {
        //         $erreur="Erreur : Le titre doit contenir au moins 2 caractères.";
        //     }elseif(strlen(F3::get('POST.description'))<20) 
        //     {
        //         $erreur="Erreur : La description doit contenir au moins 20 caractères.";
        //     }elseif(!is_numeric(F3::get('POST.prix'))) 
        //     {
        //         $erreur="Erreur : Le prix n'est pas correcte.";
        //     }else
        //     {
        //         $App=new App();
        //         $user=$App->get(F3::get('SESSION.user_id'),'pu_membre');
        //         if(F3::get('POST.tags')!='') {
        //             $App->tags(F3::get('POST.tags'));
        //         }
        //         $annonce=$App->add(array(
        //             'id_membre'=>$user->id,
        //             'titre'=>F3::get('POST.titre'), 
        //             'description'=>F3::get('POST.description'), 
        //             'prix'=>F3::get('POST.prix'), 
        //             'tags'=>F3::get('POST.tags'),
        //             'etat'=>0
        //         ),'pu_annonce');

        //         F3::reroute('/annonce/'.$annonce->id);
        //     }

        //     F3::mset(array(
        //         'form_titre'=>F3::get('POST.titre'),
        //         'form_prix'=>F3::get('POST.prix'),
        //         'form_prix'=>F3::get('POST.tags'),
        //         'form_description'=>F3::get('POST.description'),
        //         'erreur'=>$erreur
        //     ));

        //     echo Views::instance()->render('annonces/new.html');

        }
            // else
        // {
            $user=$App->get(F3::get('SESSION.user_id'), 'pu_membre');
            echo Views::instance()->render('annonces/new.html');
        // }
    }
    
        function commentaire(){

        
        $App=new App();

        if(F3::get('VERB')=='POST') 
        {
            $annonce=$App->get(F3::get('POST.id_annonce'), 'pu_annonce');
            if(!is_numeric(F3::get('POST.id_annonce')) || !$annonce) 
            {
                $erreur="Erreur : Une erreur est survenue.";
            }elseif(strlen(F3::get('POST.message'))<1) 
            {
                $erreur="Erreur : Vous n'avez pas tapé votre message.";
            }else
            {
                $App=new App();
                $user=$App->get(F3::get('SESSION.user_id'),'pu_membre');

                $commentaire=$App->add(array(
                    'id_membre'=>$user->id,
                    'id_annonce'=>F3::get('POST.id_annonce'), 
                    'message'=>F3::get('POST.message')
                ),'pu_commentaire');

                F3::reroute('/annonce/'.$annonce->id);
            }

            F3::mset(array(
                'form_message'=>F3::get('POST.message'),
                'erreur'=>$erreur
            ));

            echo Views::instance()->render('annonces/new.html');

        }else
        {
            $user=$App->get(F3::get('SESSION.user_id'), 'pu_membre');
            echo Views::instance()->render('annonces/new.html');
        }
    }

function proposition() {
        $App=new App();
        $annonce=$App->get(F3::get('PARAMS.idannonce'), 'pu_annonce');
        if(!$annonce)
        {
            F3::reroute('/');
        }
        F3::set('annonce',$annonce);
        if(F3::get('VERB')=='POST') 
        {
            $ok=0;
            if(strlen(F3::get('POST.message'))<20) 
            {
                $erreur="Erreur : Le message doit contenir au minimum 20 caractères.";
            }elseif(strlen(F3::get('POST.prix'))<1 || !is_numeric(F3::get('POST.prix'))) 
            {
                $erreur="Erreur : Le prix indiqué n'est pas valide.";
            }else
            {
                
                $proposition=$App->add(array(
                    'id_annonce'=>$annonce->id,
                    'id_membre'=>F3::get('user')->id,
                    'message'=>F3::get('POST.message'),
                    'prix'=>F3::get('POST.prix'),
                    'lu'=>0
                ), 'pu_proposition');
                $notification=$App->add(array(
                    'id_membre'=>$annonce->id_membre,
                    'type'=>'proposition',
                    'id_objet'=>$proposition->id,
                    'lu'=>0
                ), 'pu_notifications');
                $user0=$App->get($annonce->id_membre, 'pu_membre');
                $nbrePropositions=count($App->mget('pu_proposition','id_annonce=?',array($annonce->id),array('limit'=>'1000')));
                F3::mset(array(
                    'info'=>'Votre proposition a bien été envoyé.',
                    'annonce'=>$annonce,
                    'user0'=>$user0,
                    'nbrePropositions'=>$nbrePropositions
                ));
                $ok=1;
                echo Views::instance()->render('annonces/show.html');
                
            }
            if(!$ok) 
            {
                F3::set('erreur',$erreur);
                echo Views::instance()->render('annonces/proposition.html');
            }
        }else
        {
            echo Views::instance()->render('annonces/proposition.html');
        }
    }
    function deal() {
        $App=new App();
        $user=F3::get('user');
        $proposition=$App->get(F3::get('PARAMS.idproposition'), 'pu_proposition');
        if(!$proposition)
        {
            F3::reroute('/');
        }
        if($user->points<$proposition->prix) {
            F3::reroute('/mes-annonces?erreur=credits');
        }else
        {
        $annonce=$App->set($proposition->id_annonce,array('etat'=>'1', 'id_proposition'=>$proposition->id),'pu_annonce');
        $notification=$App->add(array(
                    'id_membre'=>$proposition->id_membre,
                    'type'=>'deal',
                    'id_objet'=>$annonce->id,
                    'lu'=>0
                ), 'pu_notifications');
        }
        F3::reroute('/mes-annonces');
    }
    function valider() {
        $App=new App();
        $proposition=$App->get(F3::get('PARAMS.idproposition'), 'pu_proposition');
        if(!$proposition)
        {
            F3::reroute('/');
        }
        $annonce=$App->set($proposition->id_annonce,array('etat'=>'2'),'pu_annonce');
        $user0=$App->get($proposition->id_membre, 'pu_membre');
        $user=F3::get('user');
        if($user->points>=$proposition->prix) {
            $user->points=$user->points-$proposition->prix;
            $user->update();
            $user0->points=$user0->points+$proposition->prix;
            $user0->update();
            $notification=$App->add(array(
                    'id_membre'=>$proposition->id_membre,
                    'type'=>'valider',
                    'id_objet'=>$annonce->id,
                    'lu'=>0
                ), 'pu_notifications');
            
            $notification=$App->add(array(
                    'id_membre'=>$user->id_membre,
                    'type'=>'commenter',
                    'id_objet'=>$proposition->id,
                    'lu'=>0
                ), 'pu_notifications');
            F3::reroute('/mes-annonces');
        }
        F3::reroute('/mes-annonces');
    }
    function annonces()
    {
        $App=new App();
        $annonces=$App->exec('SELECT a.etat,a.titre,a.id,p.id AS pid,count(p.id) AS compte FROM pu_annonce AS a INNER JOIN pu_proposition AS p ON a.id = p.id_annonce WHERE a.id_membre='.F3::get('user')->id);
        F3::set('annonces',$annonces);
        echo Views::instance()->render('annonces/mes-annonces.html');
    }
    function propo()
    {
        $App=new App();
        $annonces=$App->mget('pu_annonce','id_membre=?',array(F3::get('user')->id));
        $propositionsTab=array();
        foreach($annonces as $i=>$annonce) {
            if($annonce->etat==0) {
                $propositions=$App->mget('pu_proposition','id_annonce=?',array($annonce->id));
                $propositionsTab[$i]=count($propositions);
            }elseif($annonce->etat==1) {
                $propositionsTab[$i]=$annonce->id_proposition;
            }
        }
        F3::mset(array(
            'annonces'=>$annonces,
            'propositions'=>$propositionsTab
        ));
        echo Views::instance()->render('annonces/mes-annonces.html');
    }  

    function propositions()
    {
        $App=new App();

        if(F3::get('PARAMS.idannonce')) {  
            $annonce=$App->get(F3::get('PARAMS.idannonce'), 'pu_annonce'); 
            F3::mset(array(
                'page_title'=>"Propositions reçus pour l'annonce ID".$annonce->id,
                'annonce'=>$annonce,
                'propositions'=>$App->mget('pu_proposition','id_annonce=?',array($annonce->id))
            ));
            echo Views::instance()->render('annonces/propositions.html');
        }else {
            $propositions=$App->mget('pu_proposition','id_membre=?',array(F3::get('user')->id));
            echo Views::instance()->render('propositions.html');
        }
    } 
    function annonce() 
    {
        $App=new App();
        $annonce=$App->get(F3::get('PARAMS.idannonce'), 'pu_annonce');
        $nbrePropositions=count($App->mget('pu_proposition','id_annonce=?',array($annonce->id)));

        $user0=$App->get($annonce->id_membre, 'pu_membre');
        F3::mset(array(
            'page_title'=>$annonce->titre,
            'annonce'=>$annonce,
            'user0'=>$user0,
            'nbrePropositions'=>$nbrePropositions
        ));

        echo Views::instance()->render('annonces/show.html');
    }
    function signaler() 
    {
        F3::set('page_title','Signaler une annonce');
        $erreur='';
        $App=new App();
        $annonce=$App->get(F3::get('POST.id'), 'pu_annonce');
        if($annonce->dry()) 
        {
            F3::set('erreur','Une erreur est survenue, merci de réessayer.');
            echo Views::instance()->render('signalement/submit.html');
        }else 
        {
            if(F3::get('POST.id_membre')!=NULL) 
            {
                //Ajout du type d'objet signalé
                $signalement=$App->add(array(
                    'id_annonce'=>F3::get('POST.id'), 
                    'type'=>F3::get('POST.type'), 
                    'message'=>F3::get('POST.message'), 
                    'id_membre'=>F3::get('POST.id_membre')),
                'pu_signalement');
            }else
            {
                $signalement=$App->add(array(
                    'id_annonce'=>F3::get('POST.id'), 
                    'type'=>F3::get('POST.type'), 
                    'message'=>F3::get('POST.message')),
                'pu_signalement');
            }
            echo Views::instance()->render('signalement/submit.html');
        } 
    }
    function recherche() 
    {
        $App=new App();
        if(F3::get('VERB')=='POST') 
        {
            //Création tableau
            $array=array();
            if(F3::get('POST.recherche')!='') {
            $tags=explode(',',F3::get('POST.recherche'));
            echo F3::get('POST.recherche');
            foreach($tags as $value) {
              $annonces=$App->search($value,'pu_annonce');
              foreach($annonces as $annonce) {
                  if (array_key_exists($annonce->id, $array)) 
                  {
                      $array[$annonce->id]=$array[$annonce->id]+1;
                  }else
                  {
                      $array[$annonce->id]=1;
                  }
              }
            }
                
            $annonces=array();
            foreach($array as $key=>$nbr) {
              $annonces[]=$App->get($key,'pu_annonce');
            }
            if(count($annonces)<1) {
                F3::set('erreur','Aucune annonce ne correspond aux tags sélectionnés.');
            }
            //Recherche pour chaque mot clé dans les annonces, à chaque fois trouvé, on teste si cette annonce est déjà stockée, si oui on ajoute le tag dans un tableau multidimensionnel, sinon on ajoute cette annonce au tableau
            F3::set('annonces',$annonces);
            }else{
            $erreur='Une erreur est survenue';
            $annonces=array();
            F3::set('annonces',array());
            }
            echo Views::instance()->render('accueil.html');
        }else {
            F3::set('info', 'Page en cours de construction');
            echo Views::instance()->render('contact.html');
        }
    }

}
?>