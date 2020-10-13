<?php


namespace App\Form\Blocs\Type;


use App\Entity\TgProjet;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class BlPartenariatType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // ...

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
        return 'BlPartenariatType';
    }

}