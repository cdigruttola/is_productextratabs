<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Installer;

interface ActionDatabaseInterface
{
    public function execute();

    public function buildQuery();

    public function setData(array $data);
}
