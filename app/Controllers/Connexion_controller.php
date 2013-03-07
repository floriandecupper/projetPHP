<?php
class Connexion_controller{
 
function beforeroute(){
    F3::set('page_title','Connexion');
 }
 
    function connexion()
    {
        if(F3::get('SESSION.user_id'))
        {
            F3::reroute('/membre'); 
        }
        if(F3::get('VERB')=='GET') {
            echo Views::instance()->render('connexion/home.html');
        }else
        {
            $erreur='';
            $App=new App();
            $result=$App->connexion(F3::get('POST.mail'), md5(F3::get('POST.password')));
            if(is_integer($result)) 
            {
                switch ($result) 
                {
                    case 0:
                        $erreur="Erreur : Les identifiants sont incorrects.";
                        break;
                    case 1:
                        $erreur="Erreur : Vous avez dépassé le nombre de tentatives, merci de réessayer plus tard.";
                        break;
                    case 2:
                        $erreur="Erreur : Vous n'avez pas encore validé votre compte.";
                        break;
                }
                F3::set('erreur',$erreur);
                echo Views::instance()->render('connexion/home.html');
            }else
            {
                $user0=$result;
                F3::mset(array(
                    'SESSION.user_id'=>$user0->id,
                    'SESSION.user_mail'=>$user0->mail,
                    'user'=>$user0
                ));
                F3::reroute('/');
            }
        }
    }
 
  
 function oubliMDP(){
    if(F3::get('VERB')=='POST') {
        echo 'aight';
        $App=new App();
        $user0=$App->oubli(F3::get('POST.mail'));
        echo Views::instance()->render('connexion/oubli2.html');
    }else{
        echo Views::instance()->render('connexion/oubli.html');
    }
 }
   function oubli_submit(){
    
  }
    function verification(){
        $App=new App();
        $test=$App->verification(F3::get('PARAMS.idmembre'), F3::get('PARAMS.activation'));
        if($test) {
            $erreur="Votre compte a bien été validé.";
        }else {
            $erreur="Une erreur est survenue, votre compte n'a pas été validé.";
            F3::error(404);
        }
        F3::set('erreur',$erreur);
        echo Views::instance()->render('connexion/home.html');
        
  }
 function deconnexion(){
    F3::clear( 'SESSION' );
    F3::reroute('/connexion'); 
 }
   function signup()
  {
    F3::set('page_title','Inscription');
    $App=new App();
    $id_parrain=0;
    if(F3::get('POST.id_parrain') && $App->get(F3::get('POST.id_parrain'),'pu_membre')!=false) {
        $id_parrain=F3::get('POST.id_parrain');
        F3::set('id_parrain',$id_parrain);
    }elseif(F3::get('SESSION.id_parrain') && $App->get(F3::get('SESSION.id_parrain'),'pu_membre')!=false) {
        $id_parrain=F3::get('SESSION.id_parrain');
        F3::set('id_parrain',$id_parrain);
    }
    
    if(F3::get('VERB')=='POST' && F3::get('POST.nom'))
    {
        $erreur='';
        $regex='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
        if($App->mget('pu_membre', 'mail=?', array(F3::get('POST.mail')))!=false) {
            $erreur="Erreur : L'adresse E-Mail indiquée est déjà utilisée sur un autre compte.";
        }
        elseif(!preg_match($regex,F3::get('POST.mail'))) // Vérifie si l'email n'est pas valide
        {
            $erreur="Erreur : L'adresse E-Mail n'est pas valide.";
        }elseif(strlen(F3::get('POST.prenom'))<2) 
        {
            $erreur="Erreur : Votre prénom doit contenir au moins 2 caractères.";
        }elseif(strlen(F3::get('POST.nom'))<2) 
        {
            $erreur="Erreur : Votre nom doit contenir au moins 2 caractères.";
        }elseif(F3::get('POST.password1')!=F3::get('POST.password2')) 
        {
            $erreur="Erreur : Les deux mots de passe ne correspondent pas.";
        }else
        {
            $dispo_jours='';
            if(is_array(F3::get('POST.dispo_jours'))) {
                $dispo_jours = implode(",",F3::get('POST.dispo_jours'));
            }
            if(F3::get('POST.tags')!='') {
                $App->tags(F3::get('POST.tags'));
            }
            $array=array(
                'mail'=>F3::get('POST.mail'), 
                'password'=>md5(F3::get('POST.password1')), 
                'prenom'=>F3::get('POST.prenom'), 
                'nom'=>F3::get('POST.nom'), 
                'description'=>F3::get('POST.description'),
                'tags'=>F3::get('POST.tags'),
                'dispo_jours'=>$dispo_jours,
                'dispo_heures'=>F3::get('POST.dispo_heures'),
                'points'=>F3::get('start_points'),
                'activation'=>rand(1,9999999999)
            );
            if(isset($id_parrain)) {
                $array['id_parrain']=$id_parrain;
                $parrain=$App->get($id_parrain,'pu_membre');
                $parrain->points+=F3::get('bonus_parrainage');
                $App->update($parrain);
            }
            $user=$App->add($array,'pu_membre');
            $contenu = "<p>Bonjour ".$user->prenom.",<br /><br />Vous venez de vous inscrire sur ".F3::get('site_nom').". Afin de valider votre inscription, vous devez cliquer sur le lien suivant :<br /><a href='".F3::get('site_url')."verification/".$user->id."/".$user->activation."'>".F3::get('site_url')."verification/".$user->id."/".$user->activation."</a><br /><br />Cordialement,<br />L'équipe de ".F3::get('site_nom');
            $mail=new Mail($user->mail, F3::get('site_mail'), '', 'Inscription sur '.F3::get('site_nom'), $contenu);
            $mail->send();
            
            F3::reroute('/connexion?info=inscription'); 
        }
        // Effet ricochet :
        
        F3::mset(array(
            'form_mail'=>F3::get('POST.mail'),
            'form_prenom'=>F3::get('POST.prenom'),
            'form_nom'=>F3::get('POST.nom'),
            'form_description'=>F3::get('POST.description'),
            'form_tags'=>F3::get('POST.tags'),
            'form_dispo_jours'=>'',
            'form_dispo_heures'=>F3::get('POST.dispo_heures'),
            'erreur'=>$erreur
        ));
        echo Views::instance()->render('signup/home.html');
        
    }else{
        echo Views::instance()->render('signup/home.html');
    }
  }
  function connexionFB() {
    $App=new App();
    if(F3::get('SESSION.user_id'))
    {
        $user=$App->get(F3::get('SESSION.user_id'),'pu_membre');
        if($user->id_fb==0) {
            $App->set($user->id,array('id_fb'=>F3::get('POST.fb_id')),'pu_membre');
        }
        F3::reroute('/membre'); 
    }else{
        F3::mset(array(
            'form_prenom'=>F3::get('POST.fb_prenom'),
            'form_nom'=>F3::get('POST.fb_nom'),
            'form_mail'=>F3::get('POST.fb_mail'),
            'form_fb'=>F3::get('POST.fb_id')
            ));
        $this->signup();
    }
  }
}
?>