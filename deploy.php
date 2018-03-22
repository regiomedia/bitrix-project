<?php
namespace Deployer;

use Symfony\Component\Console\Input\InputOption;

require('recipe/common.php');

option(
    'skip-git-check',
    null,
    InputOption::VALUE_NONE,
    'Allows to skip checking for uncommited and unpushed changes on deployment host '
);

set('ssh_type', 'native');
set('ssh_multiplexing', true);

set('repository', 'ssh://git@your-git-repository-url');
set('shared_dirs', ['bitrix', 'upload']);
set('restart_cmd', 'sudo /usr/sbin/service apache2 restart');
set('frontend_magic', 'npm install && npm run encore -- dev');


task('check:uncommited', function() {

    $skipCheck = input()->getOption('skip-git-check');

    if ($skipCheck) {
        writeln('<info>Checking for unconmmited/unstaged changes was skipped</info>');
        return;
    }

    $result = run("cd {{deploy_path}} && if [ -d current ]; then cd current && {{bin/git}} status --porcelain; fi");
    if (strlen($result) > 0) {

        writeln("<error>$result</error>");

        throw new \RuntimeException(
            "Working copy contains uncommited/unstaged changes"
        );
    }

    else {
        writeln('<info>Working copy does not contain any uncommited/unstaged changes</info>');
    }
});

task('check:unpushed', function() {

    $skipCheck = input()->getOption('skip-git-check');

    if ($skipCheck) {
        writeln('<info>Checking for unpushed changes was skipped</info>');
        return;
    }

    $result = run("cd {{deploy_path}} && if [ -d current ]; then cd current && {{bin/git}} log --oneline origin/{{branch}}..{{branch}}; fi");
    if (strlen($result) > 0) {

        writeln("<error>$result</error>");

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


before('deploy:release', 'check:uncommited');
before('deploy:release', 'check:unpushed');


task('deploy:migrate', function() {
    cd('{{release_path}}');
    $output = run( '{{bin/php}} migrator migrate');
    writeln('<info>' . $output . '</info>');
});
task('deploy:restart', function () {
    run('{{restart_cmd}}');
});


task ('deploy:frontend', function(){

    if (has('previous_release')) {
        if (test('[ -d {{previous_release}}/node_modules ]')) {
            run('cp -R {{previous_release}}/node_modules {{release_path}}');
        }
    }

    cd('{{release_path}}');
    run('{{frontend_magic}}', ['timeout' => null]);
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
