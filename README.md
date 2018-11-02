# larpManager

Live Action Role Playing Manager

This tool was made for manage player subscription, player background and many other things on LARP event.


# Install
Before start, you need to have install MySQL.

You need to have : schema name, mysql login, mysql passwd

1) Download the source
```
git clone https://github.com/eveoniris/larpManager-php.git
```

2) Install composer
```
cd larpManager-php
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
```

3) Download required package with composer
```
./composer update
./composer install 
```

Configure MySQL connection settings.yml and normal_settings.yml

4) In console, execute :

```
vendor/bin/doctrine orm:schema-tool:drop --force
vendor/bin/doctrine orm:schema-tool:create
```
Note: J'ai rencontré une erreur avec  territoire_quete dans BaseTerritoireQuete.php. Pour finaliser l'installation , j'ai du enlever le conflit de nom en renommant 'territoire_quete' en 'territoire_quete2'  


5) Run server
```
php -S localhost:8080 -t web
```



