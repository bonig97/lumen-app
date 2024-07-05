<?php

namespace App\Repositories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProfileRepository
{
    public function all(): Collection|array
    {
        return Profile::with('attributes')->get();
    }

    public function create(array $data)
    {
        return Profile::create($data);
    }

    public function find($id): Model|Collection|Builder|array|null
    {
        return Profile::with('attributes')->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $profile = Profile::findOrFail($id);
        $profile->update($data);
        return $profile;
    }

    public function delete($id)
    {
        $profile = Profile::findOrFail($id);
        return $profile->delete();
    }
}
