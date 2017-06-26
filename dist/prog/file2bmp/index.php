<?php
require '../../include/capete.php';
if ($_GET['limba'] == "en")
{
	afiseazaAntet('Transform any file to BMP and back again', '', '', 'en');
	////EN START///////////////////////////////////////////////////////////////////////////
	?>
		<h2>Transform any file to BMP and back again</h2>
		<div class="imagine_dr"><img src="ode_to_joy.ogg.bmp" alt="Ode to Joy" /><p>For example the file <a href="http://en.wikipedia.org/wiki/File:Ode_to_Joy.ogg">Ode to Joy</a> looks like this</p></div>
		<p>Doesn't everybody ask themselves how a file would look visualy? When I was looking through Wikipedia I saw how simple the <a href="http://en.wikipedia.org/wiki/BMP_file_format#Example_of_a_2x2_Pixel.2C_24-Bit_Bitmap">BMP file format</a> is and I thought to make a Python program to store the bytes from any file into a BMP (to be viewed) but to be able to reconstruct the original file.</p>
		<p>After that I thought that this program can be useful for something: <strong>PIRACY</strong>! For example someone can transform an MP3 in multiple BMP files (of a smaller size, say 500KiB) and put them on his blag. Who wants the song can download the images and transform them back to the MP3. The idea to represent a file as something else reminds me of the <a href="http://en.wikipedia.org/wiki/Illegal_prime">illegal number</a>.</p>
		<p style="clear:both">The sourcecode is below, but you can also get it from <a href="file2bmp.py">file2bmp.py</a>.</p>
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
121</pre></td><td class="code"><div class="highlight"><pre><span class="c">#!/usr/bin/env python</span>

<span class="c"># Name: file2bmp</span>
<span class="c"># Author: Paul Nechifor &lt;irragal@gmail.com&gt;</span>
<span class="c"># Started: 05.06.2009</span>
<span class="c"># Updated: 07.07.2009</span>
<span class="c"># Description: Transforms any file into a BMP and vice versa.</span>

<span class="k">from</span> <span class="nn">math</span> <span class="k">import</span> <span class="o">*</span>
<span class="k">import</span> <span class="nn">sys</span>

<span class="k">def</span> <span class="nf">hput</span><span class="p">(</span><span class="n">bmp</span><span class="p">,</span> <span class="n">s</span><span class="p">):</span>
	<span class="sd">&quot;&quot;&quot;Writes 2 hex digits as a byte in the file&quot;&quot;&quot;</span>
	<span class="k">if</span> <span class="nb">len</span><span class="p">(</span><span class="n">s</span><span class="p">)</span> <span class="o">%</span> <span class="mf">3</span> <span class="o">==</span> <span class="mf">1</span><span class="p">:</span> <span class="k">print</span> <span class="s">&quot;bubu!&quot;</span><span class="p">;</span> <span class="nb">exit</span><span class="p">(</span><span class="mf">1</span><span class="p">)</span>
	<span class="k">for</span> <span class="n">i</span> <span class="ow">in</span> <span class="nb">xrange</span><span class="p">(</span><span class="mf">0</span><span class="p">,</span> <span class="nb">len</span><span class="p">(</span><span class="n">s</span><span class="p">),</span> <span class="mf">3</span><span class="p">):</span>
		<span class="n">bmp</span><span class="o">.</span><span class="n">write</span><span class="p">(</span><span class="nb">chr</span><span class="p">(</span><span class="nb">int</span><span class="p">(</span><span class="n">s</span><span class="p">[</span><span class="n">i</span><span class="p">:</span><span class="n">i</span><span class="o">+</span><span class="mf">2</span><span class="p">],</span> <span class="mf">16</span><span class="p">)))</span>

<span class="k">def</span> <span class="nf">intstr</span><span class="p">(</span><span class="n">n</span><span class="p">):</span>
	<span class="sd">&quot;&quot;&quot;Transforms an int32 in the format needed by hput()&quot;&quot;&quot;</span>
	<span class="nb">str</span> <span class="o">=</span> <span class="s">&quot;&quot;</span>
	<span class="k">for</span> <span class="n">i</span> <span class="ow">in</span> <span class="nb">xrange</span><span class="p">(</span><span class="mf">4</span><span class="p">):</span>
		<span class="nb">str</span> <span class="o">+=</span> <span class="s">&quot;</span><span class="si">%02x</span><span class="s"> &quot;</span> <span class="o">%</span> <span class="p">(</span><span class="n">n</span> <span class="o">&amp;</span> <span class="mf">255</span><span class="p">)</span>
		<span class="n">n</span> <span class="o">&gt;&gt;=</span> <span class="mf">8</span>
	<span class="k">return</span> <span class="nb">str</span>

<span class="k">def</span> <span class="nf">memint</span><span class="p">(</span><span class="nb">str</span><span class="p">):</span>
	<span class="sd">&quot;&quot;&quot;Transforms a binary representation of an int32 to an number&quot;&quot;&quot;</span>
	<span class="n">n</span> <span class="o">=</span> <span class="mf">0</span>
	<span class="k">for</span> <span class="n">i</span> <span class="ow">in</span> <span class="nb">xrange</span><span class="p">(</span><span class="mf">4</span><span class="p">):</span>
		<span class="n">n</span> <span class="o">=</span> <span class="p">(</span><span class="n">n</span> <span class="o">&lt;&lt;</span> <span class="mf">8</span><span class="p">)</span> <span class="o">|</span> <span class="nb">ord</span><span class="p">(</span><span class="nb">str</span><span class="p">[</span><span class="mf">3</span><span class="o">-</span><span class="n">i</span><span class="p">])</span>
	<span class="k">return</span> <span class="n">n</span>

<span class="k">def</span> <span class="nf">makeBMP</span><span class="p">(</span><span class="n">filename</span><span class="p">):</span>
	<span class="sd">&quot;&quot;&quot;Reads the file whose name is given in the parameter and writes the BMP file&quot;&quot;&quot;</span>
	<span class="nb">file</span> <span class="o">=</span> <span class="nb">open</span><span class="p">(</span><span class="n">filename</span><span class="p">)</span><span class="o">.</span><span class="n">read</span><span class="p">()</span>
	<span class="n">good</span> <span class="o">=</span> <span class="nb">len</span><span class="p">(</span><span class="nb">file</span><span class="p">)</span>
	<span class="k">print</span> <span class="s">&quot;There are&quot;</span><span class="p">,</span> <span class="n">good</span><span class="p">,</span> <span class="s">&quot;bytes.&quot;</span>
	<span class="k">if</span> <span class="n">good</span> <span class="o">%</span> <span class="mf">3</span> <span class="o">==</span> <span class="mf">1</span><span class="p">:</span> <span class="nb">file</span> <span class="o">+=</span> <span class="nb">chr</span><span class="p">(</span><span class="mf">0</span><span class="p">)</span><span class="o">*</span><span class="mf">2</span>
	<span class="k">elif</span> <span class="n">good</span> <span class="o">%</span> <span class="mf">3</span> <span class="o">==</span> <span class="mf">2</span><span class="p">:</span> <span class="nb">file</span> <span class="o">+=</span> <span class="nb">chr</span><span class="p">(</span><span class="mf">0</span><span class="p">)</span>
	<span class="n">pixeli</span> <span class="o">=</span> <span class="nb">len</span><span class="p">(</span><span class="nb">file</span><span class="p">)</span> <span class="o">/</span> <span class="mf">3</span>
	<span class="k">print</span> <span class="s">&quot;That means&quot;</span><span class="p">,</span> <span class="n">pixeli</span><span class="p">,</span> <span class="s">&quot;pixels.&quot;</span>

	<span class="n">xy</span> <span class="o">=</span> <span class="nb">int</span><span class="p">(</span><span class="n">ceil</span><span class="p">(</span><span class="n">sqrt</span><span class="p">(</span><span class="n">pixeli</span><span class="p">)))</span>
	<span class="k">print</span> <span class="s">&quot;Making a&quot;</span><span class="p">,</span> <span class="n">xy</span><span class="p">,</span> <span class="s">&quot;by&quot;</span><span class="p">,</span> <span class="n">xy</span><span class="p">,</span> <span class="s">&quot;image.&quot;</span>
	<span class="n">rest</span> <span class="o">=</span> <span class="n">xy</span><span class="o">*</span><span class="n">xy</span> <span class="o">-</span> <span class="n">pixeli</span>
	<span class="k">print</span> <span class="s">&quot;Adding&quot;</span><span class="p">,</span> <span class="n">rest</span><span class="p">,</span> <span class="s">&quot;pixels to make it a&quot;</span><span class="p">,</span> <span class="n">xy</span><span class="o">*</span><span class="n">xy</span><span class="p">,</span> <span class="s">&quot;- pixel image.&quot;</span>
	<span class="nb">file</span> <span class="o">+=</span> <span class="nb">chr</span><span class="p">(</span><span class="mf">0</span><span class="p">)</span> <span class="o">*</span> <span class="p">(</span><span class="n">rest</span><span class="o">*</span><span class="mf">3</span><span class="p">)</span>
	<span class="k">print</span> <span class="s">&quot;There are now </span><span class="si">%d</span><span class="s"> bytes for </span><span class="si">%d</span><span class="s"> pixels.&quot;</span> <span class="o">%</span> <span class="p">(</span><span class="nb">len</span><span class="p">(</span><span class="nb">file</span><span class="p">),</span> <span class="n">xy</span><span class="o">*</span><span class="n">xy</span><span class="p">)</span>
	<span class="n">mod</span> <span class="o">=</span> <span class="p">(</span><span class="n">xy</span> <span class="o">*</span> <span class="mf">3</span><span class="p">)</span> <span class="o">%</span> <span class="mf">4</span>
	<span class="k">if</span> <span class="n">mod</span> <span class="o">!=</span> <span class="mf">0</span><span class="p">:</span> <span class="n">pad</span> <span class="o">=</span> <span class="mf">4</span> <span class="o">-</span> <span class="n">mod</span>
	<span class="k">else</span><span class="p">:</span> <span class="n">pad</span> <span class="o">=</span> <span class="mf">0</span>
	<span class="k">print</span> <span class="s">&quot;Adding a padding of </span><span class="si">%d</span><span class="s"> bytes so that the </span><span class="si">%d</span><span class="s">-pixel line will be </span><span class="si">%d</span><span class="s"> bytes long.&quot;</span> <span class="o">%</span> <span class="p">(</span><span class="n">pad</span><span class="p">,</span> <span class="n">xy</span><span class="p">,</span> <span class="n">xy</span><span class="o">*</span><span class="mf">3</span> <span class="o">+</span> <span class="n">pad</span><span class="p">)</span>

	<span class="n">new</span> <span class="o">=</span> <span class="s">&quot;&quot;</span>
	<span class="k">for</span> <span class="n">i</span> <span class="ow">in</span> <span class="nb">xrange</span><span class="p">(</span><span class="mf">0</span><span class="p">,</span> <span class="nb">len</span><span class="p">(</span><span class="nb">file</span><span class="p">),</span> <span class="n">xy</span><span class="o">*</span><span class="mf">3</span><span class="p">):</span>
		<span class="n">new</span> <span class="o">+=</span> <span class="nb">file</span><span class="p">[</span><span class="n">i</span><span class="p">:</span><span class="n">i</span> <span class="o">+</span> <span class="n">xy</span><span class="o">*</span><span class="mf">3</span><span class="p">]</span> <span class="o">+</span> <span class="nb">chr</span><span class="p">(</span><span class="mf">0</span><span class="p">)</span><span class="o">*</span><span class="n">pad</span>
	<span class="nb">file</span> <span class="o">=</span> <span class="n">new</span>
	<span class="n">data</span> <span class="o">=</span> <span class="nb">len</span><span class="p">(</span><span class="nb">file</span><span class="p">)</span>
	<span class="k">print</span> <span class="s">&quot;Now there are </span><span class="si">%d</span><span class="s"> bytes.&quot;</span> <span class="o">%</span> <span class="p">(</span><span class="n">data</span><span class="p">)</span>
	<span class="n">header</span> <span class="o">=</span> <span class="mf">54</span>
	<span class="n">total</span> <span class="o">=</span> <span class="n">header</span> <span class="o">+</span> <span class="n">data</span>
	<span class="k">print</span> <span class="s">&quot;The image will be </span><span class="si">%d</span><span class="s"> bytes long.&quot;</span> <span class="o">%</span> <span class="p">(</span><span class="n">total</span><span class="p">)</span>

	<span class="n">f</span> <span class="o">=</span> <span class="nb">open</span><span class="p">(</span><span class="n">filename</span><span class="o">+</span><span class="s">&quot;.bmp&quot;</span><span class="p">,</span> <span class="s">&quot;w&quot;</span><span class="p">)</span>

	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="s">&quot;42 4D&quot;</span><span class="p">)</span>		<span class="c"># Magic number (&quot;BM&quot;)</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="n">intstr</span><span class="p">(</span><span class="n">total</span><span class="p">))</span>	<span class="c"># Size of the BMP file</span>
	<span class="c">#hput(f, &quot;00 00&quot;)		# Application Specific</span>
	<span class="c">#hput(f, &quot;00 00&quot;)		# Application Specific</span>
	<span class="c"># here i&#39;ll put how may bytes are good</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="n">intstr</span><span class="p">(</span><span class="n">good</span><span class="p">))</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="s">&quot;36 00 00 00&quot;</span><span class="p">)</span>	<span class="c"># The offset where the bitmap data (pixels) can be found.</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="s">&quot;28 00 00 00&quot;</span><span class="p">)</span>	<span class="c"># The number of bytes in the header (from this point).</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="n">intstr</span><span class="p">(</span><span class="n">xy</span><span class="p">))</span>		<span class="c"># The width of the bitmap in pixels</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="n">intstr</span><span class="p">(</span><span class="n">xy</span><span class="p">))</span>		<span class="c"># The height of the bitmap in pixels</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="s">&quot;01 00&quot;</span><span class="p">)</span>		<span class="c"># Number of color planes being used.</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="s">&quot;18 00&quot;</span><span class="p">)</span>		<span class="c"># The number of bits/pixel.</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="s">&quot;00 00 00 00&quot;</span><span class="p">)</span>	<span class="c"># No compression used</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="n">intstr</span><span class="p">(</span><span class="n">data</span><span class="p">))</span>	<span class="c"># The size of the raw BMP data (after this header)</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="s">&quot;13 0B 00 00&quot;</span><span class="p">)</span>	<span class="c"># The horizontal resolution of the image</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="s">&quot;13 0B 00 00&quot;</span><span class="p">)</span>	<span class="c"># The vertical resolution of the image</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="s">&quot;00 00 00 00&quot;</span><span class="p">)</span>	<span class="c"># Number of colors in the palette</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="s">&quot;00 00 00 00&quot;</span><span class="p">)</span>	<span class="c"># Means all colors are important</span>

	<span class="n">f</span><span class="o">.</span><span class="n">write</span><span class="p">(</span><span class="nb">file</span><span class="p">)</span>
	<span class="n">f</span><span class="o">.</span><span class="n">close</span><span class="p">()</span>

