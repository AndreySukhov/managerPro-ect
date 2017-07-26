<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class DemoCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:demo')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $application = $this->getApplication();
        $application->setAutoExit(false);

        $commandInputs = [
            'Drop database' => new ArrayInput([
               'command' => 'doctrine:database:drop',
               '--force' => true,
            ]),
            'Create database' => new ArrayInput([
                'command' => 'doctrine:database:create',
            ]),
            'Migrations' => new ArrayInput([
                'command' => 'doctrine:migrations:migrate',
                '-n' => true,
            ]),
            'Fixtures' => new ArrayInput([
                'command' => 'doctrine:fixtures:load',
                '-n' => true,
            ]),
        ];

        $bufferedOutput = new BufferedOutput(OutputInterface::VERBOSITY_NORMAL, true);
        $step = 1;
        $steps = count($commandInputs);
        foreach ($commandInputs as $outputString => $commandInput) {
            $exitCode = $application->run($commandInput, $bufferedOutput);
            $stepInfo = sprintf('Step %s/%s', $step, $steps);
            if ($exitCode > 0) {
                $output->writeln('<error>Error '.$stepInfo.':</error> '.$outputString);

                return;
            }

            $output->writeln('<info>OK '.$stepInfo.':</info> '.$outputString);
            ++$step;
        }
    }
}
