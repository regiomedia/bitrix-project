<?php

namespace Local\Console;

use Notamedia\ConsoleJedi\Application\Command\Command;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LinkCommand extends Command
{
    protected $root = 'sites/';
    protected $folders = ['bitrix', 'local', 'upload'];

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('core:link')
            ->setAliases(['link'])
            ->setDescription('Create a symbolic links for bitrix, local and upload folders.')
            ->setHelp('Bitrix, local and upload folders has been linked.')
            ->addArgument('folder', InputArgument::OPTIONAL, 'Site folder');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $arg = $input->getArgument('folder');
        $root = $this->getApplication()->getRoot();

        if ($arg === null) {
            $path = $this->getApplication()->getRoot() . '/' . $this->root . 's1';
        } else {
            $path = $this->getApplication()->getRoot() . '/' . $this->root . $arg;
        }

        if (!is_dir($path)) {
            throw new RuntimeException('Directory "' . $path . '" is missing');
        }

        if (!windows_os()) {
            foreach ($this->folders as $folder) {
                if (file_exists($path . '/' . $folder)) {
                    $output->writeln($folder . ' directory already exists.');
                    continue;
                }
                symlink($root . '/' . $folder, $path . '/' . $folder);
                $output->writeln($folder . ' has been linked.');
            }
            return;
        }

        foreach ($this->folders as $folder) {
            if (file_exists($path . '/' . $folder)) {
                $output->writeln($folder . ' directory already exists.');
                continue;
            }
            exec("mklink /J  \"{$path}/{$folder}\" \"{$folder}\"");
            $output->writeln($folder . ' has been linked.');
        }
        return null;
    }
}
