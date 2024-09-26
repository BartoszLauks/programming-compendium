## Single responsibility principle (SRP)
### Zasada jednej odpowiedzialności

**„Klasa powinna mieć jeden i tylko jeden powód do zmiany”**
Czyli klasa lub funkcja powinna robić jedną rzecz.
Pod zasada **„wysokiej spójności i niskiego sprzężenia”**.

**Spójność** określa, w jakim stopniu funkcja lub klasa jest odpowiedzialna. Przykładem niech będzie podróż samolotem oraz rowerem. W przypadku samolotu musimy wykonać wiele czynności. Kupna biletu, przygotowania dokumentów, zarezerwowanie czasu na przyjazd na lotnisko przejście przeprawy itd. Jest przykład nie spójnego kodu. Zato podróżowanie rowerem wymaga od nas tylko wsiąść na rower.
**To przykład dużej spójności. Metoda/klasa powinna być taka jak rower, zrób jedną rzecz.**

**Sprzężenie** dotyczy tego, **jak łatwo jest ponownie wykorzystać dany moduł lub klasę**

Puzzle charakteryzują się wysokim sprzężeniem. mają niski sprzęg, można je dowolnie łączyć i każdy z nich można wykorzystać w dowolnym miejscu.
**Twój kod powinien przypominać klocki Lego, łatwe do użycia w różnych miejscach.**

Zasadę pojedynczej odpowiedzialności należy stosować łącznie z zasadą **„wysokiej spójności i niskiego sprzężenia”**

**Ad 1:** Ważne aby nie posunąć się za daleko w tworzeniu klas mających tylko jedną metodę publiczną z kilkoma linijkami kodu (zwykle wywołują inną metodę z innej klasy). Całkowicie nieczytelne i fatalne w utrzymaniu


## Kod
### BodExample.php

Klasa **BlogPost** robi zbyt wiele rzeczy, a jak wiemy, powinna robić tylko jedną rzecz.
Nie powinna odpowiadać za drukowanie, jak w tym przykładzie w formacie JSON i HTML

### GoodExample.php

Dzięki usunięciu metod drukowania z klasy **BlogPost** oraz dodania interfejsu **PrintableBlogPost** za pomocą metody, która może wydrukować post na blogu. Dzięki temu spełniamy warunek **(SRP)**

Ad 1*