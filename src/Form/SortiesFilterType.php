<?php

namespace App\Form;

use App\Entity\Campus;
use Symfony\Component\Form\AbstractType;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class SortiesFilterType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'required' => false
            ])
            ->add('name_search', TextType::class, ['required' => false])
            ->add('range_start', DateType::class, [
                'required' => false,
                'widget' => 'single_text'
            ])
            ->add('range_end', DateType::class, [
                'required' => false,
                'widget' => 'single_text'
            ])
            ->add('i_am_organisateur', CheckboxType::class, ['required' => false])
            ->add('i_am_subscribed', CheckboxType::class, ['required' => false])
            ->add('i_am_not_subscribed', CheckboxType::class, ['required' => false])
            ->add('show_closed_sorties', CheckboxType::class, ['required' => false])
        ;
    }
}
