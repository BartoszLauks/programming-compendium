<?php

namespace RefactoringGuru\Decorator\RealWorld;

/**
 * The Component interface declares a filtering method that must be implemented
 * by all concrete components and decorators.
 *
 * Interfejs komponentu deklaruje metodę filtrowania, którą muszą zaimplementować wszystkie konkretne komponenty i dekoratory.
 */
interface InputFormat
{
    public function formatText(string $text): string;
}

/**
 * The Concrete Component is a core element of decoration. It contains the
 * original text, as is, without any filtering or formatting.
 *
 * Konkretny komponent jest głównym elementem dekoracji. Zawiera oryginalny tekst, taki jaki jest, bez żadnego filtrowania lub formatowania.
 */
class TextInput implements InputFormat
{
    public function formatText(string $text): string
    {
        return $text;
    }
}

/**
 * The base Decorator class doesn't contain any real filtering or formatting
 * logic. Its main purpose is to implement the basic decoration infrastructure:
 * a field for storing a wrapped component or another decorator and the basic
 * formatting method that delegates the work to the wrapped object. The real
 * formatting job is done by subclasses.
 *
 * Podstawowa klasa dekoratora nie zawiera żadnej rzeczywistej logiki filtrowania ani formatowania. Jej głównym celem jest wdrożenie podstawowej infrastruktury dekoracji: pola do przechowywania owiniętego komponentu lub innego dekoratora oraz podstawowej metody formatowania, która deleguje pracę do owiniętego obiektu. Rzeczywiste formatowanie odbywa się w podklasach.
 */
class TextFormat implements InputFormat
{
    /**
     * @var InputFormat
     */
    protected $inputFormat;

    public function __construct(InputFormat $inputFormat)
    {
        $this->inputFormat = $inputFormat;
    }

    /**
     * Decorator delegates all work to a wrapped component.
     *
     * Dekorator deleguje całą pracę do owiniętego komponentu.
     */
    public function formatText(string $text): string
    {
        return $this->inputFormat->formatText($text);
    }
}

/**
 * This Concrete Decorator strips out all HTML tags from the given text.
 *
 * Ten konkretny dekorator usuwa wszystkie tagi HTML z podanego tekstu.
 */
class PlainTextFilter extends TextFormat
{
    public function formatText(string $text): string
    {
        $text = parent::formatText($text);
        return strip_tags($text);
    }
}

/**
 * This Concrete Decorator strips only dangerous HTML tags and attributes that
 * may lead to an XSS vulnerability.
 *
 * Ten konkretny dekorator usuwa jedynie niebezpieczne tagi HTML i atrybuty, które mogą prowadzić do podatności XSS.
 */
class DangerousHTMLTagsFilter extends TextFormat
{
    private $dangerousTagPatterns = [
        "|<script.*?>([\s\S]*)?</script>|i", // ...
    ];

    private $dangerousAttributes = [
        "onclick", "onkeypress", // ...
    ];

    public function formatText(string $text): string
    {
        $text = parent::formatText($text);

        foreach ($this->dangerousTagPatterns as $pattern) {
            $text = preg_replace($pattern, '', $text);
        }

        foreach ($this->dangerousAttributes as $attribute) {
            $text = preg_replace_callback('|<(.*?)>|', function ($matches) use ($attribute) {
                $result = preg_replace("|$attribute=|i", '', $matches[1]);
                return "<" . $result . ">";
            }, $text);
        }

        return $text;
    }
}

/**
 * This Concrete Decorator provides a rudimentary Markdown → HTML conversion.
 *
 * Ten konkretny dekorator zapewnia podstawową konwersję Markdown → HTML.
 */
class MarkdownFormat extends TextFormat
{
    public function formatText(string $text): string
    {
        $text = parent::formatText($text);

        // Format block elements.
        // Formatuj elementy blokowe.
        $chunks = preg_split('|\n\n|', $text);
        foreach ($chunks as &$chunk) {
            // Format headers.
            // Formatuj nagłówki.
            if (preg_match('|^#+|', $chunk)) {
                $chunk = preg_replace_callback('|^(#+)(.*?)$|', function ($matches) {
                    $h = strlen($matches[1]);
                    return "<h$h>" . trim($matches[2]) . "</h$h>";
                }, $chunk);
            } // Format paragraphs.
            // Formatuj paragrafy.
            else {
                $chunk = "<p>$chunk</p>";
            }
        }
        $text = implode("\n\n", $chunks);

        // Format inline elements.
        // Formatuj elementy inline.
        $text = preg_replace("|__(.*?)__|", '<strong>$1</strong>', $text);
        $text = preg_replace("|\*\*(.*?)\*\*|", '<strong>$1</strong>', $text);
        $text = preg_replace("|_(.*?)_|", '<em>$1</em>', $text);
        $text = preg_replace("|\*(.*?)\*|", '<em>$1</em>', $text);

        return $text;
    }
}


