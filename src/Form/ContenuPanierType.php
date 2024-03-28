<?php

namespace App\Form;

use App\Entity\ContenuPanier;
use App\Entity\Panier;
use App\Entity\Produit;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContenuPanierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite', null, [
                'data' => 1, 
            ])
            ->add('date', DateTimeType::class, [
                'data' => new \DateTime(), 
                'widget' => 'single_text',
            ])
            ->add('produit', EntityType::class, [
                'class' => Produit::class,
'choice_label' => 'nom',
            ])
            ->add('panier', EntityType::class, [
                'class' => Panier::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContenuPanier::class,
        ]);
    }
}
