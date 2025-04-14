<?php

namespace App\State;

use App\ApiResource\StatResource;
use App\Model\StatResourceModel;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\SiteRepository;

class StatSiteProvider  implements ProviderInterface
{
    public function __construct(
        private  SiteRepository $repository
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $siteStats = $this->repository->countSite();

        $siteModel = [];

        $siteModel = array_map(
            fn ($key, $value) => new StatResourceModel($key, $value),
            array_keys($siteStats),
            $siteStats
        );

        return new StatResource(
            null,
            $siteModel
        );
 
    }

}