<?php

namespace App\Form;

use App\Entity\TgAppelProj;
use App\Entity\TgComite;

use App\Entity\TgParticipation;
use App\Entity\TgPersonne;
use App\Entity\TrDepartement;

use App\Repository\PersonneRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ComiteType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $appel = $options['appel'];

        $builder

            ->add('idAppel', EntityType::class,
                [
                    'class'=> TgAppelProj::class,
                    'query_builder' => function (EntityRepository $er) use ($appel) {
                        $result = $er->createQueryBuilder('u')
                            ->where('u.idAppel = :appel')
                            ->setParameter('appel', $appel);
                        return $result;
                    },
                    'label' => false,
//                    'translation_domain' => 'Comites',
//                    'label' => 'comite.form.appelprojet',
                    'attr' => ['class' => 'hidden'],
//                    'required' => true
                ])
            ->add('lbAcr', TextType::class, [
                'translation_domain' => 'Comites',
                'label' => 'comite.form.acron',
                'attr' => ['class' => 'form-controle','maxlength' => 10],

            ])
            ->add('lbTitre', TextType::class, ['translation_domain' => 'Comites', 'label' => 'comite.form.text', 'attr' => ['class' => 'form-controle','maxlength' => 255]])
            ->add('lbDesc', TextareaType::class, ['translation_domain' => 'Comites', 'label' => 'comite.form.desc','attr' => ['class' => 'form-controle','maxlength' => 1000]])
            ->add('iddepartement', EntityType::class, [
                'class' => TrDepartement::class,
                'multiple' => true,
                'translation_domain' => 'Comites',
                'label' => 'comite.form.departement',
                'attr' => ['class' => 'chosen-select', 'requierd' => true],

            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) { // quand y'a une modification utilisation de l'eventListner


            $participation = $event->getData()->getIdHabilitation(); // En modification l'objet Participation est envoyé avec comité PersistentCollection
            if ($participation) {
                foreach ($participation as $per) {
                    // president
                    if ($per->getIdProfil()->getIdProfil() == 4) {
                        $president = $per->getIdPersonne();
                    }
                    //CPS Principaux
                    if ($per->getIdProfil()->getIdProfil() == 6) {
                        $cpsP[] = $per->getIdPersonne();
                    }
                    //CPS Secondaires
                    if ($per->getIdProfil()->getIdProfil() == 7) {
                        $cpsS[] = $per->getIdPersonne();
                    }
                };

                $form = $event->getForm();

                $form->add('president', EntityType::class, [
                    'mapped' => false,
                    'class' => TgPersonne::class,
                    'query_builder' => function (PersonneRepository $er){
                        return $er->createQueryBuilder("p")
                            ->where('p.idPersCps is not null');
//                                 ->where("u.description LIKE '%test%'");
                        },
                    'data' => $president ?? '',
                    'translation_domain' => 'Comites',
                    'label' => 'comite.form.presi',
                    'attr' => ['class' => 'chosen-select',],
                    'required' => false
                ])
                    ->add('cpsprincipal', EntityType::class, [
                        'mapped' => false,
                        'class' => TgPersonne::class,
                        'data' => $cpsP ?? array(),
                        'translation_domain' => 'Comites',
                        'label' => 'comite.form.cpsPrin',
                        'multiple' => true,
                        'attr' => ['class' => 'chosen-select'],
                        'required' => true
                    ])
                    ->add('cpsSecondaire', EntityType::class, [
                            'mapped' => false,
                            'class' => TgPersonne::class,
                            'data' => $cpsS ?? array(),
                            'translation_domain' => 'Comites',
                            'label' => 'comite.form.cpsSecondaire',
                            'multiple' => true,
                            'attr' => ['class' => 'chosen-select',],
                            'required' => false
                        ]
                    );
            };

//            if (null === $options['data']->getIdComite()) { // si une entity est envoyée  pour modification on affiche pas les champs
//
//                $builder->add('president', EntityType::class, [
//                    'mapped' => false,
//                    'class' => TgPersonne::class,
//                    'translation_domain' => 'Comites',
//                    'label' => 'comite.form.presi',
//                    'attr' => ['class' => 'chosen-select',],
//                    'required' => false
//                ])
//                    ->add('cpsprincipal', EntityType::class, [
//                        'mapped' => false,
//                        'class' => TgPersonne::class,
//                        'translation_domain' => 'Comites',
//                        'label' => 'comite.form.cpsPrin',
//                        'multiple' => true,
//                        'attr' => ['class' => 'chosen-select',],
//                        'required' => false
//                    ])
//                    ->add('cpsSecondaire', EntityType::class, [
//                            'mapped' => false,
//                            'class' => TgPersonne::class,
//                            'translation_domain' => 'Comites',
//                            'label' => 'comite.form.cpsSecondaire',
//                            'multiple' => true,
//                            'attr' => ['class' => 'chosen-select',],
//                            'required' => false
//                        ]
//
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgComite::class,
            'appel' => null
        ]);
    }
}