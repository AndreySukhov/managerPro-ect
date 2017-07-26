<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User.
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * Идентификатор
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Client")
     * @ORM\JoinTable(name="users_clients",
     *  joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="client_id", referencedColumnName="id", unique=true)}
     * )
     */
    private $clients;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->clients = new ArrayCollection();

        parent::__construct();
    }

    /**
     * Add client.
     *
     * @param Client $client
     *
     * @return User
     */
    public function addClient(Client $client)
    {
        $this->clients[] = $client;

        return $this;
    }

    /**
     * Remove client.
     *
     * @param Client $client
     */
    public function removeClient(Client $client)
    {
        $this->clients->removeElement($client);
    }

    /**
     * Get clients.
     *
     * @return ArrayCollection
     */
    public function getClients()
    {
        return $this->clients;
    }
}
