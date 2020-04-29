<?php

namespace App\Http\Controllers;

use App\Settings;
use Illuminate\Http\Request;
use App\Http\Resources\Settings as SettingsResource;

class SettingsController extends Controller
{
    private $logo_path = 'public/logo';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Settings::count()) {
            $settings = Settings::first();
            return new SettingsResource($settings);
        }

        return response()->json([], 201);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $settings_rules = [
            'site_name' => 'required|max:191',
            'frontpage_template' => 'required',
            'language_id' => 'required|numeric',
            /*
            'phone' => 'required|max:191',
            'address' => 'required|max:191',
            'email' => 'required|max:191',
            'longitude' => ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'latitude' => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/']
            */
        ];

        if ($request->hasFile('site_logo'))
            $settings_rules['site_logo'] = 'image|nullable|max:1999';
        $this->validate($request, $settings_rules);

        $settings = Settings::count() ? Settings::first() : new Settings();

        $settings->site_name = $request->input('site_name');
        if ($request->hasFile('site_logo'))
            $settings->site_logo = $this->uploadSiteLogo($request, 'site_logo', $this->logo_path, $settings->site_logo);

        $settings->frontpage_template = $request->input('frontpage_template');
        $settings->phone = $request->input('phone');
        $settings->address = $request->input('address');
        $settings->email = $request->input('email');
        $settings->longitude = $request->input('longitude');
        $settings->latitude = $request->input('latitude');
        $settings->language_id = $request->input('language_id');

        if ($settings->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Site settings has been successfully saved!',
                'settings' => new SettingsResource($settings)
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error saving site settings!'
            ], 201);
        }
    }

    private function uploadSiteLogo(Request $request, $name, $path, $prop) {                                      // handle file upload
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
