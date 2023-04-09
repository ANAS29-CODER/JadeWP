<?php

declare(strict_types=1);

namespace org\wplake\acf_views;

use org\wplake\acf_views\AcfView\AcfViews;

defined('ABSPATH') || exit;

abstract class AcfGroup extends \org\wplake\acf_views\vendors\LightSource\AcfGroups\AcfGroup
{
    const GROUP_NAME_PREFIX = 'local_' . AcfViews::NAME . '_';
}
