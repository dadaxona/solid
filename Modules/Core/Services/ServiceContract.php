<?php
namespace Modules\Core\Services;

use Illuminate\Support\Str;

class ServiceContract {
    protected $model;

    public function getlist(?array $config = null){
        $list = $this->model->query()->orderBy('id', 'desc');
        //TODO make abstract
        if(request()->has('related_resource') && request()->has('related_resource_id')){
            $list = $list->whereHas(request()->get('related_resource'), function($query){
                $tableName = Str::plural(request()->get('related_resource'));
                $query->where( $tableName. '.id', request()->get('related_resource_id'));
            });
        }
        if($config){
            //columns
            $list = $list->select($config['columns']);
            //relations
            $list->with($config['relations']);
        }
        if(request()->has('filters')){
            $filters = json_decode(request()->filters, true);
            foreach($filters as $key => $value){
                if(isset($config['filters'][$key])){
                    $list->where($config['filters'][$key]($value));
                }else{
                    $list->where($key, 'like', '%'.$value.'%');
                }
            }
        }
        $collection_arr = $list->paginate(request()->get('limit', 15))->toArray();
        
        return $collection_arr;
    }
    public function create($data)
    {
        return $this->model->create($data);
    }
    
    public function update($data)
    {
        $model = $this->model->find($data['id']);
        $model->update($data);
        return $model;
    }
    
    public function get($id)
    {
        return $this->model->findOrFail($id);
    }
}