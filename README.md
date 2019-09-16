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

2) Install composer. Check this page to do it:  
```
https://getcomposer.org/download/
```


3) Download required package with composer
```
./composer update
./composer install 
```

Configure MySQL connection settings.yml and normal_settings.yml

4) If you have no database, you can initialize it with  :

```
vendor/bin/doctrine orm:schema-tool:drop --force
vendor/bin/doctrine orm:schema-tool:create
```
Note: J'ai rencontré une erreur avec  territoire_quete dans BaseTerritoireQuete.php. Pour finaliser l'installation , j'ai du enlever le conflit de nom en renommant 'territoire_quete' en 'territoire_quete2'  



6) Run server
```
php -S localhost:8080 -t web
```

# Developpment

All entities bases classes were generated form larpmanager.mwb. It's MySQL Workbench File.

```
vendor/mysql-workbench-schema-exporter/mysql-workbench-schema-exporter/bin/mysql-workbench-schema-export --config=config/mwb_config.json database/larpmanager.mwb
```

Note: If you meet an error about ZIP missing => install php-zip : sudo apt-get install php-zip under ubuntu


When you have generate entites with your new tables or new attributes. You could generate an SQL patch :
```
vendor/bin/doctrine orm:schema-tool:update --dump-sql
```

WARNING: Before execute in production, check that patch will not drop tables or columns with data 




