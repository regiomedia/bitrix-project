<?php
namespace Deployer;

require('recipe/common.php');


set('ssh_type', 'native');
set('ssh_multiplexing', true);

set('repository', 'ssh://git@your-git-repository-url');
set('shared_dirs', ['bitrix', 'upload']);
set('restart_cmd', 'sudo /usr/sbin/service apache2 restart');


task('check:uncommited', function() {
    $result = run("cd {{deploy_path}} && if [ -d current ]; then cd current && {{bin/git}} status --porcelain; fi");
    if (strlen($result) > 0) {
        throw new \RuntimeException(
            "Working copy contains uncommited/unstaged changes"
        );
    }

    else {
        writeln('<info>Working copy does not contain any uncommited/unstaged changes</info>');
    }
});

task('check:unpushed', function() {
    $result = run("cd {{deploy_path}} && if [ -d current ]; then cd current && {{bin/git}} log --oneline origin/{{branch}}..{{branch}}; fi");
    if (strlen($result) > 0) {
        throw new \RuntimeException(
            "Working copy contains unpushed changes"
        );
    }

    else {
        writeln('<info>Working copy does not contain any unpushed changes</info>');
    }
});

task(
    'check',
    [
        'check:uncommited',
        'check:unpushed'
    ]
);


task('deploy:migrate', function() {
    cd('{{release_path}}');
    $output = run( '{{bin/php}} migrator migrate');
    writeln('<info>' . $output . '</info>');
});
task('deploy:restart', function () {
    run('{{restart_cmd}}');
});

before('deploy:release', 'check:uncommited');
before('deploy:release', 'check:unpushed');


task ('deploy:frontend', function(){

    if (has('previous_release')) {
        if (test('[ -d {{previous_release}}/node_modules ]')) {
            run('cp -R {{previous_release}}/node_modules {{release_path}}');
        }
    }

    cd('{{release_path}}');
    run('npm install && ./node_modules/@symfony/webpack-encore/bin/encore.js dev', ['timeout' => null]);
});

task('jedi:cache:clear', function () {
    run('{{bin/php}} {{release_path}}/vendor/bin/jedi cache:clear');
});

after('deploy:symlink', 'jedi:cache:clear');


task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:frontend',
    'deploy:migrate',
    'deploy:symlink',
    'deploy:restart',
    'deploy:unlock',
    'cleanup',
    'success'
]);

inventory(__DIR__.'/hosts.yml');
