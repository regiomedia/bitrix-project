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
set('shared_files', ['.env']);
set('restart_cmd', 'sudo /usr/sbin/service apache2 restart');
set('frontend_magic', 'npm install && npm run encore -- dev');

set('sync', [
    'files' => [
        [
            'path' => 'bitrix',
            'excludes' => [
                'cache',
                'managed_cache',
                'stack_cache',
                'resize_cache',
                'tmp',
                '.settings.php',
                'php_interface/dbconn.php',
                'backup/*gz*'
            ]
        ],
        [
            'path' => 'upload',
            'excludes' => [
                'resize_cache',
                'tmp'
            ]
        ]
    ]
]);

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
    cd('{{release_path}}');
    run('{{bin/php}} ./vendor/bin/jedi cache:clear');
});

after('deploy:symlink', 'jedi:cache:clear');


option(
    '--sync-source',
    null,
    InputOption::VALUE_OPTIONAL,
    'Set the source Host from which synchronisation process will get all data'
);


task('sync:db', function() {

    $sourceHost = host(input()->getOption('sync-source'));

    $sourceHost->getRealHostname();

    $getEnv = function() {
        $host = Context::get()->getHost();

        if ($host instanceof Host\Localhost) {
            $path = '.';
        }

        else {
            $path = '{{release_path}}';
        }

        cd($path);
        $result = run('cat .env');
        return parse_ini_string($result, false);
    };

    $sourceEnv = null;
    on($sourceHost, function() use(&$sourceEnv, $getEnv){
        $sourceEnv = $getEnv();
    });


    $destEnv = $getEnv();


    $command = escapeshellcmd("ssh -p {$sourceHost->getPort()}  {$sourceHost->getUser()}@{$sourceHost->getRealHostname()} " .
            "'mysqldump --default-character-set=utf8 -h {$sourceEnv['DB_HOST']} " .
            "-u {$sourceEnv['DB_LOGIN']} -p{$sourceEnv['DB_PASSWORD']} {$sourceEnv['DB_NAME']} ".
            "--skip-lock-tables --add-drop-table --single-transaction --quick' ") .
        "|  ".
        escapeshellcmd("mysql {$destEnv['DB_NAME']} -h {$destEnv['DB_HOST']} ".
            "-u {$destEnv['DB_LOGIN']}  -p{$destEnv['DB_PASSWORD']}");

    run($command, ['timeout' => null]);

})->onStage('dev', 'test', 'stage');

task('sync:files', function() {
    $settings = get('sync');
    $sourceHost = host(input()->getOption('sync-source'));
    $destHost = Context::get()->getHost();

    $destPath = $destHost instanceof Localhost ? '.' : '{{release_path}}';


    foreach ($settings['files'] as $file) {

        $excludes = '';
        foreach ($file['excludes'] as $ex) {
            $excludes .= ' --exclude='. $ex;
        }

        $command = "rsync -e 'ssh -p {$sourceHost->getPort()}' -avz --delete " .
            "{$sourceHost->getUser()}@{$sourceHost->getRealHostname()}:" .
            "{$sourceHost->get('deploy_path')}/current/{$file['path']}/ " .
            "{$destPath}/{$file['path']} " .
            "{$excludes}";
        run(escapeshellcmd($command), ['timeout' => null]);
    }
})->onStage('dev', 'test', 'stage');

task('sync', [
    'sync:files',
    'sync:db'
]);


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
