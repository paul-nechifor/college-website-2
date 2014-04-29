<?php
require '../../include/capete.php';
if ($_GET['limba'] == "en")
{
	afiseazaAntet('Answers to questions from ConQUIZtador', '', '', 'en');
	?>
		<h2>Answers to questions from ConQUIZtador</h2>
		<p>No point to write this in english.</p>
<?php
	afiseazaSubsol('en');
}
else
{
	afiseazaAntet('Răspunsuri la întrebări de pe ConQUIZtador', '', '', 'ro');	
	?>
		<h2>Râspunsuri la întrebări de pe ConQUIZtador</h2>
		<p>Astea sunt întrebările pe care le-am cules eu când mi-am făcut un program de trișat pentru conquiztador.ro. După ceva timp cei de la ConQUIZtador au schimbat felul cum funcționează jocul și este mai greu să trișezi. Întrebările sunt ordonate și textul este în format UTF-8.</p>
		<p>Am <strong>13184</strong> de întrebări cu răspuns rapid. Pot fi găsite toate în fișierul <a href="rapide.txt">rapide.txt</a> (1,1 MiB). Fișierul conține pe o linie o întrebare și pe următoarea linie răspunsul.</p>
		<p>Am <strong>25436</strong> de întrebări grilă. Pot fi găsite toate în fișierul <a href="grila.txt">grila.txt</a> (3,6 MiB). Fișierul conține o întrebare pe 6 linii. Prima linie este textul întrebării, următoarele patru conțin cele patru variante de răspuns (ordonate alfabetic) și ultima linie conține varianta de răspuns corectă (1, 2, 3 sau 4).</p>
		<p>Cineva poate să caute prin răspunsurile asta când nu știe la ConQUIZtador. Eu am scris despre <a href="../conquizocr/index.php">o modalitate</a> în care pot fi folosite întrebările astea, dar cineva poate chiar să încarce astea într-un editor și să caute.</p>
		<p>Cine are o listă mai mare să nu ezite să mi-o dea și mie.</p>
<?php
	afiseazaSubsol('ro');
}	
?>
