<?php

namespace App\Contracts;

interface BaseInterface
{
    public function makeModel();

    public function get(int $modelId);

    public function findInSet(string $searchField, int $searchValue, string $sortOn, string $sortOrder);

    public function getPluck(string $field1, string $field2, ?string $whereKey = null, $whereValue = null);

    public function getPluckWhereIn(string $field1, string $field2, ?string $whereKey = null, ?array $whereValue = null);

    public function getIdOfNullRelation(array $modelIds, string $relation);

    public function updateOrCreate(array $data, $model = null, $isPassword = false);

    public function withFind($relations, $modelIds);

    public function insertData(array $data);

    public function updateData(array $data, $modelId);

    public function findWhereNotNull(string $column);

    public function getQueryBuilder(?string $search, string $sortOn, string $sortOrder);

    public function bulkDelete(array $requestData);

    public function bulkDeleteRelation(array $modelIds, string $relation);

    public function bulkDeleteDependancy(array $requestData, string $relation, string $modelName);

    public function getLastSortId(string $columnName);

    public function bulkUpdate(string $columnName, array $requestData);

    public function count();
}