/**
 * The client code might be a part of a real website, which renders user-
 * generated content. Since it works with formatters through the Component
 * interface, it doesn't care whether it gets a simple component object or a
 * decorated one.
 *
 * Kod klienta może być częścią rzeczywistej strony internetowej, która renderuje treści generowane przez użytkowników. Ponieważ pracuje z formatowaniem za pomocą interfejsu komponentu, nie obchodzi go, czy otrzymuje prosty obiekt komponentu, czy udekorowany.
 */
function displayCommentAsAWebsite(InputFormat $format, string $text)
{
    // ..

    echo $format->formatText($text);

    // ..
}

/**
 * Input formatters are very handy when dealing with user-generated content.
 * Displaying such content "as is" could be very dangerous, especially when
 * anonymous users can generate it (e.g. comments). Your website is not only
 * risking getting tons of spammy links but may also be exposed to XSS attacks.
 *
 * Formatery wejściowe są bardzo przydatne w przypadku pracy z treściami generowanymi przez użytkowników.
 * Wyświetlanie takich treści "tak jak są" może być bardzo niebezpieczne, zwłaszcza gdy anonimowi użytkownicy mogą je generować (np. komentarze). Twoja strona naraża się nie tylko na otrzymywanie mnóstwa spamu, ale może być również podatna na ataki XSS.
 */
$dangerousComment = <<<HERE
Hello! Nice blog post!
Please visit my <a href='http://www.iwillhackyou.com'>homepage</a>.
<script src="http://www.iwillhackyou.com/script.js">
  performXSSAttack();
</script>
HERE;

/**
 * Naive comment rendering (unsafe).
 * Naiwne renderowanie komentarzy (niebezpieczne).
 */
$naiveInput = new TextInput();
echo "Website renders comments without filtering (unsafe):\n";
displayCommentAsAWebsite($naiveInput, $dangerousComment);
echo "\n\n\n";

/**
 * Filtered comment rendering (safe).
 * Renderowanie komentarzy po przefiltrowaniu (bezpieczne).
 */
$filteredInput = new PlainTextFilter($naiveInput);
echo "Website renders comments after stripping all tags (safe):\n";
displayCommentAsAWebsite($filteredInput, $dangerousComment);
echo "\n\n\n";


/**
 * Decorator allows stacking multiple input formats to get fine-grained control
 * over the rendered content.
 *
 * Dekorator umożliwia nakładanie wielu formatów wejściowych, aby uzyskać precyzyjną kontrolę nad renderowaną treścią.
 */
$dangerousForumPost = <<<HERE
# Welcome

This is my first post on this **gorgeous** forum.

<script src="http://www.iwillhackyou.com/script.js">
  performXSSAttack();
</script>
HERE;

/**
 * Naive post rendering (unsafe, no formatting).
 * Naiwne renderowanie postów (niebezpieczne, brak formatowania).
 */
$naiveInput = new TextInput();
echo "Website renders a forum post without filtering and formatting (unsafe, ugly):\n";
displayCommentAsAWebsite($naiveInput, $dangerousForumPost);
echo "\n\n\n";

/**
 * Markdown formatter + filtering dangerous tags (safe, pretty).
 * Formatter Markdown + filtrowanie niebezpiecznych tagów (bezpieczne, estetyczne).
 */
$text = new TextInput();
$markdown = new MarkdownFormat($text);
$filteredInput = new DangerousHTMLTagsFilter($markdown);
echo "Website renders a forum post after translating markdown markup" .
    " and filtering some dangerous HTML tags and attributes (safe, pretty):\n";
displayCommentAsAWebsite($filteredInput, $dangerousForumPost);
echo "\n\n\n";
