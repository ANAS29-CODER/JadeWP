<?php

declare(strict_types=1);

namespace org\wplake\acf_views\pro;

defined('ABSPATH') || exit;

class Acf extends \org\wplake\acf_views\Acf
{
    public function getGroupedFieldTypes(): array
    {
        return array_merge_recursive(parent::getGroupedFieldTypes(), [
            'layout' => [
                'repeater',
            ],
        ]);
    }
}
