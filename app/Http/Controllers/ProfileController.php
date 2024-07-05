<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Events\ProfileEvent;
use App\Repositories\ProfileRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        try {
            $profiles = $this->repository->all();
            event(new ProfileEvent('Read all profiles'));
            return response()->json($profiles);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not fetch profiles'], 500);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'attributes' => 'array',
            'attributes.*.attribute' => 'required|string|max:255'
        ]);

        try {
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
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not create profile'], 500);
        }
    }

    public function show($id)
    {
        try {
            $profile = $this->repository->find($id);
            if (!$profile) {
                throw new ModelNotFoundException();
            }
            event(new ProfileEvent('Read profile with ID: ' . $id));
            return response()->json($profile);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Profile not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not fetch profile'], 500);
        }
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

        try {
            $profileData = $request->only(['name', 'surname', 'phone']);

            if (isset($profileData['phone'])) {
                $profileData['phone'] = $this->sanitizePhoneNumber($profileData['phone']);
            }

            $profile = $this->repository->update($id, $profileData);
            if (!$profile) {
                throw new ModelNotFoundException();
            }

            if ($request->has('attributes')) {
                $profile->attributes()->delete();
                foreach ($request->attributes as $attr) {
                    $profile->attributes()->create(['attribute' => $attr['attribute']]);
                }
            }

            event(new ProfileEvent('Updated profile with ID: ' . $id));
            return response()->json($profile->load('attributes'));
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'details' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Profile not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not update profile'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $profile = Profile::findOrFail($id);
            $profile->delete();
            event(new ProfileEvent('Deleted profile with ID: ' . $id));
            return response()->json(['message' => 'Profile deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Profile not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not delete profile'], 500);
        }
    }

    private function sanitizePhoneNumber($phoneNumber)
    {
        return preg_replace('/^\+39/', '', $phoneNumber);
    }
}
