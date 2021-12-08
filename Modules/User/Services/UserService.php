<?php
namespace Modules\User\Services;

use Modules\Core\Services\ServiceContract;
use Illuminate\Http\UploadedFile;
use Modules\User\Entities\User;

class UserService extends ServiceContract
{

    public function __construct(protected User $user)
    {
        $this->model = $user;
    }
    
    public function create($data){
        $role_ids = $data['roles']??null;
        unset($data['roles']);
        $data['password'] = bcrypt('password');
        
        if(isset($data['image']) && $data['image'] instanceof UploadedFile){
            $data['image'] = $this->updloadImage($data['image']);
        }else{
            unset($data['image']);
        }
        
        $user = $this->model->create($data);

        $user->roles()->attach($role_ids);

        return $user;
    }

    public function update($data)
    {
        $role_ids = $data['roles']??null;
        unset($data['roles']);
        
        if(isset($data['image']) && $data['image'] instanceof UploadedFile){
            $data['image'] = $this->updloadImage($data['image']);
        }else{
            unset($data['image']);
        }
        $user = $this->model->find($data['id']);
        $user->update($data);
        
        $user->roles()->sync($role_ids);
        
        return $user;
    }

    public function updloadImage($file)
    {
        $path = $file->store('users_images', 'public');

        return $path;
    }
    
    public function get($id)
    {
        return $this->model->with('client')->findOrFail($id);
    }
    public function find($identifier)
    {
        return $this->model->with('client')->where('phone', $identifier)->first();
    }
    
}