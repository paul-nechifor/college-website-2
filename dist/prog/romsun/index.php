<?php
require '../../include/capete.php';
if ($_GET['limba'] == "en")
{
	afiseazaAntet('How to make the computer speak romanian', '', '', 'en');
	?>
		<h2>How to make the computer speak romanian</h2>
		<p>I don't see why you would want to read this in english!</p>
<?php
	afiseazaSubsol('en');
}
else
{
	afiseazaAntet('Cum să faci calculatorul să vorbească română', '', '', 'ro');	
	?>
		<h2>Cum să faci calculatorul să vorbească română</h2>

		<p>Ai nevoie de programul <code>mbrola</code> care se găsește pentru foarte multe sisteme de operare și baza de date în română (<a href="http://tcts.fpms.ac.be/synthesis/mbrola/dba/ro1/ro1-980317.zip">ro1</a>). Astea se găsesc pe <a href="http://tcts.fpms.ac.be/synthesis/mbrola/mbrcopybin.html">saitul MBROLA</a>.</p>
		<p>De asemenea ai nevoie de un player de fișiere WAV care funcționează din consolă. Eu am folosit programul <code>play</code> care se găsește în pachetul <code>sox</code> (Sound Exchange) în Ubuntu și Arch Linux.</p>
		<p>De pe saitul ăsta ai nevoie de programul Python <code>romsun.py</code> și <a href="dictionar">dictionarul</a>. Acest program nu încearcă să ghicească citirea pentru cuvintele scrise fără diacritice sau cu diacritice greșite (adică „ș“ și „ț“ cu sedilă în loc de virgulă sau „ă“ cu tilda). Deci dacă nu poți să scrii cu literele corecte... nasol.</p>
		<p>Colorarea sursei am obținut-o cu <code>pygmentize -o romsun.html -O style=colorful,linenos=1 romsun.py</code></p>
		<p>Codul sursă îl scriu mai jos, dar se găsește și în fișierul <a href="romsun.py">romsun.py</a>.</p>
<table class="highlighttable"><tr><td class="linenos"><pre>  1
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
 98
 99
100
101
102
103
104
105
106
107
108
109
110
111
112
113
114
115
116
117
118
119
120
121
122
123
124
125
126
127
128
129
130
131
132
133
134
135
136
137
138
139
140
141
142
143
144
145
146
147
148
149
150
151
152
153
154
155
156
157
158
159
160
161
162
163
164
165
166
167
168
169
170
171
172
173
174
175
176
177
178
179
180
181
182
183
184
185
186
187
188
189
190
191
192
193
194
195
196
197
198
199
200
201
202
203
204
205
206
207
208
209
210
211
212
213
214
215
216
217
218
219
220
221
222
223
224
225
226
227
228
229
230
231
232
233
234
235
236
237
238
239
240
241
242
243
244
245
246
247
248
249
250
251
252
253
254
255
256
257
258
259
260
261
262
263
264
265
266
267
268
269
270
271
272
273
274
275
276
277
278
279
280
281
282
283
284
285
286
287
288
289
290
291
292
293
294
295
296
297
298
299
300
301
302
303
304
305
306
307
308
309
310
311
312
313
314
315
316
317
318
319
320
321
322
323
324
325
326
327
328
329
330</pre></td><td class="code"><div class="highlight"><pre><span class="c">#!/usr/bin/env python</span>
<span class="c"># -*- coding: UTF-8 -*-</span>

<span class="c"># Nume: Romsun - Generator sunete pentru texte în română.</span>
<span class="c"># Autor: Paul Nechifor &lt;irragal@gmail.com&gt;</span>
<span class="c"># Inceput: 01.08.2009</span>
<span class="c"># Terminat: 12.08.2009</span>

<span class="s">u&quot;&quot;&quot;Romsun - Generator sunete pentru texte în română.</span>

<span class="s">Acest script python transformă texte scrise în limba română în fișiere .pho care</span>
<span class="s">pot fi citite de către programul mbrola pentru a produce fișiere audio.</span>

<span class="s">Folosire: python romsun.py [opțiuni]</span>

<span class="s">Opțiuni:</span>
<span class="s">  -t ..., --text=...       Fișierul care va fi citit. Dacă nu este specificat,</span>
<span class="s">                           se citește de la intrarea standard.</span>
<span class="s">  -s ..., --scriere=...    Unde va fi scris fișierul. Dacă nu este specificat,</span>
<span class="s">                           se scrie la ieșirea standard.</span>
<span class="s">  -v xx, --viteza=xx       Coeficientul cu care este înmulțită durata sunetelor.</span>
<span class="s">                           Dacă nu este specificat valoarea va fi 0,75.</span>
<span class="s">  -p xx, --spatii=xx       Coeficientul cu care este înmulțită durata spațiilor.</span>
<span class="s">                           Dacă nu este specificat valoarea va fi 0,20.</span>
<span class="s">  -h, --help               Arată textul ăsta.</span>

<span class="s">Exemple:</span>
<span class="s">  ./romsun.py -t exemplu.txt -s exemplu.pho</span>
<span class="s">  ./romsun.py --viteza=0,50 -t exemplu.txt -s exemplu.pho</span>
<span class="s">&quot;&quot;&quot;</span>

<span class="k">import</span> <span class="nn">random</span><span class="o">,</span> <span class="nn">os</span><span class="o">,</span> <span class="nn">sys</span><span class="o">,</span> <span class="nn">types</span><span class="o">,</span> <span class="nn">getopt</span>

<span class="n">_viteza</span> <span class="o">=</span> <span class="mf">0.75</span>
<span class="n">_vspatii</span> <span class="o">=</span> <span class="mf">0.20</span>
<span class="n">dictionar</span> <span class="o">=</span> <span class="p">{}</span>
<span class="k">for</span> <span class="n">linie</span> <span class="ow">in</span> <span class="nb">open</span><span class="p">(</span><span class="s">&#39;dictionar&#39;</span><span class="p">,</span> <span class="s">&#39;r&#39;</span><span class="p">):</span>
	<span class="n">p</span> <span class="o">=</span> <span class="nb">unicode</span><span class="p">(</span><span class="n">linie</span><span class="o">.</span><span class="n">strip</span><span class="p">(),</span> <span class="s">&#39;utf-8&#39;</span><span class="p">)</span><span class="o">.</span><span class="kp">split</span><span class="p">(</span><span class="s">&#39;=&#39;</span><span class="p">)</span>
	<span class="k">if</span> <span class="nb">len</span><span class="p">(</span><span class="n">p</span><span class="p">[</span><span class="mf">0</span><span class="p">])</span> <span class="o">&gt;</span> <span class="mf">0</span><span class="p">:</span> <span class="n">dictionar</span><span class="p">[</span><span class="n">p</span><span class="p">[</span><span class="mf">0</span><span class="p">]]</span> <span class="o">=</span> <span class="n">p</span><span class="p">[</span><span class="mf">1</span><span class="p">]</span>

<span class="k">class</span> <span class="nc">EroareNumar</span><span class="p">(</span><span class="ne">Exception</span><span class="p">):</span> <span class="k">pass</span>
<span class="k">class</span> <span class="nc">EroareSunet</span><span class="p">(</span><span class="ne">Exception</span><span class="p">):</span> <span class="k">pass</span>
<span class="k">class</span> <span class="nc">EroareCitire</span><span class="p">(</span><span class="ne">Exception</span><span class="p">):</span> <span class="k">pass</span>
<span class="k">class</span> <span class="nc">EroarePereche</span><span class="p">(</span><span class="ne">Exception</span><span class="p">):</span> <span class="k">pass</span>

<span class="k">def</span> <span class="nf">urm</span><span class="p">(</span><span class="n">s</span><span class="p">,</span> <span class="n">i</span><span class="p">):</span>
	<span class="k">if</span> <span class="n">i</span> <span class="o">&lt;</span> <span class="nb">len</span><span class="p">(</span><span class="n">s</span><span class="p">)</span> <span class="ow">and</span> <span class="n">i</span> <span class="o">&gt;=</span> <span class="mf">0</span><span class="p">:</span> <span class="k">return</span> <span class="n">s</span><span class="p">[</span><span class="n">i</span><span class="p">]</span>
	<span class="k">else</span><span class="p">:</span> <span class="k">return</span> <span class="s">&quot;&quot;</span>

<span class="k">def</span> <span class="nf">scriereNumar</span><span class="p">(</span><span class="n">n</span><span class="p">,</span> <span class="n">m</span><span class="o">=</span><span class="bp">True</span><span class="p">,</span> <span class="n">nr</span><span class="o">=</span><span class="bp">True</span><span class="p">):</span>
	<span class="s">u&quot;&quot;&quot;Scrie cum se citește un număr primit ca int sau float în parametrul n.&quot;&quot;&quot;</span>

	<span class="n">p</span> <span class="o">=</span> <span class="p">[</span><span class="s">&#39;zero&#39;</span><span class="p">,</span> <span class="s">&#39;unu&#39;</span><span class="p">,</span> <span class="s">&#39;doi&#39;</span><span class="p">,</span> <span class="s">&#39;trei&#39;</span><span class="p">,</span> <span class="s">&#39;patru&#39;</span><span class="p">,</span> <span class="s">&#39;cinci&#39;</span><span class="p">,</span> <span class="s">u&#39;șase&#39;</span><span class="p">,</span> <span class="s">u&#39;șapte&#39;</span><span class="p">,</span> <span class="s">&#39;opt&#39;</span><span class="p">,</span> <span class="s">u&#39;nouă&#39;</span><span class="p">,</span> <span class="s">&#39;zece&#39;</span><span class="p">,</span> \
		<span class="s">&#39;unsprezece&#39;</span><span class="p">,</span> <span class="s">&#39;doisprezece&#39;</span><span class="p">,</span> <span class="s">&#39;treisprezece&#39;</span><span class="p">,</span> <span class="s">&#39;paisprezece&#39;</span><span class="p">,</span> <span class="s">&#39;cincisprezece&#39;</span><span class="p">,</span> <span class="s">u&#39;șaisprezece&#39;</span><span class="p">,</span> \
		<span class="s">u&#39;șaptisprezece&#39;</span><span class="p">,</span> <span class="s">&#39;optisprezece&#39;</span><span class="p">,</span> <span class="s">u&#39;nouăsprezece&#39;</span><span class="p">]</span>
	<span class="n">zeci</span> <span class="o">=</span> <span class="p">(</span><span class="s">u&#39;douăzeci&#39;</span><span class="p">,</span> <span class="s">u&#39;treizeci&#39;</span><span class="p">,</span> <span class="s">u&#39;patruzeci&#39;</span><span class="p">,</span> <span class="s">u&#39;cincizeci&#39;</span><span class="p">,</span> <span class="s">u&#39;șaizeci&#39;</span><span class="p">,</span> <span class="s">u&#39;șaptezeci&#39;</span><span class="p">,</span> <span class="s">u&#39;optzeci&#39;</span><span class="p">,</span> <span class="s">u&#39;nouăzeci&#39;</span><span class="p">)</span>
	<span class="n">ms</span> <span class="o">=</span> <span class="p">(</span><span class="s">&#39;o mie&#39;</span><span class="p">,</span> <span class="s">&#39;un milion&#39;</span><span class="p">,</span> <span class="s">&#39;un miliard&#39;</span><span class="p">,</span> <span class="s">&#39;un trilion&#39;</span><span class="p">)</span>
	<span class="n">mp</span> <span class="o">=</span> <span class="p">(</span><span class="s">&#39;mii&#39;</span><span class="p">,</span> <span class="s">&#39;milioane&#39;</span><span class="p">,</span> <span class="s">&#39;miliarde&#39;</span><span class="p">,</span> <span class="s">&#39;trilioane&#39;</span><span class="p">)</span>

	<span class="k">if</span> <span class="n">m</span><span class="p">:</span>
		<span class="k">if</span> <span class="n">nr</span><span class="p">:</span> <span class="k">pass</span>
		<span class="k">else</span><span class="p">:</span>
			<span class="n">p</span><span class="p">[</span><span class="mf">0</span><span class="p">]</span> <span class="o">=</span> <span class="s">&#39;niciun&#39;</span>
			<span class="n">p</span><span class="p">[</span><span class="mf">1</span><span class="p">]</span> <span class="o">=</span> <span class="s">&#39;un&#39;</span>
	<span class="k">else</span><span class="p">:</span>
		<span class="n">p</span><span class="p">[</span><span class="mf">1</span><span class="p">]</span> <span class="o">=</span> <span class="s">&#39;o&#39;</span>
		<span class="n">p</span><span class="p">[</span><span class="mf">2</span><span class="p">]</span> <span class="o">=</span> <span class="s">u&#39;două&#39;</span>
		<span class="n">p</span><span class="p">[</span><span class="mf">12</span><span class="p">]</span> <span class="o">=</span> <span class="s">u&#39;douăsprezece&#39;</span>
		<span class="k">if</span> <span class="n">nr</span><span class="p">:</span> <span class="n">p</span><span class="p">[</span><span class="mf">1</span><span class="p">]</span> <span class="o">=</span> <span class="s">&#39;una&#39;</span>
		<span class="k">else</span><span class="p">:</span>
			<span class="n">p</span><span class="p">[</span><span class="mf">0</span><span class="p">]</span> <span class="o">=</span> <span class="s">&#39;nicio&#39;</span>
			<span class="n">p</span><span class="p">[</span><span class="mf">1</span><span class="p">]</span> <span class="o">=</span> <span class="s">&#39;o&#39;</span>

	<span class="k">if</span> <span class="n">n</span> <span class="o">==</span> <span class="mf">0</span><span class="p">:</span>
		<span class="k">return</span> <span class="n">p</span><span class="p">[</span><span class="mf">0</span><span class="p">]</span>
	<span class="k">elif</span> <span class="nb">type</span><span class="p">(</span><span class="n">n</span><span class="p">)</span> <span class="o">==</span> <span class="n">types</span><span class="o">.</span><span class="n">FloatType</span><span class="p">:</span>
		<span class="n">s</span> <span class="o">=</span> <span class="nb">str</span><span class="p">(</span><span class="n">n</span><span class="p">)</span><span class="o">.</span><span class="kp">split</span><span class="p">(</span><span class="s">&quot;.&quot;</span><span class="p">)</span>
		<span class="k">return</span> <span class="n">scriereNumar</span><span class="p">(</span><span class="nb">int</span><span class="p">(</span><span class="n">s</span><span class="p">[</span><span class="mf">0</span><span class="p">]),</span> <span class="n">m</span><span class="p">,</span> <span class="n">nr</span><span class="p">)</span> <span class="o">+</span> <span class="s">u&#39; virgulă &#39;</span> <span class="o">+</span> <span class="n">scriereNumar</span><span class="p">(</span><span class="nb">int</span><span class="p">(</span><span class="n">s</span><span class="p">[</span><span class="mf">1</span><span class="p">]),</span> <span class="n">m</span><span class="p">,</span> <span class="n">nr</span><span class="p">)</span>
	<span class="k">elif</span> <span class="n">n</span> <span class="o">&lt;</span> <span class="mf">0</span><span class="p">:</span>
		<span class="k">return</span> <span class="s">&#39;minus &#39;</span> <span class="o">+</span> <span class="n">scriereNumar</span><span class="p">(</span><span class="nb">abs</span><span class="p">(</span><span class="n">n</span><span class="p">),</span> <span class="n">m</span><span class="p">,</span> <span class="n">nr</span><span class="p">)</span>
	<span class="k">elif</span> <span class="n">n</span> <span class="o">&lt;</span> <span class="mf">20</span><span class="p">:</span>
		<span class="k">return</span> <span class="n">p</span><span class="p">[</span><span class="n">n</span><span class="p">]</span>
	<span class="k">elif</span> <span class="n">n</span> <span class="o">&lt;</span> <span class="mf">100</span><span class="p">:</span>
		<span class="k">if</span> <span class="n">n</span> <span class="o">%</span> <span class="mf">10</span> <span class="o">==</span> <span class="mf">0</span><span class="p">:</span> <span class="k">return</span> <span class="n">zeci</span><span class="p">[</span><span class="n">n</span><span class="o">/</span><span class="mf">10</span> <span class="o">-</span> <span class="mf">2</span><span class="p">]</span>
		<span class="k">if</span> <span class="n">m</span><span class="p">:</span> <span class="n">p</span><span class="p">[</span><span class="mf">1</span><span class="p">]</span> <span class="o">=</span> <span class="s">&#39;unu&#39;</span>
		<span class="k">else</span><span class="p">:</span> <span class="n">p</span><span class="p">[</span><span class="mf">1</span><span class="p">]</span> <span class="o">=</span> <span class="s">&#39;una&#39;</span>
		<span class="k">return</span>  <span class="n">zeci</span><span class="p">[</span><span class="n">n</span><span class="o">/</span><span class="mf">10</span> <span class="o">-</span> <span class="mf">2</span><span class="p">]</span> <span class="o">+</span> <span class="s">u&#39; și &#39;</span> <span class="o">+</span> <span class="n">p</span><span class="p">[</span><span class="n">n</span><span class="o">%</span><span class="mf">10</span><span class="p">]</span>
	<span class="k">elif</span> <span class="n">n</span> <span class="o">&lt;</span> <span class="mf">1000</span><span class="p">:</span>
		<span class="n">f</span> <span class="o">=</span> <span class="s">&#39;&#39;</span>
		<span class="n">primul</span> <span class="o">=</span> <span class="n">n</span> <span class="o">/</span> <span class="mf">100</span>
		<span class="k">if</span> <span class="n">primul</span> <span class="o">==</span> <span class="mf">1</span><span class="p">:</span> <span class="n">f</span> <span class="o">+=</span> <span class="s">u&#39;o sută&#39;</span>
		<span class="k">elif</span> <span class="n">primul</span> <span class="o">==</span> <span class="mf">2</span><span class="p">:</span> <span class="n">f</span> <span class="o">+=</span> <span class="s">u&#39;două sute&#39;</span>
		<span class="k">else</span><span class="p">:</span> <span class="n">f</span> <span class="o">+=</span> <span class="n">p</span><span class="p">[</span><span class="n">primul</span><span class="p">]</span> <span class="o">+</span> <span class="s">&#39; sute&#39;</span>
		<span class="n">n</span> <span class="o">%=</span> <span class="mf">100</span>
		<span class="k">if</span> <span class="n">n</span> <span class="o">==</span> <span class="mf">0</span><span class="p">:</span> <span class="k">return</span> <span class="n">f</span>
		<span class="k">if</span> <span class="n">m</span><span class="p">:</span> <span class="n">p</span><span class="p">[</span><span class="mf">1</span><span class="p">]</span> <span class="o">=</span> <span class="s">u&#39;unu&#39;</span>
		<span class="k">else</span><span class="p">:</span> <span class="n">p</span><span class="p">[</span><span class="mf">1</span><span class="p">]</span> <span class="o">=</span> <span class="s">u&#39;și una&#39;</span>
		<span class="k">if</span> <span class="n">n</span> <span class="o">&lt;</span> <span class="mf">20</span><span class="p">:</span> <span class="k">return</span> <span class="n">f</span> <span class="o">+</span> <span class="s">&#39; &#39;</span> <span class="o">+</span> <span class="n">p</span><span class="p">[</span><span class="n">n</span><span class="p">]</span>
		<span class="k">if</span> <span class="ow">not</span> <span class="n">m</span><span class="p">:</span> <span class="n">p</span><span class="p">[</span><span class="mf">1</span><span class="p">]</span> <span class="o">=</span> <span class="s">&#39;una&#39;</span>
		<span class="k">if</span> <span class="n">n</span> <span class="o">%</span> <span class="mf">10</span> <span class="o">==</span> <span class="mf">0</span><span class="p">:</span> <span class="k">return</span> <span class="n">f</span> <span class="o">+</span> <span class="s">&#39; &#39;</span> <span class="o">+</span> <span class="n">zeci</span><span class="p">[</span><span class="n">n</span><span class="o">/</span><span class="mf">10</span> <span class="o">-</span> <span class="mf">2</span><span class="p">]</span>
		<span class="k">return</span> <span class="n">f</span> <span class="o">+</span> <span class="s">&#39; &#39;</span> <span class="o">+</span> <span class="n">zeci</span><span class="p">[</span><span class="n">n</span><span class="o">/</span><span class="mf">10</span> <span class="o">-</span> <span class="mf">2</span><span class="p">]</span> <span class="o">+</span> <span class="s">u&#39; și &#39;</span> <span class="o">+</span> <span class="n">p</span><span class="p">[</span><span class="n">n</span><span class="o">%</span><span class="mf">10</span><span class="p">]</span>
	<span class="k">else</span><span class="p">:</span>
		<span class="n">gr</span> <span class="o">=</span> <span class="p">[]</span>
		<span class="n">f</span> <span class="o">=</span> <span class="s">&#39;&#39;</span>
		<span class="k">while</span> <span class="n">n</span> <span class="o">&gt;</span> <span class="mf">0</span><span class="p">:</span>
			<span class="n">gr</span><span class="o">.</span><span class="kp">append</span><span class="p">(</span><span class="n">n</span> <span class="o">%</span> <span class="mf">1000</span><span class="p">)</span>
			<span class="n">n</span> <span class="o">/=</span> <span class="mf">1000</span>
		<span class="k">if</span> <span class="nb">len</span><span class="p">(</span><span class="n">gr</span><span class="p">)</span> <span class="o">&gt;</span> <span class="nb">len</span><span class="p">(</span><span class="n">mp</span><span class="p">)</span><span class="o">+</span><span class="mf">1</span><span class="p">:</span>
			<span class="k">return</span> <span class="s">u&#39;Nu știu să citesc număr așa de mare&#39;</span>
		<span class="k">for</span> <span class="n">i</span> <span class="ow">in</span> <span class="nb">xrange</span><span class="p">(</span><span class="nb">len</span><span class="p">(</span><span class="n">gr</span><span class="p">)):</span>
			<span class="k">if</span> <span class="n">i</span> <span class="o">==</span> <span class="mf">0</span><span class="p">:</span>
				<span class="k">if</span> <span class="n">gr</span><span class="p">[</span><span class="mf">0</span><span class="p">]</span> <span class="o">==</span> <span class="mf">0</span><span class="p">:</span> <span class="k">pass</span>
				<span class="k">elif</span> <span class="n">gr</span><span class="p">[</span><span class="mf">0</span><span class="p">]</span> <span class="o">==</span> <span class="mf">1</span><span class="p">:</span> <span class="n">f</span> <span class="o">=</span> <span class="s">u&#39;și una&#39;</span>
				<span class="k">else</span><span class="p">:</span> <span class="n">f</span> <span class="o">=</span> <span class="n">scriereNumar</span><span class="p">(</span><span class="n">gr</span><span class="p">[</span><span class="mf">0</span><span class="p">],</span> <span class="n">m</span><span class="p">,</span> <span class="n">nr</span><span class="p">)</span>
			<span class="k">else</span><span class="p">:</span>
				<span class="k">if</span> <span class="n">gr</span><span class="p">[</span><span class="n">i</span><span class="p">]</span> <span class="o">==</span> <span class="mf">0</span><span class="p">:</span> <span class="k">pass</span>
				<span class="k">elif</span> <span class="n">gr</span><span class="p">[</span><span class="n">i</span><span class="p">]</span> <span class="o">==</span> <span class="mf">1</span><span class="p">:</span>
					<span class="k">if</span> <span class="n">f</span> <span class="o">==</span> <span class="s">&#39;&#39;</span><span class="p">:</span>	<span class="n">f</span> <span class="o">=</span> <span class="n">ms</span><span class="p">[</span><span class="n">i</span><span class="o">-</span><span class="mf">1</span><span class="p">]</span>
					<span class="k">else</span><span class="p">:</span> <span class="n">f</span> <span class="o">=</span> <span class="n">ms</span><span class="p">[</span><span class="n">i</span><span class="o">-</span><span class="mf">1</span><span class="p">]</span> <span class="o">+</span> <span class="s">&#39; &#39;</span> <span class="o">+</span> <span class="n">f</span>
				<span class="k">else</span><span class="p">:</span> 
					<span class="k">if</span> <span class="n">gr</span><span class="p">[</span><span class="n">i</span><span class="p">]</span> <span class="o">%</span> <span class="mf">100</span> <span class="o">&gt;=</span> <span class="mf">20</span><span class="p">:</span> <span class="n">de</span> <span class="o">=</span> <span class="s">&#39; de&#39;</span>
					<span class="k">else</span><span class="p">:</span> <span class="n">de</span> <span class="o">=</span> <span class="s">&#39;&#39;</span>
					<span class="n">f</span> <span class="o">=</span> <span class="n">scriereNumar</span><span class="p">(</span><span class="n">gr</span><span class="p">[</span><span class="n">i</span><span class="p">],</span> <span class="n">m</span><span class="p">,</span> <span class="n">nr</span><span class="p">)</span> <span class="o">+</span> <span class="n">de</span> <span class="o">+</span> <span class="s">&#39; &#39;</span> <span class="o">+</span> <span class="n">mp</span><span class="p">[</span><span class="n">i</span><span class="o">-</span><span class="mf">1</span><span class="p">]</span> <span class="o">+</span> <span class="s">&#39; &#39;</span> <span class="o">+</span> <span class="n">f</span>
		<span class="k">return</span> <span class="n">f</span>
		
<span class="k">def</span> <span class="nf">scriereNumarString</span><span class="p">(</span><span class="n">n</span><span class="p">,</span> <span class="n">m</span><span class="o">=</span><span class="bp">True</span><span class="p">,</span> <span class="n">nr</span><span class="o">=</span><span class="bp">True</span><span class="p">):</span>
	<span class="s">u&quot;&quot;&quot;Scrie cum se citește un număr care este primit ca string de forma 1234,56 în </span>
<span class="s">	parametrul n. Se poate și punct în loc de virgulă.&quot;&quot;&quot;</span>

	<span class="n">n</span> <span class="o">=</span> <span class="n">n</span><span class="o">.</span><span class="n">replace</span><span class="p">(</span><span class="s">&#39;,&#39;</span><span class="p">,</span> <span class="s">&#39;.&#39;</span><span class="p">)</span>
	<span class="k">if</span> <span class="n">n</span><span class="o">.</span><span class="n">count</span><span class="p">(</span><span class="s">&#39;.&#39;</span><span class="p">)</span> <span class="o">&gt;</span> <span class="mf">1</span><span class="p">:</span> <span class="k">raise</span> <span class="n">EroareNumar</span><span class="p">,</span> <span class="s">u&#39;Nu poate fi decât un singur punct sau virgulă&#39;</span><span class="o">.</span><span class="n">encode</span><span class="p">(</span><span class="s">&#39;utf-8&#39;</span><span class="p">)</span>
	<span class="k">if</span> <span class="ow">not</span> <span class="n">n</span><span class="o">.</span><span class="n">replace</span><span class="p">(</span><span class="s">&#39;.&#39;</span><span class="p">,</span> <span class="s">&#39;&#39;</span><span class="p">)</span><span class="o">.</span><span class="n">isdigit</span><span class="p">():</span> <span class="k">raise</span> <span class="n">EroareNumar</span><span class="p">,</span> <span class="s">u&#39;Nu este parte de număr&#39;</span><span class="o">.</span><span class="n">encode</span><span class="p">(</span><span class="s">&#39;utf-8&#39;</span><span class="p">)</span>
	<span class="k">if</span> <span class="n">n</span><span class="o">.</span><span class="n">count</span><span class="p">(</span><span class="s">&#39;.&#39;</span><span class="p">)</span> <span class="o">==</span> <span class="mf">0</span><span class="p">:</span> <span class="k">return</span> <span class="n">scriereNumar</span><span class="p">(</span><span class="nb">int</span><span class="p">(</span><span class="n">n</span><span class="p">),</span> <span class="n">m</span><span class="p">,</span> <span class="n">nr</span><span class="p">)</span>
	<span class="k">else</span><span class="p">:</span> <span class="k">return</span> <span class="n">scriereNumar</span><span class="p">(</span><span class="nb">float</span><span class="p">(</span><span class="n">n</span><span class="p">),</span> <span class="n">m</span><span class="p">,</span> <span class="n">nr</span><span class="p">)</span>

<span class="k">class</span> <span class="nc">Sunet</span><span class="p">:</span>
	<span class="sd">&quot;&quot;&quot;Gestionează un sunet.</span>
<span class="sd">	   </span>
<span class="sd">	Simbolurile pentru sunetele posibile sunt:</span>
<span class="sd">		- vocale = i, I, e, a, @, o, u, 1</span>
<span class="sd">		- semivocale = j, E, w, O</span>
<span class="sd">		- consoane = p, b, t, d, k, g, T, C, G, f, v, s, z, S, J, h, m, n, l, r</span>
<span class="sd">	&quot;&quot;&quot;</span>
	<span class="n">sunete</span> <span class="o">=</span> <span class="p">(</span><span class="s">&#39;_&#39;</span><span class="p">,</span> <span class="s">&#39;i&#39;</span><span class="p">,</span> <span class="s">&#39;I&#39;</span><span class="p">,</span> <span class="s">&#39;e&#39;</span><span class="p">,</span> <span class="s">&#39;a&#39;</span><span class="p">,</span> <span class="s">&#39;@&#39;</span><span class="p">,</span> <span class="s">&#39;o&#39;</span><span class="p">,</span> <span class="s">&#39;u&#39;</span><span class="p">,</span> <span class="s">&#39;1&#39;</span><span class="p">,</span> <span class="s">&#39;j&#39;</span><span class="p">,</span> <span class="s">&#39;E&#39;</span><span class="p">,</span> <span class="s">&#39;w&#39;</span><span class="p">,</span> <span class="s">&#39;O&#39;</span><span class="p">,</span> <span class="s">&#39;p&#39;</span><span class="p">,</span> <span class="s">&#39;b&#39;</span><span class="p">,</span> <span class="s">&#39;t&#39;</span><span class="p">,</span> \
			<span class="s">&#39;d&#39;</span><span class="p">,</span> <span class="s">&#39;k&#39;</span><span class="p">,</span> <span class="s">&#39;g&#39;</span><span class="p">,</span> <span class="s">&#39;T&#39;</span><span class="p">,</span> <span class="s">&#39;C&#39;</span><span class="p">,</span> <span class="s">&#39;G&#39;</span><span class="p">,</span> <span class="s">&#39;f&#39;</span><span class="p">,</span> <span class="s">&#39;v&#39;</span><span class="p">,</span> <span class="s">&#39;s&#39;</span><span class="p">,</span> <span class="s">&#39;z&#39;</span><span class="p">,</span> <span class="s">&#39;S&#39;</span><span class="p">,</span> <span class="s">&#39;J&#39;</span><span class="p">,</span> <span class="s">&#39;h&#39;</span><span class="p">,</span> <span class="s">&#39;m&#39;</span><span class="p">,</span> <span class="s">&#39;n&#39;</span><span class="p">,</span> <span class="s">&#39;l&#39;</span><span class="p">,</span> <span class="s">&#39;r&#39;</span><span class="p">)</span>
	<span class="k">def</span> <span class="nf">__init__</span><span class="p">(</span><span class="bp">self</span><span class="p">,</span> <span class="n">valoare</span><span class="p">,</span> <span class="n">durata</span> <span class="o">=</span> <span class="mf">100</span><span class="p">,</span> <span class="kp">mod</span> <span class="o">=</span> <span class="p">[]):</span>
		<span class="k">if</span> <span class="ow">not</span> <span class="n">valoare</span> <span class="ow">in</span> <span class="bp">self</span><span class="o">.</span><span class="n">__class__</span><span class="o">.</span><span class="n">sunete</span><span class="p">:</span> <span class="k">raise</span> <span class="n">EroareSunet</span><span class="p">,</span> <span class="p">(</span><span class="s">u&quot;nu există sunetul &#39;</span><span class="si">%s</span><span class="s">&#39;&quot;</span> <span class="o">%</span> <span class="n">valoare</span><span class="p">)</span><span class="o">.</span><span class="n">encode</span><span class="p">(</span><span class="s">&#39;utf-8&#39;</span><span class="p">)</span>
		<span class="k">if</span> <span class="n">durata</span> <span class="o">&lt;</span> <span class="mf">0</span><span class="p">:</span> <span class="k">raise</span> <span class="n">EroareSunet</span><span class="p">,</span> <span class="s">u&#39;durata nu poate fi mai mică de zero&#39;</span><span class="o">.</span><span class="n">encode</span><span class="p">(</span><span class="s">&#39;utf-8&#39;</span><span class="p">)</span>
		<span class="k">if</span> <span class="nb">len</span><span class="p">(</span><span class="kp">mod</span><span class="p">)</span> <span class="o">%</span> <span class="mf">2</span> <span class="o">!=</span> <span class="mf">0</span><span class="p">:</span> <span class="k">raise</span> <span class="n">EroareSunet</span><span class="p">,</span> <span class="s">u&#39;numărul de modificări trebuie să fie par&#39;</span><span class="o">.</span><span class="n">encode</span><span class="p">(</span><span class="s">&#39;utf-8&#39;</span><span class="p">)</span>
		<span class="bp">self</span><span class="o">.</span><span class="n">valoare</span> <span class="o">=</span>  <span class="n">valoare</span>
		<span class="bp">self</span><span class="o">.</span><span class="n">durata</span> <span class="o">=</span> <span class="n">durata</span>
		<span class="bp">self</span><span class="o">.</span><span class="kp">mod</span> <span class="o">=</span> <span class="kp">mod</span>

	<span class="k">def</span> <span class="nf">text</span><span class="p">(</span><span class="bp">self</span><span class="p">):</span>
		<span class="k">if</span> <span class="bp">self</span><span class="o">.</span><span class="kp">mod</span> <span class="o">==</span> <span class="p">[]:</span> <span class="kp">mod</span> <span class="o">=</span> <span class="s">&quot;&quot;</span>
		<span class="k">else</span><span class="p">:</span> <span class="kp">mod</span> <span class="o">=</span> <span class="s">&quot; &quot;</span> <span class="o">+</span> <span class="s">&quot; &quot;</span><span class="o">.</span><span class="n">join</span><span class="p">(</span><span class="bp">self</span><span class="o">.</span><span class="kp">mod</span><span class="p">)</span>
		<span class="k">if</span> <span class="bp">self</span><span class="o">.</span><span class="n">valoare</span> <span class="o">==</span> <span class="s">&#39;_&#39;</span><span class="p">:</span> <span class="n">m</span> <span class="o">=</span> <span class="n">_vspatii</span>
		<span class="k">else</span><span class="p">:</span> <span class="n">m</span> <span class="o">=</span> <span class="n">_viteza</span>
		<span class="k">return</span> <span class="bp">self</span><span class="o">.</span><span class="n">valoare</span> <span class="o">+</span> <span class="s">&quot; &quot;</span> <span class="o">+</span> <span class="nb">str</span><span class="p">(</span><span class="nb">int</span><span class="p">(</span><span class="bp">self</span><span class="o">.</span><span class="n">durata</span> <span class="o">*</span> <span class="n">m</span><span class="p">))</span> <span class="o">+</span> <span class="kp">mod</span>

<span class="k">class</span> <span class="nc">Discurs</span><span class="p">:</span>
	<span class="s">u&quot;&quot;&quot;Gestionează o listă de sunete și afișarea lor.&quot;&quot;&quot;</span>

	<span class="k">def</span> <span class="nf">__init__</span><span class="p">(</span><span class="bp">self</span><span class="p">):</span>
		<span class="bp">self</span><span class="o">.</span><span class="n">sunete</span> <span class="o">=</span> <span class="p">[</span><span class="n">Sunet</span><span class="p">(</span><span class="s">&quot;_&quot;</span><span class="p">)]</span>

	<span class="k">def</span> <span class="nf">adauga</span><span class="p">(</span><span class="bp">self</span><span class="p">,</span> <span class="n">sunet</span><span class="p">):</span>
		<span class="s">u&quot;&quot;&quot;Adaugă sunetul primit ca parametru in lista de sunete a discursului.&quot;&quot;&quot;</span>

		<span class="c"># trebuie să verific să nu fie vreo pereche imposibilă</span>
		<span class="n">penultimul</span> <span class="o">=</span> <span class="bp">self</span><span class="o">.</span><span class="n">sunete</span><span class="p">[</span><span class="o">-</span><span class="mf">1</span><span class="p">]</span><span class="o">.</span><span class="n">valoare</span>
		<span class="n">ultimul</span> <span class="o">=</span> <span class="n">sunet</span><span class="o">.</span><span class="n">valoare</span>

		<span class="k">if</span> <span class="n">penultimul</span> <span class="o">==</span> <span class="s">&#39;a&#39;</span> <span class="ow">and</span> <span class="n">ultimul</span> <span class="o">==</span> <span class="s">&#39;e&#39;</span><span class="p">:</span> <span class="k">raise</span> <span class="n">EroarePereche</span><span class="p">,</span> <span class="s">&#39;Nu se poate a-e!&#39;</span>
		<span class="k">if</span> <span class="n">penultimul</span> <span class="o">==</span> <span class="s">&#39;I&#39;</span> <span class="ow">and</span> <span class="n">ultimul</span> <span class="o">==</span> <span class="s">&#39;i&#39;</span><span class="p">:</span> <span class="k">raise</span> <span class="n">EroarePereche</span><span class="p">,</span> <span class="s">&#39;Nu se poate I-i!&#39;</span>
		<span class="k">if</span> <span class="n">penultimul</span> <span class="o">==</span> <span class="s">&#39;O&#39;</span> <span class="ow">and</span> <span class="n">ultimul</span> <span class="o">!=</span> <span class="s">&#39;a&#39;</span><span class="p">:</span> <span class="k">raise</span> <span class="n">EroarePereche</span><span class="p">,</span> <span class="s">&#39;Nu se poate O-</span><span class="si">%s</span><span class="s">!&#39;</span> <span class="o">%</span> <span class="n">ultimul</span>
		<span class="k">if</span> <span class="n">penultimul</span> <span class="o">==</span> <span class="s">&#39;E&#39;</span><span class="p">:</span>
			<span class="k">if</span> <span class="n">ultimul</span> <span class="o">!=</span> <span class="s">&#39;a&#39;</span> <span class="ow">and</span> <span class="n">ultimul</span> <span class="o">!=</span> <span class="s">&#39;o&#39;</span><span class="p">:</span>
				<span class="k">raise</span> <span class="n">EroarePereche</span><span class="p">,</span> <span class="s">&#39;Nu se poate E-</span><span class="si">%s</span><span class="s">!&#39;</span> <span class="o">%</span> <span class="n">ultimul</span>
		<span class="k">if</span> <span class="n">ultimul</span> <span class="o">==</span> <span class="s">&#39;I&#39;</span><span class="p">:</span>
			<span class="k">if</span> <span class="n">penultimul</span> <span class="ow">in</span> <span class="p">(</span><span class="s">&#39;_&#39;</span><span class="p">,</span> <span class="s">&#39;a&#39;</span><span class="p">,</span> <span class="s">&#39;@&#39;</span><span class="p">,</span> <span class="s">&#39;e&#39;</span><span class="p">,</span> <span class="s">&#39;i&#39;</span><span class="p">,</span> <span class="s">&#39;1&#39;</span><span class="p">,</span> <span class="s">&#39;j&#39;</span><span class="p">,</span> <span class="s">&#39;o&#39;</span><span class="p">,</span> <span class="s">&#39;s&#39;</span><span class="p">,</span> <span class="s">&#39;u&#39;</span><span class="p">,</span> <span class="s">&#39;w&#39;</span><span class="p">):</span>
				<span class="k">raise</span> <span class="n">EroarePereche</span><span class="p">,</span> <span class="s">&#39;Nu se poate </span><span class="si">%s</span><span class="s">-I!&#39;</span> <span class="o">%</span> <span class="n">penultimul</span>
		<span class="k">if</span> <span class="n">ultimul</span> <span class="o">==</span> <span class="s">&#39;E&#39;</span><span class="p">:</span>
			<span class="k">if</span> <span class="n">penultimul</span> <span class="ow">in</span> <span class="p">(</span><span class="s">&#39;@&#39;</span><span class="p">,</span> <span class="s">&#39;i&#39;</span><span class="p">,</span> <span class="s">&#39;1&#39;</span><span class="p">,</span> <span class="s">&#39;o&#39;</span><span class="p">,</span> <span class="s">&#39;u&#39;</span><span class="p">,</span> <span class="s">&#39;w&#39;</span><span class="p">):</span>
				<span class="k">raise</span> <span class="n">EroarePereche</span><span class="p">,</span> <span class="s">&#39;Nu se poate </span><span class="si">%s</span><span class="s">-E!&#39;</span> <span class="o">%</span> <span class="n">penultimul</span>
		<span class="k">if</span> <span class="n">ultimul</span> <span class="o">==</span> <span class="s">&#39;_&#39;</span><span class="p">:</span>
			<span class="k">if</span> <span class="n">penultimul</span> <span class="o">==</span> <span class="s">&#39;C&#39;</span> <span class="ow">or</span> <span class="n">penultimul</span> <span class="o">==</span> <span class="s">&#39;G&#39;</span><span class="p">:</span>
				<span class="k">raise</span> <span class="n">EroarePereche</span><span class="p">,</span> <span class="s">&#39;Nu se poate </span><span class="si">%s</span><span class="s">-_!&#39;</span> <span class="o">%</span> <span class="n">penultimul</span>

		<span class="bp">self</span><span class="o">.</span><span class="n">sunete</span><span class="o">.</span><span class="kp">append</span><span class="p">(</span><span class="n">sunet</span><span class="p">)</span>

	<span class="k">def</span> <span class="nf">text</span><span class="p">(</span><span class="bp">self</span><span class="p">):</span>
		<span class="k">return</span> <span class="s">&#39;</span><span class="se">\n</span><span class="s">&#39;</span><span class="o">.</span><span class="n">join</span><span class="p">([</span><span class="n">x</span><span class="o">.</span><span class="n">text</span><span class="p">()</span> <span class="k">for</span> <span class="n">x</span> <span class="ow">in</span> <span class="bp">self</span><span class="o">.</span><span class="n">sunete</span><span class="p">])</span> <span class="o">+</span> <span class="s">&#39;</span><span class="se">\n</span><span class="s">&#39;</span>

<span class="k">class</span> <span class="nc">Cititor</span><span class="p">:</span>
	<span class="s">u&quot;&quot;&quot;Citește câte un cuvânt. Citirea unui cuvânt este căutată într-un dicționar. Dacă nu este găsit cuvântul,</span>
<span class="s">	citirea este formată după niște reguli simple, care nu dau de multe ori răspunsul corect.&quot;&quot;&quot;</span>

	<span class="n">citire</span> <span class="o">=</span> <span class="p">{</span><span class="s">&#39;a&#39;</span><span class="p">:</span><span class="s">&#39;a&#39;</span><span class="p">,</span>  <span class="s">u&#39;ă&#39;</span><span class="p">:</span><span class="s">&#39;@&#39;</span><span class="p">,</span>  <span class="s">u&#39;â&#39;</span><span class="p">:</span><span class="s">&#39;1&#39;</span><span class="p">,</span>   <span class="s">&#39;b&#39;</span><span class="p">:</span><span class="s">&#39;b&#39;</span><span class="p">,</span>   <span class="s">&#39;d&#39;</span><span class="p">:</span><span class="s">&#39;d&#39;</span><span class="p">,</span>   <span class="s">&#39;e&#39;</span><span class="p">:</span><span class="s">&#39;e&#39;</span><span class="p">,</span>   <span class="s">&#39;f&#39;</span><span class="p">:</span><span class="s">&#39;f&#39;</span><span class="p">,</span>            <span class="s">u&#39;î&#39;</span><span class="p">:</span><span class="s">&#39;1&#39;</span><span class="p">,</span> \
			  <span class="s">&#39;j&#39;</span><span class="p">:</span><span class="s">&#39;J&#39;</span><span class="p">,</span>   <span class="s">&#39;k&#39;</span><span class="p">:</span><span class="s">&#39;k&#39;</span><span class="p">,</span>   <span class="s">&#39;l&#39;</span><span class="p">:</span><span class="s">&#39;l&#39;</span><span class="p">,</span>   <span class="s">&#39;m&#39;</span><span class="p">:</span><span class="s">&#39;m&#39;</span><span class="p">,</span>   <span class="s">&#39;n&#39;</span><span class="p">:</span><span class="s">&#39;n&#39;</span><span class="p">,</span>   <span class="s">&#39;o&#39;</span><span class="p">:</span><span class="s">&#39;o&#39;</span><span class="p">,</span>   <span class="s">&#39;p&#39;</span><span class="p">:</span><span class="s">&#39;p&#39;</span><span class="p">,</span>   <span class="s">&#39;q&#39;</span><span class="p">:</span><span class="s">&#39;k&#39;</span><span class="p">,</span>  <span class="s">&#39;r&#39;</span><span class="p">:</span><span class="s">&#39;r&#39;</span><span class="p">,</span> \
			  <span class="s">&#39;s&#39;</span><span class="p">:</span><span class="s">&#39;s&#39;</span><span class="p">,</span>  <span class="s">u&#39;ș&#39;</span><span class="p">:</span><span class="s">&#39;S&#39;</span><span class="p">,</span>   <span class="s">&#39;t&#39;</span><span class="p">:</span><span class="s">&#39;t&#39;</span><span class="p">,</span>  <span class="s">u&#39;ț&#39;</span><span class="p">:</span><span class="s">&#39;T&#39;</span><span class="p">,</span>   <span class="s">&#39;u&#39;</span><span class="p">:</span><span class="s">&#39;u&#39;</span><span class="p">,</span>   <span class="s">&#39;v&#39;</span><span class="p">:</span><span class="s">&#39;v&#39;</span><span class="p">,</span>   <span class="s">&#39;w&#39;</span><span class="p">:</span><span class="s">&#39;v&#39;</span><span class="p">,</span>   <span class="s">&#39;y&#39;</span><span class="p">:</span><span class="s">&#39;i&#39;</span><span class="p">,</span>  <span class="s">&#39;z&#39;</span><span class="p">:</span><span class="s">&#39;z&#39;</span><span class="p">}</span>

	<span class="k">def</span> <span class="nf">__init__</span><span class="p">(</span><span class="bp">self</span><span class="p">,</span> <span class="n">discurs</span><span class="p">):</span>
		<span class="bp">self</span><span class="o">.</span><span class="n">discurs</span> <span class="o">=</span> <span class="n">discurs</span>

	<span class="k">def</span> <span class="nf">adaugaCuvant</span><span class="p">(</span><span class="bp">self</span><span class="p">,</span> <span class="n">cuv</span><span class="p">):</span>
		<span class="k">global</span> <span class="n">dictionar</span>

		<span class="k">if</span> <span class="n">cuv</span> <span class="o">==</span> <span class="s">&#39;&#39;</span><span class="p">:</span> <span class="k">return</span>

		<span class="c"># dacă nu a fost adaugată o pauză trebuie să o adaug</span>
		<span class="k">if</span> <span class="bp">self</span><span class="o">.</span><span class="n">discurs</span><span class="o">.</span><span class="n">sunete</span><span class="p">[</span><span class="o">-</span><span class="mf">1</span><span class="p">]</span><span class="o">.</span><span class="n">valoare</span> <span class="o">!=</span> <span class="s">&#39;_&#39;</span><span class="p">:</span> <span class="bp">self</span><span class="o">.</span><span class="n">crud</span><span class="p">(</span><span class="s">&#39;_&#39;</span><span class="p">)</span>

		<span class="k">try</span><span class="p">:</span>
			<span class="bp">self</span><span class="o">.</span><span class="n">crud</span><span class="p">(</span><span class="n">dictionar</span><span class="p">[</span><span class="n">cuv</span><span class="p">])</span>
		<span class="k">except</span> <span class="ne">KeyError</span><span class="p">:</span>
			<span class="n">pronuntare</span> <span class="o">=</span> <span class="s">&quot;&quot;</span>
			<span class="k">for</span> <span class="n">i</span> <span class="ow">in</span> <span class="nb">xrange</span><span class="p">(</span><span class="nb">len</span><span class="p">(</span><span class="n">cuv</span><span class="p">)):</span>
				<span class="k">try</span><span class="p">:</span>
					<span class="n">pronuntare</span> <span class="o">+=</span> <span class="bp">self</span><span class="o">.</span><span class="n">__class__</span><span class="o">.</span><span class="n">citire</span><span class="p">[</span><span class="n">cuv</span><span class="p">[</span><span class="n">i</span><span class="p">]]</span>
				<span class="k">except</span> <span class="ne">KeyError</span><span class="p">:</span>
					<span class="k">if</span> <span class="n">cuv</span><span class="p">[</span><span class="n">i</span><span class="p">]</span> <span class="o">==</span> <span class="s">&#39;i&#39;</span><span class="p">:</span>
						<span class="k">if</span> <span class="n">urm</span><span class="p">(</span><span class="n">cuv</span><span class="p">,</span> <span class="n">i</span><span class="o">+</span><span class="mf">1</span><span class="p">)</span> <span class="o">==</span> <span class="s">&quot;&quot;</span> <span class="ow">and</span> <span class="ow">not</span> <span class="p">(</span><span class="n">pronuntare</span><span class="p">[</span><span class="o">-</span><span class="mf">1</span><span class="p">]</span> <span class="ow">in</span> <span class="p">(</span><span class="s">&#39;a&#39;</span><span class="p">,</span> <span class="s">&#39;@&#39;</span><span class="p">,</span> <span class="s">&#39;e&#39;</span><span class="p">,</span> <span class="s">&#39;i&#39;</span><span class="p">,</span> <span class="s">&#39;1&#39;</span><span class="p">,</span> <span class="s">&#39;j&#39;</span><span class="p">,</span> <span class="s">&#39;o&#39;</span><span class="p">,</span> <span class="s">&#39;s&#39;</span><span class="p">,</span> <span class="s">&#39;u&#39;</span><span class="p">,</span> <span class="s">&#39;w&#39;</span><span class="p">)):</span> <span class="n">pronuntare</span> <span class="o">+=</span> <span class="s">&#39;I&#39;</span>
						<span class="k">else</span><span class="p">:</span> <span class="n">pronuntare</span> <span class="o">+=</span> <span class="s">&#39;i&#39;</span>
					<span class="k">elif</span> <span class="n">cuv</span><span class="p">[</span><span class="n">i</span><span class="p">]</span> <span class="o">==</span> <span class="s">&#39;c&#39;</span><span class="p">:</span>
						<span class="k">if</span> <span class="n">urm</span><span class="p">(</span><span class="n">cuv</span><span class="p">,</span> <span class="n">i</span><span class="o">+</span><span class="mf">1</span><span class="p">)</span> <span class="ow">in</span> <span class="p">(</span><span class="s">&#39;e&#39;</span><span class="p">,</span> <span class="s">&#39;i&#39;</span><span class="p">):</span> <span class="n">pronuntare</span> <span class="o">+=</span> <span class="s">&#39;C&#39;</span>
						<span class="k">else</span><span class="p">:</span> <span class="n">pronuntare</span> <span class="o">+=</span> <span class="s">&#39;k&#39;</span>
					<span class="k">elif</span> <span class="n">cuv</span><span class="p">[</span><span class="n">i</span><span class="p">]</span> <span class="o">==</span> <span class="s">&#39;g&#39;</span><span class="p">:</span>
						<span class="k">if</span> <span class="n">urm</span><span class="p">(</span><span class="n">cuv</span><span class="p">,</span> <span class="n">i</span><span class="o">+</span><span class="mf">1</span><span class="p">)</span> <span class="ow">in</span> <span class="p">(</span><span class="s">&#39;e&#39;</span><span class="p">,</span> <span class="s">&#39;i&#39;</span><span class="p">):</span> <span class="n">pronuntare</span> <span class="o">+=</span> <span class="s">&#39;G&#39;</span>
						<span class="k">else</span><span class="p">:</span> <span class="n">pronuntare</span> <span class="o">+=</span> <span class="s">&#39;g&#39;</span>
					<span class="k">elif</span> <span class="n">cuv</span><span class="p">[</span><span class="n">i</span><span class="p">]</span> <span class="o">==</span> <span class="s">&#39;h&#39;</span><span class="p">:</span>
						<span class="k">if</span> <span class="n">urm</span><span class="p">(</span><span class="n">cuv</span><span class="p">,</span> <span class="n">i</span><span class="o">-</span><span class="mf">1</span><span class="p">)</span> <span class="ow">in</span> <span class="p">(</span><span class="s">&#39;c&#39;</span><span class="p">,</span> <span class="s">&#39;g&#39;</span><span class="p">):</span> <span class="k">pass</span>
						<span class="k">else</span><span class="p">:</span> <span class="n">pronuntare</span> <span class="o">+=</span> <span class="s">&#39;h&#39;</span>
					<span class="k">elif</span> <span class="n">cuv</span><span class="p">[</span><span class="n">i</span><span class="p">]</span> <span class="o">==</span> <span class="s">&#39;x&#39;</span><span class="p">:</span>
						<span class="n">pronuntare</span> <span class="o">+=</span> <span class="s">&#39;ks&#39;</span>
					<span class="k">elif</span> <span class="n">cuv</span><span class="p">[</span><span class="n">i</span><span class="p">]</span> <span class="o">==</span> <span class="s">&#39;-&#39;</span> <span class="ow">or</span> <span class="n">cuv</span><span class="p">[</span><span class="n">i</span><span class="p">]</span> <span class="o">==</span> <span class="s">&quot;&#39;&quot;</span><span class="p">:</span> <span class="k">pass</span>
					<span class="k">else</span><span class="p">:</span> <span class="k">raise</span> <span class="n">EroareCitire</span><span class="p">,</span> <span class="p">(</span><span class="s">u&quot;Nu este parte de cuvânt &#39;</span><span class="si">%s</span><span class="s">&#39; din &#39;</span><span class="si">%s</span><span class="s">&#39;!&quot;</span> <span class="o">%</span> <span class="p">(</span><span class="n">cuv</span><span class="p">[</span><span class="n">i</span><span class="p">],</span> <span class="n">cuv</span><span class="p">))</span><span class="o">.</span><span class="n">encode</span><span class="p">(</span><span class="s">&#39;utf-8&#39;</span><span class="p">)</span>
			<span class="bp">self</span><span class="o">.</span><span class="n">crud</span><span class="p">(</span><span class="n">pronuntare</span><span class="p">)</span>

	<span class="k">def</span> <span class="nf">crud</span><span class="p">(</span><span class="bp">self</span><span class="p">,</span> <span class="n">cuv</span><span class="p">):</span>
		<span class="n">lista</span> <span class="o">=</span> <span class="n">cuv</span><span class="o">.</span><span class="kp">split</span><span class="p">(</span><span class="s">&quot;#&quot;</span><span class="p">)</span>
		<span class="k">if</span> <span class="nb">len</span><span class="p">(</span><span class="n">lista</span><span class="p">)</span> <span class="o">==</span> <span class="mf">1</span><span class="p">:</span>
			<span class="k">for</span> <span class="n">c</span> <span class="ow">in</span> <span class="n">lista</span><span class="p">[</span><span class="mf">0</span><span class="p">]:</span>
				<span class="bp">self</span><span class="o">.</span><span class="n">discurs</span><span class="o">.</span><span class="n">adauga</span><span class="p">(</span><span class="n">Sunet</span><span class="p">(</span><span class="n">c</span><span class="p">))</span>
		<span class="k">else</span><span class="p">:</span>
			<span class="n">numere</span> <span class="o">=</span> <span class="n">lista</span><span class="p">[</span><span class="mf">1</span><span class="p">]</span><span class="o">.</span><span class="kp">split</span><span class="p">(</span><span class="s">&quot;|&quot;</span><span class="p">)</span>
			<span class="k">if</span> <span class="nb">len</span><span class="p">(</span><span class="n">numere</span><span class="p">)</span> <span class="o">!=</span> <span class="nb">len</span><span class="p">(</span><span class="n">lista</span><span class="p">[</span><span class="mf">0</span><span class="p">]):</span>
				<span class="k">raise</span> <span class="n">EroareCitire</span><span class="p">,</span> <span class="p">(</span><span class="s">u&quot;Trebuie să se potrivească literele și numerele! (</span><span class="si">%s</span><span class="s">, </span><span class="si">%s</span><span class="s">)&quot;</span> <span class="o">%</span> <span class="p">(</span><span class="n">numere</span><span class="p">,</span> <span class="n">lista</span><span class="p">[</span><span class="mf">0</span><span class="p">]))</span><span class="o">.</span><span class="n">encode</span><span class="p">(</span><span class="s">&#39;utf-8&#39;</span><span class="p">)</span>
			<span class="n">sir</span> <span class="o">=</span> <span class="p">[]</span>
			<span class="k">for</span> <span class="n">n</span> <span class="ow">in</span> <span class="n">numere</span><span class="p">:</span>
				<span class="k">if</span> <span class="n">n</span> <span class="o">==</span> <span class="s">&quot;&quot;</span><span class="p">:</span> <span class="n">sir</span><span class="o">.</span><span class="kp">append</span><span class="p">([])</span>
				<span class="k">else</span><span class="p">:</span> <span class="n">sir</span><span class="o">.</span><span class="kp">append</span><span class="p">([</span> <span class="nb">int</span><span class="p">(</span><span class="n">x</span><span class="p">)</span> <span class="k">for</span> <span class="n">x</span> <span class="ow">in</span> <span class="n">n</span><span class="o">.</span><span class="kp">split</span><span class="p">(</span><span class="s">&quot;,&quot;</span><span class="p">)</span> <span class="p">])</span>

			<span class="k">for</span> <span class="n">i</span> <span class="ow">in</span> <span class="nb">range</span><span class="p">(</span><span class="nb">len</span><span class="p">(</span><span class="n">lista</span><span class="p">[</span><span class="mf">0</span><span class="p">])):</span>
				<span class="k">if</span> <span class="nb">len</span><span class="p">(</span><span class="n">sir</span><span class="p">[</span><span class="n">i</span><span class="p">])</span> <span class="o">==</span> <span class="mf">0</span><span class="p">:</span>
					<span class="bp">self</span><span class="o">.</span><span class="n">discurs</span><span class="o">.</span><span class="n">adauga</span><span class="p">(</span><span class="n">Sunet</span><span class="p">(</span><span class="n">lista</span><span class="p">[</span><span class="mf">0</span><span class="p">][</span><span class="n">i</span><span class="p">]))</span>
				<span class="k">elif</span> <span class="nb">len</span><span class="p">(</span><span class="n">sir</span><span class="p">[</span><span class="n">i</span><span class="p">])</span> <span class="o">==</span> <span class="mf">1</span><span class="p">:</span>
					<span class="bp">self</span><span class="o">.</span><span class="n">discurs</span><span class="o">.</span><span class="n">adauga</span><span class="p">(</span><span class="n">Sunet</span><span class="p">(</span><span class="n">lista</span><span class="p">[</span><span class="mf">0</span><span class="p">][</span><span class="n">i</span><span class="p">],</span> <span class="n">sir</span><span class="p">[</span><span class="n">i</span><span class="p">][</span><span class="mf">0</span><span class="p">],</span> <span class="n">sir</span><span class="p">[</span><span class="n">i</span><span class="p">][</span><span class="mf">1</span><span class="p">:]))</span>

<span class="k">class</span> <span class="nc">Procesor</span><span class="p">(</span><span class="n">Cititor</span><span class="p">):</span>
	<span class="s">u&quot;&quot;&quot;Citește orice text prin extragerea cuvintelor.&quot;&quot;&quot;</span>

	<span class="n">atomCuvant</span> <span class="o">=</span> <span class="p">(</span><span class="s">&#39;a&#39;</span><span class="p">,</span> <span class="s">u&#39;ă&#39;</span><span class="p">,</span> <span class="s">u&#39;â&#39;</span><span class="p">,</span> <span class="s">&#39;b&#39;</span><span class="p">,</span> <span class="s">&#39;c&#39;</span><span class="p">,</span> <span class="s">&#39;d&#39;</span><span class="p">,</span> <span class="s">&#39;e&#39;</span><span class="p">,</span> <span class="s">&#39;f&#39;</span><span class="p">,</span> <span class="s">&#39;g&#39;</span><span class="p">,</span> <span class="s">&#39;h&#39;</span><span class="p">,</span> <span class="s">&#39;i&#39;</span><span class="p">,</span> <span class="s">u&#39;î&#39;</span><span class="p">,</span> <span class="s">&#39;j&#39;</span><span class="p">,</span> <span class="s">&#39;k&#39;</span><span class="p">,</span> <span class="s">&#39;l&#39;</span><span class="p">,</span> <span class="s">&#39;m&#39;</span><span class="p">,</span> <span class="s">&#39;n&#39;</span><span class="p">,</span> <span class="s">&#39;o&#39;</span><span class="p">,</span> <span class="s">&#39;p&#39;</span><span class="p">,</span> \
					<span class="s">&#39;q&#39;</span><span class="p">,</span> <span class="s">&#39;r&#39;</span><span class="p">,</span> <span class="s">&#39;s&#39;</span><span class="p">,</span> <span class="s">u&#39;ș&#39;</span><span class="p">,</span> <span class="s">&#39;t&#39;</span><span class="p">,</span> <span class="s">u&#39;ț&#39;</span><span class="p">,</span> <span class="s">&#39;u&#39;</span><span class="p">,</span> <span class="s">&#39;v&#39;</span><span class="p">,</span> <span class="s">&#39;w&#39;</span><span class="p">,</span>  <span class="s">&#39;x&#39;</span><span class="p">,</span> <span class="s">&#39;y&#39;</span><span class="p">,</span> <span class="s">&#39;z&#39;</span><span class="p">,</span> <span class="s">&#39;-&#39;</span><span class="p">,</span> <span class="s">&quot;&#39;&quot;</span><span class="p">)</span>

	<span class="k">def</span> <span class="nf">__init__</span><span class="p">(</span><span class="bp">self</span><span class="p">,</span> <span class="n">discurs</span><span class="p">,</span> <span class="n">deInceput</span><span class="o">=</span><span class="s">&#39;&#39;</span><span class="p">):</span>
		<span class="bp">self</span><span class="o">.</span><span class="n">discurs</span> <span class="o">=</span> <span class="n">discurs</span>
		<span class="k">if</span> <span class="n">deInceput</span> <span class="o">!=</span> <span class="s">&#39;&#39;</span><span class="p">:</span> <span class="bp">self</span><span class="o">.</span><span class="n">proceseaza</span><span class="p">(</span><span class="n">deInceput</span><span class="p">)</span>

	<span class="k">def</span> <span class="nf">proceseaza</span><span class="p">(</span><span class="bp">self</span><span class="p">,</span> <span class="n">text</span><span class="p">):</span>
		<span class="n">text</span> <span class="o">=</span> <span class="bp">self</span><span class="o">.</span><span class="n">inlocuiesteNumere</span><span class="p">(</span><span class="n">text</span><span class="o">.</span><span class="n">lower</span><span class="p">()</span><span class="o">.</span><span class="n">replace</span><span class="p">(</span><span class="s">&#39;</span><span class="se">\n</span><span class="s">&#39;</span><span class="p">,</span> <span class="s">&#39;&#39;</span><span class="p">))</span>
		<span class="n">i</span> <span class="o">=</span> <span class="mf">0</span>
		<span class="n">cuvant</span> <span class="o">=</span> <span class="s">&#39;&#39;</span>
		<span class="k">while</span> <span class="n">i</span> <span class="o">&lt;</span> <span class="nb">len</span><span class="p">(</span><span class="n">text</span><span class="p">):</span>
			<span class="k">if</span> <span class="n">text</span><span class="p">[</span><span class="n">i</span><span class="p">]</span> <span class="ow">in</span> <span class="bp">self</span><span class="o">.</span><span class="n">__class__</span><span class="o">.</span><span class="n">atomCuvant</span><span class="p">:</span>
				<span class="n">cuvant</span> <span class="o">+=</span> <span class="n">text</span><span class="p">[</span><span class="n">i</span><span class="p">]</span>
				<span class="n">i</span> <span class="o">+=</span> <span class="mf">1</span>
			<span class="k">else</span><span class="p">:</span>
				<span class="bp">self</span><span class="o">.</span><span class="n">adaugaCuvant</span><span class="p">(</span><span class="n">cuvant</span><span class="p">)</span>
				<span class="n">cuvant</span> <span class="o">=</span> <span class="s">&#39;&#39;</span>
				<span class="k">if</span> <span class="n">text</span><span class="p">[</span><span class="n">i</span><span class="p">]</span> <span class="o">==</span> <span class="s">&#39; &#39;</span><span class="p">:</span> <span class="n">pauza</span> <span class="o">=</span> <span class="mf">100</span>
				<span class="k">elif</span> <span class="n">text</span><span class="p">[</span><span class="n">i</span><span class="p">]</span> <span class="ow">in</span> <span class="p">(</span><span class="s">&#39;,&#39;</span><span class="p">,</span> <span class="s">&#39;:&#39;</span><span class="p">):</span> <span class="n">pauza</span> <span class="o">=</span> <span class="mf">150</span>
				<span class="k">elif</span> <span class="n">text</span><span class="p">[</span><span class="n">i</span><span class="p">]</span> <span class="o">==</span> <span class="s">u&#39;—&#39;</span><span class="p">:</span> <span class="n">pauza</span> <span class="o">=</span> <span class="mf">250</span> <span class="c"># linie de pauză</span>
				<span class="k">else</span><span class="p">:</span> <span class="n">pauza</span> <span class="o">=</span> <span class="mf">200</span>
				<span class="bp">self</span><span class="o">.</span><span class="n">discurs</span><span class="o">.</span><span class="n">adauga</span><span class="p">(</span><span class="n">Sunet</span><span class="p">(</span><span class="s">&#39;_&#39;</span><span class="p">,</span> <span class="n">pauza</span><span class="p">))</span>
				<span class="k">while</span> <span class="n">i</span> <span class="o">&lt;</span> <span class="nb">len</span><span class="p">(</span><span class="n">text</span><span class="p">)</span> <span class="ow">and</span> <span class="ow">not</span> <span class="p">(</span><span class="n">text</span><span class="p">[</span><span class="n">i</span><span class="p">]</span> <span class="ow">in</span> <span class="bp">self</span><span class="o">.</span><span class="n">__class__</span><span class="o">.</span><span class="n">atomCuvant</span><span class="p">):</span> <span class="n">i</span> <span class="o">+=</span> <span class="mf">1</span>
		<span class="k">if</span> <span class="nb">len</span><span class="p">(</span><span class="n">cuvant</span><span class="p">)</span> <span class="o">&gt;</span> <span class="mf">0</span><span class="p">:</span> <span class="bp">self</span><span class="o">.</span><span class="n">adaugaCuvant</span><span class="p">(</span><span class="n">cuvant</span><span class="p">)</span>

	<span class="k">def</span> <span class="nf">inlocuiesteNumere</span><span class="p">(</span><span class="bp">self</span><span class="p">,</span> <span class="n">text</span><span class="p">):</span>
		<span class="c"># TODO: să citească corect și numerele negative sau cu virgulă XXX</span>
		<span class="n">nr</span> <span class="o">=</span> <span class="s">&quot;&quot;</span>
		<span class="n">textNou</span> <span class="o">=</span> <span class="s">&quot;&quot;</span>
		<span class="k">for</span> <span class="n">c</span> <span class="ow">in</span> <span class="n">text</span><span class="p">:</span>
			<span class="k">if</span> <span class="nb">ord</span><span class="p">(</span><span class="n">c</span><span class="p">)</span> <span class="o">&gt;=</span> <span class="nb">ord</span><span class="p">(</span><span class="s">&#39;0&#39;</span><span class="p">)</span> <span class="ow">and</span> <span class="nb">ord</span><span class="p">(</span><span class="n">c</span><span class="p">)</span> <span class="o">&lt;=</span> <span class="nb">ord</span><span class="p">(</span><span class="s">&#39;9&#39;</span><span class="p">):</span>
				<span class="n">nr</span> <span class="o">+=</span> <span class="n">c</span>
			<span class="k">else</span><span class="p">:</span>
				<span class="k">if</span> <span class="nb">len</span><span class="p">(</span><span class="n">nr</span><span class="p">)</span> <span class="o">&gt;</span> <span class="mf">1</span><span class="p">:</span>
					<span class="n">textNou</span> <span class="o">+=</span> <span class="n">scriereNumarString</span><span class="p">(</span><span class="n">nr</span><span class="p">)</span>
					<span class="n">nr</span> <span class="o">=</span> <span class="s">&quot;&quot;</span>
				<span class="n">textNou</span> <span class="o">+=</span> <span class="n">c</span>
		<span class="k">if</span> <span class="nb">len</span><span class="p">(</span><span class="n">nr</span><span class="p">)</span> <span class="o">&gt;</span> <span class="mf">1</span><span class="p">:</span> <span class="n">textNou</span> <span class="o">+=</span> <span class="n">scriereNumarString</span><span class="p">(</span><span class="n">nr</span><span class="p">)</span>
		<span class="k">return</span> <span class="n">textNou</span>

<span class="c"># ================================================== MAIN ================================================== </span>

<span class="k">if</span> <span class="n">__name__</span> <span class="o">==</span> <span class="s">&#39;__main__&#39;</span><span class="p">:</span>
	<span class="k">try</span><span class="p">:</span>
		<span class="n">opts</span><span class="p">,</span> <span class="n">args</span> <span class="o">=</span> <span class="n">getopt</span><span class="o">.</span><span class="n">getopt</span><span class="p">(</span><span class="n">sys</span><span class="o">.</span><span class="n">argv</span><span class="p">[</span><span class="mf">1</span><span class="p">:],</span> <span class="s">&#39;t:s:v:p:h&#39;</span><span class="p">,</span> <span class="p">[</span><span class="s">&#39;text=&#39;</span><span class="p">,</span> <span class="s">&#39;scriere=&#39;</span><span class="p">,</span> <span class="s">&#39;viteza=&#39;</span><span class="p">,</span> <span class="s">&#39;spatii=&#39;</span><span class="p">,</span> <span class="s">&#39;help&#39;</span><span class="p">])</span>
	<span class="k">except</span> <span class="n">getopt</span><span class="o">.</span><span class="n">GetoptError</span><span class="p">:</span>
		<span class="k">print</span> <span class="n">__doc__</span>
		<span class="nb">exit</span><span class="p">(</span><span class="mf">2</span><span class="p">)</span>
	<span class="n">text</span> <span class="o">=</span> <span class="n">sys</span><span class="o">.</span><span class="n">stdin</span>
	<span class="n">scriere</span> <span class="o">=</span> <span class="n">sys</span><span class="o">.</span><span class="n">stdout</span>
	<span class="k">for</span> <span class="n">opt</span><span class="p">,</span> <span class="n">arg</span> <span class="ow">in</span> <span class="n">opts</span><span class="p">:</span>
		<span class="k">if</span> <span class="n">opt</span> <span class="ow">in</span> <span class="p">(</span><span class="s">&#39;-h&#39;</span><span class="p">,</span> <span class="s">&#39;--help&#39;</span><span class="p">):</span>
			<span class="k">print</span> <span class="n">__doc__</span>
			<span class="nb">exit</span><span class="p">()</span>
		<span class="k">elif</span> <span class="n">opt</span> <span class="ow">in</span> <span class="p">(</span><span class="s">&#39;-t&#39;</span><span class="p">,</span> <span class="s">&#39;--text&#39;</span><span class="p">):</span>
			<span class="n">text</span> <span class="o">=</span> <span class="nb">open</span><span class="p">(</span><span class="n">arg</span><span class="p">)</span>
		<span class="k">elif</span> <span class="n">opt</span> <span class="ow">in</span> <span class="p">(</span><span class="s">&#39;-s&#39;</span><span class="p">,</span> <span class="s">&#39;--scriere&#39;</span><span class="p">):</span>
			<span class="n">scriere</span> <span class="o">=</span> <span class="nb">open</span><span class="p">(</span><span class="n">arg</span><span class="p">)</span>
		<span class="k">elif</span> <span class="n">opt</span> <span class="ow">in</span> <span class="p">(</span><span class="s">&#39;-v&#39;</span><span class="p">,</span> <span class="s">&#39;--viteza&#39;</span><span class="p">):</span>
			<span class="n">_viteza</span> <span class="o">=</span> <span class="nb">float</span><span class="p">(</span><span class="n">arg</span><span class="o">.</span><span class="n">replace</span><span class="p">(</span><span class="s">&#39;,&#39;</span><span class="p">,</span> <span class="s">&#39;.&#39;</span><span class="p">))</span>
		<span class="k">elif</span> <span class="n">opt</span> <span class="ow">in</span> <span class="p">(</span><span class="s">&#39;-p&#39;</span><span class="p">,</span> <span class="s">&#39;--spatii&#39;</span><span class="p">):</span>
			<span class="n">_vspatii</span> <span class="o">=</span> <span class="nb">float</span><span class="p">(</span><span class="n">arg</span><span class="o">.</span><span class="n">replace</span><span class="p">(</span><span class="s">&#39;,&#39;</span><span class="p">,</span> <span class="s">&#39;.&#39;</span><span class="p">))</span>

	<span class="n">d</span> <span class="o">=</span> <span class="n">Discurs</span><span class="p">()</span>
	<span class="n">p</span> <span class="o">=</span> <span class="n">Procesor</span><span class="p">(</span><span class="n">d</span><span class="p">)</span>
	<span class="n">p</span><span class="o">.</span><span class="n">proceseaza</span><span class="p">(</span><span class="nb">unicode</span><span class="p">(</span><span class="n">text</span><span class="o">.</span><span class="n">read</span><span class="p">(),</span> <span class="s">&#39;utf-8&#39;</span><span class="p">))</span>	
	<span class="n">scriere</span><span class="o">.</span><span class="n">write</span><span class="p">(</span><span class="n">d</span><span class="o">.</span><span class="n">text</span><span class="p">())</span>
</pre></div>
</td></tr></table>


<?php
	afiseazaSubsol('ro');
}	
?>
