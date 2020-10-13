<?php

namespace App\Form;

use App\Entity\TgAdrMail;
use App\Entity\TgCourriel;
use App\Entity\TrCatModele;
use App\Entity\TrTypeDocument;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TgCourrielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lbDesignation',TextType::class,[
                'label' => 'Désignation'
            ])
            ->add('idCatModele' ,EntityType::class,[
                'class' => TrCatModele::class,
                'label' => 'Catégorie',
                  'attr' => ['required' => 'required','class' => 'chosen-select2']
            ])

            ->add('adrMail',EntityType::class,[
                'class' => TgAdrMail::class,
                'label' => 'Expéditeur',
                'attr' => ['required' => 'required','class' => 'chosen-select2']
            ])
            ->add('copieMail',EntityType::class,[
                    'class' => TgAdrMail::class,
                    'multiple' => true,
                    'mapped' => false,
                    'attr' => ['required' => 'required','class' => 'chosen-select2']
            ]
            )
            ->add('adrEmetteur',HiddenType::class,[
                'label' => 'Emetteur',
                'data'  => 'test@agence.fr'
            ])
//            ->add('dhCreation')
//            ->add('dhEnvoi')
                ->add('lbObjet', TextType::class,[
                    'label' => 'Objet'
            ])
            ->add('lbFormat', ChoiceType::class,[
                'choices'  => [
                    'HTML' => 'HTML',
                    'TEXTE' => 'TEXTE',
                ],
            ])
            ->add('typCourriel')
            ->add('blModifiableCps')
            ->add('modele')
            ->add('destTest')
            ->add('idBalise')

            ->add('lbMessageFr', TextareaType::class,[
//                'config' => [
////                    'toolbar' => 'full',
//                    'extraPlugins' => ['autocomplete','textwatcher']
//                ],
//                'plugins' => [
//                    'autocomplete' => [
//                        'path' => '/bundles/fosckeditor/plugins/autocomplete/',
//                        'filename' => 'plugin.js'
//                    ],
//                    'textWatcher' => [
//                        'path' => '/bundles/fosckeditor/plugins/textwatcher/',
//                        'filename' => 'plugin.js'
//    ]
//
//                ]
            ])
//            ->add('lbMessageEn', CKEditorType::class,[
////                'plugins' => [
////                            'autocomplete' => [
////                            'path' => '/bundles/fosckeditor/plugins/autocomplete/',
////                            'filename' => 'plugin.js'
////                            ]
////                ]
//            ])
            ->add('idTypeDoc', EntityType::class, [
                'class' => TrTypeDocument::class,
                'multiple' => true,
                'expanded' => true,
                'label' => 'Pièces Jointes'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TgCourriel::class,
        ]);
    }
}
