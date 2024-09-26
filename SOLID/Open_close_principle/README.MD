## Open/closed principle (OCP)
### Zasada otwarty/zamknięt

**„Kod powinien być otwarty na rozszerzenie, ale zamknięty na modyfikację”**
Podczas tworzenia kodu nie przestrzeganie tej zasady może spowodować wiele konsekwencji. Zmiana deklaracji metody może być powodem nieprawidłowego działania w miejscu, w którym jest używana.

Przykładem nieprzestrzegania zasady niech będzie interfejs API. Załóżmy że mamy system który udostępnia materiały wideo do pobrania w jednym formacie.

*GET /video/{id}/download*

Użytkownicy korzystający z API żądają dodania możliwości pobierania wideo w różnych formatach. Adres zostanie zmieniony na:

*GET /video/{id}/{format}*

W ten sposób łamiemy zasadę **otwarte/zamknięte** ponieważ nasza zmiana zaburzy działanie aplikacji, która wykorzystują nasza aplikację. Poprawną zmiana powinno być:

*GET /video/{id}/download/{format}*

W ten sposób nasze api posiada **kompatybilność wsteczną**.

Przykładem zastosowania zasady **(OCP)** w kodzie będzie kilka klas zwierząt oraz klasa, która pozwala zwierzętom komunikować się.

## Kod
### BodExample.php

Klasa **Communication** nie przestrzega zasady **otwarte/zamknięte** ponieważ dodanie nowej klasy “zwierze” wymagać będzie wymagało zmiany w istniejącym kodzie oraz modyfikacje przełącznika w **funkcji communication()**

### GoodExample.php

W kodzie warto korzystać z interfejsów i się ich trzymać. Jeśli jednak chcesz coś zmienić, rozważ wzór dekoratora.

Klasa lub metoda powinna być na tyle mała i mieć jedno konkretne zadanie, aby żadne przyszłe zdarzenie nie wymagało modyfikacji (zasada pojedynczej odpowiedzialności). Ale musisz także rozważyć, czy w przyszłości może zaistnieć potrzeba zmian, takich jak nowy format odpowiedzi lub dodatkowy parametr, Twój kod powinien zostać zamknięty do modyfikacji.