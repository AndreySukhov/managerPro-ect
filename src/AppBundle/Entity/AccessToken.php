<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\AccessToken as BaseAccessToken;

/**
 * AccessToken.
 *
 * @ORM\Entity
 * @ORM\Table(name="access_tokens")
 */
class AccessToken extends BaseAccessToken
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
     * Клиент.
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Client")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $client;

    /**
     * Пользователь.
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    protected $user;
}
