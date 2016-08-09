<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class Load_01_UserData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    use \AppBundle\DataFixtures\Traits\OrderedFixtureTrait;
    use \Symfony\Component\DependencyInjection\ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $exists = $userManager->findUserByUsername($this->container->getParameter('app.super_admin.username'));

        if (!$exists) {
            $superAdmin = $userManager->createUser();
            $superAdmin
                ->setUsername($this->container->getParameter('app.super_admin.username'))
                ->setEmail($this->container->getParameter('app.super_admin.email'))
                ->setPlainpassword($this->container->getParameter('app.super_admin.password'))
                ->setEnabled(true)
                ->addRole('ROLE_ADMIN')
                ->addRole('ROLE_SUPER_ADMIN')
                ->addRole('ROLE_ALLOWED_TO_SWITCH')
                ->addRole('ROLE_API')
            ;
            $userManager->updateUser($superAdmin, false);
        }

        $manager->flush();
    }
}
