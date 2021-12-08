<?php
namespace Modules\CRM\Services;

use Modules\Core\Services\ServiceContract;
use Modules\CRM\Entities\ClientFamilyMember;

class ClientFamilyMemberService extends ServiceContract {

    public function __construct(ClientFamilyMember $client)
    {
        $this->model = $client;
    }
    
    public function get($id)
    {
        return $this->model->with('client')->findOrFail($id);
    }
}