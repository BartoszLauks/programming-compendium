<?php

namespace RefactoringGuru\AbstractFactory\RealWorld;

/**
 * The Abstract Factory interface declares creation methods for each distinct
 * product type.
 *
 * Interfejs Abstract Factory deklaruje metody tworzenia dla każdego
 * odrębnego typu produktu.
 */
interface TemplateFactory
{
    public function createTitleTemplate(): TitleTemplate;

    public function createPageTemplate(): PageTemplate;

    public function getRenderer(): TemplateRenderer;
}

/**
 * Each Concrete Factory corresponds to a specific variant (or family) of
 * products.
 *
 * Każda Konkretna Fabryka odpowiada konkretnemu wariantowi (lub rodzinie) produktów.
 *
 * This Concrete Factory creates Twig templates.
 *
 * Ta Konkretna Fabryka tworzy szablony Twig.
 */
class TwigTemplateFactory implements TemplateFactory
{
    public function createTitleTemplate(): TitleTemplate
    {
        return new TwigTitleTemplate();
    }

    public function createPageTemplate(): PageTemplate
    {
        return new TwigPageTemplate($this->createTitleTemplate());
    }

    public function getRenderer(): TemplateRenderer
    {
        return new TwigRenderer();
    }
}

/**
 * And this Concrete Factory creates PHPTemplate templates.
 *
 * A ta Konkretna Fabryka tworzy szablony PHPTemplate.
 */
class PHPTemplateFactory implements TemplateFactory
{
    public function createTitleTemplate(): TitleTemplate
    {
        return new PHPTemplateTitleTemplate();
    }

    public function createPageTemplate(): PageTemplate
    {
        return new PHPTemplatePageTemplate($this->createTitleTemplate());
    }

    public function getRenderer(): TemplateRenderer
    {
        return new PHPTemplateRenderer();
    }
}

/**
 * Each distinct product type should have a separate interface. All variants of
 * the product must follow the same interface.
 *
 * Każdy odrębny typ produktu powinien mieć osobny interfejs. Wszystkie warianty produktu
 * muszą stosować ten sam interfejs.
 *
 * For instance, this Abstract Product interface describes the behavior of page
 * title templates.
 *
 * Na przykład ten interfejs Abstrakcyjnego Produktu opisuje zachowanie szablonów
 * tytułów stron.
 */
interface TitleTemplate
{
    public function getTemplateString(): string;
}

/**
 * This Concrete Product provides Twig page title templates.
 *
 * Ten Konkretny Produkt dostarcza szablony tytułów stron Twig.
 */
class TwigTitleTemplate implements TitleTemplate
{
    public function getTemplateString(): string
    {
        return "<h1>{{ title }}</h1>";
    }
}

/**
 * And this Concrete Product provides PHPTemplate page title templates.
 *
 * A ten Konkretny Produkt dostarcza szablony tytułów stron PHPTemplate.
 */
class PHPTemplateTitleTemplate implements TitleTemplate
{
    public function getTemplateString(): string
    {
        return "<h1><?= \$title; ?></h1>";
    }
}

/**
 * This is another Abstract Product type, which describes whole page templates.
 *
 * To jest inny typ Abstrakcyjnego Produktu, który opisuje szablony całych stron.
 */
interface PageTemplate
{
    public function getTemplateString(): string;
}

/**
 * The page template uses the title sub-template, so we have to provide the way
 * to set it in the sub-template object. The abstract factory will link the page
 * template with a title template of the same variant.
 *
 * Szablon strony używa podszablonu tytułu, więc musimy zapewnić sposób
 * ustawienia go w obiekcie podszablonu. Fabryka abstrakcyjna połączy szablon
 * strony z szablonem tytułu tego samego wariantu.
 */
abstract class BasePageTemplate implements PageTemplate
{
    protected $titleTemplate;

    public function __construct(TitleTemplate $titleTemplate)
    {
        $this->titleTemplate = $titleTemplate;
    }
}

/**
 * The Twig variant of the whole page templates.
 *
 * Wariant Twig dla całych szablonów stron.
 */
