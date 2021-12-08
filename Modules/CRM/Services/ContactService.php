<?php
namespace Modules\CRM\Services;

use Modules\Core\Services\ServiceContract;
use Modules\CRM\Entities\Contact;

class ContactService extends ServiceContract {

    public function __construct(Contact $contact)
    {
        $this->model = $contact;
    }
    
}