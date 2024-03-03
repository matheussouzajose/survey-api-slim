<?php

declare(strict_types=1);

namespace Survey\Infrastructure\Persistence\MongoDb\Helpers;

class QueryBuilder
{
    private array $query;

    private function addStep(string $step, array $data): QueryBuilder
    {
        $this->query[] = [$step => $data];

        return $this;
    }

    public function match(array $data): QueryBuilder
    {
        return $this->addStep('$match', $data);
    }

    public function group(array $data): QueryBuilder
    {
        return $this->addStep('$group', $data);
    }

    public function sort(array $data): QueryBuilder
    {
        return $this->addStep('$sort', $data);
    }

    public function unwind(array $data): QueryBuilder
    {
        return $this->addStep('$unwind', $data);
    }

    public function lookup(array $data): QueryBuilder
    {
        return $this->addStep('$lookup', $data);
    }

    public function project(array $data): QueryBuilder
    {
        return $this->addStep('$project', $data);
    }

    public function set(array $data): QueryBuilder
    {
        return $this->addStep('$set', $data);
    }

    public function build(): array
    {
        return $this->query;
    }
}
