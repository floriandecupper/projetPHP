<?php
class Paypal 
{

	private
		$api_paypal = 'https://api-3t.sandbox.paypal.com/nvp?',
		$version = 56.0,
		$user,
		$pass,
		$signature;

	function construit_url()
	{

		$api_paypal = $this->api_paypal.'VERSION='.$this->version.'&USER='.$this->user.'&PWD='.$this->pass.'&SIGNATURE='.$this->signature; // Ajoute tous les paramètres

		return  $api_paypal; // Renvoie la chaîne contenant tous nos paramètres.
	}
	function recup_param($resultat_paypal)
  	{
	    $liste_parametres = explode("&",$resultat_paypal); // Crée un tableau de paramètres
	    foreach($liste_parametres as $param_paypal) // Pour chaque paramètre
	    {
	        list($nom, $valeur) = explode("=", $param_paypal); // Sépare le nom et la valeur
	        $liste_param[$nom]=urldecode($valeur); // Crée l'array final
	    }
	    return $liste_param; // Retourne l'array
  	}
	function paiement($montant,$desc,$cancel,$return,$currency="EUR") {
		$requete = $this->construit_url();
		$requete = $requete."&METHOD=SetExpressCheckout".
		    "&CANCELURL=".urlencode($cancel).
		    "&RETURNURL=".urlencode($return).
		    "&AMT=".$montant.
		    "&CURRENCYCODE=".$currency.
		    "&DESC=".urlencode($desc).
		    "&LOCALECODE=FR";
		var_dump($requete);
		$ch = curl_init($requete);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$resultat_paypal=curl_exec($ch);
		var_dump($resultat_paypal);
		if ($resultat_paypal)
			{
				$liste_param = $this->recup_param($resultat_paypal); // Lance notre fonction qui dispatche le résultat obtenu en un array
			    print_r($liste_param);
			    if ($liste_param['ACK'] == 'Success')
			    {
			        header("Location: https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=".$liste_param['TOKEN']);
                	exit();
			    }
			    else
			    {
			    	return "<p>Erreur de communication avec le serveur PayPal.<br />".$liste_param['L_SHORTMESSAGE0']."<br />".$liste_param['L_LONGMESSAGE0']."</p>";
			    }   
            }
			else
			{
				return curl_error($ch);
			}

		curl_close($ch);
	}
	function __construct() 
	  {
	    $this->user=F3::get('PAYPAL_user');
	    $this->pass=F3::get('PAYPAL_pass');
	    $this->signature=F3::get('PAYPAL_signature');
	    return $this;
	  }
}
?>