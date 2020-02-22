Mit diesem Plugin können Sie einen dynamischen DNS-Dienst aktualisieren
(DynDNS, NOIP, duckdns ...)

Plugin Konfiguration 
=======================

Après téléchargement du Plugin, il vous suffit juste d’activer celui-ci,
il n’y a aucune configuration à ce niveau.

![Dyndns] (../ images / dyndns.PNG)

Gerätekonfiguration 
=============================

Die Konfiguration der Dyndns-Geräte ist über das Menü zugänglich
Plugin :

![Dyndns2] (../ images / dyndns2.PNG)

So sieht die Dyndns-Plugin-Seite aus (hier mit bereits 1
Ausrüstung) :

![Dyndns3] (../ images / dyndns3.PNG)

> **Spitze**
>
> Comme à beaucoup d’endroits sur Jeedom, placer la souris tout à gauche
> permet de faire apparaître un menu d’accès rapide (vous pouvez, à
> Lassen Sie es in Ihrem Profil immer sichtbar.

Une fois que vous cliquez sur l’un d’eux, vous obtenez :

![Dyndns4] (../ images / dyndns4.PNG)

Hier finden Sie die gesamte Konfiguration Ihrer Geräte :

-   **Name de l’équipement Ambilight** : Name Ihrer Ausrüstung
    Ambilight

-   **Übergeordnetes Objekt** : indique l’objet parent auquel appartient
    l’équipement,

-   **Kategorie** : les catégories de l’équipement (il peut appartenir à
    mehrere Kategorien),

-   **activate** : macht Ihre Ausrüstung aktiv,

-   **sichtbar** : macht Ihre Ausrüstung auf dem Armaturenbrett sichtbar,

-   **Service** : Der Name des verwendeten Dienstes (dyndns.org, noip.com)

-   **Host-Namen** : vollständiger zu aktualisierender DNS-Name (z. B. toto.ddns.net)

-   **Name d’utilisateur** : nom d’utilisateur sur le service en question

-   **Passwort** : Passwort für den betreffenden Dienst

-   **Zeichen** : Zeichen für Enten

Nachfolgend finden Sie die Liste der Bestellungen :

-   **Name** : der auf dem Dashboard angezeigte Name,

-   **Anzeige** : permet d’afficher la donnée sur le dashboard,

-   **Test** : ermöglicht das Testen des Befehls

> **Notiz**
>
> Jeedom va vérifier toute les 15 minutes l’ip externe, si celle-ci a
> geändert, dann wird der DNS aktualisiert

> **wichtig**
>
> Das Plugin bietet einen Befehl zum Erzwingen der Aktualisierung. Achten Sie darauf
> Missbrauche es nicht, da du möglicherweise vorübergehend vom Dienst ausgeschlossen wirst
> DNS

> **Notiz**
>
> Si vous utilisez OVH, attention le nom d’utilisateur est de la forme
> mydomain.com-ID, während sich die Domain weit unter dem befindet
> Formular mon-dynHost.mmydomain.com
