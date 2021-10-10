# Configuration pour Ubuntu

```
sudo apt-get install docker docker.io
sudo apt-get install docker-compose
```

# Configuration pour Windows

TODO Faire la doc avec multipass Ubuntu.
```
https://multipass.run/docs/installing-on-windows
```


# Lancement du serveur de developpement.

1) Copiez larpmanager-anonymized.sql dans docker/init/

2) Executez en console
```
sudo docker-compose up --build
```

3) Pour le premier démarrage, il faut initialiser la base de données.

3.1) Récupérer l'id du container
```
sudo docker container ls
```
Vous obtiendrez :
```
CONTAINER ID   IMAGE                   COMMAND                  CREATED        STATUS        PORTS                                       NAMES
fd130ee08fe7   larpmanager-php_larpm   "docker-php-entrypoi…"   17 hours ago   Up 17 hours   0.0.0.0:8080->8080/tcp, :::8080->8080/tcp   larpmanager-php_larpm_1
02e1ceff030e   mariadb:10.5.12         "docker-entrypoint.s…"   17 hours ago   Up 17 hours   3306/tcp                                    larpmanager-php_larpmdbmysql_1
```
Le nom du container du server web : larpmanager-php_larpm_1

3.2) Ouvrir un terminal & executez le initDatabase.sh
```
sudo docker exec -ti larpmanager-php_larpm_1 /bin/bash
cd docker/init
bash initDatabase.sh
```

4) Quand vous souhaitez relancer l'application sans rebuild, il suffit de faire .
```
sudo docker-compose up
```


# Ci-dessous, un petit glossaire de commandes à retenir pour Docker.

Pour lister les container
```
sudo docker container ls
```


Pour voir la charge d'éxecution.
```
sudo docker stats
```


Pour ouvrir un terminal dans la VM docker.
Récupérez les nom via *docker container ls* par exemple *larpmanager-php_larpm_1*
```
sudo docker exec -ti larpmanager-php_larpm_1 /bin/bash
```

