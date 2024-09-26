# KISS (Keep It Simple Stupid)

Jest jedną z fundamentalnych zasad inżynierii oprogramowania, która mówi, że systemy i kod powinny być tak proste, jak to możliwe. Prostota zwiększa czytelność, łatwość utrzymania i zmniejsza ryzyko błędów.

## Kluczowe aspekty zasady KISS:

- **Prostota:** Złożoność powinna być minimalizowana. Kod powinien być napisany w taki sposób, aby był łatwy do zrozumienia i utrzymania.
- **Czytelność:** Kod powinien być czytelny dla innych programistów. Stosowanie jasnych nazw zmiennych, funkcji i klas pomaga w zrozumieniu kodu.
- **Unikanie nadmiaru:** Unikaj dodawania zbędnych funkcji i logiki, które nie są konieczne dla obecnych wymagań.
- **Rozdzielanie odpowiedzialności:** Każda klasa lub funkcja powinna mieć jedno jasno określone zadanie.

## Kod
### BodExample.php

Przyład złożongo kodu.

### GoodExample.php

**Podział odpowiedzialności:**

Klasa User odpowiada tylko za dane użytkownika.
Klasa Database odpowiada tylko za operacje na bazie danych.
Klasa UserManager odpowiada za zarządzanie użytkownikami.

**Czytelność i prostota:**

Każda klasa i metoda mają jasno określone zadania.
Kod jest bardziej modularny i łatwiejszy do testowania.

**Unikanie nadmiaru:**

Metody są krótsze i wykonują tylko jedno zadanie, co zwiększa ich czytelność.
