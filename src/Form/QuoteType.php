<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Category;

class QuoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add('quote', TextType::class, array(
                'label' => false
            ))
            ->add('meta', TextType::class, array(
                'label' => false
            ))
            ->add('category', EntityType::class, array("class" => Category::class, "choice_label" => 'name', 'label' => false))
            ->add('submit', SubmitType::class);
    }
}
