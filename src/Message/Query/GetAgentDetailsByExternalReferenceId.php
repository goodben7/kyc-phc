<?php

namespace App\Message\Query;

class GetAgentDetailsByExternalReferenceId  implements QueryInterface
{
    public function __construct(
        public string $externalReferenceId,
    )
    {
    }
}

