<?php


namespace App\Form;


use App\Entity\tgPersCps;
use App\Entity\TrGenre;
use App\Entity\TrProfil;
use App\Service\Habilitation;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersCpsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            $modif = $options['modif'];
            $profil = $options['profil'];
            $motcles = $options['motcle'];
            $participGroupe = $options['participGroupe'];
         if($motcles) {
             foreach ($motcles as $motcle) {
                 $mots[] = $motcle;
             }
             $motclescps = implode(', ',$mots);

        }else{
             $motclescps = null;
         }
            $form = $event->getForm(); //recuperation du formulaire
            $form
                ->add('idGenre',EntityType::class,[
                    'class' => TrGenre::class,
                    'label' => 'Genre',
                    'placeholder' => 'Sélectionner un genre',
                    'required' => true,
                    'attr'=>['class' => 'chosen-select']
                ])
                ->add('lbNomFr', TextType::class,['attr'=>['maxlength' => 50],'label' => 'Nom'])
                ->add('lbPrenom', TextType::class,['attr'=>['maxlength' => 50],'label' => 'Prénom'])
                ->add('lbWebPerso', TextType::class,['attr'=>['maxlength' => 100],'label' => 'Web Perso', 'required' => false])
                ->add('lbVilleHeberg',TextType::class,['attr'=>['maxlength' => 100],'label' => 'Ville / Région / Pays', 'required' => false])
                ->add('lblangue',ChoiceType::class,['label' => 'Langue', 'required' => false,
                    'choices' => [
                        'Français' => 'Fr',
                        'Anglais' => 'En'
                    ]
                ])
                ->add('lbAdrMail',EmailType::class,['label' => 'Courriel','required' => false])
                ->add('lbOrganisme',TextType::class,['attr'=>['maxlength' => 100],'label' => 'Organisme','required' => false]);
            if(null === $modif){
                $form

                    ->add('groupe',TextType::class,
                        [
                            'attr'=>['maxlength' => 100],
                            'mapped' => false,
                            'label' => 'Thématique (scientifique)',
                            'required' => false,

                        ])

                    ->add('motcle',TextType::class,[
                        'mapped' => false,
                        'label' => 'Mot clé',
                        'required' => false,
                        'help' => 'Mots clé séparé par une virgule.',
                        'attr' => ['maxlength' => 200, 'placeholder' => 'Maillage, Modelling, ....']
                    ])

                    ->add('profil' , EntityType::class, [
                        'class'=>TrProfil::class,
                        'mapped'=> false,
                        'query_builder' => function (EntityRepository $er){
                            $qb =  $er->createQueryBuilder('u')
                                ->where('u.idProfil in (:profil)')
                                ->setParameter('profil', [9,8])
                                ->orderBy('u.idProfil', 'DESC');
                            return $qb;
                        }

                    ]);
            }else{
                $form
                    ->add('groupe',TextType::class,
                        [
                            'attr'=>['maxlength' => 100],
                            'mapped' => false,
                            'label' => 'Groupe',
                            'required' => false,
                            'data' => $participGroupe->getLbGroupe(),
                        ])
                    ->add('motcle',TextType::class,[
                        'mapped' => false,
                        'label' => 'Mot clé',
                        'required' => false,
                        'help' => 'Mots clé séparé par une virgule.',
                        'attr' => ['maxlength' => 200,'placeholder' => 'Maillage, Modelling, ....'],
                        'data' => $motclescps
                    ])

                ->add('profil' , EntityType::class, [
                    'class'=>TrProfil::class,
                    'mapped'=> false,
                    'query_builder' => function (EntityRepository $er) use ($profil){
                        $qb =  $er->createQueryBuilder('u')
                            ->where('u.idProfil in (:profil)');
                        if($profil->getIdProfil() == 4) {
                            $qb->setParameter('profil', [9, 8, 4]);
                        }else {
                            $qb->setParameter('profil', [9, 8]);
                        }
                        $qb->orderBy('u.idProfil', 'DESC');
                        return $qb;
                    },
                        'data' => $profil,
                ]);
            }

        }); // fin addEventListener
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgPersCps::class,
            'modif' => null,
            'motcle'=> null,
            'profil'=> null,
            'participGroupe' => null
        ]);
    }
}