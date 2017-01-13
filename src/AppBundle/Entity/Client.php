<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\Client as BaseClient;

/**
 * @ORM\Entity
 * @ORM\Table(name="clients")
 */
class Client extends BaseClient
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Name (label in view).
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    public function __construct()
    {
        parent::__construct();
    }

    public function __toString()
    {
        $string = $this->getName();
        if (!$string) {
            $string = $this->getPublicId();
        }

        return (string) $string;
    }

    public function getClientId()
    {
        return $this->getPublicId();
    }

    public function getClientSecret()
    {
        return $this->getSecret();
    }

    /**
     * Get the value of Name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of Name.
     *
     * @param string name
     * @param mixed $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
