# optigov-api-php
Für weitere Informationen, siehe [optiGov API](https://entwickler.optigov.de).

## Installation

```bash
composer require optigov/optigov-api-php
```

## Schnellstart

### Instanziierung

Ein optiGov-Client kann über die `Client`-Klasse intantiiert werden. Dabei wird die Url des API-Endpunkts benötigt.

```php
$optiGov = new \OptiGov\Client("https://optigov.de/api");
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

Einen Login-Flow für einen Bürger erzeugen:
```php
$optiGov->loginBuerger(SUCCESS_REDIRECT_URL);
```

#### Die VerwaltungResponsibility

Alle Dienstleistungen abfragen:
```php
$optiGov->verwaltung($id)->alleDienstleistungen();
```

Alle Dienstleistungskategorien abfragen:
```php
$optiGov->verwaltung($id)->alleDienstleistungskategorien();
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

**Hinweis:** benötigt die Datei `tests/bootstrap.php`, welche die Konstante `BACKEND_URL` definiert. Z.B.:
```php
const BACKEND_URL = "https://optigov.de/api";
```

Ausführen aller Tests:
```
composer run-script test
```

### Beteiligung

Für Beteiligung an der Entwicklung der Bibliothek, wenden Sie sich bitte an [hallo@optigov.de](mailto:hallo@optigov.de).