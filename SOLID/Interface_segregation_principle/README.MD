## Interface segregation principle (ISP)
### Zasada segregacji interfejsów

Prosta zasada mówiąca; "**Żaden klient nie powinien być zmuszany do polegania na metodach, których nie używa**”.
Przestrzega od tworzenia tzw. "**grubych interfejsów**", które deklarują wiele metod, jeśli któraś z nich mogłaby pozostać niewykorzystana.
lepiej mieć kilka dedykowanych małych interfejsów niż jeden zbyt ogólny.

Przykładem niech będzie system do importowania różnych formatów dokumentów.

## Kod
### BodExample.php

W powyższym przypadku zmusiliśmy do tego klasę **CreditNote**, która implementuje metodę **getPDF()** choć w ogóle jej nie potrzebuje.

### GoodExample.php

W ten sposób **CreditNote** nie musi się już martwić implementacją nieużywanej funkcji publicznej **getPDF()** . Jeśli zajdzie taka potrzeba w przyszłości, wystarczy skorzystać z osobnego interfejsu i go zaimplementować.