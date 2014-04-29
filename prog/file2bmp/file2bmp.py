#!/usr/bin/env python

# Name: file2bmp
# Author: Paul Nechifor <irragal@gmail.com>
# Started: 05.06.2009
# Updated: 07.07.2009
# Description: Transforms any file into a BMP and vice versa.

from math import *
import sys

def hput(bmp, s):
	"""Writes 2 hex digits as a byte in the file"""
	if len(s) % 3 == 1: print "bubu!"; exit(1)
	for i in xrange(0, len(s), 3):
		bmp.write(chr(int(s[i:i+2], 16)))

def intstr(n):
	"""Transforms an int32 in the format needed by hput()"""
	str = ""
	for i in xrange(4):
		str += "%02x " % (n & 255)
		n >>= 8
	return str

def memint(str):
	"""Transforms a binary representation of an int32 to an number"""
	n = 0
	for i in xrange(4):
		n = (n << 8) | ord(str[3-i])
	return n

def makeBMP(filename):
	"""Reads the file whose name is given in the parameter and writes the BMP file"""
	file = open(filename).read()
	good = len(file)
	print "There are", good, "bytes."
	if good % 3 == 1: file += chr(0)*2
	elif good % 3 == 2: file += chr(0)
	pixeli = len(file) / 3
	print "That means", pixeli, "pixels."

	xy = int(ceil(sqrt(pixeli)))
	print "Making a", xy, "by", xy, "image."
	rest = xy*xy - pixeli
	print "Adding", rest, "pixels to make it a", xy*xy, "- pixel image."
	file += chr(0) * (rest*3)
	print "There are now %d bytes for %d pixels." % (len(file), xy*xy)
	mod = (xy * 3) % 4
	if mod != 0: pad = 4 - mod
	else: pad = 0
	print "Adding a padding of %d bytes so that the %d-pixel line will be %d bytes long." % (pad, xy, xy*3 + pad)

	new = ""
	for i in xrange(0, len(file), xy*3):
		new += file[i:i + xy*3] + chr(0)*pad
	file = new
	data = len(file)
	print "Now there are %d bytes." % (data)
	header = 54
	total = header + data
	print "The image will be %d bytes long." % (total)

	f = open(filename+".bmp", "w")

	hput(f, "42 4D")		# Magic number ("BM")
	hput(f, intstr(total))	# Size of the BMP file
	#hput(f, "00 00")		# Application Specific
	#hput(f, "00 00")		# Application Specific
	# here i'll put how may bytes are good
	hput(f, intstr(good))
	hput(f, "36 00 00 00")	# The offset where the bitmap data (pixels) can be found.
	hput(f, "28 00 00 00")	# The number of bytes in the header (from this point).
	hput(f, intstr(xy))		# The width of the bitmap in pixels
	hput(f, intstr(xy))		# The height of the bitmap in pixels
	hput(f, "01 00")		# Number of color planes being used.
	hput(f, "18 00")		# The number of bits/pixel.
	hput(f, "00 00 00 00")	# No compression used
	hput(f, intstr(data))	# The size of the raw BMP data (after this header)
	hput(f, "13 0B 00 00")	# The horizontal resolution of the image
	hput(f, "13 0B 00 00")	# The vertical resolution of the image
	hput(f, "00 00 00 00")	# Number of colors in the palette
	hput(f, "00 00 00 00")	# Means all colors are important

	f.write(file)
	f.close()

def getFromBMP(input, original):
	file = open(input).read()
	xy = memint(file[18:24])
	print "The image size is %dx%d." % (xy, xy)
	line = xy * 3
	print "A line is %d bytes long." % (line)
	padding = int(ceil(line / 4.0) * 4  -  line)
	print "This means %d bytes are padding bytes." % (padding)
	good = memint(file[6:10])
	print "There are %d good bytes after the padding is removed" % (good)

	file = file[54:] # removing the header
	new = ""
	for i in xrange(0, len(file), line + padding):
		new += file[i:i+line]
	
	f = open(original, "w")
	f.write(new[:good]) # writing only the good bytes
	f.close()	


if len(sys.argv) < 3:
	print "Usage: file2bmp.py to file.txt        -  creates file.txt.bmp\n" + \
		  "       file2bmp.py from file.txt.bmp  -  recreates file.txt"
else:
	if sys.argv[1] == "to":
		makeBMP(sys.argv[2])
	elif sys.argv[1] == "from":
		out = sys.argv[2]
		if out[-4:].lower() == ".bmp": out = out[:-4]
		else: out = out + "_from"
		getFromBMP(sys.argv[2], out)
	else:
		print "Must be 'to' or 'from'"


