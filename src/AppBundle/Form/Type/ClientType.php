<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Client;
use OAuth2\OAuth2;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('redirectUris', CollectionType::class, [
                'entry_type' => UrlType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'attr' => [
                    'class' => 'collection',
                ],
            ])
            ->add('allowedGrantTypes', ChoiceType::class, [
                'choices' => [
                    'authorization_code' => OAuth2::GRANT_TYPE_AUTH_CODE,
                    'token' => OAuth2::GRANT_TYPE_IMPLICIT,
                    'password' => OAuth2::GRANT_TYPE_USER_CREDENTIALS,
                    'client_credentials' => OAuth2::GRANT_TYPE_CLIENT_CREDENTIALS,
                    'refresh_token' => OAuth2::GRANT_TYPE_REFRESH_TOKEN,
                    'extensions' => OAuth2::GRANT_TYPE_EXTENSIONS,
                ],
                'expanded' => true,
                'multiple' => true,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_client';
    }
}
