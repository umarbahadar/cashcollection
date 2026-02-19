<?php

namespace App\Helpers;
use App\Models\UserClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use App\Models\Account;
use App\Models\CashCollection;
use App\Models\Ledger;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;
 use Carbon\Carbon;
class ReceivePaymentHelper {

    public static function index()
    {
        // Get all agents (role_id = 2)
        $agents = User::where('role_id', 2)
            ->orderBy('name')
            ->get();

        return view(
           

            'admin.agent-payments.payments', compact('agents'));
    }

    public static function add()
    {
        // Get all agents (role_id = 2)
        $agents = User::where('role_id', 2)
            ->orderBy('name')
            ->get();

        $accounts = Account::all();

        return view(
            'admin.agent-payments.add-payment', 
            compact('agents', 'accounts')
        );
    }

   
     public static function save(Request $request)
    {
        $userId = Auth::id();

        // ─────────────────────────────────────────────
        // STEP 1: VALIDATION
        // ─────────────────────────────────────────────
        $messages = [
            'agent_id.required'       => 'Please select an agent.',
            'agent_id.exists'         => 'Selected agent does not exist.',
            'account_id.required'     => 'Please select an account.',
            'account_id.exists'       => 'Selected account is invalid.',
            'collection_date.required'=> 'Please select a collection date.',
            'collection_date.date'    => 'Collection date must be a valid date.',
            'amount.required'         => 'Please enter the amount.',
            'amount.numeric'          => 'Amount must be a number.',
            'amount.min'              => 'Amount must be at least 0.01.',
            'notes.max'               => 'Notes cannot exceed 500 characters.',
        ];

        $validator = Validator::make($request->all(), [
            'agent_id'        => 'required|exists:users,id',
            'account_id'      => 'required|exists:accounts,id',
            'collection_date' => 'required|date',
            'amount'          => 'required|numeric|min:0.01',
            'notes'           => 'nullable|string|max:500',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'msg'    => $validator->errors()->first()
            ], 422);
        }

        // ─────────────────────────────────────────────
        // STEP 2: DATABASE TRANSACTION
        // ─────────────────────────────────────────────
        DB::beginTransaction();

        try {
            // ─────────────────────────────────────────────
            // CREATE CASH COLLECTION
            // ─────────────────────────────────────────────
            $cashCollection = CashCollection::create([
                'agent_id'        => $request->agent_id,
                'collection_date' => $request->collection_date,
                'amount'          => $request->amount,
                'notes'           => $request->notes,
            ]);

            // ─────────────────────────────────────────────
            // CREATE LEDGER ENTRY
            // ─────────────────────────────────────────────
            Ledger::create([
                'cash_collection_id' => $cashCollection->id,
                'account_id'         => $request->account_id,
                'type'               => 'credit', // cash incoming
                'amount'             => $request->amount,
                'description'        => 'Cash collected from agent ID: ' . $request->agent_id,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'msg'    => 'Cash collection recorded successfully.',
                'id'     => $cashCollection->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'msg'    => 'Failed to record collection: ' . $e->getMessage()
            ], 500);
        }
    }



    // public static function getAll()
    // {
    //     $collections = CashCollection::with('agent')->get();

    //     $formattedData = $collections->values()->map(function ($item, $index) {
    //         return [
    //             'serialNumber'   => $index + 1,
    //             'id'             => $item->id,
    //             'agent'          => $item->agent->name ?? 'N/A',
    //             'collection_date'=> $item->collection_date,
    //             'amount'         => number_format($item->amount, 2),
    //             'notes'          => $item->notes ?? 'N/A',
    //             'created_at'     => $item->created_at->format('Y-m-d'),
    //         ];
    //     });

    //     return response()->json(['data' => $formattedData]);
    // }


    public static function getAll()
    {
        $collections = CashCollection::with([
            'agent',
            'ledgers.account'
        ])
        ->orderBy('id', 'desc')
        ->get();

        $formattedData = $collections->values()->map(function ($item, $index) {

            // Get first ledger (since one collection = one ledger in your logic)
            $ledger = $item->ledgers->first();

            return [
                'serialNumber'   => $index + 1,
                'id'             => $item->id,

                'agent'          => $item->agent->name ?? 'N/A',

                'account'        => $ledger && $ledger->account
                                    ? $ledger->account->name
                                    : 'N/A',

                'type'           => $ledger->type ?? 'N/A',

                'collection_date'=> $item->collection_date,

                'amount'         => number_format($item->amount, 2),

                'created_at'     => $item->created_at->format('Y-m-d'),
            ];
        });

        return response()->json(['data' => $formattedData]);
    }


    public static function view(Request $request, $id)
    {
        // Fetch single cash collection with relations
        $collection = CashCollection::with([
            'agent',
            'ledgers.account'
        ])
        ->where('id', $id)
        ->firstOrFail();

        // Get first ledger (assuming one ledger per collection)
        $ledger = $collection->ledgers->first();

        // Get recent collections for same agent
        $recentCollections = CashCollection::where('agent_id', $collection->agent_id)
            ->latest('collection_date')
            ->limit(5)
            ->get();

        return view(
            'admin.agent-payments.view-payment',
            compact('collection', 'ledger', 'recentCollections')
        );
    }


    public static function filter($clientId = null, $dateFrom = null, $dateTo = null)
    {
        $query = CashCollection::with([
            'agent',
            'ledgers.account'
        ])->orderBy('id', 'desc');

        if ($clientId) {
            $query->where('agent_id', $clientId);
        }

        if ($dateFrom) {
            $query->whereDate('collection_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('collection_date', '<=', $dateTo);
        }

        $collections = $query->get();

        $formattedData = $collections->values()->map(function ($item, $index) {
            $ledger = $item->ledgers->first();

            return [
                'serialNumber'    => $index + 1,
                'id'              => $item->id,
                'agent'           => $item->agent->name ?? 'N/A',
                'account'         => $ledger?->account?->name ?? 'N/A',
                'type'            => $ledger?->type ?? 'N/A',
                'collection_date' => $item->collection_date,
                'amount'          => number_format($item->amount, 2),
                'created_at'      => $item->created_at->format('Y-m-d'),
            ];
        });

        return response()->json(['data' => $formattedData]);
    }


    public static function pdf(Request $request, $id)
    {
        $collection = CashCollection::with([
            'agent',
            'ledgers.account',
            // 'user.businessProfiles', // optional if you want company info later
        ])
        ->where('id', $id)
        ->firstOrFail();

        // Get first ledger (as per your logic: one collection = one ledger)
        $ledger = $collection->ledgers->first();

        Pdf::setOptions([
            'isRemoteEnabled' => true,
            'defaultFont'     => 'dejavusans',
        ]);

        $pdf = Pdf::loadView(
            'admin.agent-payments.export-pdf-payment',
            [
                'collection' => $collection,
                'ledger'     => $ledger,
            ]
        )->setPaper('a4', 'portrait');

        $fileName = 'COLLECTION_' . $collection->id . '.pdf';

        return $pdf->download($fileName);
    }




}
