<?php

declare(strict_types=1);

namespace Locastic\SyliusHTPayWayPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class HTPayWayOffsiteGatewayConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'sandbox',
                ChoiceType::class,
                [
                    'choices' => [
                        'locastic.sylius_ht_payway_plugin.form.no' => 0,
                        'locastic.sylius_ht_payway_plugin.form.yes' => 1,
                    ],
                    'label' => 'locastic.sylius_ht_payway_plugin.form.sandbox',
                ]
            )
            ->add(
                'shop_id',
                TextType::class,
                [
                    'label' => 'locastic.sylius_ht_payway_plugin.form.shop_id',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'locastic.sylius_ht_payway_plugin.form.shop_id_not_blank',
                                'groups' => ['sylius'],
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'secret_key',
                TextType::class,
                [
                    'label' => 'locastic.sylius_ht_payway_plugin.form.secret_key',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'locastic.sylius_ht_payway_plugin.form.secret_key_not_blank',
                                'groups' => ['sylius'],
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'authorization_type',
                ChoiceType::class,
                [
                    'choices' => [
                        'locastic.sylius_ht_payway_plugin.form.no' => 1,
                        'locastic.sylius_ht_payway_plugin.form.yes' => 0,
                    ],
                    'label' => 'locastic.sylius_ht_payway_plugin.form.authorization_type',
                ]
            )
            ->add(
                'disable_installments',
                ChoiceType::class,
                [
                    'choices' => [
                        'locastic.sylius_ht_payway_plugin.form.no' => 0,
                        'locastic.sylius_ht_payway_plugin.form.yes' => 1,
                    ],
                    'label' => 'locastic.sylius_ht_payway_plugin.form.disable_installments',
                ]
            );
    }
}