<span class="k">def</span> <span class="nf">getFromBMP</span><span class="p">(</span><span class="nb">input</span><span class="p">,</span> <span class="n">original</span><span class="p">):</span>
	<span class="nb">file</span> <span class="o">=</span> <span class="nb">open</span><span class="p">(</span><span class="nb">input</span><span class="p">)</span><span class="o">.</span><span class="n">read</span><span class="p">()</span>
	<span class="n">xy</span> <span class="o">=</span> <span class="n">memint</span><span class="p">(</span><span class="nb">file</span><span class="p">[</span><span class="mf">18</span><span class="p">:</span><span class="mf">24</span><span class="p">])</span>
	<span class="k">print</span> <span class="s">&quot;The image size is </span><span class="si">%d</span><span class="s">x</span><span class="si">%d</span><span class="s">.&quot;</span> <span class="o">%</span> <span class="p">(</span><span class="n">xy</span><span class="p">,</span> <span class="n">xy</span><span class="p">)</span>
	<span class="n">line</span> <span class="o">=</span> <span class="n">xy</span> <span class="o">*</span> <span class="mf">3</span>
	<span class="k">print</span> <span class="s">&quot;A line is </span><span class="si">%d</span><span class="s"> bytes long.&quot;</span> <span class="o">%</span> <span class="p">(</span><span class="n">line</span><span class="p">)</span>
	<span class="n">padding</span> <span class="o">=</span> <span class="nb">int</span><span class="p">(</span><span class="n">ceil</span><span class="p">(</span><span class="n">line</span> <span class="o">/</span> <span class="mf">4.0</span><span class="p">)</span> <span class="o">*</span> <span class="mf">4</span>  <span class="o">-</span>  <span class="n">line</span><span class="p">)</span>
	<span class="k">print</span> <span class="s">&quot;This means </span><span class="si">%d</span><span class="s"> bytes are padding bytes.&quot;</span> <span class="o">%</span> <span class="p">(</span><span class="n">padding</span><span class="p">)</span>
	<span class="n">good</span> <span class="o">=</span> <span class="n">memint</span><span class="p">(</span><span class="nb">file</span><span class="p">[</span><span class="mf">6</span><span class="p">:</span><span class="mf">10</span><span class="p">])</span>
	<span class="k">print</span> <span class="s">&quot;There are </span><span class="si">%d</span><span class="s"> good bytes after the padding is removed&quot;</span> <span class="o">%</span> <span class="p">(</span><span class="n">good</span><span class="p">)</span>

	<span class="nb">file</span> <span class="o">=</span> <span class="nb">file</span><span class="p">[</span><span class="mf">54</span><span class="p">:]</span> <span class="c"># removing the header</span>
	<span class="n">new</span> <span class="o">=</span> <span class="s">&quot;&quot;</span>
	<span class="k">for</span> <span class="n">i</span> <span class="ow">in</span> <span class="nb">xrange</span><span class="p">(</span><span class="mf">0</span><span class="p">,</span> <span class="nb">len</span><span class="p">(</span><span class="nb">file</span><span class="p">),</span> <span class="n">line</span> <span class="o">+</span> <span class="n">padding</span><span class="p">):</span>
		<span class="n">new</span> <span class="o">+=</span> <span class="nb">file</span><span class="p">[</span><span class="n">i</span><span class="p">:</span><span class="n">i</span><span class="o">+</span><span class="n">line</span><span class="p">]</span>
	
	<span class="n">f</span> <span class="o">=</span> <span class="nb">open</span><span class="p">(</span><span class="n">original</span><span class="p">,</span> <span class="s">&quot;w&quot;</span><span class="p">)</span>
	<span class="n">f</span><span class="o">.</span><span class="n">write</span><span class="p">(</span><span class="n">new</span><span class="p">[:</span><span class="n">good</span><span class="p">])</span> <span class="c"># writing only the good bytes</span>
	<span class="n">f</span><span class="o">.</span><span class="n">close</span><span class="p">()</span>	


