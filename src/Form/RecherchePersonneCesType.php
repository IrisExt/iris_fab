<?php

namespace App\Form;

use App\Entity\RecherchePersonneCes;
use App\Entity\TgMotCleCps;
use App\Entity\TgOrganisme;
use App\Entity\TgPersCps;
use App\Entity\TgPersonne;
use App\Entity\TrGenre;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class RecherchePersonneCesType extends AbstractType
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

            $comite = $options['comite'];

            $builder
                ->add('genre',EntityType::class,[
                    'class' => TrGenre::class,
                    'label' => 'Genre',
                    'placeholder' => 'Sélectionner un genre',
                    'required' => false,
                    'attr'=>['class' => 'chosen-select']
                ])
                ->add('personne', EntityType::class, [
                    'class' => TgPersonne::class,
                    'query_builder' => function (EntityRepository $er) use ($comite) {
                        $resltUser = $er->createQueryBuilder('p')
                            ->join('p.idPersCps', 'pc')
                            ->where('p.idPersCps is not null');
                        return $resltUser;
                    },
                    'required' => false,
                    'attr' => ['class' => 'chosen-select'],
                    'label' => 'Nom',
                    'choice_label' => function ($personne) {
                        return $personne->getLbNomUsage();
                            }
                ])
                ->add('email', EntityType::class, [
                    'class' => TgPersonne::class,
                    'query_builder' => function (EntityRepository $er) use ($comite) {
                        $resltUser = $er->createQueryBuilder('p')
                            ->join('p.idPersCps', 'pc')
                            ->where('p.idPersCps is not null');
                        return $resltUser;
                    },
                    'required' => false,
                    'attr' => ['class' => 'chosen-select'],
                    'label' => 'Email',
                    'choice_label' => function ($personne) {
                        return $personne->getIdPersCps()->getLbadrMail();
                    }
                ])
                ->add('motcle', EntityType::class, [
                    'class' => TgMotCleCps::class,
                    'required' => false,
                    'attr' => ['class' => 'chosen-select'],
                    'label' => 'Mot Clé'
                ])
                ->add('organisme');

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecherchePersonneCes::class,
            'method' => 'get',
            'csrf_protection' => false,
            'comite' => null

        ]);
    }

    public function organismeAll(){
        $organismes = $this->em->getRepository(TgPersCps::class)->oragnismePrsCps();

        foreach ($organismes as $key => $organisme){

            $listOrga[] = $organisme['lbOrganisme'];

        }

        return $listOrga;
    }


    public function getBlockPrefix()
    {
        return '';
    }
}
