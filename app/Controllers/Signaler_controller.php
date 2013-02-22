<?php
class Signaler_controller{
 
 function __construct(){
    F3::set('page_title','Signaler une annonce');
    
 }
 
function submit()
{

    $erreur='';
    $App=new App();
    $annonce=$App->show(F3::get('POST.id'), 'pu_annonce');
    if($annonce->dry()) {
        F3::set('erreur','Une erreur est survenue, merci de réessayer.');
        echo Views::instance()->render('signalement/submit.html');
    }else 
    {
        if(F3::get('POST.id_membre')!=NULL) {
            $signalement=$App->signaler(F3::get('POST.id'), F3::get('POST.type'), F3::get('POST.message'), F3::get('POST.id_membre'));
        }else{
            $signalement=$App->signaler(F3::get('POST.id'), F3::get('POST.type'), F3::get('POST.message'));
        }
        echo Views::instance()->render('signalement/submit.html');
    }
}
 
function __destruct()
{

} 
}
?>