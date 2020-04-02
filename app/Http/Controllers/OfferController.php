<?php

namespace App\Http\Controllers;

use App\Offer;
use App\Http\Resources\Offer as OfferResource;
use App\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfferController extends Controller
{
    private $promo_image_path = 'public/promo_images';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        $owner_id = $id ? $id : auth('api')->user()->id;

        $offers = Offer::where('deleted_at', null)
                       ->where('owner_id', $owner_id)
                       ->orderBy('created_at', 'desc')
                       ->paginate(30);

        return OfferResource::collection($offers);
    }

    public function get($fromDashboard = false)
    {
        $get = $fromDashboard ? 4 : 30;

        $featured_offers = Offer::select('offers.*')
                                ->join('tickets', 'tickets.offer_id', '!=', 'offers.id')
                                ->where('offers.deleted_at', null)
                                ->where('offers.include_global', 1)
                                ->where('offers.featured', 1)
                                ->orderBy('offers.created_at', 'desc')
                                ->paginate($get);

        $other_offers = Offer::select('offers.*')
                               ->join('tickets', 'tickets.offer_id', '!=', 'offers.id')
                               ->where('offers.deleted_at', null)
                               ->where('offers.include_global', 1)
                               ->where('offers.featured', 0)
                               ->orderBy('offers.created_at', 'desc')
                               ->paginate($get);

        return response()->json([
            'offers' => [
                'featured' => OfferResource::collection($featured_offers),
                'other' => OfferResource::collection($other_offers)
            ],
        ], 201);
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
