<?php

namespace App\Form;

use App\Entity\TgHabilitation;
use App\Entity\TgPersonne;
use App\Entity\TrGenre;
use App\Entity\TrRole;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TgPersonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $idPersonne = $options['idpersonne'];


//        $builder


        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use($options) {
            $evaluateur = $event->getData(); //recuperation de l'objet sur lequel le formulaire se base
            $form = $event->getForm(); //recuperation du formulaire
            $idRolePresident = $options['president']; // idRole Président

            if($options['idComite'] != false){  // constitution CES ajouter evaluateur

                    $form->add('lbNomUsage',TextType::class, ['translation_domain' => 'Personne', 'label' => 'personne.from.lbNomUsage', 'attr' => ['class' => 'form-controle'],])
                         ->add('lbPrenom',TextType::class, ['translation_domain' => 'Personne', 'label' => 'personne.from.lbPrenom', 'attr' => ['class' => 'form-controle'],])
                         ->add('courriel',EmailType::class, [ 'mapped' => false, 'translation_domain' => 'Personne', 'label' => 'personne.from.courriel', 'attr' => ['class' => 'form-controle'],])

                        ->add('cdFrancophone',  ChoiceType::class, [
                            'choices' => [
                                'Francophone' => 'Fr',
                                'Non francophone' => 'NFr',
                                'Langue inconnue' => 'INC'
                            ],
                            'translation_domain' => 'Personne',
                            'label' => 'personne.from.langue',
                             'attr' =>['class' => 'chosen-select']
                        ]);
            }else{

                $idPersonne = $options['idpersonne'];

                $form ->add('idGenre',EntityType::class, ['class' => TrGenre::class,  'required' => true])
                    ->add('lbNomUsage',TextType::class, ['translation_domain' => 'Personne', 'label' => 'personne.from.lbNomUsage', 'attr' => ['class' => 'form-controle'],])
                    ->add('lbPrenom',TextType::class, ['translation_domain' => 'Personne', 'label' => 'personne.from.lbPrenom', 'attr' => ['class' => 'form-controle'],])

                    ->add('cdFrancophone',  ChoiceType::class, [
                        'choices' => [
                            'francophone' => 'Fr',
                            'Non francophone' => 'NFr',
                            'Langue inconnue' => 'INC'
                        ],
                        'translation_domain' => 'Personne', 'label' => 'personne.from.cdfrancophone'
                    ])
                    ->add('lbWebPerso',TextType::class, ['required' => false, 'translation_domain' => 'Personne', 'label' => 'personne.from.lbWebPerso', 'attr' => ['class' => 'form-controle'],])
                    ->add('fonction',TextType::class, ['required' => false, 'translation_domain' => 'Personne', 'label' => 'personne.from.fonction', 'attr' => ['class' => 'form-controle'],]);
                if(!$idPersonne){
                    $form->add('Users',EntityType::class,[
                        'class' => User::class,
                        'placeholder' => 'Choisir un compte',
                        'required' => false,
                        'query_builder' => function (EntityRepository $er) use ($idPersonne)
                        {
                            $resltUser =  $er->createQueryBuilder('u');
//                            if($idPersonne){
//                                $resltUser ->where('u.idPersonne = :idpersonne')
//                                    ->setParameter('idpersonne', $idPersonne);
//                            }else{
                                $resltUser ->where('u.idPersonne is null');
//                            }
                            $resltUser ->orderBy('u.username', 'ASC');

                            return $resltUser;
                        },
                        'translation_domain' => 'Personne', 'label' => 'personne.from.iduser', 'attr' =>['class' => 'chosen-select']
                    ]);
                }

                ;

            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgPersonne::class,
            'idpersonne' => null,
            'idComite' => false,
            'president' => null
        ]);
    }
}
