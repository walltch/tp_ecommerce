<?php

namespace App\Form;

use App\Entity\Produit;
use PHPUnit\TextUI\XmlConfiguration\File;
use Symfony\Component\Form\AbstractType;
<<<<<<< Updated upstream
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
=======
use Symfony\Component\Form\Extension\Core\Type\FileType;
>>>>>>> Stashed changes
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('prix')
            ->add('stock')
<<<<<<< Updated upstream
            ->add('photo')
            ->add('save', SubmitType::class, [
                'label' => $options['button_label'], 
            ]);
=======
            ->add('photo', FileType::class, [
                'label' => 'Image (jpg, png)',
                'mapped' => false,
                'required' => false,
            ])

>>>>>>> Stashed changes
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
            'button_label' => 'Save',
        ]);
    }
}
