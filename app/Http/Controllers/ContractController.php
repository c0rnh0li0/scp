<?php

namespace App\Http\Controllers;

use App\Contract;
use App\Http\Resources\Contract as ContractResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    /**
     * People table data.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function index(Request $request)
    {
        $itemsPerPage = $request->input('itemsPerPage');
        $page = $request->input('page');

        $sortBy = $request->input('sortBy');
        $sortBy = str_replace('user.', 'users.', $sortBy);

        $dir = $request->input('dir');
        $q = $request->input('q');

        if ($sortBy == '')
            $sortBy = 'contracts.created_at';

        $paid = $request->input('paid');
        $valid = $request->input('valid');
        $expired = $request->input('expired');

        $filterPaid = $paid != '' && $paid > -1;
        $filterValid = $valid != '' && $valid > -1;
        $filterExpired = $expired != '' && $expired > -1;

        //DB::enableQueryLog();

        $contracts = Contract::join('users', 'users.id', '=', 'contracts.owner_id')
            ->whereHas('owner', function ($query) use ($q) {
                if ($q && $q != '') {
                    $query->where('name', 'like', "%$q%")
                        ->orWhere('email', 'like', "%$q%");
                }
            })
            ->select('contracts.*')
            ->when($filterPaid, function ($query) use ($paid) {
                return $query->where('paid', '=', $paid);
            })
            ->when($filterValid, function ($query) use ($valid) {
                return $query->where('valid', '=', $valid);
            })
            ->when($filterExpired, function ($query) use ($expired) {
                $operator = $expired == 1 ? '>' : '<';
                return $query->where('expires_at', $operator, DB::raw('NOW()'));
            })
            ->orderBy($sortBy, $dir)->paginate($itemsPerPage);

        //dd(DB::getQueryLog());

        return ContractResource::collection($contracts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $rules = [
            'owner_id' => 'required|numeric',
            'contract_length_id' => 'required|numeric',
            'start_at' => 'required',
            'expires_at' => 'required',
        ];

        $this->validate($request, $rules);

        $contract= $request->input('id') <= 0 ? new Contract() : Contract::findOrFail($request->input('id'));

        $contract->owner_id = $request->input('owner_id');
        $contract->contract_length_id = $request->input('contract_length_id');
        $contract->paid = $request->input('paid') == 'true' ? 1 : 0;
        $contract->valid = $request->input('valid') == 'true' ? 1 : 0;
        $contract->start_at = $request->input('start_at');
        $contract->expires_at = $request->input('expires_at');

        if ($contract->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Contract successfully saved!',
                'item' => new ContractResource($contract)
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Error saving contract!'
            ], 201);
        }
    }
}
