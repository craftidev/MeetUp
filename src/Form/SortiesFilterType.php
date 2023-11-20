<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use App\Entity\Sortie;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class SortiesFilterType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('campus', TextType::class)
            ->add('name_search', TextType::class)
            ->add('range_start', DateType::class)
            ->add('range_end', DateType::class)
            ->add('i_am_organisateur', CheckboxType::class)
            ->add('i_am_subscribed', CheckboxType::class)
            ->add('i_am_not_subscribed', CheckboxType::class)
            ->add('show_closed_sorties', CheckboxType::class)
        ;
    }
}
