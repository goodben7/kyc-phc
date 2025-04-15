<?php

namespace App\State;

use App\ApiResource\StatResource;
use App\Model\StatResourceModel;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\FunctionTitleRepository;

class StatFunctionProvider  implements ProviderInterface
{
    public function __construct(
        private  FunctionTitleRepository $repository
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $functionStats = $this->repository->countFunctionTitle();

        $functionModel = [];

        $functionModel = array_map(
            fn ($key, $value) => new StatResourceModel($key, $value),
            array_keys($functionStats),
            $functionStats
        );

        return new StatResource(
            null,
            $functionModel
        );
 
    }

}