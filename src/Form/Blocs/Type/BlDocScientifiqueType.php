<?php


namespace App\Form\Blocs\Type;


use App\Entity\TgProjet;
use App\Entity\TrLangue;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

/**
 * Class BlDocScientifiqueType
 *
 * @package App\Form\Blocs\Type
 */
class BlDocScientifiqueType extends AbstractType
{

    function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lbLangue',  EntityType::class, [
                'class' => TrLangue::class,
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.docsc.inflang',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['lbLangue']],
                'disabled' => $options['op_disabled']['lbLangue']
            ])
            ->add('lbPreproposition',  FileType::class, [
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.docsc.file',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['lbPreproposition']],
                'disabled' => $options['op_disabled']['lbPreproposition']
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgProjet::class,
            'op_readonly' =>  ['readonly' => false],
            'op_disabled' =>  false,
            'hidden' =>  0,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'BlDocScientifiqueType';
    }
}