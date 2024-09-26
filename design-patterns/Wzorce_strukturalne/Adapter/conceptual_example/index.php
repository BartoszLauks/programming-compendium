<?php

namespace RefactoringGuru\Adapter\Conceptual;

/**
 * The Target defines the domain-specific interface used by the client code.
 *
 * Klasa Target definiuje interfejs specyficzny dla danej domeny, który
 * jest używany przez kod klienta.
 */
class Target
{
    public function request(): string
    {
        return "Target: The default target's behavior.";
        // Domyślne zachowanie Targeta.
    }
}

/**
 * The Adaptee contains some useful behavior, but its interface is incompatible
 * with the existing client code. The Adaptee needs some adaptation before the
 * client code can use it.
 *
 * Klasa Adaptee zawiera użyteczne zachowanie, ale jej interfejs jest niezgodny
 * z istniejącym kodem klienta. Adaptee wymaga adaptacji, zanim kod klienta
 * będzie mógł go używać.
 */
class Adaptee
{
    public function specificRequest(): string
    {
        return ".eetpadA eht fo roivaheb laicepS";
        // Specjalne zachowanie Adaptee.
    }
}

/**
 * The Adapter makes the Adaptee's interface compatible with the Target's
 * interface.
 *
 * Klasa Adapter sprawia, że interfejs Adaptee jest zgodny z interfejsem Targeta.
 */
class Adapter extends Target
{
    private $adaptee;

    public function __construct(Adaptee $adaptee)
    {
        $this->adaptee = $adaptee;
    }

    public function request(): string
    {
        return "Adapter: (TRANSLATED) " . strrev($this->adaptee->specificRequest());
        // Adapter: (PRZETŁUMACZONO) " . strrev($this->adaptee->specificRequest());
    }
}

/**
 * The client code supports all classes that follow the Target interface.
 *
 * Kod klienta obsługuje wszystkie klasy, które przestrzegają interfejsu Target.
 */
function clientCode(Target $target)
{
    echo $target->request();
}

echo "Client: I can work just fine with the Target objects:\n";
// Klient: Mogę bez problemu pracować z obiektami Target:
$target = new Target();
clientCode($target);
echo "\n\n";

$adaptee = new Adaptee();
echo "Client: The Adaptee class has a weird interface. See, I don't understand it:\n";
// Klient: Klasa Adaptee ma dziwny interfejs. Zobacz, nie rozumiem tego:
echo "Adaptee: " . $adaptee->specificRequest();
echo "\n\n";

echo "Client: But I can work with it via the Adapter:\n";
// Klient: Ale mogę z nią pracować za pomocą Adaptera:
$adapter = new Adapter($adaptee);
clientCode($adapter);