<span class="k">if</span> <span class="nb">len</span><span class="p">(</span><span class="n">sys</span><span class="o">.</span><span class="n">argv</span><span class="p">)</span> <span class="o">&lt;</span> <span class="mf">3</span><span class="p">:</span>
	<span class="k">print</span> <span class="s">&quot;Usage: file2bmp.py to file.txt        -  creates file.txt.bmp</span><span class="se">\n</span><span class="s">&quot;</span> <span class="o">+</span> \
		  <span class="s">&quot;       file2bmp.py from file.txt.bmp  -  recreates file.txt&quot;</span>
<span class="k">else</span><span class="p">:</span>
	<span class="k">if</span> <span class="n">sys</span><span class="o">.</span><span class="n">argv</span><span class="p">[</span><span class="mf">1</span><span class="p">]</span> <span class="o">==</span> <span class="s">&quot;to&quot;</span><span class="p">:</span>
		<span class="n">makeBMP</span><span class="p">(</span><span class="n">sys</span><span class="o">.</span><span class="n">argv</span><span class="p">[</span><span class="mf">2</span><span class="p">])</span>
	<span class="k">elif</span> <span class="n">sys</span><span class="o">.</span><span class="n">argv</span><span class="p">[</span><span class="mf">1</span><span class="p">]</span> <span class="o">==</span> <span class="s">&quot;from&quot;</span><span class="p">:</span>
		<span class="n">out</span> <span class="o">=</span> <span class="n">sys</span><span class="o">.</span><span class="n">argv</span><span class="p">[</span><span class="mf">2</span><span class="p">]</span>
		<span class="k">if</span> <span class="n">out</span><span class="p">[</span><span class="o">-</span><span class="mf">4</span><span class="p">:]</span><span class="o">.</span><span class="n">lower</span><span class="p">()</span> <span class="o">==</span> <span class="s">&quot;.bmp&quot;</span><span class="p">:</span> <span class="n">out</span> <span class="o">=</span> <span class="n">out</span><span class="p">[:</span><span class="o">-</span><span class="mf">4</span><span class="p">]</span>
		<span class="k">else</span><span class="p">:</span> <span class="n">out</span> <span class="o">=</span> <span class="n">out</span> <span class="o">+</span> <span class="s">&quot;_from&quot;</span>
		<span class="n">getFromBMP</span><span class="p">(</span><span class="n">sys</span><span class="o">.</span><span class="n">argv</span><span class="p">[</span><span class="mf">2</span><span class="p">],</span> <span class="n">out</span><span class="p">)</span>
	<span class="k">else</span><span class="p">:</span>
		<span class="k">print</span> <span class="s">&quot;Must be &#39;to&#39; or &#39;from&#39;&quot;</span>
