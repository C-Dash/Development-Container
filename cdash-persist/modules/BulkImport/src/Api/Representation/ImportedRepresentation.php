<?php declare(strict_types=1);

namespace BulkImport\Api\Representation;

use Omeka\Api\Representation\AbstractEntityRepresentation;
use Omeka\Api\Representation\JobRepresentation;

class ImportedRepresentation extends AbstractEntityRepresentation
{
    public function getJsonLd()
    {
        return [
            'o:job' => $this->job()->getReference(),
            'entity_id' => $this->entityId(),
            'entity_name' => $this->entityName(),
        ];
    }

    public function getJsonLdType()
    {
        return 'o-module-bulk:Imported';
    }

    public function job(): JobRepresentation
    {
        return $this->getAdapter('jobs')
            ->getRepresentation($this->resource->getJob());
    }

    public function entityId(): int
    {
        return $this->resource->getEntityId();
    }

    public function entityName(): string
    {
        return $this->resource->getEntityName();
    }
}
