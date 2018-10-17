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

    # File: /etc/nginx/conf.d/server.com.conf
    server {
            listen       80;
            server_name  ~^server\.com$;
            root /var/www/server.com/current/public;
            index  index.php index.html index.htm;
    
            location / {
                    try_files $uri /index.php$is_args$args;
            }
    
            location ~ ^/index\.php(/|$) {
                    fastcgi_pass unix:/run/php-fpm/www.sock;
                    fastcgi_split_path_info ^(.+\.php)(/.*)$;
                    include fastcgi_params;
                    fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
                    fastcgi_param DOCUMENT_ROOT $realpath_root;
                    internal;
            }
    
            location ~ \.php$ {
                    return 404;
            }
    
            location ~ /\.(ht|svn|git|idea) {
                    deny all;
            }
    }

## PHP settings

    # File: /etc/php-fpm.d/www.conf
    listen = /run/php-fpm/www.sock
    listen.acl_users = nginx

