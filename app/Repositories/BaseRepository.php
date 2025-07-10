<?php

namespace App\Repositories;

use App\Contracts\BaseInterface;
use App\Exceptions\RepositoryException;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class BaseRepository implements BaseInterface
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Model
     */
    protected $model;

    public function __construct()
    {
        $this->app = new Application;
        $this->makeModel();
    }

    /**
     * @return Model
     *
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (! $model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    public function get(int $modelId)
    {
        return $this->model->whereId($modelId)->first();
    }

    public function findInSet(string $searchField, int $searchValue, string $sortOn, string $sortOrder)
    {
        return $this->model->where('status', 1)
            ->whereRaw('FIND_IN_SET(?, '.$searchField.') > 0', [$searchValue])
            ->orderBy($sortOn, $sortOrder)
            ->get();
    }

    public function getPluck(string $field1, string $field2, ?string $whereKey = null, $whereValue = null)
    {
        return $this->model->select($field1, $field2)
            ->when(($whereKey !== null && $whereValue !== null), function ($query) use ($whereKey, $whereValue) {
                $query->where($whereKey, $whereValue);
            })
            ->pluck($field2, $field1);
    }

    public function getPluckWhereIn(string $field1, string $field2, ?string $whereKey = null, ?array $whereValue = null)
    {
        $query = $this->model->select($field1, $field2)
            ->when(($whereKey !== null && $whereValue !== null), function ($subQuery) use ($whereKey, $whereValue) {
                $subQuery->whereIn($whereKey, $whereValue);
            });
        if ($field1 == $field2) {
            return $query->pluck($field1);
        }

        return $query->pluck($field1, $field2);
    }

    public function getIdOfNullRelation(array $modelIds, string $relation)
    {
        return $this->model->select('id')->whereIn('id', $modelIds)->whereDoesntHave($relation)->get();
    }

    public function updateOrCreate(array $data, $model = null, $isPassword = false)
    {
        if ($model === null) {
            $model = $this->model;

            if ($isPassword) {
                $model->password = Hash::make($data['password']);
                unset($data['password']);
            }
        }
        $modelFill = $model->getFillable();

        $modelData = array_filter(
            $data,
            function ($key) use ($modelFill) {
                return in_array($key, $modelFill) >= 0;
            },
            ARRAY_FILTER_USE_KEY
        );
        $model->fill($modelData);
        $model->save();

        return $model;
    }

    public function withFind($relations, $modelIds)
    {
        return $this->model->with($relations)->find($modelIds);
    }

    public function insertData(array $data)
    {
        return $this->model->insert($data);
    }

    public function updateData(array $data, $modelId)
    {
        return $this->model->where('id', $modelId)->update($data);
    }

    public function findWhereNotNull(string $column)
    {
        return $this->model->whereNotNull($column)->get();
    }

    public function getQueryBuilder(?string $search, string $sortOn, string $sortOrder)
    {
        $queryBuilder = $this->model->when($search !== null, function ($query1) use ($search) {
            $query1->where(function ($query2) use ($search) {
                foreach ($this->model::SEARCH_FIELDS as $field) {
                    $query2->orWhere($field, 'LIKE', '%'.$search.'%');
                }
            });
        })
            ->orderBy($sortOn, $sortOrder);

        return $queryBuilder;
    }

    public function bulkDelete(array $requestData)
    {
        $modelIds = json_decode($requestData['data_id']);
        $this->model->whereIn('id', $modelIds)->delete();

        return ['status' => true, 'message' => __('message.deleteSuccess')];
    }

    public function bulkDeleteRelation(array $modelIds, string $relation)
    {
        return $this->model->whereIn('id', $modelIds)->whereDoesntHave($relation)->delete();
    }

    public function bulkDeleteDependancy(array $requestData, string $relation, string $modelName)
    {
        $status = false;
        $message = __('message.oopsError');
        $modelIds = json_decode($requestData['data_id']);
        $modelData = $this->getIdOfNullRelation($modelIds, $relation);

        if (count($modelIds) != count(data_get($modelData->toArray(), '*.id'))) {
            $this->bulkDeleteRelation($modelIds, $relation);
            $message = __('message.cannotDelete', ['name' => $modelName, 'relation' => strtolower($relation)]);
        } else {
            if ($this->bulkDelete($requestData)) {
                $status = true;
                $message = __('message.deleteSuccess');
            }
        }

        return ['status' => $status, 'message' => $message];
    }

    public function getLastSortId(string $columnName)
    {
        return $this->model->max($columnName) + 1;
    }

    public function bulkUpdate(string $columnName, array $requestData)
    {
        return $this->model->where('id', $requestData['status_id'])->update([$columnName => $requestData['status']]);
    }

    public function count()
    {
        return $this->model->count();
    }
}
