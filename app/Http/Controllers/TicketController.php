<?php

namespace App\Http\Controllers;

use App\Offer;
use App\Ticket;
use App\Http\Resources\Ticket as TicketResource;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\BaconQrCodeGenerator as QrCode;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::where('deleted_at', null)
                         ->where('used', 0)
                         ->where('user_id', auth('api')->user()->id)
                         ->orderBy('created_at', 'desc')
                         ->paginate(30);

        return TicketResource::collection($tickets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function buy(Request $request)
    {
        $rules = [
            'offer_id' => 'required|numeric',
            'amount' => 'required|numeric',
        ];

        $this->validate($request, $rules);

        $ticket = new Ticket();

        $amount = $request->input('amount');
        $user = auth('api')->user();
        $offer = Offer::findOrFail($request->input('offer_id'));

        $ticket->user_id = $user->id;
        $ticket->offer_id = $offer->id;
        $ticket->used = false;
        $ticket->amount = $amount;
        $ticket->save();

        $ticket->qr_code = $this->qr($user->id, $ticket->id, $amount);

        if ($ticket->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Ticket successfully bought!',
                'ticket' => new TicketResource($ticket)
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error buying ticket!'
            ], 201);
        }
    }

    /**
     * @param $user
     * @param $offer
     * @param $amount
     * @return image
     */
    public function qr($user, $ticket, $amount) {
        $qr_data = [
            $ticket,
            $user,
            $amount,
            now(),
        ];

        $qrcode_name_raw = join('_', $qr_data);
        $qrcode_name = str_replace(' ', '_' , $qrcode_name_raw);
        $qrcode_name = str_replace(':', '-' , $qrcode_name);
        $qrcode_path = public_path('storage\tickets\\' . $qrcode_name . '.png');
        $data = $qrcode_name_raw;

        //touch($qrcode_path);

        $qr = new QrCode();
        $pngImage = $qr->format('png')
                       ->size(400)
                       ->errorCorrection('H')
                       ->generate($data, $qrcode_path);
        return $qrcode_name . '.png';
    }

    /**
     * get ticket information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function check(Request $request) {
        $rules = [
            'ticket_id' => 'required|numeric',
            'user_id' => 'required|numeric',
            'amount' => 'required|numeric'
        ];

        $this->validate($request, $rules);

        $ticket = Ticket::findOrFail($request->input('ticket_id'));

        if ($ticket->used == 0) {
            return response()->json([
                'success' => true,
                'message' => 'Ticket is valid!',
                'ticket' => new TicketResource($ticket)
            ], 200);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Ticket is not valid!',
                'ticket' => new TicketResource($ticket)
            ], 200);
        }

    }

    /**
     * use ticket
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function use(Request $request) {
        $rules = [
            'ticket_id' => 'required|numeric',
        ];

        $this->validate($request, $rules);

        $ticket = Ticket::findOrFail($request->input('ticket_id'));
        $ticket->used = 1;

        if ($ticket->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Ticket is used!',
                'ticket' => new TicketResource($ticket)
            ], 200);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Ticket is not used!'
            ], 200);
        }

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
