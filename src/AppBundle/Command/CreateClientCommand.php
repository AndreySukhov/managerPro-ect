<?php

namespace AppBundle\Command;

use Acme\OAuthServerBundle\Document\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateClientCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:oauth-server:client:create')
            ->setDescription('Создаёт нового API клиента')
            ->addOption(
                'redirect-uri',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Ссылки перенаправления.',
                null
            )
            ->addOption(
                'grant-type',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Тип аутентификации',
                null
            )
            ->setHelp(
                <<<'EOT'
                    <info>%command.name%</info> команда создаёт клиента.

<info>php %command.full_name% [--redirect-uri=...] [--grant-type=...] name</info>

Пример 
<info>app:oauth-server:client:create --redirect-uri="http://localhost:8000" --grant-type="authorization_code" --grant-type="password" --grant-type="refresh-token" --grant-type="token" --grant-type="client_credentials"</info>

EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRedirectUris($input->getOption('redirect-uri'));
        $client->setAllowedGrantTypes($input->getOption('grant-type'));
        $clientManager->updateClient($client);
        $output->writeln(
            sprintf(
                'Добавлен клиент: client_id <info>%s</info>, secret <info>%s</info>',
                $client->getPublicId(),
                $client->getSecret()
            )
        );
    }
}
