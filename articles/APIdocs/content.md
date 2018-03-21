# General
## API
Backend CloudsdaleFM.net pozwala na odrobine interakcji użytkownika z botem streamującym.
Całe api znajduje się pod `https://cloudsdalefm.net/api/`.
Wszystkie odpowiedzi są w formacie JSON i zawsze mają conajmniej jedną własciwość, `code` która jest liczbą i jest to kopia statusu odpowiedzi, jeżeli request sie powiódł będzie to 200.
Jeżeli status code jest inny niż 200 to resopnse będzie posiadać właściwosć `error` która opisuje dokładniej błąd.
Nazwy piosenek zapisane w tekście mają format `Wykonawca - tytół`


## Error Codes
Lista wszystkich statusów jakimi odpowida serwer, są one standardowymi kodami HTML i znajdują się w statusie odpowiedzi oraz jako pierwsza wartość w każdej odpowiedzi

| Status | Opis |
| --- | --- |
| 200 (OK) | Wszystko przebiegło pomyślnie. |
| 400 (BAD REQUEST) | Twój request nie był poprawny. |
| 401 (UNAUTHORIZED) | Brakowało headera `Authorization` w requeście a był on wymagany. |
| 403 (FORBIDDEN) | Podany token nie miał wystarczających uprawnień. |
| 404 (NOT FOUND) | Nic nie znaleziono bądź nie istnieje. |
| 413 (FULL) | Niestandardowy kod zwracany gdy kolejka jest pełna. |
| 429 (TOO MANY REQUESTS) | Może oznaczać że było zbyt dużo requestów od ciebie lub coś jest ograniczone czasowo. |
| 5xx (SERVER ERROR) | Błąd serwera z którym dużo nie zrobisz. |


## FAQ
Ktokolwiek ma jakieś pytania?


## How To Read
Dokumentacja ma pare miejsc w kórych można sie zgubić/nie wiedzieć jak coś rozumieć, postaram sie je wytłumaczyć.

**W Routes** każda ścieżka ma swoją nazwe, metode zapytania, ścieżke i opis. Niektóre mogą mieć parametry url (Query parameters) które wysyłamy dodając `?param=value` do ścieżki albo parametry body (JSON parameters) które powiniy mieć formatowanie JSON i zostać wysłane z headerem `application/json`
Czyli:

### Nazwa
`Metoda` /to/jest/ścieżka

A to jest opis

**Query Params**
- nazwa `<Typ parametru>` Opis | Podstawowa wartość? (? oznacza że jest opcjonalny, w innym wypadku musi zostać dodany do requesty)

**JSON Params**
- nazwa `<Typ>` Opis | Podstawowa wartość?

**W Response structures** wszystko oznacza objekt json gdzie:
- nazwa warości `<Typ>` Opis

## Routes
Lista wszystkich dostępnych ścieżek API

### Next Playing
`GET` /data/next

Piosenka jaka będzie lecieć następna może to być piosenka zamówiona lub losowa.


### Now Playing
`GET` /data/playing

Zwraca piosenke która aktualnie leci w radiu.

### Queue
`GET` /data/queue

Zwraca długość kolejki zamówionych piosenek i ich liste. Jeżeli nic nie zostało zamówione pierwszym elementem będzie losowa piosenka która poleci następna.

### Request Song
`POST` /requestsong

Dodaje podaną piosenke do kolejki w radiu. Wymaga dosłownej nazwy piosenki, dlatego warto użyć /data/searchsong przed użyciem tej ścierzki.

**JSON Params**
- title `<String>` Dokładna nazwa piosenki | undefined


### History
`GET` /data/history

Ostatnie 5 piosenek które leciały w radiu. Nie ma w niej piosenki która leci teraz.

### Search
`GET` /data/searchsong

Wyszukuje w bazie piosenek podany tytuł, Wyszukiwanie nie jest dosłowne i jest caseinsensitive. Stara się zwrócić zawsze 5 piosenek więc czasami może bardzo nie trafić.

**Query Params**
- title `<String>` Częściowa lub cała nazwa piosenki | undefined

### Songs List
`GET` /data/songs

Zwraca listę wszystkich dostępnych piosenek. Można podzielić ją na strony dodając parametry url.
**Query Params**

- page `<Number>` Numer strony | 1?
- size `<Number>` Ilość piosenek na 1 strone | 20?

### Status
`GET` /data/status

Status i informacje o serwerze.

### Likes W.I.P
`GET` /data/likes

Ilość polubień jakie posiada piosenka która teraz leci.

### Listeners W.I.P
`GET` /data/listeners

Ilość słuchaczy radia.

## Response structures
Struktóry różnych odpowiedzi, mają takie same nazwy co ścieżki w ,,Routes,,

#### Next Playing Object
```js
{
    track: <String> Cała nazwa piosenki
    title: <String> Sam tytół
    artist: <String> Sam wykonawca
}
```


#### Now Playing Object
```js
{
    track: <String> Cała nazwa piosenki
    title: <String> Sam tytół 
    artist: <String> Sam wykonawca
    listeners: <Number> Ilość słuchaczy (W.I.P)
    duration: <Number> Długość piosenki w sekundach (W.I.P)
    playingFor: <Number> Ile piosenki już poleciało nie licząc opuźnień (W.I.P)
}
```


#### Queue Object
```js
{
    legth: <Number> Długość kolejki
    queue: <Array<String>> Kolejno nazwy piosenek
}
```

#### Request Song Object
Tylko dla statusu 200
```js
{
    status: <String> Status zamówienia.
    title: <String> Nazwa zamówionej piosenki
}
```
Dla innego statusu
```js
{
    error: <String> Opis błędu
}
```

#### History Object
```js
{
    data: <Array<String>> Lista piosenek od dopiero puszczonej [0] do najpuźniej puszczonej [4]
}
```

#### Search Result Object
```js
{
    exists: <Boolean> Czy jakikolwiek wynik został znaleziony
    titles: <Array<String>> Lista nazw piosenek
}
```

#### Songs List Ojbect
```js
{
    data: <Array<String>> Alfabetycznie sortowane nazwy piosenek
}
```

#### Status Object
```js
{
    data: <Object> {
        unit: <String> W jakich jednostkach jest podany czas
        serverTime: <String> Czas lokalny serwera w formatcie YYYY-MM-DD HH-MM-SS
        clientUptime: <Number> Uptime bota streamującego
        streamUptime: <Number> Uptime streamu
        lastDisconnect: <Number<Timestamp>> Kiedy ostatnio stream rozłączył się z icecastem (Timestamp) 0 jeżeli nigdy
        playingSince: <Number<Timestamp>> Czas kiedy bot zaczął nadawać
        songsPlayed: <Number> Łączna ilość puszczonych piosenek
        songsTotal: <Number> Ile jest łącznie piosenek dostępnych na serwerze
        bumperAfter: <Number> Za ile piosenek poleci kolejny bumper
        status: <Object> Status usług na serwerze
    }
}
```


#### Likes Ojbect `W.I.P`
```js
{
    data: <Number> Ilość polubień tej piosenki
}
```

#### Listeners Object `W.I.P`
```js
{
    listeners: <Number> Ilość słuchaczy (W.I.P)
}
```

<script>(function(){document.getElementsByClassName("article")[0].style.backgroundColor="#111";document.getElementsByClassName("article")[0].style.color="#ccc";})()</script>