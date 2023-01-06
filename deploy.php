<?php

namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'tweetcomposer');

// Config
set('repository', 'https://github.com/opmvpc/tweetcomposer.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts
host('tsix.be')
    ->set('deploy_path', '/var/www/laravel/{{application}}')
    ->set('identityFile', '~/.ssh/id_ed25519')
    ->set('remote_user', 'root')
    ->set('bin/deployer', function () {
        return 'php8.1 ./vendor/bin/dep';
    })
;

// Tasks
task('build', function () {
    run('cd {{release_path}} && build');
});

task('npm:run:prod', function () {
    cd('{{release_path}}');
    run('npm i');
    run('npm run build');
});

// Hooks
// [Optional] if deploy fails automatically unlock.
after('deploy:prepare', 'deploy:unlock');
// Migrate database before symlink new release.
before('deploy:setup', 'artisan:down');
after('artisan:migrate', 'artisan:queue:restart');
after('artisan:queue:restart', 'npm:run:prod');
after('deploy:success', 'artisan:up');