</pre></div>
</td></tr></table>
<?php
	////EN STOP////////////////////////////////////////////////////////////////////////////
	afiseazaSubsol('en');
}
else
{
	afiseazaAntet('Contact', '', '', 'ro');	
	////RO START///////////////////////////////////////////////////////////////////////////
	?>
		<h2>Transformă orice fișier în BMP și invers</h2>
		<div class="imagine_dr"><img src="ode_to_joy.ogg.bmp" alt="Ode to Joy" /><p>Spre exemplu fișierul <a href="http://en.wikipedia.org/wiki/File:Ode_to_Joy.ogg">Ode to Joy</a> așa arată</p></div>
		<p>Nu se-ntreabă toată lumea cum ar arăta reprezentarea unui fișier într-un mod vizual? Când umblam pe Wikipedia am văzut cât de simplu este formatul pentru un fișier <a href="http://en.wikipedia.org/wiki/BMP_file_format#Example_of_a_2x2_Pixel.2C_24-Bit_Bitmap">BMP</a> și m-am gândit să fac un program Python care să rețină biți din orice fișier într-un fișier BMP (pentru a fi vizualizat), dar să pot să refac fișierul original.</p>
		<p>După ceva timp m-am gândit că programul ăsta poate avea o aplicație: PIRATERIE! Spre exemplu cineva poate să transforme un fișier MP3 în mai multe fișiere BMP (de o mărime mai mică, ca 500 KiB) și să pună pe blogul lui imaginile. Cine vrea melodia, poate să descarce imagile și să le transforme înapoi în MP3. Ideea de a reprezenta un fișier altfel îmi amintește de <a href="http://en.wikipedia.org/wiki/Illegal_prime">numărul ilegal</a>.</p>
		<p style="clear:both">Codul sursă îl scriu mai jos, dar se găsește și în fișierul <a href="file2bmp.py">file2bmp.py</a>.</p>
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
121</pre></td><td class="code"><div class="highlight"><pre><span class="c">#!/usr/bin/env python</span>

<span class="c"># Name: file2bmp</span>
<span class="c"># Author: Paul Nechifor &lt;irragal@gmail.com&gt;</span>
<span class="c"># Started: 05.06.2009</span>
<span class="c"># Updated: 07.07.2009</span>
<span class="c"># Description: Transforms any file into a BMP and vice versa.</span>

<span class="k">from</span> <span class="nn">math</span> <span class="k">import</span> <span class="o">*</span>
<span class="k">import</span> <span class="nn">sys</span>

<span class="k">def</span> <span class="nf">hput</span><span class="p">(</span><span class="n">bmp</span><span class="p">,</span> <span class="n">s</span><span class="p">):</span>
	<span class="sd">&quot;&quot;&quot;Writes 2 hex digits as a byte in the file&quot;&quot;&quot;</span>
	<span class="k">if</span> <span class="nb">len</span><span class="p">(</span><span class="n">s</span><span class="p">)</span> <span class="o">%</span> <span class="mf">3</span> <span class="o">==</span> <span class="mf">1</span><span class="p">:</span> <span class="k">print</span> <span class="s">&quot;bubu!&quot;</span><span class="p">;</span> <span class="nb">exit</span><span class="p">(</span><span class="mf">1</span><span class="p">)</span>
	<span class="k">for</span> <span class="n">i</span> <span class="ow">in</span> <span class="nb">xrange</span><span class="p">(</span><span class="mf">0</span><span class="p">,</span> <span class="nb">len</span><span class="p">(</span><span class="n">s</span><span class="p">),</span> <span class="mf">3</span><span class="p">):</span>
		<span class="n">bmp</span><span class="o">.</span><span class="n">write</span><span class="p">(</span><span class="nb">chr</span><span class="p">(</span><span class="nb">int</span><span class="p">(</span><span class="n">s</span><span class="p">[</span><span class="n">i</span><span class="p">:</span><span class="n">i</span><span class="o">+</span><span class="mf">2</span><span class="p">],</span> <span class="mf">16</span><span class="p">)))</span>

<span class="k">def</span> <span class="nf">intstr</span><span class="p">(</span><span class="n">n</span><span class="p">):</span>
	<span class="sd">&quot;&quot;&quot;Transforms an int32 in the format needed by hput()&quot;&quot;&quot;</span>
	<span class="nb">str</span> <span class="o">=</span> <span class="s">&quot;&quot;</span>
	<span class="k">for</span> <span class="n">i</span> <span class="ow">in</span> <span class="nb">xrange</span><span class="p">(</span><span class="mf">4</span><span class="p">):</span>
		<span class="nb">str</span> <span class="o">+=</span> <span class="s">&quot;</span><span class="si">%02x</span><span class="s"> &quot;</span> <span class="o">%</span> <span class="p">(</span><span class="n">n</span> <span class="o">&amp;</span> <span class="mf">255</span><span class="p">)</span>
		<span class="n">n</span> <span class="o">&gt;&gt;=</span> <span class="mf">8</span>
	<span class="k">return</span> <span class="nb">str</span>

<span class="k">def</span> <span class="nf">memint</span><span class="p">(</span><span class="nb">str</span><span class="p">):</span>
	<span class="sd">&quot;&quot;&quot;Transforms a binary representation of an int32 to an number&quot;&quot;&quot;</span>
	<span class="n">n</span> <span class="o">=</span> <span class="mf">0</span>
	<span class="k">for</span> <span class="n">i</span> <span class="ow">in</span> <span class="nb">xrange</span><span class="p">(</span><span class="mf">4</span><span class="p">):</span>
		<span class="n">n</span> <span class="o">=</span> <span class="p">(</span><span class="n">n</span> <span class="o">&lt;&lt;</span> <span class="mf">8</span><span class="p">)</span> <span class="o">|</span> <span class="nb">ord</span><span class="p">(</span><span class="nb">str</span><span class="p">[</span><span class="mf">3</span><span class="o">-</span><span class="n">i</span><span class="p">])</span>
	<span class="k">return</span> <span class="n">n</span>

<span class="k">def</span> <span class="nf">makeBMP</span><span class="p">(</span><span class="n">filename</span><span class="p">):</span>
	<span class="sd">&quot;&quot;&quot;Reads the file whose name is given in the parameter and writes the BMP file&quot;&quot;&quot;</span>
	<span class="nb">file</span> <span class="o">=</span> <span class="nb">open</span><span class="p">(</span><span class="n">filename</span><span class="p">)</span><span class="o">.</span><span class="n">read</span><span class="p">()</span>
	<span class="n">good</span> <span class="o">=</span> <span class="nb">len</span><span class="p">(</span><span class="nb">file</span><span class="p">)</span>
	<span class="k">print</span> <span class="s">&quot;There are&quot;</span><span class="p">,</span> <span class="n">good</span><span class="p">,</span> <span class="s">&quot;bytes.&quot;</span>
	<span class="k">if</span> <span class="n">good</span> <span class="o">%</span> <span class="mf">3</span> <span class="o">==</span> <span class="mf">1</span><span class="p">:</span> <span class="nb">file</span> <span class="o">+=</span> <span class="nb">chr</span><span class="p">(</span><span class="mf">0</span><span class="p">)</span><span class="o">*</span><span class="mf">2</span>
	<span class="k">elif</span> <span class="n">good</span> <span class="o">%</span> <span class="mf">3</span> <span class="o">==</span> <span class="mf">2</span><span class="p">:</span> <span class="nb">file</span> <span class="o">+=</span> <span class="nb">chr</span><span class="p">(</span><span class="mf">0</span><span class="p">)</span>
	<span class="n">pixeli</span> <span class="o">=</span> <span class="nb">len</span><span class="p">(</span><span class="nb">file</span><span class="p">)</span> <span class="o">/</span> <span class="mf">3</span>
	<span class="k">print</span> <span class="s">&quot;That means&quot;</span><span class="p">,</span> <span class="n">pixeli</span><span class="p">,</span> <span class="s">&quot;pixels.&quot;</span>

	<span class="n">xy</span> <span class="o">=</span> <span class="nb">int</span><span class="p">(</span><span class="n">ceil</span><span class="p">(</span><span class="n">sqrt</span><span class="p">(</span><span class="n">pixeli</span><span class="p">)))</span>
	<span class="k">print</span> <span class="s">&quot;Making a&quot;</span><span class="p">,</span> <span class="n">xy</span><span class="p">,</span> <span class="s">&quot;by&quot;</span><span class="p">,</span> <span class="n">xy</span><span class="p">,</span> <span class="s">&quot;image.&quot;</span>
	<span class="n">rest</span> <span class="o">=</span> <span class="n">xy</span><span class="o">*</span><span class="n">xy</span> <span class="o">-</span> <span class="n">pixeli</span>
	<span class="k">print</span> <span class="s">&quot;Adding&quot;</span><span class="p">,</span> <span class="n">rest</span><span class="p">,</span> <span class="s">&quot;pixels to make it a&quot;</span><span class="p">,</span> <span class="n">xy</span><span class="o">*</span><span class="n">xy</span><span class="p">,</span> <span class="s">&quot;- pixel image.&quot;</span>
	<span class="nb">file</span> <span class="o">+=</span> <span class="nb">chr</span><span class="p">(</span><span class="mf">0</span><span class="p">)</span> <span class="o">*</span> <span class="p">(</span><span class="n">rest</span><span class="o">*</span><span class="mf">3</span><span class="p">)</span>
	<span class="k">print</span> <span class="s">&quot;There are now </span><span class="si">%d</span><span class="s"> bytes for </span><span class="si">%d</span><span class="s"> pixels.&quot;</span> <span class="o">%</span> <span class="p">(</span><span class="nb">len</span><span class="p">(</span><span class="nb">file</span><span class="p">),</span> <span class="n">xy</span><span class="o">*</span><span class="n">xy</span><span class="p">)</span>
	<span class="n">mod</span> <span class="o">=</span> <span class="p">(</span><span class="n">xy</span> <span class="o">*</span> <span class="mf">3</span><span class="p">)</span> <span class="o">%</span> <span class="mf">4</span>
	<span class="k">if</span> <span class="n">mod</span> <span class="o">!=</span> <span class="mf">0</span><span class="p">:</span> <span class="n">pad</span> <span class="o">=</span> <span class="mf">4</span> <span class="o">-</span> <span class="n">mod</span>
	<span class="k">else</span><span class="p">:</span> <span class="n">pad</span> <span class="o">=</span> <span class="mf">0</span>
	<span class="k">print</span> <span class="s">&quot;Adding a padding of </span><span class="si">%d</span><span class="s"> bytes so that the </span><span class="si">%d</span><span class="s">-pixel line will be </span><span class="si">%d</span><span class="s"> bytes long.&quot;</span> <span class="o">%</span> <span class="p">(</span><span class="n">pad</span><span class="p">,</span> <span class="n">xy</span><span class="p">,</span> <span class="n">xy</span><span class="o">*</span><span class="mf">3</span> <span class="o">+</span> <span class="n">pad</span><span class="p">)</span>

	<span class="n">new</span> <span class="o">=</span> <span class="s">&quot;&quot;</span>
	<span class="k">for</span> <span class="n">i</span> <span class="ow">in</span> <span class="nb">xrange</span><span class="p">(</span><span class="mf">0</span><span class="p">,</span> <span class="nb">len</span><span class="p">(</span><span class="nb">file</span><span class="p">),</span> <span class="n">xy</span><span class="o">*</span><span class="mf">3</span><span class="p">):</span>
		<span class="n">new</span> <span class="o">+=</span> <span class="nb">file</span><span class="p">[</span><span class="n">i</span><span class="p">:</span><span class="n">i</span> <span class="o">+</span> <span class="n">xy</span><span class="o">*</span><span class="mf">3</span><span class="p">]</span> <span class="o">+</span> <span class="nb">chr</span><span class="p">(</span><span class="mf">0</span><span class="p">)</span><span class="o">*</span><span class="n">pad</span>
	<span class="nb">file</span> <span class="o">=</span> <span class="n">new</span>
	<span class="n">data</span> <span class="o">=</span> <span class="nb">len</span><span class="p">(</span><span class="nb">file</span><span class="p">)</span>
	<span class="k">print</span> <span class="s">&quot;Now there are </span><span class="si">%d</span><span class="s"> bytes.&quot;</span> <span class="o">%</span> <span class="p">(</span><span class="n">data</span><span class="p">)</span>
	<span class="n">header</span> <span class="o">=</span> <span class="mf">54</span>
	<span class="n">total</span> <span class="o">=</span> <span class="n">header</span> <span class="o">+</span> <span class="n">data</span>
	<span class="k">print</span> <span class="s">&quot;The image will be </span><span class="si">%d</span><span class="s"> bytes long.&quot;</span> <span class="o">%</span> <span class="p">(</span><span class="n">total</span><span class="p">)</span>

	<span class="n">f</span> <span class="o">=</span> <span class="nb">open</span><span class="p">(</span><span class="n">filename</span><span class="o">+</span><span class="s">&quot;.bmp&quot;</span><span class="p">,</span> <span class="s">&quot;w&quot;</span><span class="p">)</span>

	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="s">&quot;42 4D&quot;</span><span class="p">)</span>		<span class="c"># Magic number (&quot;BM&quot;)</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="n">intstr</span><span class="p">(</span><span class="n">total</span><span class="p">))</span>	<span class="c"># Size of the BMP file</span>
	<span class="c">#hput(f, &quot;00 00&quot;)		# Application Specific</span>
	<span class="c">#hput(f, &quot;00 00&quot;)		# Application Specific</span>
	<span class="c"># here i&#39;ll put how may bytes are good</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="n">intstr</span><span class="p">(</span><span class="n">good</span><span class="p">))</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="s">&quot;36 00 00 00&quot;</span><span class="p">)</span>	<span class="c"># The offset where the bitmap data (pixels) can be found.</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="s">&quot;28 00 00 00&quot;</span><span class="p">)</span>	<span class="c"># The number of bytes in the header (from this point).</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="n">intstr</span><span class="p">(</span><span class="n">xy</span><span class="p">))</span>		<span class="c"># The width of the bitmap in pixels</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="n">intstr</span><span class="p">(</span><span class="n">xy</span><span class="p">))</span>		<span class="c"># The height of the bitmap in pixels</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="s">&quot;01 00&quot;</span><span class="p">)</span>		<span class="c"># Number of color planes being used.</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="s">&quot;18 00&quot;</span><span class="p">)</span>		<span class="c"># The number of bits/pixel.</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="s">&quot;00 00 00 00&quot;</span><span class="p">)</span>	<span class="c"># No compression used</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="n">intstr</span><span class="p">(</span><span class="n">data</span><span class="p">))</span>	<span class="c"># The size of the raw BMP data (after this header)</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="s">&quot;13 0B 00 00&quot;</span><span class="p">)</span>	<span class="c"># The horizontal resolution of the image</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="s">&quot;13 0B 00 00&quot;</span><span class="p">)</span>	<span class="c"># The vertical resolution of the image</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="s">&quot;00 00 00 00&quot;</span><span class="p">)</span>	<span class="c"># Number of colors in the palette</span>
	<span class="n">hput</span><span class="p">(</span><span class="n">f</span><span class="p">,</span> <span class="s">&quot;00 00 00 00&quot;</span><span class="p">)</span>	<span class="c"># Means all colors are important</span>

	<span class="n">f</span><span class="o">.</span><span class="n">write</span><span class="p">(</span><span class="nb">file</span><span class="p">)</span>
	<span class="n">f</span><span class="o">.</span><span class="n">close</span><span class="p">()</span>

