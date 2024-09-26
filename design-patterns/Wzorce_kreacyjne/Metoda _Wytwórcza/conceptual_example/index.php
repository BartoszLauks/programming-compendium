<?php

namespace RefactoringGuru\FactoryMethod\Conceptual;

/**
 * The Creator class declares the factory method that is supposed to return an
 * object of a Product class. The Creator's subclasses usually provide the
 * implementation of this method.
 *
 * Klasa Creator deklaruje metodę fabryczną, która ma zwracać obiekt klasy Produkt.
 * Podklasy Creator zazwyczaj dostarczają implementację tej metody.
 */
abstract class Creator
{
    /**
     * Note that the Creator may also provide some default implementation of the
     * factory method.
     *
     * Zauważ, że Creator może również dostarczyć domyślną implementację metody fabrycznej.
     */
    abstract public function factoryMethod(): Product;

    /**
     * Also note that, despite its name, the Creator's primary responsibility is
     * not creating products. Usually, it contains some core business logic that
     * relies on Product objects, returned by the factory method. Subclasses can
     * indirectly change that business logic by overriding the factory method
     * and returning a different type of product from it.
     *
     * Zauważ również, że mimo swojej nazwy, główną odpowiedzialnością Creator nie
     * jest tworzenie produktów. Zazwyczaj zawiera on pewną podstawową logikę biznesową,
     * która opiera się na obiektach Produkt, zwracanych przez metodę fabryczną.
     * Podklasy mogą pośrednio zmieniać tę logikę biznesową, nadpisując metodę
     * fabryczną i zwracając z niej inny typ produktu.
     */
    public function someOperation(): string
    {
        // Call the factory method to create a Product object.
        // Wywołaj metodę fabryczną, aby utworzyć obiekt Produkt.
        $product = $this->factoryMethod();
        // Now, use the product.
        // Teraz użyj produktu.
        $result = "Creator: The same creator's code has just worked with " .
            $product->operation();

        return $result;
    }
}

/**
 * Concrete Creators override the factory method in order to change the
 * resulting product's type.
 *
 * Konkretne Kreatory nadpisują metodę fabryczną w celu zmiany typu
 * zwracanego produktu.
 */
class ConcreteCreator1 extends Creator
{
    /**
     * Note that the signature of the method still uses the abstract product
     * type, even though the concrete product is actually returned from the
     * method. This way the Creator can stay independent of concrete product
     * classes.
     *
     * Zauważ, że sygnatura metody nadal używa abstrakcyjnego typu produktu,
     * mimo że z metody faktycznie zwracany jest konkretny produkt. W ten sposób
     * Creator może pozostać niezależny od konkretnych klas produktów.
     */
    public function factoryMethod(): Product
    {
        return new ConcreteProduct1();
    }
}

class ConcreteCreator2 extends Creator
{
    public function factoryMethod(): Product
    {
        return new ConcreteProduct2();
    }
}

/**
 * The Product interface declares the operations that all concrete products must
 * implement.
 *
 * Interfejs Produkt deklaruje operacje, które muszą zaimplementować wszystkie
 * konkretne produkty.
 */
interface Product
{
    public function operation(): string;
}

/**
 * Concrete Products provide various implementations of the Product interface.
 *
 * Konkretne Produkty dostarczają różne implementacje interfejsu Produkt.
 */
class ConcreteProduct1 implements Product
{
    public function operation(): string
    {
        return "{Result of the ConcreteProduct1}";
    }
}

class ConcreteProduct2 implements Product
{
    public function operation(): string
    {
        return "{Result of the ConcreteProduct2}";
    }
}

/**
 * The client code works with an instance of a concrete creator, albeit through
 * its base interface. As long as the client keeps working with the creator via
 * the base interface, you can pass it any creator's subclass.
 *
 * Kod klienta pracuje z instancją konkretnego kreatora, choć poprzez jego
 * podstawowy interfejs. Dopóki klient nadal pracuje z kreatorem poprzez
 * podstawowy interfejs, możesz przekazać mu dowolną podklasę kreatora.
 */
function clientCode(Creator $creator): void
{
    // ...
    echo "Client: I'm not aware of the creator's class, but it still works.\n"
        . $creator->someOperation();
    // ...
}

/**
 * The Application picks a creator's type depending on the configuration or
 * environment.
 *
 * Aplikacja wybiera typ kreatora w zależności od konfiguracji lub środowiska.
 */
echo "App: Launched with the ConcreteCreator1.\n";
clientCode(new ConcreteCreator1());
echo "\n\n";

echo "App: Launched with the ConcreteCreator2.\n";
clientCode(new ConcreteCreator2());
