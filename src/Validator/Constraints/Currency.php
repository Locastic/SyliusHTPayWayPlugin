<?php

namespace Locastic\SyliusHTPayWayPlugin\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

final class Currency extends Constraint
{
    public $message;

    public function validatedBy(): string
    {
        return 'locastic_sylius_ht_payway_plugin_currency';
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
