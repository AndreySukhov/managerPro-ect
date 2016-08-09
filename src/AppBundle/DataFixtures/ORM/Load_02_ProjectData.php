<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectFile;

class Load_02_ProjectData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    use \AppBundle\DataFixtures\Traits\OrderedFixtureTrait;
    use \Symfony\Component\DependencyInjection\ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        for ($projectIndex = 1; $projectIndex <= 12; ++$projectIndex) {
            $project = new Project();
            $project
                ->setName('Project '.$projectIndex)
                ->setSource($this->container->get('kernel')->getRootDir().'/../')
                ->setBranch('develop')
            ;
            foreach ([
                'README.md',
                'ROADMAP.md',
            ] as $filename) {
                $projectFile = new ProjectFile();
                $projectFile
                    ->setName($filename)
                    ->setProject($project)
                    ->setContent('# '.$filename."\n")
                ;

                $manager->persist($projectFile);
            }
            $manager->persist($project);
        }

        $manager->flush();
    }
}
