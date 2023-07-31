<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Hook;

interface HookInterface
{
    public function execute(array $params);
}
