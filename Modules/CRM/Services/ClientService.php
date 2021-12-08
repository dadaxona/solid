<?php
namespace Modules\CRM\Services;

use Modules\Core\Services\ServiceContract;
use Modules\CRM\Entities\Client;
use Modules\User\Services\UserService;

class ClientService extends ServiceContract {

    public function __construct(Client $client)
    {
        $this->model = $client;
    }
    
    public function create($data)
    {
        $userData = $data['user'];
        $familyMembers = $data['client_family_members'];
        unset($data['user']);
        unset($data['client_family_members']);
        
        $userService = app()->make(UserService::class);
        $user = null;
        if(isset($userData['id'])){
            $user = $userService->update($userData);
        }else{
            $user = $userService->create($userData);
        }
        $data['user_id'] = $user->id;
        
        $client = $this->model->create($data);
        
        $clientFamilyMemberService = app()->make(ClientFamilyMemberService::class);
        foreach ($familyMembers as $member) {
            $member['client_id'] = $client->id;
            $clientFamilyMember = $clientFamilyMemberService->create($member);
            $clientFamilyMember->client()->associate($client);
        }
        return $client;
    }
    
    public function update($data)
    {
        $client = $this->model->find($data['id']);
        
        $userData = $data['user'];
        $familyMembers = $data['client_family_members'];
        unset($data['user']);
        unset($data['client_family_members']);
        
        $userService = app()->make(UserService::class);
        $user = null;
        if(isset($userData['id'])){
            $user = $userService->update($userData);
        }else{
            $user = $userService->create($userData);
        }
        $data['user_id'] = $user->id;
        $client->update($data);

        $member_ids = $client->clientFamilyMembers()->pluck('id')->toArray();
        $updated_ids = [];
        $clientFamilyMemberService = app()->make(ClientFamilyMemberService::class);
        foreach ($familyMembers as $member) {
            if(isset($member['id'])){
                $updated_ids[] = $member['id'];
                $clientFamilyMember = $clientFamilyMemberService->update($member);
            }else{
                $member['client_id'] = $client->id;
                $clientFamilyMember = $clientFamilyMemberService->create($member);
                $clientFamilyMember->client()->associate($client);
            }
        }
        $delete_ids = array_diff($member_ids, $updated_ids);
        $client->clientFamilyMembers()->whereIn('id', $delete_ids)->delete();
        return $client;
    }
    public function get($id)
    {
        return $this->model->with(['user','clientFamilyMembers'])->findOrFail($id);
    }
    public function find($identifier)
    {
        return $this->model->with('user')->whereHas('user', function($query) use ($identifier){
            $query->where('users.phone', $identifier);
        })->first();
    }
}