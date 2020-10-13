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

class BlAnnexePrepropositionType extends AbstractType
{

    function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lbAnnexePreproposition', FileType::class, [
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.docsc.file',
                'data_class' => null,
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['lbAnnexePreproposition']],
                'disabled' => $options['op_disabled']['lbAnnexePreproposition']
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
        return 'BlAnnexePrepropositionType';
    }
}