This plugin allows to update a dynamic DNS service
(Dyndns, NOIP, duckdns ...)

Plugin configuration 
=======================

Après téléchargement du plugin, il vous suffit juste d’activer celui-ci,
il n’y a aucune configuration à ce niveau.

![Dyndns] (../ images / dyndns.PNG)

Equipment configuration 
=============================

The configuration of Dyndns equipment is accessible from the menu
plugin :

![Dyndns2] (../ images / dyndns2.PNG)

This is what the Dyndns plugin page looks like (here with already 1
equipment) :

![Dyndns3] (../ images / dyndns3.PNG)

> **Tip**
>
> Comme à beaucoup d’endroits sur Jeedom, placer la souris tout à gauche
> permet de faire apparaître un menu d’accès rapide (vous pouvez, à
> from your profile, always leave it visible).

Une fois que vous cliquez sur l’un d’eux, vous obtenez :

![Dyndns4] (../ images / dyndns4.PNG)

Here you find all the configuration of your equipment :

-   **Last name de l’équipement Ambilight** : name of your equipment
    Ambilight

-   **Parent object** : indique l’objet parent auquel appartient
    l’équipement,

-   **Category** : les catégories de l’équipement (il peut appartenir à
    several categories),

-   **Activate** : makes your equipment active,

-   **Visible** : makes your equipment visible on the dashboard,

-   **Service** : The name of the service used (dyndns.org, noip.com)

-   **hostname** : full DNS name to update (eg toto.ddns.net)

-   **Last name d’utilisateur** : nom d’utilisateur sur le service en question

-   **Password** : password on the service in question

-   **token** : token used for duckdns

Below you find the list of orders :

-   **Last name** : the name displayed on the dashboard,

-   **Pin up** : permet d’afficher la donnée sur le dashboard,

-   **Test** : allows to test the command

> **Note**
>
> Jeedom va vérifier toute les 15 minutes l’ip externe, si celle-ci a
> changed then the DNS will be updated

> **Important**
>
> The plugin provides a command to force the update. Be careful with
> do not abuse it because you may be temporarily banned from the service
> DNS

> **Note**
>
> Si vous utilisez OVH, attention le nom d’utilisateur est de la forme
> my field.com-identifier while the domain is well under the
> form mon-dynHost.mmy field.com
