<?php

namespace App\Http\Controllers;

use App\Offer;
use App\Http\Resources\Offer as OfferResource;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    private $promo_image_path = 'public/promo_images';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers = Offer::where('deleted_at', null)->orderBy('created_at', 'desc')->paginate(30);

        return OfferResource::collection($offers);
    }

    public function get()
    {
        $offers = Offer::where('deleted_at', null)->where('include_global', 1)->orderBy('created_at', 'desc')->paginate(30);

        return OfferResource::collection($offers);
    }

    /**
     * Save the specified resource in storage or create new one if it doesn't exist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, Offer $offer)
    {
        $rules = [
            'title' => 'required|max:255',
            'short_description' => 'required',
            'promo_image' => 'image|nullable|max:2048|mimes:jpeg,png,jpg',
            'real_price' => 'required',
            'offered_price' => 'required'
        ];

        $this->validate($request, $rules);

        //$data = json_decode($request->input('jsonstring'));

        $offer = $request->input('id') == 0 ? new Offer() : Offer::findOrFail($request->input('id'));

        $offer->title = $request->input('title');
        $offer->short_description = $request->input('short_description');
        $offer->long_description = $request->input('long_description');
        $offer->promo_image = $this->uploadPromoImage($request, 'promo_image', $this->promo_image_path, $offer->promo_image);
        $offer->real_price = $request->input('real_price');
        $offer->offered_price = $request->input('offered_price');
        $offer->include_global = $request->input('include_global') ? 1 : 0;
        $offer->featured = $request->input('featured') ? 1 : 0;
        $offer->notes = $request->input('notes');
        $offer->owner_id = auth('api')->user()->id;

        if ($offer->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Offer successfully saved!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error saving offer!'
            ], 201);
        }
    }

    private function uploadPromoImage(Request $request, $name, $path, $prop) {                                      // handle file upload
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
     * Delete the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $offer = Offer::findOrFail($id);

        if ($offer->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Order successfully deleted!'
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting offer!'
            ], 201);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Offer $offer)
    {
        //
    }
}