class TwigPageTemplate extends BasePageTemplate
{
    public function getTemplateString(): string
    {
        $renderedTitle = $this->titleTemplate->getTemplateString();

        return <<<HTML
        <div class="page">
            $renderedTitle
            <article class="content">{{ content }}</article>
        </div>
        HTML;
    }
}

/**
 * The PHPTemplate variant of the whole page templates.
 *
 * Wariant PHPTemplate dla całych szablonów stron.
 */
class PHPTemplatePageTemplate extends BasePageTemplate
{
    public function getTemplateString(): string
    {
        $renderedTitle = $this->titleTemplate->getTemplateString();

        return <<<HTML
        <div class="page">
            $renderedTitle
            <article class="content"><?= \$content; ?></article>
        </div>
        HTML;
    }
}

/**
 * The renderer is responsible for converting a template string into the actual
 * HTML code. Each renderer behaves differently and expects its own type of
 * template strings passed to it. Baking templates with the factory let you pass
 * proper types of templates to proper renders.
 *
 * Renderer jest odpowiedzialny za konwersję ciągu szablonu na rzeczywisty
 * kod HTML. Każdy renderer zachowuje się inaczej i oczekuje własnego typu
 * ciągów szablonów przekazanych do niego. Tworzenie szablonów za pomocą fabryki
 * pozwala przekazywać odpowiednie typy szablonów do odpowiednich rendererów.
 */
interface TemplateRenderer
{
    public function render(string $templateString, array $arguments = []): string;
}

/**
 * The renderer for Twig templates.
 *
 * Renderer dla szablonów Twig.
 */
class TwigRenderer implements TemplateRenderer
{
    public function render(string $templateString, array $arguments = []): string
    {
        return \Twig::render($templateString, $arguments);
    }
}

/**
 * The renderer for PHPTemplate templates. Note that this implementation is very
 * basic, if not crude. Using the `eval` function has many security
 * implications, so use it with caution in real projects.
 *
 * Renderer dla szablonów PHPTemplate. Zauważ, że ta implementacja jest bardzo
 * podstawowa, jeśli nie prymitywna. Użycie funkcji `eval` ma wiele implikacji
 * bezpieczeństwa, więc używaj jej ostrożnie w rzeczywistych projektach.
 */
class PHPTemplateRenderer implements TemplateRenderer
{
    public function render(string $templateString, array $arguments = []): string
    {
        extract($arguments);

        ob_start();
        eval(' ?>' . $templateString . '<?php ');
        $result = ob_get_contents();
        ob_end_clean();

        return $result;
    }
}

/**
 * The client code. Note that it accepts the Abstract Factory class as the
 * parameter, which allows the client to work with any concrete factory type.
 *
 * Kod klienta. Zauważ, że akceptuje klasę Fabryki Abstrakcyjnej jako parametr,
 * co pozwala klientowi pracować z dowolnym typem konkretnej fabryki.
 */
class Page
{
    public $title;
    public $content;

    public function __construct($title, $content)
    {
        $this->title = $title;
        $this->content = $content;
    }

    // Here's how would you use the template further in real life. Note that the
    // page class does not depend on any concrete template classes.
    //
    // Oto, jak można używać szablonu dalej w rzeczywistości. Zauważ, że
    // klasa strony nie zależy od żadnych konkretnych klas szablonów.
    public function render(TemplateFactory $factory): string
    {
        $pageTemplate = $factory->createPageTemplate();

        $renderer = $factory->getRenderer();

        return $renderer->render($pageTemplate->getTemplateString(), [
            'title' => $this->title,
            'content' => $this->content
        ]);
    }
}

/**
 * Now, in other parts of the app, the client code can accept factory objects of
 * any type.
 *
 * Teraz, w innych częściach aplikacji, kod klienta może akceptować obiekty fabryki
 * dowolnego typu.
 */
$page = new Page('Sample page', 'This is the body.');

echo "Testing actual rendering with the PHPTemplate factory:\n";
echo $page->render(new PHPTemplateFactory());


// Uncomment the following if you have Twig installed.
//
// Odkomentuj poniższe, jeśli masz zainstalowany Twig.
//
// echo "Testing rendering with the Twig factory:\n"; echo $page->render(new
// TwigTemplateFactory());
