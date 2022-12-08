<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Categorieannonce;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titrea')
            ->add('descriptiona',TextareaType::class)
            ->add('prixa')
            ->add('datecreationa', DateType::class, [
                'widget' => 'choice',
                'input'  => 'string'

            ])
            ->add('imagea',FileType::class, array('data_class' => null))
            ->add('user')
            ->add('categorieannonce',EntityType::class,[

                'class'=> Categorieannonce::class,
                'choice_label'=>'NomCat',
            ])
            ->add('Ajouter',SubmitType::class)
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'homepage',
                'locale' => 'de',
            ]);


    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {

        $resolver->setDefaults([
            'data_class' => Annonce::class,
            "allow_extra_fields" => true
        ]);
    }
}
