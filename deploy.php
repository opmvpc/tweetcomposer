<?php

namespace Deployer;

require 'recipe/laravel.php';

// Config
set('bin/php', function () {
    return which('php8.0');
});

set('bin/composer', function () {
    return 'php8.0 /usr/local/bin/composer';
});

// Project name
set('application', 'tweetcomposer');

set('repository', 'https://github.com/opmvpc/tweetcomposer.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('tsix.be')
    ->set('deploy_path', '/var/www/laravel/{{application}}')
    ->set('identityFile', '~/.ssh/id_ed25519')
    ->set('remote_user', 'root')
;

// Hooks

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

task('npm:run:prod', function () {
    cd('{{release_path}}');
    run('npm install');
    run('npm run build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
// Migrate database before symlink new release.
before('deploy:symlink', 'artisan:migrate');
after('artisan:migrate', 'npm:run:prod');
