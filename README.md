# optigov-api-php
Für weitere Informationen, siehe [optiGov-Dokumentation](https://doku.optigov.de).

## Installation

```bash
composer require optigov/optigov-api-php
```

## Schnellstart

### Instanziierung

Ein optiGov-Client kann über die `Client`-Klasse intantiiert werden. Dabei werden die Urls des API-Endpunkts, des OAuth2.0-Auth-Endpunkts und des OAuth2.0-Token-Endpunkts benötigt.

```php
$optiGov = new \OptiGov\Client(API_ENDPUNKT, OAUTH_AUTH_ENDPUNKT, OAUTH_TOKEN_ENDPUNKT);
```

### Abfragen

Die Abfragen innerhalb der Bibliothek teilen sich in sogenannte `Responsibilities` auf. 
Diese enthalten verschiedene Verantwortlichekeiten - so ist z.B. die `GlobalResponsibility` für alle allgemeinen, von einer Verwaltung losgelösten Anfragen zuständig. 
Die `VerwaltungResponsibility` handelt alle Abfragen bzgl. aller Einträge einer Verwaltung ab - und die `BuergerResponsibility` übernimmt alle Funktionen, welche eine Bürgerauthorisierung benötigen.  

#### Die GlobalResponsibility

Eine Dienstleistung, oder nur ihren Namen abfragen:
```php
$optiGov->dienstleistung($id);
$optiGov->dienstleistungName($id);
```

Eine Einrichtung, oder nur ihren Namen abfragen:
```php
$optiGov->einrichtung($id);
$optiGov->einrichtungName($id);
```

Einen Mitarbeiter, oder nur seinen / ihren Namen abfragen:
```php
$optiGov->mitarbeiter($id);
$optiGov->mitarbeiterName($id);
```

Um einen Bürger einzuloggen verwendet diese Bibliothek den von optiGov bereitgestellten OAuth2.0 Authorization-Flow mit PKCE. 
Dieser wird in zwei Schritten durchgeführt. Als Erstes wird eine URL zur Authorisierung für die Weiterleitung erzeugt:  
```php
// get data needed for OAuth2.0 authorization
$oauthInformation = $optiGov->oauthAuthorize(
    OPTIGOV_OAUTH_CLIENT_ID,
    OPTIGOV_OAUTH_CLIEBNT_REDIRECT_URL,
);

// get state and code verifier and authorization url
$state = $oauthInformation["state"];
$codeVerifier = $oauthInformation["code_verifier"];
$url = $oauthInformation["url"];
```

Nachdem nun der Bürger eingeloggt ist und an die `OPTIGOV_OAUTH_CLIEBNT_REDIRECT_URL` der Authorization-Code gesendet wurde, kann darüber das Access- und Refresh-Token wie folgt abgefragt werden:
```php
// get tokens form oauth token endpoint
$tokens = $optiGov->oauthGetTokens(
    $codeVerifier, // code verifier from step one
    $_GET["code"], // code from the get parameters
    OPTIGOV_OAUTH_CLIENT_ID,
    OPTIGOV_OAUTH_CLIEBNT_REDIRECT_URL,
);
```

#### Die VerwaltungResponsibility

Alle Dienstleistungen abfragen:
```php
$optiGov->verwaltung($id)->alleDienstleistungen();
```

Alle Themenfelder abfragen:
```php
$optiGov->verwaltung($id)->alleThemenfelder();
```

Alle Einrichtungen abfragen:
```php
$optiGov->verwaltung($id)->alleEinrichtungen();
```

Alle Mitarbeiter abfragen:
```php
$optiGov->verwaltung($id)->alleMitarbeiter();
```

#### Die BuergerResponsibility
Alle Anträge eines Bürgers abfragen:
```php
$optiGov->buerger(REFRESH_TOKEN)->alleAntraege();
```

Alle Termine eines Bürgers abfragen:
```php
$optiGov->buerger(REFRESH_TOKEN)->alleTermine();
```

Alle Chats eines Bürgers abfragen:
```php
$optiGov->buerger(REFRESH_TOKEN)->alleChats();
```

Einen Chat eines Bürgers abfragen:
```php
$optiGov->buerger(REFRESH_TOKEN)->chat($id);
```

Alle Daten eines Bürgers abfragen:
```php
$optiGov->buerger(REFRESH_TOKEN)->daten();
```

Einen Antrag mit vorbefüllten Daten stellen:
```php
$optiGov->buerger(REFRESH_TOKEN)->stelleAntrag(
    FORMULAR_ID,
    SUCCESS_REDIRECT_URL,
    [
        "AS.Daten.Parkzone" => "Zone F",
        "cancelUrl" => CANCEL_REDIRECT_URL,
    ]
);
```

Eine Datei hochladen:
```php
$optiGov->buerger(REFRESH_TOKEN)->dateiHochladen($pfad, $name, $bezeichner);
```

Alle Daten eines Bürgers löschen:
```php
$optiGov->buerger(REFRESH_TOKEN)->loescheBuerger();
```

Einen neuen Chat erstellen:
```php
$optiGov->buerger(REFRESH_TOKEN)->erstelleChat($name, $mitarbeiterId);
```

Eine neue Nachricht senden:
```php
$optiGov->buerger(REFRESH_TOKEN)->sendeNachricht($inhalt, $chatId, dateien: []);
```
## Tests und Beteiligung

### Unit-Tests

**Hinweis:** Die Unit-Tests benötigen die Datei `tests/bootstrap.php`, welche notwenige (folgende) Konstanten definiert:
```php
const BACKEND_URL = "https://..."; // API-Endpunkt
const REFRESH_TOKEN = "..."; // Refresh-Token eines Bürgers
const TEST_CHAT_ID = -1; // ID eines Chats, welcher dem Bürger gehört
const TEST_ANTRAG_FORMULAR_ID = -1; // ID eines Antrags, welcher im Namen des Bürgers gestellt wird
const TEST_ANTRAG_WEITERLEITUNG_URL = "https://..."; // Ziel-URL für erfolgreiche Weiterleitung nach Antragdurchführung
const TEST_ANTRAG_PARAMETER = []; // Parameter, welche dem Antrag übergeben werden
```

Ausführen aller Tests:
```
composer run-script test
```

### Beteiligung

Für Beteiligung an der Entwicklung der Bibliothek wenden Sie sich bitte an [hallo@optigov.de](mailto:hallo@optigov.de).