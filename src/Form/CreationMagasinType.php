<?php

namespace App\Form;

use App\Entity\Magasin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CreationMagasinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du magasin',
                'empty_data' => 'test',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer un nom de magasin.'
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre nom de magasin doit contenir au moins {{ limit }} caractères.',
                        'max' => 40,
                    ])
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Déscription (150 caractères max.)',
                'attr' => array('rows' => '3'),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer une description de magasin.'
                    ]),
                    new Length([
                        'min' => 30,
                        'minMessage' => 'Votre description doit au moins faire {{ limit }} caractères.',
                        'max' => 150,
                    ])
                ],
            ])
            ->add('tel', TextType::class, [
                'label' => 'Numéro de téléphone',
                'constraints' => [
                    new Regex('/0[1-9][0-9]{8}/', 'Veuillez indiquer un numéro de téléphone valide (e.g 0688776655)'),
                    new NotBlank([
                        'message' => 'Veuillez indiquer un numéro de téléphone.'
                    ])
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'Adresse email',
                'constraints' => [
                    new Regex('/[a-z0-9]*@[a-z0-9]*\.[a-z]*/', 'Veuillez indiquer une adresse email valide (e.g utilisateur@domaine.com)'),
                    new NotBlank([
                        'message' => 'Veuillez indiquer une adresse email.'
                    ])
                ],
            ])
            ->add('siren', TextType::class, [
                'label' => 'Numéro SIREN',
                'constraints' => [
                    new Regex('/[0-9]{9}/', 'Veuillez indiquer un numéro SIREN valide (e.g 123456789)'),
                    new NotBlank([
                        'message' => 'Veuillez indiquer un numéro SIREN valide.'
                    ])
                ]
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse postale du magasin',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer une adresse valide.'
                    ])
                ]
            ])
            ->add('typeMagasin', null, [
                'required' => true
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Ajouter une image',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez ajouter une image dans le format suivant : {{ types }}',
                        'maxSizeMessage' => 'Veuillez ajouter une image ne dépassant pas {{ limit }}'
                    ])
                ]
            ])
            ->add('latitude', HiddenType::class)
            ->add('longitude', HiddenType::class)
            ->add('Confirmer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary btn-block',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Magasin::class,
        ]);
    }
}
