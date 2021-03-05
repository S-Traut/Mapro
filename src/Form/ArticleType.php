<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'article',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer un nom d\'article valide.'
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                        'max' => 40,
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description (200 caractères max.)',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer un prénom valide.'
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'La description de l\'article doit contenir au moins {{ limit }} caractères.',
                        'max' => 200,
                    ])
                ]
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix de l\'article (€)',
                'constraints' => [
                    new Positive([
                        'message' => 'Le prix de l\'article ne peut pas être négatif.'
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez indiquer un prénom valide.'
                    ])
                ]
            ])
            ->add('type', null, [
                'label' => 'Type d\'article',
            ])
            ->add('Confirmer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
