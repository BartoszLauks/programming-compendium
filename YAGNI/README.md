# YAGNI (You Aren't Gonna Need It)

to jedna z zasad programowania agile, która mówi, że nie powinieneś implementować funkcji, dopóki rzeczywiście nie będziesz ich potrzebował. Celem tej zasady jest uniknięcie nadmiernej komplikacji kodu i oszczędność zasobów poprzez ograniczenie niepotrzebnej pracy.

## Kluczowe aspekty zasady YAGNI:

- **Minimalizm:** Twórz tylko to, co jest potrzebne do spełnienia obecnych wymagań.
- **Unikanie nadmiaru:** Nie dodawaj funkcji "na zapas" ani nie implementuj potencjalnych przyszłych scenariuszy, które mogą nigdy się nie wydarzyć.
- **Zwinność:** Skup się na dostarczaniu wartości biznesowej i bądź gotowy na szybkie dostosowanie kodu do zmieniających się wymagań.

## Kod
### BodExample.php

Wyobraźmy sobie, że pracujemy nad systemem zarządzania użytkownikami i decydujemy się dodać funkcję wyszukiwania użytkowników według różnych kryteriów, chociaż obecne wymagania tego nie obejmują.

### GoodExample.php

Zgodnie z zasadą YAGNI, implementujemy tylko te funkcje, które są rzeczywiście potrzebne. W tym przypadku skupiamy się na prostym zarządzaniu użytkownikami, bez zbędnych funkcji wyszukiwania.

**Omówienie:**

- **Minimalizm:** Implementując tylko to, co jest potrzebne, unikasz nadmiernej komplikacji kodu.
- **Skupienie na wartości:** Zasada YAGNI pomaga skupić się na dostarczaniu wartości biznesowej zamiast tracić czas na funkcje, które mogą nigdy nie być użyte.
- **Oszczędność czasu i zasobów:** Implementując tylko niezbędne funkcje, oszczędzasz czas i zasoby, które można przeznaczyć na inne ważne zadania.
- **Elastyczność:** Gdy wymagania się zmienią, możesz szybko dostosować kod bez konieczności refaktoryzacji zbędnych funkcji.

**Praktyczne zastosowanie YAGNI**

Podczas implementacji nowych funkcji warto zadawać sobie pytania:

- Czy ta funkcja jest rzeczywiście potrzebna teraz?
- Czy istnieje prostszy sposób na osiągnięcie tego samego celu?
- Czy ta funkcja dostarcza bezpośrednią wartość dla obecnych wymagań?

Jeśli odpowiedź na te pytania wskazuje, że dana funkcja nie jest potrzebna, warto z niej zrezygnować i skupić się na bardziej priorytetowych zadaniach.

Stosowanie zasady YAGNI prowadzi do bardziej zwinnego i efektywnego procesu programistycznego, gdzie kod jest prostszy, bardziej czytelny i łatwiejszy do utrzymania.