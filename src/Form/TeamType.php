<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\Country;
use App\Entity\Player;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Unique;
use Doctrine\ORM\EntityRepository;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255])
                ],
            ])
            ->add('moneyBalance', IntegerType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'placeholder' => 'Select a country',
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
            // ->add('players', EntityType::class, [
            //     'class' => Player::class,
            //     'choice_label' => 'name',
            //     'multiple' => true,
            //     'expanded' => true,
            //     'query_builder' => function (EntityRepository $er) {
            //         return $er->createQueryBuilder('p')
            //             ->leftJoin('p.team', 't')
            //             ->where('t IS NULL')
            //             ->orderBy('p.name', 'ASC');
            //     }
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}