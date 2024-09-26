# DRY (Don't Repeat Yourself)

To jedna z fundamentalnych zasad programowania, która mówi, że informacje, logika lub fragmenty kodu nie powinny być duplikowane. Innymi słowy, każda unikalna część wiedzy lub logiki powinna mieć jedno, jednoznaczne przedstawienie w kodzie.

## Kluczowe aspekty zasady DRY:

- **Unikanie duplikacji:** Kod nie powinien być powielany. Zamiast tego, powinien być zorganizowany w taki sposób, aby fragmenty, które mogą być użyte wielokrotnie, były zdefiniowane w jednym miejscu.
- **Centralizacja wiedzy:** Informacje, które są wykorzystywane w wielu miejscach, powinny być przechowywane w jednym miejscu, aby łatwo było je modyfikować i utrzymywać.
- **Modularność:** Kod powinien być podzielony na moduły lub komponenty, które można łatwo wielokrotnie używać i testować.

## Kod
### BodExample.php

W powyższym kodzie, logika połączenia z bazą danych i zamykania połączenia jest powtarzana w obu metodach.

### GoodExample.php

**Centralizacja wiedzy:**

Połączenie z bazą danych i zamykanie połączenia są zarządzane w klasie **Database**, dzięki czemu kod nie jest powtarzany w metodach **getUserData** i **getUserPosts**.

**Modularność:**

Klasa **Database** jest odpowiedzialna za wszystkie operacje związane z bazą danych, podczas gdy klasa User zajmuje się logiką specyficzną dla użytkowników.

**Czytelność i utrzymanie:**

Dzięki eliminacji powtarzających się fragmentów kodu, jest on bardziej czytelny i łatwiejszy do utrzymania. Wprowadzenie zmiany, np. zmiana sposobu połączenia z bazą danych, wymaga modyfikacji tylko w jednym miejscu.
