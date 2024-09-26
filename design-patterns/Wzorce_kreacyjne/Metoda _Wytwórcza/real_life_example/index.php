<?php

namespace RefactoringGuru\FactoryMethod\RealWorld;

/**
 * The Creator declares a factory method that can be used as a substitution for
 * the direct constructor calls of products, for instance:
 *
 * - Before: $p = new FacebookConnector();
 * - After: $p = $this->getSocialNetwork;
 *
 * This allows changing the type of the product being created by
 * SocialNetworkPoster's subclasses.
 *
 * Klasa Creator deklaruje metodę fabryczną, która może być używana jako
 * zamiennik bezpośrednich wywołań konstruktora produktów, na przykład:
 *
 * - Przed: $p = new FacebookConnector();
 * - Po: $p = $this->getSocialNetwork;
 *
 * To pozwala zmieniać typ tworzonego produktu przez podklasy SocialNetworkPoster.
 */
abstract class SocialNetworkPoster
{
    /**
     * The actual factory method. Note that it returns the abstract connector.
     * This lets subclasses return any concrete connectors without breaking the
     * superclass' contract.
     *
     * Właściwa metoda fabryczna. Zauważ, że zwraca ona abstrakcyjny konektor.
     * To pozwala podklasom zwracać dowolne konkretne konektory bez łamania
     * kontraktu nadklasy.
     */
    abstract public function getSocialNetwork(): SocialNetworkConnector;

    /**
     * When the factory method is used inside the Creator's business logic, the
     * subclasses may alter the logic indirectly by returning different types of
     * the connector from the factory method.
     *
     * Gdy metoda fabryczna jest używana wewnątrz logiki biznesowej Creatora,
     * podklasy mogą pośrednio zmieniać tę logikę, zwracając różne typy konektorów
     * z metody fabrycznej.
     */
    public function post($content): void
    {
        // Call the factory method to create a Product object...
        // Wywołaj metodę fabryczną, aby utworzyć obiekt Produkt...
        $network = $this->getSocialNetwork();
        // ...then use it as you will.
        // ...następnie użyj go zgodnie z potrzebami.
        $network->logIn();
        $network->createPost($content);
        $network->logOut();
    }
}

/**
 * This Concrete Creator supports Facebook. Remember that this class also
 * inherits the 'post' method from the parent class. Concrete Creators are the
 * classes that the Client actually uses.
 *
 * Ten konkretny Kreator obsługuje Facebooka. Pamiętaj, że ta klasa dziedziczy
 * również metodę 'post' z klasy nadrzędnej. Konkretne Kreatory to klasy,
 * których faktycznie używa Klient.
 */
class FacebookPoster extends SocialNetworkPoster
{
    private $login, $password;

    public function __construct(string $login, string $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    public function getSocialNetwork(): SocialNetworkConnector
    {
        return new FacebookConnector($this->login, $this->password);
    }
}

/**
 * This Concrete Creator supports LinkedIn.
 *
 * Ten konkretny Kreator obsługuje LinkedIn.
 */
class LinkedInPoster extends SocialNetworkPoster
{
    private $email, $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function getSocialNetwork(): SocialNetworkConnector
    {
        return new LinkedInConnector($this->email, $this->password);
    }
}

/**
 * The Product interface declares behaviors of various types of products.
 *
 * Interfejs Produkt deklaruje zachowania różnych typów produktów.
 */
interface SocialNetworkConnector
{
    public function logIn(): void;

    public function logOut(): void;

    public function createPost($content): void;
}

/**
 * This Concrete Product implements the Facebook API.
 *
 * Ten konkretny Produkt implementuje API Facebooka.
 */
class FacebookConnector implements SocialNetworkConnector
{
    private $login, $password;

    public function __construct(string $login, string $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    public function logIn(): void
    {
        echo "Send HTTP API request to log in user $this->login with " .
            "password $this->password\n";
        // Wyślij żądanie HTTP API, aby zalogować użytkownika $this->login
        // z hasłem $this->password
    }

    public function logOut(): void
    {
        echo "Send HTTP API request to log out user $this->login\n";
        // Wyślij żądanie HTTP API, aby wylogować użytkownika $this->login
    }

    public function createPost($content): void
    {
        echo "Send HTTP API requests to create a post in Facebook timeline.\n";
        // Wyślij żądania HTTP API, aby utworzyć post na osi czasu Facebooka.
    }
}

/**
 * This Concrete Product implements the LinkedIn API.
 *
 * Ten konkretny Produkt implementuje API LinkedIn.
 */
class LinkedInConnector implements SocialNetworkConnector
{
    private $email, $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function logIn(): void
    {
        echo "Send HTTP API request to log in user $this->email with " .
            "password $this->password\n";
        // Wyślij żądanie HTTP API, aby zalogować użytkownika $this->email
        // z hasłem $this->password
    }

    public function logOut(): void
    {
        echo "Send HTTP API request to log out user $this->email\n";
        // Wyślij żądanie HTTP API, aby wylogować użytkownika $this->email
    }

    public function createPost($content): void
    {
        echo "Send HTTP API requests to create a post in LinkedIn timeline.\n";
        // Wyślij żądania HTTP API, aby utworzyć post na osi czasu LinkedIn.
    }
}

/**
 * The client code can work with any subclass of SocialNetworkPoster since it
 * doesn't depend on concrete classes.
 *
 * Kod klienta może działać z dowolną podklasą SocialNetworkPoster, ponieważ
 * nie zależy od konkretnych klas.
 */
function clientCode(SocialNetworkPoster $creator)
{
    // ...
    $creator->post("Hello world!");
    $creator->post("I had a large hamburger this morning!");
    // ...
}

/**
 * During the initialization phase, the app can decide which social network it
 * wants to work with, create an object of the proper subclass, and pass it to
 * the client code.
 *
 * Podczas fazy inicjalizacji aplikacja może zdecydować, z jaką siecią społecznościową
 * chce współpracować, utworzyć obiekt odpowiedniej podklasy i przekazać go do
 * kodu klienta.
 */
echo "Testing ConcreteCreator1:\n";
clientCode(new FacebookPoster("john_smith", "******"));
echo "\n\n";

echo "Testing ConcreteCreator2:\n";
clientCode(new LinkedInPoster("john_smith@example.com", "******"));
