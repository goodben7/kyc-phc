<?php
namespace App\Model;

use Symfony\Component\Serializer\Annotation\Groups;


class AgentFilterInterface
{
    #[Groups(['export_agent:post'])]
    public ?string $site = null;

    #[Groups(['export_agent:post'])]
    public ?string $category = null;

    #[Groups(['export_agent:post'])]
    public ?string $functionTitle = null;

    #[Groups(['export_agent:post'])]
    public ?string $affectedLocation = null;

    #[Groups(['export_agent:post'])]
    public ?string $division = null;
}