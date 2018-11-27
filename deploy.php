<?php namespace Deployer;

include('recipe/symfony.php');

// Configuration
set('keep_releases', 10);
set('ssh_type', 'phpseclib');
set('ssh_multiplexing', true);
set('writable_mode', 'chmod');
set('writable_chmod_mode', '0777');

set('repository', 'git@gitlab.srvdev.ru:php/parking-pay.git');
set('composer_options', '{{composer_action}} --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader');

set('bin_dir', 'bin');
set('var_dir', 'var');
set('writable_dirs', ['var/cache', 'var/log', 'var/db', 'var/mailer']);
set('shared_dirs', ['var/log', 'var/db', 'var/mailer']);
set('shared_files', []);

task('deploy:assets:install', function () {
    run('{{env_vars}} {{bin/php}} {{bin/console}} assets:install {{console_options}}');
})->desc('Install bundle assets');

after('deploy:failed', 'deploy:unlock');

after('deploy:release', 'deploy:releases_list');
task('deploy:releases_list', function () {
    writeln('Release path: ' . get('release_path'));
});

before('deploy:shared', 'deploy:shared:my');
task('deploy:shared:my', function () {
    $stage = input()->getArgument('stage');
    if ($stage == 'prod') {
        run('ln -s {{release_path}}/.env.prod {{release_path}}/.env');
    } elseif ($stage == 'dev') {
        run('ln -s {{release_path}}/.env.dev {{release_path}}/.env');
    }
});

include('deploy_custom.php');
// Servers
server('dev', 'host')
    ->set('env', 'dev')
    ->user('php-fpm')
    ->password(DEV_PASSWORD)
    ->set('deploy_path', '/var/www/site.com');
