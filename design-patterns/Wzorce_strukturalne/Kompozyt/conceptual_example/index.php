<?php

namespace RefactoringGuru\Composite\Conceptual;

/**
 * The base Component class declares common operations for both simple and
 * complex objects of a composition.
 *
 * Podstawowa klasa Komponentu deklaruje wspólne operacje zarówno dla prostych, jak i złożonych obiektów kompozycji.
 */
abstract class Component
{
    /**
     * @var Component|null
     */
    protected $parent;

    /**
     * Optionally, the base Component can declare an interface for setting and
     * accessing a parent of the component in a tree structure. It can also
     * provide some default implementation for these methods.
     *
     * Opcjonalnie, podstawowy Komponent może zadeklarować interfejs do ustawiania i uzyskiwania dostępu do rodzica
     * komponentu w strukturze drzewa. Może również dostarczyć domyślną implementację tych metod.
     */
    public function setParent(?Component $parent)
    {
        $this->parent = $parent;
    }

    public function getParent(): Component
    {
        return $this->parent;
    }

    /**
     * In some cases, it would be beneficial to define the child-management
     * operations right in the base Component class. This way, you won't need to
     * expose any concrete component classes to the client code, even during the
     * object tree assembly. The downside is that these methods will be empty
     * for the leaf-level components.
     *
     * W niektórych przypadkach korzystne byłoby zdefiniowanie operacji zarządzania dziećmi bezpośrednio w podstawowej
     * klasie Komponentu. W ten sposób nie będziesz musiał ujawniać żadnych konkretnych klas komponentów dla kodu klienta,
     * nawet podczas składania drzewa obiektów. Minusem jest to, że te metody będą puste dla komponentów na poziomie liści.
     */
    public function add(Component $component): void { }

    public function remove(Component $component): void { }

    /**
     * You can provide a method that lets the client code figure out whether a
     * component can bear children.
     *
     * Możesz dostarczyć metodę, która pozwala kodowi klienta ustalić, czy komponent może mieć dzieci.
     */
    public function isComposite(): bool
    {
        return false;
    }

    /**
     * The base Component may implement some default behavior or leave it to
     * concrete classes (by declaring the method containing the behavior as
     * "abstract").
     *
     * Podstawowy Komponent może zaimplementować jakieś domyślne zachowanie lub pozostawić to konkretnym klasom
     * (poprzez zadeklarowanie metody zawierającej to zachowanie jako "abstrakcyjną").
     */
    abstract public function operation(): string;
}

/**
 * The Leaf class represents the end objects of a composition. A leaf can't have
 * any children.
 *
 * Usually, it's the Leaf objects that do the actual work, whereas Composite
 * objects only delegate to their sub-components.
 *
 * Klasa Liść reprezentuje końcowe obiekty kompozycji. Liść nie może mieć żadnych dzieci.
 * Zazwyczaj to obiekty Liści wykonują rzeczywistą pracę, podczas gdy obiekty Kompozytu jedynie delegują ją do swoich podkomponentów.
 */
class Leaf extends Component
{
    public function operation(): string
    {
        return "Leaf";
    }
}

/**
 * The Composite class represents the complex components that may have children.
 * Usually, the Composite objects delegate the actual work to their children and
 * then "sum-up" the result.
 *
 * Klasa Kompozyt reprezentuje złożone komponenty, które mogą mieć dzieci.
 * Zazwyczaj obiekty Kompozytu delegują rzeczywistą pracę do swoich dzieci, a następnie "sumują" wynik.
 */
class Composite extends Component
{
    /**
     * @var \SplObjectStorage
     */
    protected $children;

    public function __construct()
    {
        $this->children = new \SplObjectStorage();
    }

    /**
     * A composite object can add or remove other components (both simple or
     * complex) to or from its child list.
     *
     * Obiekt Kompozytu może dodawać lub usuwać inne komponenty (zarówno proste, jak i złożone) do swojej listy dzieci.
     */
    public function add(Component $component): void
    {
        $this->children->attach($component);
        $component->setParent($this);
    }

    public function remove(Component $component): void
    {
        $this->children->detach($component);
        $component->setParent(null);
    }

    public function isComposite(): bool
    {
        return true;
    }

    /**
     * The Composite executes its primary logic in a particular way. It
     * traverses recursively through all its children, collecting and summing
     * their results. Since the composite's children pass these calls to their
     * children and so forth, the whole object tree is traversed as a result.
     *
     * Kompozyt wykonuje swoją podstawową logikę w określony sposób. Rekursywnie przechodzi przez wszystkie swoje dzieci,
     * zbierając i sumując ich wyniki. Ponieważ dzieci kompozytu przekazują te wywołania swoim dzieciom i tak dalej,
     * w rezultacie przechodzi przez całe drzewo obiektów.
     */
    public function operation(): string
    {
        $results = [];
        foreach ($this->children as $child) {
            $results[] = $child->operation();
        }

        return "Branch(" . implode("+", $results) . ")";
    }
}

/**
 * The client code works with all of the components via the base interface.
 *
 * Kod klienta pracuje ze wszystkimi komponentami za pośrednictwem podstawowego interfejsu.
 */
function clientCode(Component $component)
{
    // ...

    echo "RESULT: " . $component->operation();

    // ...
}

/**
 * This way the client code can support the simple leaf components...
 *
 * W ten sposób kod klienta może obsługiwać proste komponenty liści...
 */
$simple = new Leaf();
echo "Client: I've got a simple component:\n";
clientCode($simple);
echo "\n\n";

/**
 * ...as well as the complex composites.
 *
 * ...jak również złożone kompozyty.
 */
$tree = new Composite();
$branch1 = new Composite();
$branch1->add(new Leaf());
$branch1->add(new Leaf());
$branch2 = new Composite();
$branch2->add(new Leaf());
$tree->add($branch1);
$tree->add($branch2);
echo "Client: Now I've got a composite tree:\n";
clientCode($tree);
echo "\n\n";

/**
 * Thanks to the fact that the child-management operations are declared in the
 * base Component class, the client code can work with any component, simple or
 * complex, without depending on their concrete classes.
 *
 * Dzięki temu, że operacje zarządzania dziećmi są zadeklarowane w podstawowej klasie Komponentu, kod klienta może pracować
 * z dowolnym komponentem, prostym lub złożonym, bez zależności od ich konkretnych klas.
 */
function clientCode2(Component $component1, Component $component2)
{
    // ...

    if ($component1->isComposite()) {
        $component1->add($component2);
    }
    echo "RESULT: " . $component1->operation();

    // ...
}

echo "Client: I don't need to check the components classes even when managing the tree:\n";
clientCode2($tree, $simple);
