<?php

namespace RefactoringGuru\Composite\RealWorld;

/**
 * The base Component class declares an interface for all concrete components,
 * both simple and complex.
 *
 * Podstawowa klasa Komponentu deklaruje interfejs dla wszystkich konkretnych komponentów, zarówno prostych, jak i złożonych.
 *
 * In our example, we'll be focusing on the rendering behavior of DOM elements.
 * W naszym przykładzie będziemy koncentrować się na zachowaniu renderowania elementów DOM.
 */
abstract class FormElement
{
    /**
     * We can anticipate that all DOM elements require these 3 fields.
     * Możemy założyć, że wszystkie elementy DOM wymagają tych 3 pól.
     */
    protected $name;
    protected $title;
    protected $data;

    public function __construct(string $name, string $title)
    {
        $this->name = $name;
        $this->title = $title;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setData($data): void
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Each concrete DOM element must provide its rendering implementation, but
     * we can safely assume that all of them are returning strings.
     *
     * Każdy konkretny element DOM musi dostarczyć własną implementację renderowania, ale możemy bezpiecznie założyć, że wszystkie z nich zwracają ciągi znaków.
     */
    abstract public function render(): string;
}

/**
 * This is a Leaf component. Like all the Leaves, it can't have any children.
 *
 * Jest to komponent Liścia. Jak wszystkie Liście, nie może mieć żadnych dzieci.
 */
class Input extends FormElement
{
    private $type;

    public function __construct(string $name, string $title, string $type)
    {
        parent::__construct($name, $title);
        $this->type = $type;
    }

    /**
     * Since Leaf components don't have any children that may handle the bulk of
     * the work for them, usually it is the Leaves who do the most of the heavy-
     * lifting within the Composite pattern.
     *
     * Ponieważ komponenty Liści nie mają żadnych dzieci, które mogłyby wykonać za nie większość pracy, zazwyczaj to właśnie Liście wykonują największą część pracy w ramach wzorca Kompozyt.
     */
    public function render(): string
    {
        return "<label for=\"{$this->name}\">{$this->title}</label>\n" .
            "<input name=\"{$this->name}\" type=\"{$this->type}\" value=\"{$this->data}\">\n";
    }
}

/**
 * The base Composite class implements the infrastructure for managing child
 * objects, reused by all Concrete Composites.
 *
 * Podstawowa klasa Kompozytu implementuje infrastrukturę do zarządzania obiektami potomnymi, wykorzystywaną przez wszystkie Konkretny Kompozyty.
 */
abstract class FieldComposite extends FormElement
{
    /**
     * @var FormElement[]
     */
    protected $fields = [];

    /**
     * The methods for adding/removing sub-objects.
     *
     * Metody do dodawania/usuwania pod-obiektów.
     */
    public function add(FormElement $field): void
    {
        $name = $field->getName();
        $this->fields[$name] = $field;
    }

    public function remove(FormElement $component): void
    {
        $this->fields = array_filter($this->fields, function ($child) use ($component) {
            return $child != $component;
        });
    }

    /**
     * Whereas a Leaf's method just does the job, the Composite's method almost
     * always has to take its sub-objects into account.
     *
     * In this case, the composite can accept structured data.
     *
     * Podczas gdy metoda Liścia po prostu wykonuje zadanie, metoda Kompozytu niemal zawsze musi brać pod uwagę swoje pod-obiekty.
     * W tym przypadku, kompozyt może akceptować zorganizowane dane.
     *
     * @param array $data
     */
    public function setData($data): void
    {
        foreach ($this->fields as $name => $field) {
            if (isset($data[$name])) {
                $field->setData($data[$name]);
            }
        }
    }

    /**
     * The same logic applies to the getter. It returns the structured data of
     * the composite itself (if any) and all the children data.
     *
     * Ta sama logika dotyczy gettera. Zwraca on dane zorganizowane kompozytu (jeśli istnieją) oraz wszystkie dane dzieci.
     */
    public function getData(): array
    {
        $data = [];

        foreach ($this->fields as $name => $field) {
            $data[$name] = $field->getData();
        }

        return $data;
    }

    /**
     * The base implementation of the Composite's rendering simply combines
     * results of all children. Concrete Composites will be able to reuse this
     * implementation in their real rendering implementations.
     *
     * Podstawowa implementacja renderowania Kompozytu po prostu łączy wyniki wszystkich dzieci. Konkretny Kompozyt będzie mógł ponownie wykorzystać tę implementację w swoich rzeczywistych implementacjach renderowania.
     */
    public function render(): string
    {
        $output = "";

        foreach ($this->fields as $name => $field) {
            $output .= $field->render();
        }

        return $output;
    }
}

/**
 * The fieldset element is a Concrete Composite.
 *
 * Element fieldset jest Konkretnym Kompozytem.
 */
class Fieldset extends FieldComposite
{
    public function render(): string
    {
        // Note how the combined rendering result of children is incorporated
        // into the fieldset tag.
        // Zauważ, jak połączony wynik renderowania dzieci jest wbudowany w tag fieldset.
        $output = parent::render();

        return "<fieldset><legend>{$this->title}</legend>\n$output</fieldset>\n";
    }
}

/**
 * And so is the form element.
 *
 * Tak samo jak element form.
 */
class Form extends FieldComposite
{
    protected $url;

    public function __construct(string $name, string $title, string $url)
    {
        parent::__construct($name, $title);
        $this->url = $url;
    }

    public function render(): string
    {
        $output = parent::render();
        return "<form action=\"{$this->url}\">\n<h3>{$this->title}</h3>\n$output</form>\n";
    }
}

/**
 * The client code gets a convenient interface for building complex tree
 * structures.
 *
 * Kod klienta otrzymuje wygodny interfejs do budowania złożonych struktur drzewa.
 */
function getProductForm(): FormElement
{
    $form = new Form('product', "Add product", "/product/add");
    $form->add(new Input('name', "Name", 'text'));
    $form->add(new Input('description', "Description", 'text'));

    $picture = new Fieldset('photo', "Product photo");
    $picture->add(new Input('caption', "Caption", 'text'));
    $picture->add(new Input('image', "Image", 'file'));
    $form->add($picture);

    return $form;
}

/**
 * The form structure can be filled with data from various sources. The Client
 * doesn't have to traverse through all form fields to assign data to various
 * fields since the form itself can handle that.
 *
 * Struktura formularza może być wypełniona danymi z różnych źródeł. Klient nie musi przeszukiwać wszystkich pól formularza, aby przypisać dane do poszczególnych pól, ponieważ sam formularz może to obsłużyć.
 */
function loadProductData(FormElement $form)
{
    $data = [
        'name' => 'Apple MacBook',
        'description' => 'A decent laptop.',
        'photo' => [
            'caption' => 'Front photo.',
            'image' => 'photo1.png',
        ],
    ];

    $form->setData($data);
}

/**
 * The client code can work with form elements using the abstract interface.
 * This way, it doesn't matter whether the client works with a simple component
 * or a complex composite tree.
 *
 * Kod klienta może pracować z elementami formularza za pomocą abstrakcyjnego interfejsu.
 * W ten sposób nie ma znaczenia, czy klient pracuje z prostym komponentem, czy złożonym drzewem kompozytu.
 */
function renderProduct(FormElement $form)
{
    // ..

    echo $form->render();

    // ..
}

$form = getProductForm();
loadProductData($form);
renderProduct($form);
