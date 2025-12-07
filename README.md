# project
ArtGorka Project  

UBUNTU 24.04.5 (ubuntu.com download, PHP 8.3.6 (cli), MySQL Ver 8.0.44-0ubuntu0.24.04.1 (mysqli), Composer version 2.9.2)

> sudo apt install php  
> sudo apt install mysql-client mysql-server  
> sudo apt install php-mysql  


> git clone https://github.com/MikhailovMV/project.git
> cd project  
> php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"  

> php -r "if (hash_file('sha384', 'composer-setup.php') === 'c8b085408188070d5f52bcfe4ecfbee5f727afa458b2573b8eaaf77b3419b0bf2768dc67c86944da1544f06fa544fd47') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"  

> php composer-setup.php  
> php -r "unlink('composer-setup.php');"  

> php composer.phar install  

> sudo mysql < create_db.sql  
> cp .env.example .env
> nano .env

> cd public  
> php -S 127.0.0.1:80 (or php -S 127.0.0.1:8080)  
