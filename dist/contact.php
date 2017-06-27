<?php
require 'include/capete.php';
if ($_GET['limba'] == "en")
{
	afiseazaAntet('Contact', '', '', 'en');
	?>
		<h2>Contact</h2>
		<p>I can be found only on the internet. I have a phone, but I barely use it (I don't even know my number). If somebody wants to write me something they can do so at <code>paul at-sign nechifor.net</code>.</p>
<?php
	afiseazaSubsol('en');
}
else
{
	afiseazaAntet('Contact', '', '', 'ro');	
	?>
		<h2>Contact</h2>
		<p>Pot fi contactat doar pe internet. Am și telefon, dar nu-l prea folosesc (nici nu știu numărul pe de rost). Dacă are cineva ceva să-mi spună poate să-mi scrie la <code>paul coadăDeMaimuță nechifor.net</code>.</p>
<?php
	afiseazaSubsol('ro');
}	
?>
