Este complemento permite actualizar un servicio DNS dinámico
(Dyndns, NOIP, duckdns ...)

Configuración del complemento 
=======================

Après téléchargement du plugin, il vous suffit juste d’activer celui-ci,
il n’y a aucune configuration à ce niveau.

![DYNDNS] (../ images / dyndns.PNG)

Configuración del equipo 
=============================

Se puede acceder a la configuración del equipo Dyndns desde el menú
plugin :

![Dyndns2] (../ images / dyndns2.PNG)

Así es como se ve la página del complemento Dyndns (aquí con 1
equipos) :

![Dyndns3] (../ images / dyndns3.PNG)

> **punta**
>
> Comme à beaucoup d’endroits sur Jeedom, placer la souris tout à gauche
> permet de faire apparaître un menu d’accès rapide (vous pouvez, à
> desde su perfil, siempre déjelo visible).

Une fois que vous cliquez sur l’un d’eux, vous obtenez :

![Dyndns4] (../ images / dyndns4.PNG)

Aquí encontrarás toda la configuración de tu equipo :

-   **apellido de l’équipement Ambilight** : nombre de su equipo
    Ambilight

-   **Objeto padre** : indique l’objet parent auquel appartient
    l’équipement,

-   **categoría** : les catégories de l’équipement (il peut appartenir à
    varias categorías),

-   **Activar** : activa su equipo,

-   **visible** : hace que su equipo sea visible en el tablero de instrumentos,

-   **servicio** : El nombre del servicio utilizado (dyndns.org, noip.com)

-   **nombre de host** : apellidobre DNS completo para actualizar (por ejemplo, toto.ddns.net)

-   **apellido d’utilisateur** : nom d’utilisateur sur le service en question

-   **Contraseña** : contraseña en el servicio en cuestión

-   **simbólico** : ficha utilizada para duckdns

A continuación encontrará la lista de pedidos. :

-   **apellido** : el nombre que se muestra en el tablero,

-   **visualización** : permet d’afficher la donnée sur le dashboard,

-   **prueba** : permite probar el comando

> **nota**
>
> Jeedom va vérifier toute les 15 minutes l’ip externe, si celle-ci a
> cambiado, luego se actualizará el DNS

> **importante**
>
> El complemento proporciona un comando para forzar la actualización. Atención a
> no abuses de él porque puedes ser expulsado temporalmente del servicio
> DNS

> **nota**
>
> Si vous utilisez OVH, attention le nom d’utilisateur est de la forme
> mydomain.com-identificador mientras el dominio está bien bajo
> formulario mon-dynHost.mmydomain.com
