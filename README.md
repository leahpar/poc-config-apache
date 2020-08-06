# POC config apache

POC de gestion des paramètres dans la config apache




## Apache

### Activation modules nécessaires

```apacheconfig
# .../apache/httpd.conf

# https://httpd.apache.org/docs/2.4/fr/mod/mod_macro.html
LoadModule macro_module lib/httpd/modules/mod_macro.so

# https://httpd.apache.org/docs/2.4/fr/mod/mod_env.html
LoadModule env_module lib/httpd/modules/mod_env.so
```

### Config Apache

```apacheconfig
# .../apache/sites.conf

<Macro VHost $name $domain>
<VirtualHost *:80>
    ServerName $domain
    DocumentRoot "/Users/raphael/projets/stim/poc-config-apache/public"
    	
    <Directory /Users/raphael/projets/stim/poc-config-apache/public>
		AllowOverride All
		Require all granted
	</Directory>

    SetEnv APP_PARAMS_NAME "$name"
    SetEnv APP_PARAMS_DOMAIN "$domain"
</VirtualHost>
</Macro>

Use VHost site1 site1.com
Use VHost site2 site2.com
Use VHost site3 site3.fr

# permet d'éviter les conflits de définitions qui pourraient provenir de l'utilisation ultérieure de macros contenant les mêmes noms de variables.
UndefMacro VHost
```

`SetEnv` permet de définir des variables récupérables dans `$_SERVER`.

Utiliser des variables préfixées par `APP_` pour ne pas être nettoyé par apache.
Sinon il faut explicitement récupérer les variables avec `$_SERVEUR[VAR] = getenv("VAR")` avant le bootEnv dans `index.php`.
(https://stackoverflow.com/a/17913262/11105345)

### Localhost

```
# /etc/hosts

127.0.0.1	site1.com site2.com site3.fr
```

## Symfony

Récupération des paramètres :

```yaml
# config/services.yaml
parameters:
    app.name: '%env(string:APP_PARAMS_NAME)%'
    app.domain: '%env(string:APP_PARAMS_DOMAIN)%'
```

Utilisation dans un controleur :

```php
$this->getParameter("app.domain");
```

Utilisation dans une config :

```yaml
services:
    App\Service\SomeService:
        arguments:
            string $someArgument: '%app.name%'
```

Utilisation dn paramètre autowiré :

```yaml
# config/services.yaml
services:
    _defaults:
        bind:
          string $someArgument: '%app.name%'
```

