<?php

namespace RefactoringGuru\Bridge\Conceptual;

/**
 * The Abstraction defines the interface for the "control" part of the two class
 * hierarchies. It maintains a reference to an object of the Implementation
 * hierarchy and delegates all of the real work to this object.
 *
 * Abstrakcja definiuje interfejs dla "części kontrolnej" w dwóch hierarchiach klas. Utrzymuje odniesienie do obiektu hierarchii Implementacji i deleguje całą faktyczną pracę na ten obiekt.
 */
class Abstraction
{
    /**
     * @var Implementation
     */
    protected $implementation;

    public function __construct(Implementation $implementation)
    {
        $this->implementation = $implementation;
    }

    public function operation(): string
    {
        return "Abstraction: Podstawowa operacja z:\n" .
            $this->implementation->operationImplementation();
    }
}

/**
 * You can extend the Abstraction without changing the Implementation classes.
 *
 * Możesz rozszerzyć Abstrakcję bez zmiany klas Implementacji.
 */
class ExtendedAbstraction extends Abstraction
{
    public function operation(): string
    {
        return "ExtendedAbstraction: Rozszerzona operacja z:\n" .
            $this->implementation->operationImplementation();
    }
}

/**
 * The Implementation defines the interface for all implementation classes. It
 * doesn't have to match the Abstraction's interface. In fact, the two
 * interfaces can be entirely different. Typically the Implementation interface
 * provides only primitive operations, while the Abstraction defines higher-
 * level operations based on those primitives.
 *
 * Implementacja definiuje interfejs dla wszystkich klas implementacji. Nie musi on odpowiadać interfejsowi Abstrakcji. W rzeczywistości oba interfejsy mogą być całkowicie różne. Zazwyczaj interfejs Implementacji dostarcza jedynie prymitywne operacje, podczas gdy Abstrakcja definiuje operacje wyższego poziomu oparte na tych prymitywach.
 */
interface Implementation
{
    public function operationImplementation(): string;
}

/**
 * Each Concrete Implementation corresponds to a specific platform and
 * implements the Implementation interface using that platform's API.
 *
 * Każda Konkretna Implementacja odpowiada specyficznej platformie i implementuje interfejs Implementacji, wykorzystując API tej platformy.
 */
class ConcreteImplementationA implements Implementation
{
    public function operationImplementation(): string
    {
        return "ConcreteImplementationA: Oto wynik na platformie A.\n";
    }
}

class ConcreteImplementationB implements Implementation
{
    public function operationImplementation(): string
    {
        return "ConcreteImplementationB: Oto wynik na platformie B.\n";
    }
}

/**
 * Except for the initialization phase, where an Abstraction object gets linked
 * with a specific Implementation object, the client code should only depend on
 * the Abstraction class. This way the client code can support any abstraction-
 * implementation combination.
 *
 * Z wyjątkiem fazy inicjalizacji, w której obiekt Abstrakcji zostaje połączony z konkretnym obiektem Implementacji, kod klienta powinien zależeć tylko od klasy Abstrakcji. W ten sposób kod klienta może obsługiwać dowolną kombinację Abstrakcji i Implementacji.
 */
function clientCode(Abstraction $abstraction)
{
    // ...

    echo $abstraction->operation();

    // ...
}

/**
 * The client code should be able to work with any pre-configured abstraction-
 * implementation combination.
 *
 * Kod klienta powinien być w stanie pracować z dowolną wstępnie skonfigurowaną kombinacją Abstrakcji i Implementacji.
 */
$implementation = new ConcreteImplementationA();
$abstraction = new Abstraction($implementation);
clientCode($abstraction);

echo "\n";

$implementation = new ConcreteImplementationB();
$abstraction = new ExtendedAbstraction($implementation);
clientCode($abstraction);
