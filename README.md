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



6) Run server
```
php -S localhost:8080 -t web
```

# Install with Docker.
Please refer to docker/readme.md

# Developpment

## ORM
All entities(1) bases classes were generated from larpmanager.mwb. It's MySQL Workbench File.

```
vendor/mysql-workbench-schema-exporter/mysql-workbench-schema-exporter/bin/mysql-workbench-schema-export --config=config/mwb_config.json database/larpmanager.mwb
```

This command generate all files under "generate" directory. You must copy one-by-one BaseXXX.php manually in  src/LarpManager/Entities. Never overwrite Entities with content of generate. Because one or more(1) class are manually altered. For those class, you need merge modification with caution.

All BaseXXXX classes are inherited. If you need specialize an entity. You need to modify the inherited class.


Note: If you meet an error about ZIP missing => install php-zip : sudo apt-get install php-zip under ubuntu


When you have generate entites with your new tables or new attributes. You could generate an SQL patch :
```
vendor/bin/doctrine orm:schema-tool:update --dump-sql
```
WARNING: Before execute in production, check that patch will not drop tables or columns with data 

If you want add new entity property to "Sample" class : 
1. Modify larpmanager.mwb (ask before, because the merge can be tricky if other one modify it) 
2. Generate BaseSample.php (with command bellow)
3. Copy BaseSample.php from generated to LarpManager/Entites/
4. Check that you're new BaseSample.php don't remove specific code and he only code needed for the new attribute
5. vendor/bin/doctrine orm:schema-tool:update --dump-sql and execute the alter table on your database
6. If you need some methods. Add them to Sample.php which inherit of BaseSample.php
7. When you push, verify you push also larpmanager.mwb.

If you want add new entity "Dummy" class :
1. Modify larpmanager.mwb (ask before, because the merge can be tricky if other one modify it) 
2. Generate BaseDummy.php (with command bellow)
3. Copy BaseDummy.php from generated to LarpManager/Entites/
4. Create Dummy.php inherit from BaseDummy.php, (use copy of an existing inherited class). 
5. Add to Dummy.php specific method.
6. Create new Repository, i use to copy an existing Repository (eg : AttributeTypeRepository.php)
7. vendor/bin/doctrine orm:schema-tool:update --dump-sql and execute create table on your database
8. When you push, verify you push also larpmanager.mwb.

PS(1): BaseTerritoire.php which have specific mapping (ManyToMany on self). that can't been modelize with Workbench without generate named table.


