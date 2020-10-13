<?php


namespace App\Form\CvBlocs;

use App\Entity\TgCompetenceLangue;
use App\Entity\TgPersonne;
use App\Entity\TrLangue;
use App\Entity\TrNiveauLangue;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TgCompetenceLangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $langues = $options['langue_add'];
        $langueUpdate = $options['langue_update'];
        if (!empty($langues)) {
            $builder
                ->add('idLangue', EntityType::class, [
                    'class' => TrLangue::class,
                    'label' => 'Langue',
                    'query_builder' => function (EntityRepository $er) use ($langues) {
                        $qb = $er->createQueryBuilder('u')
                            ->where('u.idLangue in (:compLangue)')
                            ->setParameter('compLangue', $langues);
                        return $qb;
                    },
                    'attr' => ['required' => 'required','class' => 'chosen-select']
                ]);
        };
        if($langueUpdate){
            $builder
                ->add('idLangue', EntityType::class, [
                    'class' => TrLangue::class,
                    'label' => 'Langue',
                    'query_builder' => function (EntityRepository $er) use ($langues, $langueUpdate) {
                        $qb = $er->createQueryBuilder('u')
                            ->where('u.idLangue in (:compLangue)')
                            ->orWhere('u.idLangue = :langue_')
                            ->setParameter('compLangue', $langues)
                            ->setParameter('langue_', $langueUpdate);
                        return $qb;
                    },
                    'attr' => ['required' => 'required','class' => 'chosen-select2']
                ]);
        };
        $builder
            ->add('niveauEcrit', EntityType::class, [
                'class' => TrNiveauLangue::class,
                'label' => 'Ecrit',
                 'attr' => ['required' => 'required','class' => 'chosen-select']
            ])
            ->add('niveauLu', EntityType::class, [
                'class' => TrNiveauLangue::class,
                'label' => 'Lu',
                'attr' => ['required' => 'required','class' => 'chosen-select']
            ])
            ->add('niveauParle', EntityType::class, [
                'class' => TrNiveauLangue::class,
                'label' => 'Parlé',
                'attr' => ['required' => 'required','class' => 'chosen-select']
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgCompetenceLangue::class,
            'langue_add' => false,
            'langue_update' => false,


        ]);
    }

}