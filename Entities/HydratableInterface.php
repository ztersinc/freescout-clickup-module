<?php

namespace Modules\ClickupIntegration\Entities;

interface HydratableInterface
{
    public static function hydrate(array $data);
}