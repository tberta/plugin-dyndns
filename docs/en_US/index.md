This plugin allows to update a dynamic DNS service
(Dyndns, NOIP, duckdns ...)

Plugin configuration 
=======================

After downloading the plugin, you just need to activate it,
there is no configuration at this level.

![dyndns](../images/dyndns.PNG)

Equipment configuration 
=============================

The configuration of Dyndns equipment is accessible from the menu
plugin :

![dyndns2](../images/dyndns2.PNG)

This is what the Dyndns plugin page looks like (here with already 1
equipment) :

![dyndns3](../images/dyndns3.PNG)

> **Tip**
>
> As in many places on Jeedom, place the mouse on the far left
> brings up a quick access menu (you can, at
> from your profile, always leave it visible).

Once you click on one of them, you get :

![dyndns4](../images/dyndns4.PNG)

Here you find all the configuration of your equipment :

-   **Ambilight equipment name** : name of your equipment
    Ambilight

-   **Parent object** : indicates the parent object to which belongs
    equipment,

-   **Category** : equipment categories (it may belong to
    several categories),

-   **Activate** : makes your equipment active,

-   **Visible** : makes your equipment visible on the dashboard,

-   **Service** : The name of the service used (dyndns.org, noip.com)

-   **hostname** : full DNS name to update (eg toto.ddns.net)

-   **username** : username on the service in question

-   **Password** : password on the service in question

-   **token** : token used for duckdns

Below you find the list of orders :

-   **Last name** : the name displayed on the dashboard,

-   **Pin up** : displays the data on the dashboard,

-   **Test** : allows to test the command

> **Note**
>
> Jeedom will check the external ip every 15 minutes, if it has
> changed then the DNS will be updated

> **Important**
>
> The plugin provides a command to force the update. Be careful with
> do not abuse it because you may be temporarily banned from the service
> DNS

> **Note**
>
> If you use OVH, be careful the username is in the form
> my field.com-identifier while the domain is well under the
> form mon-dynHost.mmy field.com
