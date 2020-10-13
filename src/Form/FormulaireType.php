<?php


namespace App\Form;


use App\Entity\TgAppelProj;
use App\Entity\TgFormulaire;

use App\Entity\TrClasseFormulaire;
use App\Entity\TrNiveau;
use App\Entity\TrPhase;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class FormulaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('idAppel', EntityType::class,[
//            'class' => TgAppelProj::class,
//            'label' => 'Appel à projet :',
//            'placeholder'  => 'Choisir un appel à projet',
//           'attr' => ['class' => 'chosen-select', 'requierd' => true],
//        ])
            ->add('lbformulaire', TextType::class, [
                'label' => 'Libellé Formulaire :',
                'attr' => ['placeholder' =>'nom du formulaire']
            ])
            ->add('idClasseFormulaire', EntityType::class,[
                'class' => TrClasseFormulaire::class,
                'label' => 'Classe formulaire:',
                'placeholder' => 'Choisir la classe formulaire',
                'attr' => ['class' => 'chosen-select','requierd' => true,'id' => "idselect" , 'onchange' => 'Javascript:changerSelect();'],
        ])
       ;
    }
//
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgFormulaire::class,
        ]);
    }
}