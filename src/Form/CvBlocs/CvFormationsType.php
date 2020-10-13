<?php


namespace App\Form\CvBlocs;


use App\Entity\TgCv;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CvFormationsType  extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if($options['valo']) {
            $builder
                ->add('lbValorisation',TextareaType::class,[
                    'label' => false,
                    'attr' => ['style' => 'height: 120px;', 'maxlength'=>1000],
                    'data' => $options['lbValo'],
                    'required' => false
                ]);

        }elseif($options['proj']) {
            $builder
                ->add('lbDistinction', TextareaType::class,[
                    'label' => false,
                    'data' => $options['distinction'],
                    'required' => false,
                    'attr' => ['style' => 'height: 120px;', 'maxlength'=>1000]
                ]);
        }else{
            $value = null;
            $builder
                ->add('dtSoutenanceDeThese', DateType::class, [
                    'widget' => 'single_text',
                    'label' => false,
                    'format' => "dd/mm/yyyy",
                    'data' => $options['dtSoutenance'],
                    'required' => false,
                    'attr' => array(
                        'placeholder' => 'mm/yyyy',
                        'pattern' => '^(((0)[0-9])|((1)[0-2]))(\/)\d{4}$'
                    )

                ])
                ->add('diplomeAcademique', TextareaType::class, [
                    'label' => false,
                    'attr' => ['style' => 'height: 120px;', 'maxlength'=>1000],
                    'data' => $options['diplome'],
                    'required' => false,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgCv::class,
            'diplome' => false,
            'distinction'=>false,
            'valo' => false,
            'lbValo' => false,
            'dtSoutenance' => false,
            'proj' => false

        ]);
    }
}