<?php


namespace App\Form;


use Alsatian\FormBundle\Form\ExtensibleEntityType;
use App\Entity\TgAppelProj;


use App\Entity\TgPersonne;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class AppelProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pilote = $options['pilote'];
        $builder
            ->add('dtMillesime',TextType::class,[
                'translation_domain' => 'AppelProjet',
                'label' => 'appel.form.edition',
                'attr' => ['maxlength' => 4, 'pattern' => "[0-9]{4}", 'placeholder' => 'YYYY'],
                'required'   => true,
            ])
            ->add('lbAcronyme',TextType::class,[
                'translation_domain' => 'AppelProjet',
                'label' => 'appel.form.lbacronyme',
            ])
            ->add('lbAppel',TextType::class,[
                'translation_domain' => 'AppelProjet',
                'label' => 'appel.form.titre',
            ])

            ->add('dtCloFin',null,[
                'translation_domain' => 'AppelProjet',
                'label' => 'appel.form.dtclofin',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datapicker'
                ]
            ])
            ->add('pilote', Select2EntityType::class, [
                'class' => TgPersonne::class,
                'translation_domain' => 'AppelProjet',
                'primary_key' => 'idPersonne',
                'remote_route' => 'set_ajax_personne',
                'label' => 'appel.form.pilote',
                'required' => true,
            ])

            ->add('nbPhase',IntegerType::class, [
                'label' => 'Nombre de Phase',
                'attr' => ['min' => 1, 'max' => 4],
                'help' => 'nombre de phase de l\'appel',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgAppelProj::class,
            'pilote' => null,
        ]);
    }


}