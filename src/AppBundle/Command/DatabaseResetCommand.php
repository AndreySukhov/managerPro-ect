<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class DatabaseResetCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:database:reset')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $application = $this->getApplication();
        $application->setAutoExit(false);

        // Remove all migrations
        $finder = new Finder();
        $finder->in($application->getKernel()->getRootDir().'/DoctrineMigrations')->name('*.php');

        $filesistem = new Filesystem();
        foreach ($finder as $file) {
            $filesistem->remove($file);
        }

        $commandInputs = [
            new ArrayInput([
               'command' => 'doctrine:database:drop',
               '--force' => true,
            ]),
            new ArrayInput([
                'command' => 'doctrine:database:create',
            ]),
            new ArrayInput([
                'command' => 'doctrine:migrations:diff',
            ]),
            new ArrayInput([
                'command' => 'doctrine:migrations:migrate',
                '-n' => true,
            ]),
            new ArrayInput([
                'command' => 'doctrine:fixtures:load',
                '-n' => true,
            ]),
        ];

        $bufferedOutput = new BufferedOutput(
            OutputInterface::VERBOSITY_NORMAL,
            true
        );
        foreach ($commandInputs as $commandInput) {
            $exitCode = $application->run($commandInput, $bufferedOutput);
            if ($exitCode > 0) {
                $output->writeln('<error>Fail:</error> '.(string) $commandInput);

                return;
            }

            $output->writeln('<info>OK:</info> '.(string) $commandInput);
        }

        $application->setAutoExit(true);
    }
}