<span class="k">def</span> <span class="nf">getFromBMP</span><span class="p">(</span><span class="nb">input</span><span class="p">,</span> <span class="n">original</span><span class="p">):</span>
	<span class="nb">file</span> <span class="o">=</span> <span class="nb">open</span><span class="p">(</span><span class="nb">input</span><span class="p">)</span><span class="o">.</span><span class="n">read</span><span class="p">()</span>
	<span class="n">xy</span> <span class="o">=</span> <span class="n">memint</span><span class="p">(</span><span class="nb">file</span><span class="p">[</span><span class="mf">18</span><span class="p">:</span><span class="mf">24</span><span class="p">])</span>
	<span class="k">print</span> <span class="s">&quot;The image size is </span><span class="si">%d</span><span class="s">x</span><span class="si">%d</span><span class="s">.&quot;</span> <span class="o">%</span> <span class="p">(</span><span class="n">xy</span><span class="p">,</span> <span class="n">xy</span><span class="p">)</span>
	<span class="n">line</span> <span class="o">=</span> <span class="n">xy</span> <span class="o">*</span> <span class="mf">3</span>
	<span class="k">print</span> <span class="s">&quot;A line is </span><span class="si">%d</span><span class="s"> bytes long.&quot;</span> <span class="o">%</span> <span class="p">(</span><span class="n">line</span><span class="p">)</span>
	<span class="n">padding</span> <span class="o">=</span> <span class="nb">int</span><span class="p">(</span><span class="n">ceil</span><span class="p">(</span><span class="n">line</span> <span class="o">/</span> <span class="mf">4.0</span><span class="p">)</span> <span class="o">*</span> <span class="mf">4</span>  <span class="o">-</span>  <span class="n">line</span><span class="p">)</span>
	<span class="k">print</span> <span class="s">&quot;This means </span><span class="si">%d</span><span class="s"> bytes are padding bytes.&quot;</span> <span class="o">%</span> <span class="p">(</span><span class="n">padding</span><span class="p">)</span>
	<span class="n">good</span> <span class="o">=</span> <span class="n">memint</span><span class="p">(</span><span class="nb">file</span><span class="p">[</span><span class="mf">6</span><span class="p">:</span><span class="mf">10</span><span class="p">])</span>
	<span class="k">print</span> <span class="s">&quot;There are </span><span class="si">%d</span><span class="s"> good bytes after the padding is removed&quot;</span> <span class="o">%</span> <span class="p">(</span><span class="n">good</span><span class="p">)</span>

	<span class="nb">file</span> <span class="o">=</span> <span class="nb">file</span><span class="p">[</span><span class="mf">54</span><span class="p">:]</span> <span class="c"># removing the header</span>
	<span class="n">new</span> <span class="o">=</span> <span class="s">&quot;&quot;</span>
	<span class="k">for</span> <span class="n">i</span> <span class="ow">in</span> <span class="nb">xrange</span><span class="p">(</span><span class="mf">0</span><span class="p">,</span> <span class="nb">len</span><span class="p">(</span><span class="nb">file</span><span class="p">),</span> <span class="n">line</span> <span class="o">+</span> <span class="n">padding</span><span class="p">):</span>
		<span class="n">new</span> <span class="o">+=</span> <span class="nb">file</span><span class="p">[</span><span class="n">i</span><span class="p">:</span><span class="n">i</span><span class="o">+</span><span class="n">line</span><span class="p">]</span>
	
	<span class="n">f</span> <span class="o">=</span> <span class="nb">open</span><span class="p">(</span><span class="n">original</span><span class="p">,</span> <span class="s">&quot;w&quot;</span><span class="p">)</span>
	<span class="n">f</span><span class="o">.</span><span class="n">write</span><span class="p">(</span><span class="n">new</span><span class="p">[:</span><span class="n">good</span><span class="p">])</span> <span class="c"># writing only the good bytes</span>
	<span class="n">f</span><span class="o">.</span><span class="n">close</span><span class="p">()</span>	


