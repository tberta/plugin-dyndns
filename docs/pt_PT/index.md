Este plugin permite atualizar um serviço DNS dinâmico
(dyndns, noip, duckdns,…)

Configuração do plugin 
=======================

Depois de baixar o plugin, você só precisa ativá-lo,
não há configuração neste nível.

![dyndns](../images/dyndns.PNG)

Configuração do equipamento 
=============================

A configuração do equipamento Dyndns pode ser acessada no menu
plugin :

![dyndns2](../images/dyndns2.PNG)

É assim que a página do plugin Dyndns se parece (aqui com 1
equipamento) :

![dyndns3](../images/dyndns3.PNG)

> **Tip**
>
> Como em muitos lugares em Jeedom, coloque o mouse na extremidade esquerda
> abre um menu de acesso rápido (você pode, em
> do seu perfil, deixe-o sempre visível).

Depois de clicar em um deles, você obtém :

![dyndns4](../images/dyndns4.PNG)

Aqui você encontra toda a configuração do seu equipamento :

-   **Nome do equipamento Ambilight** : nome do seu equipamento
    Ambilight,

-   **Objeto pai** : indica o objeto pai ao qual pertence
    o equipamento,

-   **Categoria** : categorias de equipamentos (pode pertencer a
    várias categorias),

-   **Activer** : torna seu equipamento ativo,

-   **Visible** : torna seu equipamento visível no painel,

-   **Service** : O nome do serviço usado (dyndns.org, noip.com)

-   **Hostname** : nome DNS completo a ser atualizado (por exemplo, toto.ddns.net)

-   **Nome de Usuário** : nome de usuário no serviço em questão

-   **Senha** : senha no serviço em questão

-   **Token** : token usado para duckdns

Abaixo você encontra a lista de pedidos :

-   **Nom** : o nome exibido no painel,

-   **Afficher** : permite exibir os dados no painel,

-   **Tester** : permite testar o comando

> **Note**
>
> O Jeedom irá verificar o ip externo a cada 15 minutos, se houver
> alterado, o DNS será atualizado

> **Important**
>
> O plug-in fornece um comando para forçar a atualização. Preste atenção em
> não abuse, porque você pode ser temporariamente banido do serviço
> DNS

> **Note**
>
> Se você usa OVH, tenha cuidado com o nome de usuário no formato
> mondomain.identificador de com enquanto o domínio está bem abaixo do
> formulário mon-dynHost.mmondomaine.com
