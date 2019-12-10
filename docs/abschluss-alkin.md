
# Abschlussprojekt - Transportmanagementsystem in der Cloud

## Zusammenfassung

Das Projekt soll ein vereinfachtes Transportmanagementsystem in der Cloud modellieren. Die Software soll die Transportkosten für LKW-Fahrten überwachen.

Die Daten werden auf einem MySQL-Datenbankcluster in der Azure Cloud gespeichert. Über eine einfache Weboberfläche kann man Fahrten anlegen. Das Tracking der Fahrzeuge wird durch einen Raspberry Pi simuliert, der auf Knopfdruck vorgefertigte Daten an die Cloud sendet.

## Technologien

Datenbank: MySQL auf Azure
Webinterface: PHP
Eingabe: Python-Skript auf Raspberry, das auf Ereignisse reagiert JSON-Daten an eine API sendet

## Features

In der Datenbank sind Orte mit ihren Geodaten als Stammdaten hinterlegt.

LKWs werden mit Durchschnittsgeschwindigkeit, Fixkosten und Fahrtkosten ebenfalls als Stammdaten gespeichert.

Über das Webinterface können Routen zwischen zwei Orten mit Zwischenstationen und einem gewünschten Abfahrtszeitpunkt angelegt werden. Das System kalkuliert automatisch die Distanzen sowie die voraussichtlichen Reisezeiten und Kosten.

Das System empfängt Informationen über die tatsächlichen Bewegungen der LKWs und berechnet die Differenzen zwischen den erwarteten und den tatsächlichen Reisezeiten und Kosten.


