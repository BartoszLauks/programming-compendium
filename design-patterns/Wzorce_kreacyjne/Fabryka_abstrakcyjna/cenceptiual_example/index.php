<?php

declare(strict_types=1);

namespace RefactoringGuru\AbstractFactory\Conceptual;

/**
 * The Abstract Factory interface declares a set of methods that return
 * different abstract products. These products are called a family and are
 * related by a high-level theme or concept. Products of one family are usually
 * able to collaborate among themselves. A family of products may have several
 * variants, but the products of one variant are incompatible with products of
 * another.
 *
 * Interfejs Abstract Factory deklaruje zestaw metod, które zwracają
 * różne abstrakcyjne produkty. Te produkty nazywane są rodziną i są
 * powiązane przez motyw lub koncepcję na wysokim poziomie. Produkty jednej rodziny zwykle
 * potrafią współpracować ze sobą. Rodzina produktów może mieć kilka wariantów,
 * ale produkty jednego wariantu są niekompatybilne z produktami innego.
 */
interface AbstractFactory
{
    public function createProductA(): AbstractProductA;

    public function createProductB(): AbstractProductB;
}

/**
 * Concrete Factories produce a family of products that belong to a single
 * variant. The factory guarantees that resulting products are compatible. Note
 * that signatures of the Concrete Factory's methods return an abstract product,
 * while inside the method a concrete product is instantiated.
 *
 * Konkretne Fabryki produkują rodzinę produktów, które należą do jednego
 * wariantu. Fabryka gwarantuje, że powstałe produkty są kompatybilne.
 * Zauważ, że sygnatury metod Konkretnej Fabryki zwracają abstrakcyjny produkt,
 * podczas gdy wewnątrz metody tworzony jest konkretny produkt.
 */
class ConcreteFactory1 implements AbstractFactory
{
    public function createProductA(): AbstractProductA
    {
        return new ConcreteProductA1();
    }

    public function createProductB(): AbstractProductB
    {
        return new ConcreteProductB1();
    }
}

/**
 * Each Concrete Factory has a corresponding product variant.
 *
 * Każda Konkretna Fabryka ma odpowiedni wariant produktu.
 */
class ConcreteFactory2 implements AbstractFactory
{
    public function createProductA(): AbstractProductA
    {
        return new ConcreteProductA2();
    }

    public function createProductB(): AbstractProductB
    {
        return new ConcreteProductB2();
    }
}

/**
 * Each distinct product of a product family should have a base interface. All
 * variants of the product must implement this interface.
 *
 * Każdy odrębny produkt z rodziny produktów powinien mieć podstawowy interfejs.
 * Wszystkie warianty produktu muszą implementować ten interfejs.
 */
interface AbstractProductA
{
    public function usefulFunctionA(): string;
}

/**
 * Concrete Products are created by corresponding Concrete Factories.
 *
 * Konkretnie Produkty są tworzone przez odpowiednie Konkretne Fabryki.
 */
class ConcreteProductA1 implements AbstractProductA
{
    public function usefulFunctionA(): string
    {
        return "The result of the product A1.";
    }
}

class ConcreteProductA2 implements AbstractProductA
{
    public function usefulFunctionA(): string
    {
        return "The result of the product A2.";
    }
}

/**
 * Here's the base interface of another product. All products can interact
 * with each other, but proper interaction is possible only between products of
 * the same concrete variant.
 *
 * Oto podstawowy interfejs innego produktu. Wszystkie produkty mogą się
 * ze sobą komunikować, ale właściwa interakcja jest możliwa tylko między
 * produktami tego samego konkretnego wariantu.
 */
interface AbstractProductB
{
    /**
     * Product B is able to do its own thing...
     *
     * Produkt B potrafi wykonywać swoje własne funkcje...
     */
    public function usefulFunctionB(): string;

    /**
     * ...but it also can collaborate with the ProductA.
     *
     * ...ale może także współpracować z ProduktemA.
     *
     * The Abstract Factory makes sure that all products it creates are of the
     * same variant and thus, compatible.
     *
     * Fabryka Abstrakcyjna zapewnia, że wszystkie produkty, które tworzy, są tego samego
     * wariantu i są zatem kompatybilne.
     */
    public function anotherUsefulFunctionB(AbstractProductA $collaborator): string;
}

/**
 * Concrete Products are created by corresponding Concrete Factories.
 *
 * Konkretnie Produkty są tworzone przez odpowiednie Konkretne Fabryki.
 */
class ConcreteProductB1 implements AbstractProductB
{
    public function usefulFunctionB(): string
    {
        return "The result of the product B1.";
    }

    /**
     * The variant, Product B1, is only able to work correctly with the variant,
     * Product A1. Nevertheless, it accepts any instance of AbstractProductA as
     * an argument.
     *
     * Wariant, Produkt B1, jest w stanie poprawnie współpracować tylko z wariantem,
     * Produktem A1. Niemniej jednak, akceptuje dowolną instancję AbstractProductA jako
     * argument.
     */
    public function anotherUsefulFunctionB(AbstractProductA $collaborator): string
    {
        $result = $collaborator->usefulFunctionA();

        return "The result of the B1 collaborating with the ({$result})";
    }
}

class ConcreteProductB2 implements AbstractProductB
{
    public function usefulFunctionB(): string
    {
        return "The result of the product B2.";
    }

    /**
     * The variant, Product B2, is only able to work correctly with the variant,
     * Product A2. Nevertheless, it accepts any instance of AbstractProductA as
     * an argument.
     *
     * Wariant, Produkt B2, jest w stanie poprawnie współpracować tylko z wariantem,
     * Produktem A2. Niemniej jednak, akceptuje dowolną instancję AbstractProductA jako
     * argument.
     */
    public function anotherUsefulFunctionB(AbstractProductA $collaborator): string
    {
        $result = $collaborator->usefulFunctionA();

        return "The result of the B2 collaborating with the ({$result})";
    }
}

/**
 * The client code works with factories and products only through abstract
 * types: AbstractFactory and AbstractProduct. This lets you pass any factory or
 * product subclass to the client code without breaking it.
 *
 * Kod klienta działa z fabrykami i produktami tylko za pośrednictwem typów abstrakcyjnych:
 * AbstractFactory i AbstractProduct. Dzięki temu można przekazać dowolną fabrykę lub
 * podklasę produktu do kodu klienta bez jego łamania.
 */
function clientCode(AbstractFactory $factory)
{
    $productA = $factory->createProductA();
    $productB = $factory->createProductB();

    echo $productB->usefulFunctionB() . "\n";
    echo $productB->anotherUsefulFunctionB($productA) . "\n";
}

/**
 * The client code can work with any concrete factory class.
 *
 * Kod klienta może działać z dowolną klasą konkretnej fabryki.
 */
echo "Client: Testing client code with the first factory type:\n";
clientCode(new ConcreteFactory1());

echo "\n";

echo "Client: Testing the same client code with the second factory type:\n";
clientCode(new ConcreteFactory2());
?>