<span class="k">if</span> <span class="nb">len</span><span class="p">(</span><span class="n">sys</span><span class="o">.</span><span class="n">argv</span><span class="p">)</span> <span class="o">&lt;</span> <span class="mf">3</span><span class="p">:</span>
	<span class="k">print</span> <span class="s">&quot;Usage: file2bmp.py to file.txt        -  creates file.txt.bmp</span><span class="se">\n</span><span class="s">&quot;</span> <span class="o">+</span> \
		  <span class="s">&quot;       file2bmp.py from file.txt.bmp  -  recreates file.txt&quot;</span>
<span class="k">else</span><span class="p">:</span>
	<span class="k">if</span> <span class="n">sys</span><span class="o">.</span><span class="n">argv</span><span class="p">[</span><span class="mf">1</span><span class="p">]</span> <span class="o">==</span> <span class="s">&quot;to&quot;</span><span class="p">:</span>
		<span class="n">makeBMP</span><span class="p">(</span><span class="n">sys</span><span class="o">.</span><span class="n">argv</span><span class="p">[</span><span class="mf">2</span><span class="p">])</span>
	<span class="k">elif</span> <span class="n">sys</span><span class="o">.</span><span class="n">argv</span><span class="p">[</span><span class="mf">1</span><span class="p">]</span> <span class="o">==</span> <span class="s">&quot;from&quot;</span><span class="p">:</span>
		<span class="n">out</span> <span class="o">=</span> <span class="n">sys</span><span class="o">.</span><span class="n">argv</span><span class="p">[</span><span class="mf">2</span><span class="p">]</span>
		<span class="k">if</span> <span class="n">out</span><span class="p">[</span><span class="o">-</span><span class="mf">4</span><span class="p">:]</span><span class="o">.</span><span class="n">lower</span><span class="p">()</span> <span class="o">==</span> <span class="s">&quot;.bmp&quot;</span><span class="p">:</span> <span class="n">out</span> <span class="o">=</span> <span class="n">out</span><span class="p">[:</span><span class="o">-</span><span class="mf">4</span><span class="p">]</span>
		<span class="k">else</span><span class="p">:</span> <span class="n">out</span> <span class="o">=</span> <span class="n">out</span> <span class="o">+</span> <span class="s">&quot;_from&quot;</span>
		<span class="n">getFromBMP</span><span class="p">(</span><span class="n">sys</span><span class="o">.</span><span class="n">argv</span><span class="p">[</span><span class="mf">2</span><span class="p">],</span> <span class="n">out</span><span class="p">)</span>
	<span class="k">else</span><span class="p">:</span>
		<span class="k">print</span> <span class="s">&quot;Must be &#39;to&#39; or &#39;from&#39;&quot;</span>
</pre></div>
</td></tr></table>
<?php
	////RO STOP////////////////////////////////////////////////////////////////////////////
	afiseazaSubsol('ro');
}	
?>
