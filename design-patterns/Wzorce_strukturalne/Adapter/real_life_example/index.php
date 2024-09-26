<?php

namespace RefactoringGuru\Adapter\RealWorld;

/**
 * The Target interface represents the interface that your application's classes
 * already follow.
 *
 * Interfejs Target reprezentuje interfejs, który jest już używany przez klasy w aplikacji.
 */
interface Notification
{
    public function send(string $title, string $message);
}

/**
 * Here's an example of the existing class that follows the Target interface.
 *
 * Poniżej znajduje się przykład istniejącej klasy, która implementuje interfejs Target.
 *
 * The truth is that many real apps may not have this interface clearly defined.
 * If you're in that boat, your best bet would be to extend the Adapter from one
 * of your application's existing classes. If that's awkward (for instance,
 * SlackNotification doesn't feel like a subclass of EmailNotification), then
 * extracting an interface should be your first step.
 *
 * W rzeczywistości wiele aplikacji może nie mieć tak jasno zdefiniowanego interfejsu.
 * Jeśli tak jest w twoim przypadku, najlepszym rozwiązaniem jest utworzenie Adaptera
 * na podstawie jednej z istniejących klas w aplikacji. Jeśli to wydaje się niewłaściwe
 * (na przykład, gdy SlackNotification nie pasuje jako podklasa EmailNotification),
 * pierwszym krokiem powinno być wyodrębnienie interfejsu.
 */
class EmailNotification implements Notification
{
    private $adminEmail;

    public function __construct(string $adminEmail)
    {
        $this->adminEmail = $adminEmail;
    }

    public function send(string $title, string $message): void
    {
        mail($this->adminEmail, $title, $message);
        echo "Sent email with title '$title' to '{$this->adminEmail}' that says '$message'.";
    }
}

/**
 * The Adaptee is some useful class, incompatible with the Target interface. You
 * can't just go in and change the code of the class to follow the Target
 * interface, since the code might be provided by a 3rd-party library.
 *
 * Adaptee to przydatna klasa, której interfejs jest niekompatybilny z interfejsem Target.
 * Nie można po prostu zmienić kodu tej klasy, aby dostosować go do interfejsu Target,
 * ponieważ kod może pochodzić z biblioteki zewnętrznej.
 */
class SlackApi
{
    private $login;
    private $apiKey;

    public function __construct(string $login, string $apiKey)
    {
        $this->login = $login;
        $this->apiKey = $apiKey;
    }

    public function logIn(): void
    {
        // Send authentication request to Slack web service.
        // Wysłanie żądania uwierzytelnienia do usługi sieciowej Slack.
        echo "Logged in to a slack account '{$this->login}'.\n";
    }

    public function sendMessage(string $chatId, string $message): void
    {
        // Send message post request to Slack web service.
        // Wysłanie żądania wysłania wiadomości do usługi sieciowej Slack.
        echo "Posted following message into the '$chatId' chat: '$message'.\n";
    }
}

/**
 * The Adapter is a class that links the Target interface and the Adaptee class.
 * In this case, it allows the application to send notifications using Slack
 * API.
 *
 * Adapter to klasa, która łączy interfejs Target z klasą Adaptee.
 * W tym przypadku umożliwia ona aplikacji wysyłanie powiadomień przy użyciu API Slacka.
 */
class SlackNotification implements Notification
{
    private $slack;
    private $chatId;

    public function __construct(SlackApi $slack, string $chatId)
    {
        $this->slack = $slack;
        $this->chatId = $chatId;
    }

    /**
     * An Adapter is not only capable of adapting interfaces, but it can also
     * convert incoming data to the format required by the Adaptee.
     *
     * Adapter potrafi nie tylko adaptować interfejsy, ale może również
     * konwertować dane wejściowe do formatu wymaganego przez Adaptee.
     */
    public function send(string $title, string $message): void
    {
        $slackMessage = "#" . $title . "# " . strip_tags($message);
        $this->slack->logIn();
        $this->slack->sendMessage($this->chatId, $slackMessage);
    }
}

/**
 * The client code can work with any class that follows the Target interface.
 *
 * Kod klienta może współpracować z dowolną klasą, która implementuje interfejs Target.
 */
function clientCode(Notification $notification)
{
    // ...

    echo $notification->send("Website is down!",
        "<strong style='color:red;font-size: 50px;'>Alert!</strong> " .
        "Our website is not responding. Call admins and bring it up!");

    // ...
}

echo "Client code is designed correctly and works with email notifications:\n";
// Kod klienta jest poprawnie zaprojektowany i działa z powiadomieniami e-mail:
$notification = new EmailNotification("developers@example.com");
clientCode($notification);
echo "\n\n";


echo "The same client code can work with other classes via adapter:\n";
// Ten sam kod klienta może działać z innymi klasami za pomocą adaptera:
$slackApi = new SlackApi("example.com", "XXXXXXXX");
$notification = new SlackNotification($slackApi, "Example.com Developers");
clientCode($notification);
