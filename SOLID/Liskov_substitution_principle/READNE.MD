## Liskov substitution principle (LSP)
### Zasada jednej odpowiedzialności 

**Zasada mówi, że zamiast klasy bazowej możemy użyć dowolnej klasy dziedziczącej**
Oznacza to, że zaimplementowana podklasa musimy również móc jej użyć zamiast klasy głównej.
Jeśli jest to nie możliwe oznacza to, że zasada *(LSP)* została złamana.



## Kod
### BodExample.php

Zgodnie z zasadą podstawienia Liskowa klasę Rectangle powinniśmy móc zastąpić klasą Square. Ale jeśli go wymienimy, okaże się, że test nie przechodzi (100 != 20). Zastąpienie metod setWidth() i setHight() złamało regułę podstawienia Liskova.
**Nie powinniśmy zmieniać sposobu działania metod klasy nadrzędnej.**

### GoodExample.php

**Nie każdy pomysł z „rzeczywistości” powinien zostać zaimplementowany w kodzie 1:1**. Klasa Square nie powinna dziedziczyć po klasie Rectangle. Jeśli obie te klasy mogą mieć obszar obliczeniowy, niech implementują wspólny interfejs, a nie dziedziczą jednej po drugiej, ponieważ są zupełnie różne.