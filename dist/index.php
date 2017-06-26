<?php
require 'include/capete.php';
if ($_GET['limba'] == "en")
{
	afiseazaAntet('First page', '', '', 'en');
	?>
		<h2>First page</h2>
		<p>Welcome to Paul Nechifor's personal web site. This is were you can find information about me and my programs. You can also find how to contact me.</p>
		<p>The pages are written in romanian and english, but some are written in a single language. If you find mistakes in the english text, don't worry, I make plenty of mestakes in romanian as well so <strong>keep 'em to yourself!</strong></p>
		<p>All the pages and probably all the programs were written in the <a href="http://en.wikipedia.org/wiki/Vim_(text_editor)">Vim</a> text editor.</p>
		
		<h3>What's new</h3>
		<ul>
			<li>2009-07-06 — to be added</li>
			<li>2009-07-05 — I started this site</li>
		</ul>
<?php
	afiseazaSubsol('en');
}
else
{
	afiseazaAntet('Prima pagină', '', '', 'ro');	
	?>
		<h2>Prima pagină</h2>
		<p>Bine ai venit pe saitul personal a lui Paul Nechifor. Aici poți afla informații despre mine și programele mele. Sau poţi să aflii cum să mă contactezi (promit că nu sunt greu de abordat <strong>:D</strong>).</p>
		<p>Paginile sunt în română și în engleză, dar sunt unele care sunt scrise doar într-o singură limbă.</p>
		<p>Toate paginile și probabil toate programele au fost scrise în editorul <a href="http://en.wikipedia.org/wiki/Vim_(text_editor)">Vim</a>.</p>
		
		<h3>Ce-i nou</h3>
		<ul>
			<li>2009-07-06 — de adăugat</li>
			<li>2009-07-05 — am început acest sait</li>
		</ul>
<?php
	afiseazaSubsol('ro');
}	
?>
