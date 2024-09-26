<?php

interface TheDuck
{
    public function swim(): void;
}

class ToyDuck implements TheDuck
{
    private bool $isTurnedOn = false;

    public function swim(): void
    {
        if (!$this->isTurnedOn) {
            return;
        }

        // ...
    }
}

class LiveDuck implements TheDuck
{
    public function swim(): void
    {
        // ...
    }
}

class SomeController
{
    public function swim(): void
    {
        $this->releaseDucks([
            new LiveDuck(),
            new ToyDuck()
        ]);
    }

    private function releaseDucks(array $ducks): void
    {
        /** @var TheDuck $duck */
        foreach ($ducks as $duck) {
            $duck->swim(); // ToyDuck nie pływa bo jest wyłączona
        }
    }
}

// Klasa ToyDuck ma specyficzne zachowanie, gdzie metoda swim() działa tylko wtedy, gdy ToyDuck jest włączony.
// To może być zaskoczeniem dla użytkownika, który spodziewa się, że metoda swim() zawsze będzie działać, łamiąć w ten sposób zasadę Liskova.
