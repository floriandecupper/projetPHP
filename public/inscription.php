<?php
echo F3::get('pseudo');
?>

<form method="post" action="post"><br />Pour vous inscrire, merci de remplir le formulaire ci-dessous.<br /><br />
	<input type="text" placeholder="Pseudo" name="pseudo" /><br /><br />
	<input type="password" placeholder="Password 1" name="password1" required /><br /><br />
	<input type="password" placeholder="Password 2" name="password2" required /><br /><br />
	<input type="mail" placeholder="Adresse E-mail" name="mail" required /><br /><br />
	<input type="submit" value="Je m'inscris !" />
</form>