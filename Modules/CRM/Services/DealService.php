<?php
namespace Modules\CRM\Services;

use Modules\Core\Services\ServiceContract;
use Modules\CRM\Entities\Deal;
use Modules\User\Services\UserService;

class DealService extends ServiceContract {

    public function __construct(Deal $deal)
    {
        $this->model = $deal;
    }
    
    public function create($data)
    {
        $products = $data['products'] ?? [];
        unset($data['products']);
        $clientData = $data['client'];
        unset($data['client']);

        $clientService = app()->make(ClientService::class);

        $client = null;
        if(isset($clientData['id'])){
            $client = $clientService->update($clientData);
        }else{
            $client = $clientService->create($clientData);
        }

        $pivot_products = [];
        foreach ($products as $product) {
            $pivot_products[$product['id']] = [
                'price' => $product['price'],
                'quantity' => $product['pivot']['quantity'],
                'discount' => $product['pivot']['discount']??0,
                'paid_price' => $product['pivot']['paid_price']??0,
            ];
        }

        $deal = $this->model->create($data);
        
        $deal->products()->attach($pivot_products);
        $deal->client()->associate($client);
        $deal->update();
        return $deal;
    }
    
    public function update($data)
    {
        $deal = $this->model->find($data['id']);
        
        $products = $data['products'] ?? [];
        unset($data['products']);
        $clientData = $data['client'];
        unset($data['client']);
        
        $clientService = app()->make(ClientService::class);
        
        $client = null;
        if(isset($clientData['id'])){
            $client = $clientService->update($clientData);
        }else{
            $client = $clientService->create($clientData);
        }
        $pivot_products = [];
        foreach ($products as $product) {
            $pivot_products[$product['id']] = [
                'price' => $product['price'],
                'quantity' => $product['pivot']['quantity'],
                'discount' => $product['pivot']['discount']??0,
                'paid_price' => $product['pivot']['paid_price']??0,
            ];
        }
        $deal->products()->sync($pivot_products);
        $deal->client()->associate($client);
        $deal->update($data);
        
        return $deal;
    }
    
    public function get($id)
    {
        return $this->model->with(['client.user', 'products'])->findOrFail($id);
    }
    
}