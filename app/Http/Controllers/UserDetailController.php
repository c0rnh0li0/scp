<?php

namespace App\Http\Controllers;

use App\City;
use App\Location;
use App\User;
use App\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use App\Http\Resources\UserDetails as UserDetailResource;

class UserDetailController extends Controller
{
    private $avatar_path = 'public/avatars';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = UserDetail::paginate(10);
        return UserDetailResource::collection($users);
    }

    public function companies()
    {
        $sort = FacadesRequest::get('sort');
        $dir = FacadesRequest::get('direction');
        $search = FacadesRequest::get('q');

        if ($sort == '')
            $sort = 'created_at';

        if ($search != '') {
            $users = UserDetail::where('is_company', 1)
                ->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('description', 'LIKE', '%' . $search . '%')
                ->paginate(10);
        }
        else {
            $users = UserDetail::where('is_company', 1)->orderBy($sort, $dir)->paginate(10);
        }

        return UserDetailResource::collection($users);
    }

    /**
     * Save the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserDetail  $userDetail
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, UserDetail $userDetail)
    {
        $data = json_decode($request->input('jsonstring'));
        $is_company = $request->input('is_company') == "1" ? true : false;

        $userDetail = UserDetail::findOrFail($data->id);

        if ($is_company) {
            $place_rules = [
                'picture' => 'image|nullable|max:1999',
                'name' => 'required|max:255',
                'longitude' => ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
                'latitude' => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
                'address' => 'required|max:255',
                'city_id' => 'required|numeric',
                'valute_id' => 'required|numeric',
                'category_id' => 'required|numeric',
                'subcategory_id' => 'numeric'
            ];

            $this->validate($request, $place_rules);

            // save location data
            $location = $userDetail->location_id ? Location::findOrFail($userDetail->location_id) : new Location();
            $location->longitude = $data->location->longitude;
            $location->latitude = $data->location->latitude;
            $location->address = $data->location->address;
            $location->city_id = $data->location->city_id;
            $location->category_id = $data->location->category_id;
            $location->subcategory_id = $data->location->subcategory_id;

            if ($location->save()) {
                $userDetail->location_id = $location->id;
            }

            // save user data
            $user = User::findOrFail($userDetail->user_id);
            $user->name = $data->user->name;
            $user->save();

            // save user details data
            $userDetail->phone = $data->phone;
            $userDetail->description = $data->description;
            $userDetail->gender_id = $data->gender;
            $userDetail->valute_id = $data->valute;
            $userDetail->picture = $this->uploadAvatar($request, 'picture', $this->avatar_path, $userDetail->picture);
        }
        else {
            $tourist_rules = [
                'name' => 'required|max:255'
            ];

            $this->validate($request, $tourist_rules);

            // save location data
            $location = $userDetail->location_id ? Location::findOrFail($userDetail->location_id) : new Location();
            $location->address = $data->location->address;
            $location->country_id = $data->location->country_id;

            // if city does not exist in db, add it and assoc to the country
            if (!is_numeric($data->location->city_id) && is_numeric($location->country_id)) {
                $city = new City();
                $city->name = $data->location->city_id;
                $city->country_id = $data->location->country_id;

                if ($city->save()) {
                    $location->city_id = $city->id;
                }
            }
            else
                $location->city_id = $data->location->city_id;

            if ($location->save()) {
                $userDetail->location_id = $location->id;
            }

            // save user data
            $user = User::findOrFail($userDetail->user_id);
            $user->name = $data->user->name;
            $user->save();

            // save user details data
            $userDetail->phone = $data->phone;
            $userDetail->description = $data->description;
            $userDetail->gender_id = $data->gender_id;
            $userDetail->picture = $this->uploadAvatar($request, 'picture', $this->avatar_path, $userDetail->picture);
        }

        if ($userDetail->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Your profile info has been successfully saved!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error saving profile info!'
            ], 201);
        }
    }

    private function uploadAvatar(Request $request, $name, $path, $prop) {                                      // handle file upload
        if ($request->hasFile($name) && $request->file($name) != null) {
            $filename = pathinfo($request->file($name)->getClientOriginalName(), PATHINFO_FILENAME);    // get just filename
            $extension = $request->file($name)->getClientOriginalExtension();                                   // get just extension
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;                                     // filename to store
            $filepath = $request->file($name)->storeAs($path, $fileNameToStore);                                // upload

            return $fileNameToStore;
        }
        else {
            if ($prop == '' || $prop == null) {
                return null;
            }
            else
                return $prop;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserDetail  $userDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserDetail $userDetail)
    {
        //
    }
}
