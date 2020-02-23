Mit diesem Plugin können Sie einen dynamischen DNS-Dienst aktualisieren
(DynDNS, NOIP, duckdns ...)

Plugin Konfiguration 
=======================

Nachdem Sie das Plugin heruntergeladen haben, müssen Sie es nur noch aktivieren.
Auf dieser Ebene gibt es keine Konfiguration.

![dyndns](../images/dyndns.PNG)

Gerätekonfiguration 
=============================

Die Konfiguration der Dyndns-Geräte ist über das Menü zugänglich
Plugin :

![dyndns2](../images/dyndns2.PNG)

So sieht die Dyndns-Plugin-Seite aus (hier mit bereits 1
Ausrüstung) :

![dyndns3](../images/dyndns3.PNG)

> **Spitze**
>
> Platzieren Sie wie an vielen Stellen in Jeedom die Maus ganz links
> ruft ein Schnellzugriffsmenü auf (Sie können unter
> Lassen Sie es in Ihrem Profil immer sichtbar.

Sobald Sie auf eine davon klicken, erhalten Sie :

![dyndns4](../images/dyndns4.PNG)

Hier finden Sie die gesamte Konfiguration Ihrer Geräte :

-   **Name der Ambilight-Ausrüstung** : Name Ihrer Ausrüstung
    Ambilight

-   **Übergeordnetes Objekt** : gibt das übergeordnete Objekt an, zu dem es gehört
    Ausrüstung,

-   **Kategorie** : Gerätekategorien (es kann gehören
    mehrere Kategorien),

-   **activate** : macht Ihre Ausrüstung aktiv,

-   **sichtbar** : macht Ihre Ausrüstung auf dem Armaturenbrett sichtbar,

-   **Service** : Der Name des verwendeten Dienstes (dyndns.org, noip.com)

-   **Host-Namen** : vollständiger zu aktualisierender DNS-Name (z. B. toto.ddns.net)

-   **Benutzername** : Benutzername des betreffenden Dienstes

-   **Passwort** : Passwort für den betreffenden Dienst

-   **Zeichen** : Zeichen für Enten

Nachfolgend finden Sie die Liste der Bestellungen :

-   **Name** : der auf dem Dashboard angezeigte Name,

-   **Anzeige** : zeigt die Daten im Dashboard an,

-   **Test** : ermöglicht das Testen des Befehls

> **Notiz**
>
> Jeedom überprüft die externe IP alle 15 Minuten, falls vorhanden
> geändert, dann wird der DNS aktualisiert

> **wichtig**
>
> Das Plugin bietet einen Befehl zum Erzwingen der Aktualisierung. Achten Sie darauf
> Missbrauche es nicht, da du möglicherweise vorübergehend vom Dienst ausgeschlossen wirst
> DNS

> **Notiz**
>
> Wenn Sie OVH verwenden, achten Sie darauf, dass der Benutzername im Formular enthalten ist
> mydomain.com-ID, während sich die Domain weit unter dem befindet
> Formular mon-dynHost.mmydomain.com
