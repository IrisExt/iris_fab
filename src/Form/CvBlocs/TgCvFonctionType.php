<?php


namespace App\Form\CvBlocs;


use App\Entity\TgCv;
use App\Entity\TrFonction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TgCvFonctionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $tgidExterne = $options['tgidExterne'];


        $builder
            ->add('idFonction', EntityType::class,[
                'class'=> TrFonction::class,
                'label' => 'Fonction',
            ])
            ->add('orcid', TextType::class,[
                'mapped' => false,
                'label' => 'Identifiant ORCID',
                'help' => 'Ex. xxxxxxxxxxxxxxxx',
                'required' => false,
                'data' => $options['orcid'],
                'attr' => [
                    'pattern' => '([A-Za-z0-9]{16})',
                    'maxlength'=> 16,
                    'placeholder' => 'xxxxxxxxxxxxxxxx'
                ]
            ])
            ->add('researchID', TextType::class,[
                'help' => 'Ex. A-0000-0000',
                'label' => 'ResearchID',
                'attr' => [   'pattern' => '([A-Z]{1})-([0-9]{4})-([0-9]{4})', 'maxlength'=> 11 ,  'placeholder' => 'A-0000-0000'],
                'data' => $options['researchID'],
                'mapped' => false,
                'required' => false,

            ])
            ->add('idHal', TextType::class,[
                'help' => 'Ex. xxx-XXX-XXxx',
                'mapped' => false,
                'label' => 'idHal',
                'attr' => ['pattern' => '(^[a-zA-Z-]*$)','placeholder' => 'xxx-XXX-XXxx'],
                'data' => $options['idHal'],
                'required' => false,

            ])
            ->add('idRef', TextType::class,[
                'mapped' => false,
                'help' => 'Ex. xxx-XXX-XXxx',
                'label' => 'IdRef',
                'attr' => ['pattern' => '(^[a-zA-Z-]*$)','placeholder' => 'xxx-XXX-XXxx'],
                'data' => $options['idRef'],
                'required' => false,

            ])
            ->add('idChercheur', TextType::class,[
                'label' => 'Autres identifiants',
                'required' => false,
            ])
       ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgCv::class,
            'orcid' =>null,
            'researchID' =>null,
            'idHal' =>null,
            'idRef' =>null,


        ]);
    }
}