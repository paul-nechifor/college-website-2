/*
 * Nume: gaseste
 * Autor: Paul Nechifor <irragal@gmail.com>
 * Inceput: 23.07.2009
 * Terminat: 23.07.2009
 * Descriere: Citeste o intrebare de la stdin si cauta intrebarea cea mai apropiata
 * din fisierele rapide.txt si grila.txt.
*/

#include <stdio.h>
#include <string.h>
#include <stdlib.h>

#define LUNG 5

struct intrebare
{
	char text[300];
	char rasp[200];
};

struct intrebare intrebari[30000];
int nr = 0;

int scorPentru(char* corect, char* verific)
{
	int i, scor = 0, lc = strlen(corect) - LUNG;
	char save;

	for (i=0; i<lc; i++)
	{
		save = corect[i + LUNG];
		corect[i + LUNG] = 0;
		if (strstr(verific, &corect[i]) == NULL) scor++;
		corect[i + LUNG] = save;
	}
	return scor;
}

void incarcaRapide()
{
	int i;
	FILE* rapide = fopen("rapide.txt", "r");
	if (!rapide) { printf("Nu exista 'rapide.txt'!\n");   exit(4); }

	while (fgets(intrebari[nr].text, 300, rapide))
	{
		intrebari[nr].text[strlen(intrebari[nr].text)-1] = 0;
		fgets(intrebari[nr].rasp, 10, rapide);
		nr++;
	}
	fclose(rapide);
}
void incarcaGrila()
{
	char r[4][200];
	int i, c;
	FILE* grila = fopen("grila.txt", "r");
	if (!grila) { printf("Nu exista 'grila.txt'!\n");   exit(3); }

	while (fgets(intrebari[nr].text, 300, grila))
	{
		intrebari[nr].text[strlen(intrebari[nr].text)-1] = 0;
		for (i=0; i<4; i++)
			fgets(r[i], 200, grila);
		fscanf(grila, "%d\n", &c);
		strcpy(intrebari[nr].rasp, r[c-1]);
		nr++;
	}
	fclose(grila);
}

int main(int argc, char *argv[])
{
	int i, s, min = 9999;
	char cauta[1000];
	
	if (argc < 2) { printf("Utilizare: %s grila|rapid\n", argv[0]);   exit(1); }

	if (!strcmp(argv[1], "rapid")) incarcaRapide();
	else if (!strcmp(argv[1], "grila")) incarcaGrila();
	else { printf("Trebuie grila sau rapid\n");   exit(2); }

	fgets(cauta, sizeof(cauta), stdin);

	for (i=0; i<nr; i++)
	{
		s = scorPentru(cauta, intrebari[i].text);
		if (s < min)
		{
			min = s;
			printf("%s\n", intrebari[i].text);
			printf("S:%-4d  CORECT: %s", s, intrebari[i].rasp);
		}
	}

	return 0;
}
