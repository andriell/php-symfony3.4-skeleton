# Symfony 3.4 skeleton
Symfony 3.4 and Sonata admin
## Installation
    yum install epel-release
    yum install nginx
    systemctl enable nginx

    curl 'https://setup.ius.io/' -o setup-ius.sh
    bash setup-ius.sh
    yum install php71u-fpm php71u-cli php71u-intl php71u-pdo php71u-xml php71u-mbstring php71u-gd php71u-json
    systemctl enable php-fpm

    php composer.phar install
    php bin/console doctrine:schema:update --force
    php bin/console fos:user:create sadmin admin@admin.ru password --super-admin
    php bin/console assets:install

## Nginx settings

    # File: /etc/nginx/conf.d/symfony.conf
    server {
        listen       80 default_server;
        server_name  _;
        root /var/www/symfony/current/public;
        index  index.php index.html index.htm;
    
        location / {
            try_files $uri /index.php$is_args$args;
        }
    
        location ~ \.php$ {
            # fastcgi_pass unix:/run/php-fpm/www.sock;
            fastcgi_pass php:9000;
            fastcgi_split_path_info ^(.+\.php)(/.*)$;
            include fastcgi_params;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        }
    
        location ~ /\.(ht|svn|git|idea) {
            deny all;
        }
    }

## PHP settings

    # File: /etc/php-fpm.d/www.conf
    listen = /run/php-fpm/www.sock
    listen.acl_users = nginx

## Docker-compose
    docker-composer up -d
    docker-machine ip
    docker-composer ps
    docker-composer down

## Debugging PHP Scripts

Press button start listening

    docker exec -it symfony_php /bin/bash
    cd /var/www/symfony/current/
    php bin/console app:test-db