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
$optiGov = new \OptiGov\Client("https://optigov.de/api")
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
$optiGov->loginBuerger($redirect_url);
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