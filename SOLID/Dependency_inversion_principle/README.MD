## Dependency inversion principle (DIP)
### Zasada inwersji zależności

Zasada nakazuje, aby **moduły wysokiego poziomu nie powinny importować ineczego z modułów niskiego poziomu**. Obydwa powinny zależeć od abstrakcji (np. interfejsów).
Abstrakcje nie powinny zależeć od szczegółów. Szczegóły (konkretne realizacje) powinny zależeć od abstrakcji.

Powinniśmy ograniczyć zależności od konkretnych implementacji, ale polegać na interfejsach . Jeśli dokonamy jakiejkolwiek zmiany w interfejsie (narusza to zasadę open/close), zmiana ta pociąga za sobą konieczność zmian w implementacjach tego interfejsu. Ale jeśli musimy zmienić konkretną implementację, prawdopodobnie nie musimy zmieniać naszego interfejsu.

Przykłądem będzie klasa PHP, która wysyła e-maile. W przypadku błędu szczegóły błędu są rejestrowane w bazie danych za pomocą rejestratora.

## Kod
### BodExample.php

Łamie to zasadę **inwersji zależności**. Nasza usługa wysyłania wiadomości e-mail wykorzystuje specyficzną implementację rejestratora. A co jeśli chcemy logować informacje o błędach do pliku lub Sentry? Będziemy musieli zmienić **MailerService**.

### GoodExample.php

Zgodnie z tą zasadą **MailerService powinna opierać się na abstrakcji**, a nie na szczegółowej implementacji. Dlatego dodajemy interfejs **LoggerInterface**.

W ten sposób możemy dowolnie podmieniać logi w bazie danych na logi gdziekolwiek chcemy, pod warunkiem, że szczegółowa implementacja implementuje **LoggerInterface**. Zmiana ta nie będzie wymagała modyfikacji **MailerService**.