<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\Client as BaseClient;

/**
 * Client.
 *
 * @ORM\Entity
 * @ORM\Table(name="clients")
 */
class Client extends BaseClient
{
    /**
     * Идентификатор.
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Имя (лэйбл во вью).
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    public function __toString()
    {
        $string = $this->getName();
        if (!$string) {
            $string = $this->getPublicId();
        }

        return (string) $string;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->getPublicId();
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->getSecret();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
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
