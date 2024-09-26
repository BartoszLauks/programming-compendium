<?php

class File
{
    public function read()
    {
        // ...
    }

    public function write()
    {
        // ...
    }
}

class ReadOnlyFile extends File
{
    public function write()
    {
        throw new ItsReadOnlyFileException();
    }
}

// Zachowanie niezgodne z klasą bazową: Klasa ReadOnlyFile zmienia oczekiwane zachowanie metody write() z klasy File.
// W klasie bazowej File, metoda write() jest dostępna i oczekuje się, że będzie działać prawidłowo.
// Jednak w klasie ReadOnlyFile, wywołanie write() powoduje rzucenie wyjątku.
// Oznacza to, że nie można bezpiecznie zastąpić obiektu klasy File obiektem klasy ReadOnlyFile, ponieważ kod,
// który korzysta z klasy bazowej, nie spodziewa się takiego zachowania.

// Zaburzenie kontraktu klasy bazowej: Klasa bazowa File definiuje pewien kontrakt,
// który mówi, że obiekt tej klasy może zarówno odczytywać, jak i zapisywać dane.
// Klasa ReadOnlyFile łamie ten kontrakt, ograniczając funkcjonalność tylko do odczytu i wywołując wyjątek przy próbie zapisu.
// Zasada Liskov wymaga, aby każda klasa pochodna w pełni spełniała kontrakt klasy bazowej.
