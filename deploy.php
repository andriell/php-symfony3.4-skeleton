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

//<editor-fold desc="yak">
after('deploy:update_code', 'deploy:yak');
task('deploy:yak', function () {
    run('cd {{release_path}} && unzip yakpro-po.zip');
    run('php {{release_path}}/yakpro-po/yakpro-po.php --config-file {{release_path}}/yakpro-po.php');
    run('cd {{release_path}} && ls -A | grep -v \'obfuscated\' | xargs rm -rf');
    run('cd {{release_path}}/obfuscated/yakpro-po/obfuscated && mv $(ls -A) {{release_path}}');
    run('rm -fr {{release_path}}/.git');
    run('rm -fr {{release_path}}/docs');
    run('rm -fr {{release_path}}/obfuscated');
    run('rm -fr {{release_path}}/yakpro-po');
    run('rm {{release_path}}/.gitignore');
    run('rm {{release_path}}/deploy.php');
    run('rm {{release_path}}/RememberMe.txt');
    run('rm {{release_path}}/yakpro-po.php');
    run('rm {{release_path}}/yakpro-po.zip');
});
//</editor-fold>

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
