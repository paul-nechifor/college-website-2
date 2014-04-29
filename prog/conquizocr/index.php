<?php
require '../../include/capete.php';
if ($_GET['limba'] == "en")
{
	afiseazaAntet('Another way to cheat at ConQUIZtador', '', '', 'en');
	?>
		<h2>Another way to cheat at ConQUIZtador</h2>
		<p>No point to write this in english.</p>
<?php
	afiseazaSubsol('en');
}
else
{
	afiseazaAntet('Alt fel de a trișa la ConQUIZtador', '', '', 'ro');	
	?>
		<h2>Alt fel de a trișa la ConQUIZtador</h2>
		
		<p>Am o istorie lungă de trișat la ConQUIZtador. Eu inițial foloseam o metodă prin care obținem întrebările și răspunsurile folosind funcții JavaScript cu un script în AutoIt. După ceva timp ei au schimbat cum funționează clientul. Acum întrebarea nu mai este lăsată într-o variabilă. Ea este transformată într-o imagine și variabila este golită. Am aflat asta decompilând fișierele SWF. Totuși, eu am rămas cu mii de întrebări de pe ConQUIZtador.</p>
		<p>Acum pentru că n-am mai trișat de mult, vreau să joc din nou. M-am gândit că metoda cea mai simplă de a reveni în lumea trișorilor ar fi să folosesc un program <a href="http://en.wikipedia.org/wiki/Optical_character_recognition">OCR</a> pentru a citi întrebarea direct de pe ecran și să o caut printre întrebările pe care le am deja. Dar pentru că programele OCR nu sunt exacte trebuie să caut întrebarea cea mai apropiată, și nu să verific caracter cu caracter.</p>
		<p>Înainte foloseam AutoIt, deci mergea doar pe Windows. Acum am făcut să meargă pe Linux. Pentru a captura imaginea am folosit comanda <code>import</code> care aparține de ImageMagick (se instalează în Ubuntu cu <code>apt-get install imagemagick</code>). Exemplul următor capturează o imagine în format PBM cu mărimea 900x110 începând de la poziția 105x382 (la poziția asta se află întrebarea din ConQUIZtador pe ecranul meu).</p>
		<pre class="fara">import -window root -crop 900x110+105+382 imagine.pbm</pre>
		<p>Pentru a citi textul folosesc programul <a href="http://www.gnu.org/software/ocrad/ocrad.html">ocrad</a>. Programul ăsta nu recunoaște decât 3 tipuri de imagini. De asta trebuie ca <code>import</code> să salveze în formatul PBM. Exempul următor afișează pe ecran textul din imagine.pbm. Trebuie <code>-i</code> ca să inverseze imaginea deoarece în joc culoarea textului este mai deschisă.</p>
		<pre class="fara">ocrad --charset=ascii -i imagine.pbm</pre>
	<p>Pentru a găsi răspunsul la întrebare am nevoie de <a href="../intrconq/index.php">lista mea de întrebări și răspunsuri</a> colectate de pe ConQUIZtador. Adică fișierele <code>rapide.txt</code> și <code>grila.txt</code>. Dar pentru că <code>ocrad</code> nu detectează diacriticele corect, trebuie să scap de ele.</p>
		<pre class="fara">iconv -f utf-8 -t ascii//TRANSLIT rapide.txt &gt; rapide.txt.ascii
iconv -f utf-8 -t ascii//TRANSLIT grila.txt &gt; grila.txt.ascii
rm grila.txt rapide.txt
mv rapide.txt.ascii rapide.txt
mv grila.txt.ascii grila.txt</pre>
		<p>Acum am nevoie de un progrămel care să caute întrebarea cea mai apropiată. Mă gândeam să folosesc <a href="http://en.wikipedia.org/wiki/Levenshtein_distance">algoritmul Levenshtein</a>, dar eu am nevoie să găsesc un răspuns cât mai rapid! Așa că am facut un program C cu o metodă proprie de a afla diferența dintre două șiruri de caractere.</p>
		<p style="clear:both">Codul sursă îl scriu mai jos, dar se găsește și în fișierul <a href="gaseste.c">gaseste.c</a>.</p>

		<table class="highlighttable"><tr><td class="linenos"><pre> 1
 2
 3
 4
 5
 6
 7
 8
 9
10
11
12
13
14
15
16
17
18
19
20
21
22
23
24
25
26
27
28
29
30
31
32
33
34
35
36
37
38
39
40
41
42
43
44
45
46
47
48
49
50
51
52
53
54
55
56
57
58
59
60
61
62
63
64
65
66
67
68
69
70
71
72
73
74
75
76
77
78
79
80
81
82
83
84
85
86
87
88
89
90
91
92
93
94
95
96
97
98</pre></td><td class="code"><div class="highlight"><pre><span class="c">/*</span>
<span class="c"> * Nume: gaseste</span>
<span class="c"> * Autor: Paul Nechifor &lt;irragal@gmail.com&gt;</span>
<span class="c"> * Inceput: 23.07.2009</span>
<span class="c"> * Terminat: 23.07.2009</span>
<span class="c"> * Descriere: Citeste o intrebare de la stdin si cauta intrebarea cea mai apropiata</span>
<span class="c"> * din fisierele rapide.txt si grila.txt.</span>
<span class="c">*/</span>

<span class="cp">#include &lt;stdio.h&gt;</span>
<span class="cp">#include &lt;string.h&gt;</span>
<span class="cp">#include &lt;stdlib.h&gt;</span>

<span class="cp">#define LUNG 5</span>

<span class="k">struct</span> <span class="n">intrebare</span>
<span class="p">{</span>
	<span class="kt">char</span> <span class="n">text</span><span class="p">[</span><span class="mi">300</span><span class="p">];</span>
	<span class="kt">char</span> <span class="n">rasp</span><span class="p">[</span><span class="mi">200</span><span class="p">];</span>
<span class="p">};</span>

<span class="k">struct</span> <span class="n">intrebare</span> <span class="n">intrebari</span><span class="p">[</span><span class="mi">30000</span><span class="p">];</span>
<span class="kt">int</span> <span class="n">nr</span> <span class="o">=</span> <span class="mi">0</span><span class="p">;</span>

<span class="kt">int</span> <span class="nf">scorPentru</span><span class="p">(</span><span class="kt">char</span><span class="o">*</span> <span class="n">corect</span><span class="p">,</span> <span class="kt">char</span><span class="o">*</span> <span class="n">verific</span><span class="p">)</span>
<span class="p">{</span>
	<span class="kt">int</span> <span class="n">i</span><span class="p">,</span> <span class="n">scor</span> <span class="o">=</span> <span class="mi">0</span><span class="p">,</span> <span class="n">lc</span> <span class="o">=</span> <span class="n">strlen</span><span class="p">(</span><span class="n">corect</span><span class="p">)</span> <span class="o">-</span> <span class="n">LUNG</span><span class="p">;</span>
	<span class="kt">char</span> <span class="n">save</span><span class="p">;</span>

	<span class="k">for</span> <span class="p">(</span><span class="n">i</span><span class="o">=</span><span class="mi">0</span><span class="p">;</span> <span class="n">i</span><span class="o">&lt;</span><span class="n">lc</span><span class="p">;</span> <span class="n">i</span><span class="o">++</span><span class="p">)</span>
	<span class="p">{</span>
		<span class="n">save</span> <span class="o">=</span> <span class="n">corect</span><span class="p">[</span><span class="n">i</span> <span class="o">+</span> <span class="n">LUNG</span><span class="p">];</span>
		<span class="n">corect</span><span class="p">[</span><span class="n">i</span> <span class="o">+</span> <span class="n">LUNG</span><span class="p">]</span> <span class="o">=</span> <span class="mi">0</span><span class="p">;</span>
		<span class="k">if</span> <span class="p">(</span><span class="n">strstr</span><span class="p">(</span><span class="n">verific</span><span class="p">,</span> <span class="o">&amp;</span><span class="n">corect</span><span class="p">[</span><span class="n">i</span><span class="p">])</span> <span class="o">==</span> <span class="nb">NULL</span><span class="p">)</span> <span class="n">scor</span><span class="o">++</span><span class="p">;</span>
		<span class="n">corect</span><span class="p">[</span><span class="n">i</span> <span class="o">+</span> <span class="n">LUNG</span><span class="p">]</span> <span class="o">=</span> <span class="n">save</span><span class="p">;</span>
	<span class="p">}</span>
	<span class="k">return</span> <span class="n">scor</span><span class="p">;</span>
<span class="p">}</span>

<span class="kt">void</span> <span class="nf">incarcaRapide</span><span class="p">()</span>
<span class="p">{</span>
	<span class="kt">int</span> <span class="n">i</span><span class="p">;</span>
	<span class="n">FILE</span><span class="o">*</span> <span class="n">rapide</span> <span class="o">=</span> <span class="n">fopen</span><span class="p">(</span><span class="s">&quot;rapide.txt&quot;</span><span class="p">,</span> <span class="s">&quot;r&quot;</span><span class="p">);</span>
	<span class="k">if</span> <span class="p">(</span><span class="o">!</span><span class="n">rapide</span><span class="p">)</span> <span class="p">{</span> <span class="n">printf</span><span class="p">(</span><span class="s">&quot;Nu exista &#39;rapide.txt&#39;!</span><span class="se">\n</span><span class="s">&quot;</span><span class="p">);</span>   <span class="n">exit</span><span class="p">(</span><span class="mi">4</span><span class="p">);</span> <span class="p">}</span>

	<span class="k">while</span> <span class="p">(</span><span class="n">fgets</span><span class="p">(</span><span class="n">intrebari</span><span class="p">[</span><span class="n">nr</span><span class="p">].</span><span class="n">text</span><span class="p">,</span> <span class="mi">300</span><span class="p">,</span> <span class="n">rapide</span><span class="p">))</span>
	<span class="p">{</span>
		<span class="n">intrebari</span><span class="p">[</span><span class="n">nr</span><span class="p">].</span><span class="n">text</span><span class="p">[</span><span class="n">strlen</span><span class="p">(</span><span class="n">intrebari</span><span class="p">[</span><span class="n">nr</span><span class="p">].</span><span class="n">text</span><span class="p">)</span><span class="o">-</span><span class="mi">1</span><span class="p">]</span> <span class="o">=</span> <span class="mi">0</span><span class="p">;</span>
		<span class="n">fgets</span><span class="p">(</span><span class="n">intrebari</span><span class="p">[</span><span class="n">nr</span><span class="p">].</span><span class="n">rasp</span><span class="p">,</span> <span class="mi">10</span><span class="p">,</span> <span class="n">rapide</span><span class="p">);</span>
		<span class="n">nr</span><span class="o">++</span><span class="p">;</span>
	<span class="p">}</span>
	<span class="n">fclose</span><span class="p">(</span><span class="n">rapide</span><span class="p">);</span>
<span class="p">}</span>
<span class="kt">void</span> <span class="nf">incarcaGrila</span><span class="p">()</span>
<span class="p">{</span>
	<span class="kt">char</span> <span class="n">r</span><span class="p">[</span><span class="mi">4</span><span class="p">][</span><span class="mi">200</span><span class="p">];</span>
	<span class="kt">int</span> <span class="n">i</span><span class="p">,</span> <span class="n">c</span><span class="p">;</span>
	<span class="n">FILE</span><span class="o">*</span> <span class="n">grila</span> <span class="o">=</span> <span class="n">fopen</span><span class="p">(</span><span class="s">&quot;grila.txt&quot;</span><span class="p">,</span> <span class="s">&quot;r&quot;</span><span class="p">);</span>
	<span class="k">if</span> <span class="p">(</span><span class="o">!</span><span class="n">grila</span><span class="p">)</span> <span class="p">{</span> <span class="n">printf</span><span class="p">(</span><span class="s">&quot;Nu exista &#39;grila.txt&#39;!</span><span class="se">\n</span><span class="s">&quot;</span><span class="p">);</span>   <span class="n">exit</span><span class="p">(</span><span class="mi">3</span><span class="p">);</span> <span class="p">}</span>

	<span class="k">while</span> <span class="p">(</span><span class="n">fgets</span><span class="p">(</span><span class="n">intrebari</span><span class="p">[</span><span class="n">nr</span><span class="p">].</span><span class="n">text</span><span class="p">,</span> <span class="mi">300</span><span class="p">,</span> <span class="n">grila</span><span class="p">))</span>
	<span class="p">{</span>
		<span class="n">intrebari</span><span class="p">[</span><span class="n">nr</span><span class="p">].</span><span class="n">text</span><span class="p">[</span><span class="n">strlen</span><span class="p">(</span><span class="n">intrebari</span><span class="p">[</span><span class="n">nr</span><span class="p">].</span><span class="n">text</span><span class="p">)</span><span class="o">-</span><span class="mi">1</span><span class="p">]</span> <span class="o">=</span> <span class="mi">0</span><span class="p">;</span>
		<span class="k">for</span> <span class="p">(</span><span class="n">i</span><span class="o">=</span><span class="mi">0</span><span class="p">;</span> <span class="n">i</span><span class="o">&lt;</span><span class="mi">4</span><span class="p">;</span> <span class="n">i</span><span class="o">++</span><span class="p">)</span>
			<span class="n">fgets</span><span class="p">(</span><span class="n">r</span><span class="p">[</span><span class="n">i</span><span class="p">],</span> <span class="mi">200</span><span class="p">,</span> <span class="n">grila</span><span class="p">);</span>
		<span class="n">fscanf</span><span class="p">(</span><span class="n">grila</span><span class="p">,</span> <span class="s">&quot;%d</span><span class="se">\n</span><span class="s">&quot;</span><span class="p">,</span> <span class="o">&amp;</span><span class="n">c</span><span class="p">);</span>
		<span class="n">strcpy</span><span class="p">(</span><span class="n">intrebari</span><span class="p">[</span><span class="n">nr</span><span class="p">].</span><span class="n">rasp</span><span class="p">,</span> <span class="n">r</span><span class="p">[</span><span class="n">c</span><span class="o">-</span><span class="mi">1</span><span class="p">]);</span>
		<span class="n">nr</span><span class="o">++</span><span class="p">;</span>
	<span class="p">}</span>
	<span class="n">fclose</span><span class="p">(</span><span class="n">grila</span><span class="p">);</span>
<span class="p">}</span>

<span class="kt">int</span> <span class="nf">main</span><span class="p">(</span><span class="kt">int</span> <span class="n">argc</span><span class="p">,</span> <span class="kt">char</span> <span class="o">*</span><span class="n">argv</span><span class="p">[])</span>
<span class="p">{</span>
	<span class="kt">int</span> <span class="n">i</span><span class="p">,</span> <span class="n">s</span><span class="p">,</span> <span class="n">min</span> <span class="o">=</span> <span class="mi">9999</span><span class="p">;</span>
	<span class="kt">char</span> <span class="n">cauta</span><span class="p">[</span><span class="mi">1000</span><span class="p">];</span>
	
	<span class="k">if</span> <span class="p">(</span><span class="n">argc</span> <span class="o">&lt;</span> <span class="mi">2</span><span class="p">)</span> <span class="p">{</span> <span class="n">printf</span><span class="p">(</span><span class="s">&quot;Utilizare: %s grila|rapid</span><span class="se">\n</span><span class="s">&quot;</span><span class="p">,</span> <span class="n">argv</span><span class="p">[</span><span class="mi">0</span><span class="p">]);</span>   <span class="n">exit</span><span class="p">(</span><span class="mi">1</span><span class="p">);</span> <span class="p">}</span>

	<span class="k">if</span> <span class="p">(</span><span class="o">!</span><span class="n">strcmp</span><span class="p">(</span><span class="n">argv</span><span class="p">[</span><span class="mi">1</span><span class="p">],</span> <span class="s">&quot;rapid&quot;</span><span class="p">))</span> <span class="n">incarcaRapide</span><span class="p">();</span>
	<span class="k">else</span> <span class="k">if</span> <span class="p">(</span><span class="o">!</span><span class="n">strcmp</span><span class="p">(</span><span class="n">argv</span><span class="p">[</span><span class="mi">1</span><span class="p">],</span> <span class="s">&quot;grila&quot;</span><span class="p">))</span> <span class="n">incarcaGrila</span><span class="p">();</span>
	<span class="k">else</span> <span class="p">{</span> <span class="n">printf</span><span class="p">(</span><span class="s">&quot;Trebuie grila sau rapid</span><span class="se">\n</span><span class="s">&quot;</span><span class="p">);</span>   <span class="n">exit</span><span class="p">(</span><span class="mi">2</span><span class="p">);</span> <span class="p">}</span>

	<span class="n">fgets</span><span class="p">(</span><span class="n">cauta</span><span class="p">,</span> <span class="k">sizeof</span><span class="p">(</span><span class="n">cauta</span><span class="p">),</span> <span class="n">stdin</span><span class="p">);</span>

	<span class="k">for</span> <span class="p">(</span><span class="n">i</span><span class="o">=</span><span class="mi">0</span><span class="p">;</span> <span class="n">i</span><span class="o">&lt;</span><span class="n">nr</span><span class="p">;</span> <span class="n">i</span><span class="o">++</span><span class="p">)</span>
	<span class="p">{</span>
		<span class="n">s</span> <span class="o">=</span> <span class="n">scorPentru</span><span class="p">(</span><span class="n">cauta</span><span class="p">,</span> <span class="n">intrebari</span><span class="p">[</span><span class="n">i</span><span class="p">].</span><span class="n">text</span><span class="p">);</span>
		<span class="k">if</span> <span class="p">(</span><span class="n">s</span> <span class="o">&lt;</span> <span class="n">min</span><span class="p">)</span>
		<span class="p">{</span>
			<span class="n">min</span> <span class="o">=</span> <span class="n">s</span><span class="p">;</span>
			<span class="n">printf</span><span class="p">(</span><span class="s">&quot;%s</span><span class="se">\n</span><span class="s">&quot;</span><span class="p">,</span> <span class="n">intrebari</span><span class="p">[</span><span class="n">i</span><span class="p">].</span><span class="n">text</span><span class="p">);</span>
			<span class="n">printf</span><span class="p">(</span><span class="s">&quot;S:%-4d  CORECT: %s&quot;</span><span class="p">,</span> <span class="n">s</span><span class="p">,</span> <span class="n">intrebari</span><span class="p">[</span><span class="n">i</span><span class="p">].</span><span class="n">rasp</span><span class="p">);</span>
		<span class="p">}</span>
	<span class="p">}</span>

	<span class="k">return</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>
</pre></div>
</td></tr></table>

		<p>Acum mai am nevoie doar de o metodă simplă de a alege tipul de întrebare. Am făcut un script Python care folosește <code>xlib</code> (adică <code>apt-get install python-xlib</code> pentru a instala pe Ubuntu) pentru a asculta tastele. Am ales patru butoane. Când apas, scriptul apelează programele <code>import</code> și <code>ocrad</code> pentru a primi textul. Apoi apelează programul <code>gaseste</code>.</p>
		<p style="clear:both">Codul sursă îl scriu mai jos, dar se găsește și în fișierul <a href="conquizocr">conquizocr.py</a>.</p>

		<table class="highlighttable"><tr><td class="linenos"><pre> 1
 2
 3
 4
 5
 6
 7
 8
 9
10
11
12
13
14
15
16
17
18
19
20
21
22
23
24
25
26
27
28
29
30
31
32
33
34
35
36
37
38
39
40
41
42
43
44
45
46
47
48
49
50
51
52
53
54
55
56
57
58
59
60
61
62</pre></td><td class="code"><div class="highlight"><pre><span class="c">#!/usr/bin/env python</span>

<span class="c"># Nume: conquiz_ocr</span>
<span class="c"># Autor: Paul Nechifor &lt;irragal@gmail.com&gt;</span>
<span class="c"># Inceput: 22.07.2009</span>
<span class="c"># Terminat: 23.07.2009</span>
<span class="c"># Descriere: Programul asta asculta niste taste folosind xlib si face screenshot la o intrebare</span>
<span class="c"># de pe ConQUIZtador. Apoi citeste textul din imagine si cauta in baza de date raspunsul.</span>

<span class="k">import</span> <span class="nn">os</span><span class="o">,</span> <span class="nn">re</span>
<span class="k">from</span> <span class="nn">Xlib.display</span> <span class="k">import</span> <span class="n">Display</span>
<span class="k">from</span> <span class="nn">Xlib</span> <span class="k">import</span> <span class="n">X</span>

<span class="n">dinNou</span> <span class="o">=</span> <span class="bp">False</span>

<span class="k">def</span> <span class="nf">handle_event</span><span class="p">(</span><span class="n">event</span><span class="p">):</span>
	<span class="n">keycode</span> <span class="o">=</span> <span class="n">event</span><span class="o">.</span><span class="n">detail</span>
	<span class="k">global</span> <span class="n">dinNou</span>

	<span class="k">if</span> <span class="n">keycode</span> <span class="o">==</span> <span class="mf">51</span><span class="p">:</span>
		<span class="n">dinNou</span> <span class="o">=</span> <span class="bp">True</span>
		<span class="k">print</span> <span class="s">&quot;</span><span class="se">\n</span><span class="s">&quot;</span> <span class="o">*</span> <span class="mf">50</span>
		<span class="k">return</span>
	
	<span class="k">if</span> <span class="ow">not</span> <span class="n">dinNou</span><span class="p">:</span> <span class="k">return</span>
	<span class="n">dinNou</span> <span class="o">=</span> <span class="bp">False</span>

	<span class="k">if</span> <span class="n">keycode</span> <span class="o">==</span> <span class="mf">47</span><span class="p">:</span>
		<span class="n">marime</span> <span class="o">=</span> <span class="s">&quot;900x110+105+382&quot;</span> <span class="c"># pentru intrebare rapida</span>
	<span class="k">elif</span> <span class="n">keycode</span> <span class="o">==</span> <span class="mf">48</span> <span class="ow">or</span> <span class="n">keycode</span> <span class="o">==</span> <span class="mf">34</span><span class="p">:</span> 
		<span class="n">marime</span> <span class="o">=</span> <span class="s">&quot;1230x100+105+424&quot;</span> <span class="c"># pentru intrebare grila si intrebare rapida care vine dupa intrebare grila</span>

	<span class="c"># folosesc programul import pentru a face un screenshot, care este salvat ca &#39;imagine.pbm&#39;</span>
	<span class="n">os</span><span class="o">.</span><span class="n">system</span><span class="p">(</span><span class="s">&quot;import -window root -crop &quot;</span> <span class="o">+</span> <span class="n">marime</span> <span class="o">+</span> <span class="s">&quot; imagine.pbm&quot;</span><span class="p">)</span>
	<span class="c"># folosesc programul ocrad pentru a citi textul din imagine</span>
	<span class="n">text</span> <span class="o">=</span> <span class="n">os</span><span class="o">.</span><span class="n">popen</span><span class="p">(</span><span class="s">&quot;ocrad --charset=ascii -i imagine.pbm&quot;</span><span class="p">)</span><span class="o">.</span><span class="n">read</span><span class="p">()</span>
	<span class="c"># inlocuiesc mai multe caractere albe cu un singur spatiu</span>
	<span class="n">text</span> <span class="o">=</span> <span class="n">re</span><span class="o">.</span><span class="n">sub</span><span class="p">(</span><span class="s">&quot;[ </span><span class="se">\t\n</span><span class="s">]+&quot;</span><span class="p">,</span> <span class="s">&quot; &quot;</span><span class="p">,</span> <span class="n">text</span><span class="p">)</span>
	<span class="c"># &#39;escape&#39; pentru apostrof si slash</span>
	<span class="n">text</span><span class="o">.</span><span class="n">replace</span><span class="p">(</span><span class="s">&quot;</span><span class="se">\\</span><span class="s">&quot;</span><span class="p">,</span> <span class="s">&quot;</span><span class="se">\\\\</span><span class="s">&quot;</span><span class="p">)</span><span class="o">.</span><span class="n">replace</span><span class="p">(</span><span class="s">&quot;&#39;&quot;</span><span class="p">,</span> <span class="s">&quot;</span><span class="se">\\</span><span class="s">&#39;&quot;</span><span class="p">)</span>

	<span class="c"># apelez programul C care trebuie sa gasesca intrebarea cea mai apropiata si raspunsul</span>
	<span class="k">if</span> <span class="n">keycode</span> <span class="o">==</span> <span class="mf">48</span><span class="p">:</span>
		<span class="n">os</span><span class="o">.</span><span class="n">system</span><span class="p">(</span><span class="s">&quot;echo &#39;&quot;</span> <span class="o">+</span> <span class="n">text</span> <span class="o">+</span> <span class="s">&quot;&#39; | ./gaseste grila&quot;</span><span class="p">)</span>
	<span class="k">elif</span> <span class="n">keycode</span> <span class="o">==</span> <span class="mf">34</span> <span class="ow">or</span> <span class="n">keycode</span> <span class="o">==</span> <span class="mf">47</span><span class="p">:</span>
		<span class="n">os</span><span class="o">.</span><span class="n">system</span><span class="p">(</span><span class="s">&quot;echo &#39;&quot;</span> <span class="o">+</span> <span class="n">text</span> <span class="o">+</span> <span class="s">&quot;&#39; | ./gaseste rapid&quot;</span><span class="p">)</span>
	
	<span class="k">print</span> <span class="s">&quot;=&quot;</span> <span class="o">*</span> <span class="mf">100</span><span class="p">,</span>

<span class="c"># current display</span>
<span class="kp">disp</span> <span class="o">=</span> <span class="n">Display</span><span class="p">()</span>
<span class="n">root</span> <span class="o">=</span> <span class="kp">disp</span><span class="o">.</span><span class="n">screen</span><span class="p">()</span><span class="o">.</span><span class="n">root</span>

<span class="c"># we tell the X server we want to catch keyPress event</span>
<span class="n">root</span><span class="o">.</span><span class="n">change_attributes</span><span class="p">(</span><span class="n">event_mask</span> <span class="o">=</span> <span class="n">X</span><span class="o">.</span><span class="n">KeyPressMask</span><span class="p">)</span>

<span class="k">for</span> <span class="n">keycode</span> <span class="ow">in</span> <span class="p">[</span><span class="mf">47</span><span class="p">,</span> <span class="mf">48</span><span class="p">,</span> <span class="mf">34</span><span class="p">,</span> <span class="mf">51</span><span class="p">]:</span> <span class="c"># tastele sunt ;&#39;[\</span>
	<span class="n">root</span><span class="o">.</span><span class="n">grab_key</span><span class="p">(</span><span class="n">keycode</span><span class="p">,</span> <span class="n">X</span><span class="o">.</span><span class="n">AnyModifier</span><span class="p">,</span> <span class="mf">1</span><span class="p">,</span> <span class="n">X</span><span class="o">.</span><span class="n">GrabModeAsync</span><span class="p">,</span> <span class="n">X</span><span class="o">.</span><span class="n">GrabModeAsync</span><span class="p">)</span>

<span class="k">while</span> <span class="mf">1</span><span class="p">:</span>
	<span class="n">event</span> <span class="o">=</span> <span class="n">root</span><span class="o">.</span><span class="n">display</span><span class="o">.</span><span class="n">next_event</span><span class="p">()</span>
	<span class="n">handle_event</span><span class="p">(</span><span class="n">event</span><span class="p">)</span>
</pre></div>
</td></tr></table>
<?php
	afiseazaSubsol('ro');
}	
?>
