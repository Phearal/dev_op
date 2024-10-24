<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('introduction')
            ->add('image', FileType::class, [
                'mapped' => false, // Indique que ce champ n'est pas mappé directement à une propriété de l'entité Article
                // 'required' => false, // Le champ n'est pas obligatoire
                // 'constraints' => [
                //     new File([
                //         'maxSize' => '1024k',
                //         'mimeTypes' => [
                //             'application/jpg',
                //             'application/jpeg',
                //             'application/webp',
                //             'application/png',
                //         ],
                //         'mimeTypesMessage' => 'Image invalide, formats autorisés : jpg, jpeg, png, webp',
                //     ])
                // ]
            ])
            ->add('content', HiddenType::class, [
                'attr' => ['id' => 'content']
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name', // Le champ à afficher pour chaque option
                'multiple' => true, // Permet de sélectionner plusieurs tags
                'expanded' => false, // Affiche les tags sous forme de cases à cocher
                'by_reference' => false, // Dis à Doctrine de ne pas modifier la table article mais bien tag_article
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
