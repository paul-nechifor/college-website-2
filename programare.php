<?php
require 'include/capete.php';
if ($_GET['limba'] == "en")
{
	afiseazaAntet('Programming', '', '', 'en');
	?>
		<h2>Programming</h2>
		<p>Programming is my passion. Don't laugh! I know a few programming languages (C, Python, ActionScript, C++, PHP), but none well enough. I like to find out how things work and when I see something cool I try to make it. That's why I immitated games like Bomberman, Breakout etc. ......................</p>
		<p>From now on I won't add unfinished projects here.</p>
		<p><a href="prog/romsun/index.php">How to make the computer speak romanian (romanian)</a></p>
		<p><a href="prog/conquizocr/index.php?limba=en">Another way to cheat at ConQUIZtador (romanian)</a></p>
		<p><a href="prog/file2bmp/index.php?limba=en">Transform any file to BMP and back again</a></p>
		<p><a href="prog/intrconq/index.php?limba=en">Answers to questions from ConQUIZtador (romanian)</a></p>
<?php
	afiseazaSubsol('en');
}
else
{
	afiseazaAntet('Programare', '', '', 'ro');	
	?>
		<h2>Programare</h2>
		<p>Programarea este pasiunea mea. Nu râde! Știu câteva limbaje de programare (C, Python, ActionScript, C++, PHP), dar nici unul foarte bine. Îmi place să aflu cum funcționează lucrurile și când văd ceva vreau să încerc să fac și eu. De asta am imitat jocuri de genul Bomberman, Breakout etc. Deseori îmi vin idei de programe, jocuri, scripturi, imagini pa care să le fac. Problema e că-mi vin prea multe idei și atunci când încep un proiect și sunt un pic satisfăcut — renunț. Până acum majoritatea lucrurilor pe care le-am început nu le-am terminat, de multe ori pentru că nu am avut timp sau nu am învățat ce trebuie ca să continui.</p>
		<p>De acum vreau să duc proiectele până la capăt și să nu mai pun chestii neterminate aici.</p>
		<p><a href="prog/romsun/index.php">Cum să faci calculatorul să vorbească română</a></p>
		<p><a href="prog/conquizocr/index.php">Alt fel de a trișa la ConQUIZtador</a></p>
		<p><a href="prog/file2bmp/index.php">Transformă orice fișier în BMP și invers</a></p>
		<p><a href="prog/intrconq/index.php">Răspunsuri la întrebări de pe ConQUIZtador</a></p>
<?php
	afiseazaSubsol('ro');
}	
?>
