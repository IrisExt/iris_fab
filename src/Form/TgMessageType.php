<?php

namespace App\Form;

use App\Entity\TgComite;
use App\Entity\TgMessage;
use App\Entity\TgParticipation;
use App\Entity\TgPersonne;
use App\Repository\MessageRepository;
use App\Repository\PersonneRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TgMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {

            $comiteObject = $options['comiteObject'];
            $personne = $options['personne'];
            $appel = $options['appel'];
            $participant = $options['participant'];  // variable envoyé  depuis la liste des memebres
            $message = $event->getData(); //recuperation de l'objet TgMessage sur lequel le formulaire se base
            $form = $event->getForm(); //recuperation du formulaire

            $form

                ->add('emetteur', EntityType::class, [
                    'class' => TgPersonne::class,
                    'query_builder' => function (EntityRepository $er) use ($personne) {
                        return $er->createQueryBuilder('p')
                            ->where('p.idPersonne = :idpersonne')
                            ->setParameter('idpersonne', $personne);
                    },
                    'label' => false,
                    'attr' => ['class' => 'hidden'],
                ]);
                if($participant){
            $form
                    ->add('idParticipation', EntityType::class, [
                        'class'=>TgParticipation::class,
                        'query_builder' => function (EntityRepository $er) use ($participant){
                            $qb =$er->createQueryBuilder('p')
                                ->where('p.idParticipation = :participant')
                                ->setParameter('participant', $participant);
                               return $qb;
                        },
                        'label' => false,
                         'attr' => ['class' => 'hidden'],
                    ])
                ->add('destinataire', EntityType::class, [
                    'class' => TgPersonne::class,
                    'query_builder' => function (EntityRepository $er) use ($personne) {
                        return $er->createQueryBuilder('p')
                            ->where('p.idPersonne = :pers')
                            ->setParameter('pers', $personne);
                    },
                    'label' => false,
                    'attr' => ['class' => 'hidden'],
                ]);
                }else {
                    $form
                        ->add('destinataire', EntityType::class, [
                            'class' => TgPersonne::class,
                            'query_builder' => function (EntityRepository $er) use ($comiteObject, $personne, $appel, $participant) {
                                return $er->listMsgCmt($comiteObject, $personne, $appel, $participant);
                            },
                            'choice_label' => function ($personne) {
                                return $personne->getLbNomUsage() . ' ' . $personne->getLbPrenom() . ' ( ' . $personne->getIdHabilitation()[0]->getIdProfil() . ' )';
                            }
                        ]);
                };
               $form ->add('texte', TextareaType::class, ['label' => 'Message' ,'help' => '(100 caractères maximum)','attr' => ['style' => 'background-color:#fbf7f7','maxlength' => 100, ]]);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgMessage::class,
            'messagecomite' => false,
            'personne' => false,
            'comiteObject' => false,
            'participant' => false,
            'appel' => false
        ]);
    }
}
