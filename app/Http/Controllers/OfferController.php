<?php

namespace App\Http\Controllers;

use App\Offer;
use App\Http\Resources\Offer as OfferResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfferController extends Controller
{
    private $promo_image_path = 'public/promo_images';

    /**
     * Offers table data.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \App\Http\Resources\Offer
     */
    public function index(Request $request)
    {
        $itemsPerPage = $request->input('itemsPerPage');
        if ($itemsPerPage < 0)
            $itemsPerPage = 1000;

        $page = $request->input('page');

        $sortBy = $request->input('sortBy');
        //$sortBy = str_replace('offer.', 'offers.', $sortBy);

        $dir = $request->input('dir');
        $q = $request->input('q');

        if ($sortBy == '')
            $sortBy = 'created_at';

        $offers = Offer::whereNull('deleted_at')
            ->where('title', 'like', "%$q%")
            ->orderBy($sortBy, $dir)
            ->paginate($itemsPerPage);

        return OfferResource::collection($offers);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list($id = null)
    {
        $owner_id = $id ? $id : auth('api')->user()->id;

        $offers = Offer::where('deleted_at', null)
                       ->where('owner_id', $owner_id)
                       ->where('ends_at', '>=', DB::raw('NOW()'))
                       ->orderBy('created_at', 'desc')
                       ->paginate(30);

        return OfferResource::collection($offers);
    }

    public function get(Request $request, $fromDashboard = false)
    {
        $category = $request->input('category');
        $subcategory = $request->input('subcategory');

        $get = $fromDashboard ? 4 : 30;

        //DB::enableQueryLog();

        $featured_offers = Offer::select('offers.*')
                                ->join('tickets', 'tickets.offer_id', '!=', 'offers.id')
                                ->join('users', 'offers.owner_id', '=', 'users.id')
                                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                                ->join('locations', 'locations.id', '=', 'user_details.location_id')
                                ->where('offers.deleted_at', null)
                                ->where('offers.include_global', 1)
                                ->where('offers.featured', 1)
                                ->when($category, function ($query) use ($category) {
                                    return $query->where('locations.category_id', '=', $category);
                                })
                                ->when($subcategory, function ($query) use ($subcategory) {
                                    return $query->where('locations.subcategory_id', '=', $subcategory);
                                })
                                ->where('ends_at', '>=', DB::raw('NOW()'))
                                ->orderBy('offers.created_at', 'desc')
                                ->groupBy('offers.id')
                                ->paginate($get);
        //dd(DB::getQueryLog());

        $other_offers = Offer::select('offers.*')
                               ->join('tickets', 'tickets.offer_id', '!=', 'offers.id')
                               ->join('users', 'offers.owner_id', '=', 'users.id')
                               ->join('user_details', 'user_details.user_id', '=', 'users.id')
                               ->join('locations', 'locations.id', '=', 'user_details.location_id')
                               ->where('offers.deleted_at', null)
                               ->where('offers.include_global', 1)
                               ->where('offers.featured', 0)
                               ->when($category, function ($query) use ($category) {
                                    return $query->where('locations.category_id', '=', $category);
                               })
                               ->when($subcategory, function ($query) use ($subcategory) {
                                    return $query->where('locations.subcategory_id', '=', $subcategory);
                               })
                               ->where('ends_at', '>=', DB::raw('NOW()'))
                               ->orderBy('offers.created_at', 'desc')
                               ->groupBy('offers.id')
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
            'real_price' => 'required',
            'offered_price' => 'required',
            'owner_id' => 'required',
            'starts_at' => 'required',
            'ends_at' => 'required',
        ];

        if ($request->hasFile('promo_image'))
            $rules['promo_image'] = 'image|nullable|max:2048|mimes:jpeg,png,jpg';

        $this->validate($request, $rules);

        $offer = $request->input('id') <= 0 ? new Offer() : Offer::findOrFail($request->input('id'));

        $offer->title = $request->input('title');
        $offer->short_description = $request->input('short_description');
        $offer->long_description = $request->input('long_description');
        $offer->real_price = $request->input('real_price');
        $offer->offered_price = $request->input('offered_price');
        $offer->include_global = $request->input('include_global') ? 1 : 0;
        $offer->featured = $request->input('featured') ? 1 : 0;
        $offer->notes = $request->input('notes');
        $offer->owner_id = $request->input('owner_id');
        $offer->starts_at = $request->input('starts_at');
        $offer->ends_at = $request->input('ends_at');

        if ($request->hasFile('promo_image'))
            $offer->promo_image = $this->uploadPromoImage($request, 'promo_image', $this->promo_image_path, $offer->promo_image);

        if ($offer->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Offer successfully saved!',
                'offer' => new OfferResource($offer)
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
}
