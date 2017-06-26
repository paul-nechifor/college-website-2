<?php
require 'include/capete.php';
if ($_GET['limba'] == "en")
{
	afiseazaAntet('About me', '', '', 'en');
	?>
		<h2>About me</h2>
		<div class="imagine_dr"><img src="imagini/portret.png" alt="Paul Nechifor Portret" /><p>when I'm curious</p></div>

		<p>I'm Paul Răzvan Nechifor and I'm 20 years old… or young… 20 years young. I'm a student at the <a href="http://www.infoiasi.ro/bin/Main/">Faculty of Computer Science</a> (FII) from Alexandru Ioan Cuza University.</p>

		<h3>Games</h3>
		<p>I like to play interesting computer games with decent graphics and a story behind the action. I'm not a gamer, I just play to have fun. Some of the best games I've played are:</p>
		<ul>
			<li>Max Payne 1 and 2 for TPS</li>
			<li>Warcraft III for RTS (I've never played Starcraft)</li>
			<li>GTA Vice City and GTA San Andreas for the action genre</li>
			<li>Morrowind for RPG (Oblivion's good too, but on different levels)</li>
			<li>Unreal Tournament 2004 for FPS</li>
			<li>Spore for God-game</li>
			<li>King's Bounty: The Legend for TBS</li>
		</ul>

		<div class="imagine_st"><img src="imagini/alterego.jpg" alt="Paul Nechifor - Alter ego" /><p>alter ego</p></div>
		<h3>Music</h3>
		<p>I listen to a lot of music genres which are very different. From <em>death metal</em> to <em>black metal</em>, from <em>gothic metal</em> to <em>funeral doom metal</em>, from <em>folk metal</em> to <em>symphonic black metal</em>… And many others like <em>symphonic metal</em>, <em>viking metal</em>, <em>melodic death metal</em>, <em>blackened death metal</em>, <em>thrash metal</em>, <em>new wave of british heavy metal</em>, <em>black funeral doom metal</em>… you get the idea.</p>
		<p>I would make a list of all the bands I like, but it would be to long and it would take to much time to make. Anyway, close to the top (but not in this order) would be Bathory, Burzum, Mayhem, Marduk, Cannibal Corpse, Ulver, Haggard, Funeral, Theatre of Tragedy, Negură Bunget (best romanian band), Cradle of Filth, Deicide și Dimmu Borgir.</p>
		<p style="clear:both"></p>

<?php
	afiseazaSubsol('en');
}
else
{
	afiseazaAntet('Despre mine', '', '', 'ro');	
	?>
		<h2>Despre mine</h2>
		<div class="imagine_dr"><img src="imagini/portret.png" alt="Portret Paul Nechifor" /><p>când sunt curios</p></div>

		<p>Mă numesc Paul Răzvan Nechifor și am 20 de ani. Sunt student la <a href="http://www.infoiasi.ro/bin/Main/">Facultatea de Informatică Iași</a> (FII) de la Universitatea Alexandru Ioan Cuza.</p>

		<h3>Jocuri</h3>
		<p>Îmi place să joc jocuri interesante care au o grafică cât-de-cât decentă și au o poveste în spatele acțiunii. Eu nu sunt <em>gamer</em> înrăit, joc doar că să mă distrez. Printre cele mai bune jocuri cred că sunt:</p>
		<ul>
			<li>Max Payne 1 și 2 pentru TPS</li>
			<li>Warcraft III pentru RTS (n-am jucat niciodată Starcraft)</li>
			<li>GTA Vice City și GTA San Andreas pentru genul acțiune</li>
			<li>Morrowind pentru RPG (și Oblivion e bun, dar nu se compară)</li>
			<li>Unreal Tournament 2004 pentru FPS</li>
			<li>Spore pentru God-game</li>
			<li>King's Bounty: The Legend pentru TBS</li>
		</ul>

		<div class="imagine_st"><img src="imagini/alterego.jpg" alt="Paul Nechifor - Alter ego" /><p>alter ego</p></div>
		<h3>Muzică</h3>
		<p>Ascult foarte multe genuri de muzică de o variație foarte mare. De la <em>death metal</em> la <em>black metal</em>, de la <em>gothic metal</em> la <em>funeral doom metal</em>, de la <em>folk metal</em> la <em>symphonic black metal</em>… Și multe altele cum ar fi <em>symphonic metal</em>, <em>viking metal</em>, <em>melodic death metal</em>, <em>blackened death metal</em>, <em>thrash metal</em>, <em>new wave of british heavy metal</em>, <em>black funeral doom metal</em>… se-nțelege, de toate genurile.</p>
		<p>Aș face o listă cu trupe care-mi plac mult, dar ar fi prea lungă și ar dura prea mult s-o fac. Oricum, aproape de vârf ar fi (nu neapărat în această ordine) Bathory, Burzum, Mayhem, Marduk, Cannibal Corpse, Ulver, Haggard, Funeral, Theatre of Tragedy, Negură Bunget (cea mai tare trupă românească de Black metal sau orice alt gen), Cradle of Filth, Deicide și Dimmu Borgir.</p>
		<p style="clear:both"></p>
<?php
	afiseazaSubsol('ro');
}	
?>
