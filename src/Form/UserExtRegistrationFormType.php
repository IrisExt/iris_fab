<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserExtRegistrationFormType extends AbstractType
{
//    /**
//     * @var string
//     */
//    private $class;
//
//    /**
//     * @param string $class The User class name
//     */
//    public function __construct($class)
//    {
//        $this->class = $class;
//    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('username', HiddenType::class,
                array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('nom', TextType::class,[
                'mapped' =>false ,
                'label' => 'personne.from.lbNomUsage', 'translation_domain' => 'Personne'
            ])
            ->add('prenom',TextType::class,[
                'mapped' =>false,
                'label' => 'personne.from.lbPrenom', 'translation_domain' => 'Personne'
            ])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'options' => array(
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array(
                        'autocomplete' => 'new-password',
                    ),
                ),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
//            'data_class' => $this->class,
            'csrf_token_id' => 'registration',
        ));
    }

}
