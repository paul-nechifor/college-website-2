<?php
require 'include/capete.php';
if ($_GET['limba'] == "en")
{
	afiseazaAntet('Altele', '', '', 'en');
	?>
		<h2>Others</h2>
		<p>This is where I'll put things that aren't related to programming.</p>
<?php
	afiseazaSubsol('en');
}
else
{
	afiseazaAntet('Others', '', '', 'ro');	
	?>
		<h2>Altele</h2>
		<p>Aici pun chestii care nu au legătură cu programarea.</p>
<?php
	afiseazaSubsol('ro');
}	
?>
