#!/usr/bin/env python

# Nume: conquiz_ocr
# Autor: Paul Nechifor <irragal@gmail.com>
# Inceput: 22.07.2009
# Terminat: 23.07.2009
# Descriere: Programul asta asculta niste taste folosind xlib si face screenshot la o intrebare
# de pe ConQUIZtador. Apoi citeste textul din imagine si cauta in baza de date raspunsul.

import os, re
from Xlib.display import Display
from Xlib import X

dinNou = False

def handle_event(event):
	keycode = event.detail
	global dinNou

	if keycode == 51:
		dinNou = True
		print "\n" * 50
		return
	
	if not dinNou: return
	dinNou = False

	if keycode == 47:
		marime = "900x110+105+382" # pentru intrebare rapida
	elif keycode == 48 or keycode == 34: 
		marime = "1230x100+105+424" # pentru intrebare grila si intrebare rapida care vine dupa intrebare grila

	# folosesc programul import pentru a face un screenshot, care este salvat ca 'imagine.pbm'
	os.system("import -window root -crop " + marime + " imagine.pbm")
	# folosesc programul ocrad pentru a citi textul din imagine
	text = os.popen("ocrad --charset=ascii -i imagine.pbm").read()
	# inlocuiesc mai multe caractere albe cu un singur spatiu
	text = re.sub("[ \t\n]+", " ", text)
	# 'escape' pentru apostrof si slash
	text.replace("\\", "\\\\").replace("'", "\\'")

	# apelez programul C care trebuie sa gasesca intrebarea cea mai apropiata si raspunsul
	if keycode == 48:
		os.system("echo '" + text + "' | ./gaseste grila")
	elif keycode == 34 or keycode == 47:
		os.system("echo '" + text + "' | ./gaseste rapid")
	
	print "=" * 100,

# current display
disp = Display()
root = disp.screen().root

# we tell the X server we want to catch keyPress event
root.change_attributes(event_mask = X.KeyPressMask)

for keycode in [47, 48, 34, 51]: # tastele sunt ;'[\
	root.grab_key(keycode, X.AnyModifier, 1, X.GrabModeAsync, X.GrabModeAsync)

while 1:
	event = root.display.next_event()
	handle_event(event)
