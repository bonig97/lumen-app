<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Events\ProfileEvent;
use App\Repositories\ProfileRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    protected ProfileRepository $repository;

    public function __construct(ProfileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $profiles = $this->repository->all();
        event(new ProfileEvent('Read all profiles'));
        return response()->json($profiles);
    }

    public function store(Request $request)
    {
        Log::info('Request Payload:', $request->all());

        try {
            $this->validate($request, [
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'attributes' => 'array',
                'attributes.*.attribute' => 'required|string|max:255'
            ]);
        } catch (ValidationException $e) {
            Log::error('Validation Errors:', $e->errors());
            return response()->json($e->errors(), 422);
        }

        $profileData = $request->only(['name', 'surname', 'phone']);
        $profileData['phone'] = $this->sanitizePhoneNumber($profileData['phone']);
        $profile = $this->repository->create($profileData);

        if ($request->has('attributes')) {
            foreach ($request->attributes as $attr) {
                $profile->attributes()->create(['attribute' => $attr['attribute']]);
            }
        }

        event(new ProfileEvent('Created profile with ID: ' . $profile->id));
        return response()->json($profile->load('attributes'), 201);
    }

    public function show($id)
    {
        $profile = $this->repository->find($id);
        event(new ProfileEvent('Read profile with ID: ' . $id));
        return response()->json($profile);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'sometimes|required|string|max:255',
            'surname' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:15',
            'attributes' => 'array',
            'attributes.*.attribute' => 'sometimes|required|string|max:255'
        ]);

        $profileData = $request->only(['name', 'surname', 'phone']);

        if (isset($profileData['phone'])) {
            $profileData['phone'] = $this->sanitizePhoneNumber($profileData['phone']);
        }

        $profile = $this->repository->update($id, $profileData);

        if ($request->has('attributes')) {
            $profile->attributes()->delete();
            foreach ($request->attributes as $attr) {
                $profile->attributes()->create(['attribute' => $attr['attribute']]);
            }
        }

        event(new ProfileEvent('Updated profile with ID: ' . $id));
        return response()->json($profile->load('attributes'));
    }

    public function destroy($id)
    {
        $profile = Profile::findOrFail($id);
        $profile->delete();
        event(new ProfileEvent('Deleted profile with ID: ' . $id));
        return response()->json(['message' => 'Profile deleted successfully'], 200);
    }

    private function sanitizePhoneNumber($phoneNumber)
    {
        return preg_replace('/^\+39/', '', $phoneNumber);
    }
}
