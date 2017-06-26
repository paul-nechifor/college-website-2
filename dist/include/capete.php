<?php
function afiseazaAntet($titlu, $descriere, $cuvinteCheie, $limba)
{
	if ($limba == "en")
	{
		if ($titlu == "") $titlu = "Paul Nechifor";
		if ($descriere == "") $descriere = "Paul Nechifor's personal web site.";
		if ($cuvinteCheie == "") $cuvinteCheie = "paul nechifor, paul, nechifor, programming, linux, personal web site";
	}
	else
	{
		if ($titlu == "") $titlu = "Paul Nechifor";
		if ($descriere == "") $descriere = "Saitul personal al lui Paul Nechifor.";
		if ($cuvinteCheie == "") $cuvinteCheie = "paul nechifor, paul, nechifor, programare, linux, sait personal";
	}

	print '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="'.$limba.'">
<head>
	<title>'.$titlu.' — Paul Nechifor</title>
	<!-- smart developers always view source -->
	<link href="/stil.css" rel="stylesheet" type="text/css" />
	<link href="/pygments.css" rel="stylesheet" type="text/css" />
	<link href="/imagini/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="'.$descriere.'" />
	<meta name="keywords" content="'.$cuvinteCheie.'" />
	<meta name="author" content="Paul Nechifor" />
</head>
<body>
	<div id="antet">
		<h1>Paul Nechifor</h1>
	</div>
	
	';
	
	if ($limba == "en")
		print '<div id="meniu">
		<ul>
			<li><a href="/index.php?limba=en">First page</a></li>
			<li><a href="/despre_mine.php?limba=en">About me</a></li>
			<li><a href="/programare.php?limba=en">Programming</a></li>
			<li><a href="/altele.php?limba=en">Others</a></li>
			<li><a href="/contact.php?limba=en">Contact</a></li>
		</ul>
		<div id="limba"><a href="?limba=ro">[română]</a></div>
	</div>';
	else
		print '<div id="meniu">
		<ul>
			<li><a href="/index.php">Prima pagină</a></li>
			<li><a href="/despre_mine.php">Despre mine</a></li>
			<li><a href="/programare.php">Programare</a></li>
			<li><a href="/altele.php">Altele</a></li>
			<li><a href="/contact.php">Contact</a></li>
		</ul>
		<div id="limba"><a href="?limba=en">[english]</a></div>
	</div>';

	print '

	<div id="continut">
';
}

function afiseazaSubsol($limba)
{
	print '	</div>

	<div id="subsol">
		<p>';
	
	if ($limba == "en")
		print 'Copyright © 2009 <a href="#">Paul Nechifor</a> — I don\'t know what\'s with this right, but I have it.';
	else
		print 'Drept de copiere © 2009 <a href="#">Paul Nechifor</a> — Ce-i cu dreptul ăsta, nu știu, dar îl am.';
	
	print '</p>
	</div>
</body>
</html>';
}

?>
