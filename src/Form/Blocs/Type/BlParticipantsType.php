<?php

namespace App\Form\Blocs\Type;

use App\Entity\TgMcCes;
use App\Entity\TgPartenariat;
use App\Entity\TgPersonne;
use App\Entity\TgProjet;
use App\Entity\TrCivilite;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class BlParticipantsType
 * @package App\Form\Blocs\Type
 */
class BlParticipantsType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * BlMotCleCesType constructor.
     * @param EntityManagerInterface $em
     * @param SessionInterface $session
     * @param TranslatorInterface $translator
     */
    public function __construct(EntityManagerInterface $em, SessionInterface $session, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->session = $session;
        $this->translator = $translator;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('resp', CheckboxType::class, [
                    'label' => 'bloc.form.blparticipants.respsc',
                    'mapped' => false,
                    'required'=>false,
                    'translation_domain' => 'Blocs',
                    'data' => true,
                    'attr' => ['class' => 'hidden_'.$options['hidden']['resp']],
                    'disabled' => $options['op_disabled']['resp']
                ])
            ->add('civ', EntityType::class, [
                'class' => TrCivilite::class,
                'mapped' => false,
                'label' => false,
                'required'=>false,
                'expanded'=>true,
                'multiple'=>false,
                'placeholder'=>false,
                'translation_domain' => 'Blocs',
                'attr' => ['class' => 'gender hidden_'.$options['hidden']['civ']],
                'disabled' => $options['op_disabled']['civ']
            ])
            ->add('lbNomUsage', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.blparticipants.nom',
                'required' => true,
                'attr' => ['class' => 'hidden_'.$options['hidden']['nom']],
                'disabled' => $options['op_disabled']['nom']
            ])
            ->add('lbPrenom', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.blparticipants.prenom',
                'required' => true,
                'attr' => ['class' => 'hidden_'.$options['hidden']['prenom']],
                'disabled' => $options['op_disabled']['prenom']
            ])
            ->add('adrMail', EmailType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.blparticipants.email',
                'required' => true,
                'attr' => ['class' => 'hidden_'.$options['hidden']['email'], 'placeholder' => 'Mettre une adresse mail valide'],
                'disabled' => $options['op_disabled']['email']
            ])
            ->add('orcid', TextType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'label' => 'bloc.form.blparticipants.orcid',
                'required' => false,
                'attr' => ['class' => 'hidden_'.$options['hidden']['orcid'], 'minlength' => 16, 'maxlength' => 16],
                'disabled' => $options['op_disabled']['orcid']
            ])
            ->add('idPartenaire', HiddenType::class, [
                'mapped' => false,
                'translation_domain' => 'Blocs',
                'required' => false
            ])
        ;

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
        return 'BlParticipantsType';
    }
}