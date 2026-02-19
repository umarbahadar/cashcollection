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

class ledgerHelper {

    // Show ledger page with agents filter
    public static function index()
    {
        // Get all agents (role_id = 2)
        $agents = User::where('role_id', 2)
            ->orderBy('name')
            ->get();

        return view('admin.ledgers.ledger', compact('agents'));
    }

    // Fetch all ledger data (for DataTable)
    public static function getAll()
    {
        $collections = CashCollection::with([
            'agent',
            'ledgers.account',
        ])
        ->orderBy('collection_date', 'desc')
        ->get();

        $formattedData = $collections->map(function ($item, $index) {
            $ledger = $item->ledgers->first(); // Assuming one ledger per collection

            return [
                'serialNumber'   => $index + 1,
                'id'             => $item->id,
                'agent'          => $item->agent->name ?? 'N/A',
                'account'        => $ledger?->account?->name ?? 'N/A',
                'type'           => $ledger?->type ?? 'N/A',
                'collection_date'=> $item->collection_date,
                'amount'         => number_format($item->amount, 2),
                'created_at'     => $item->created_at->format('Y-m-d'),
            ];
        });

        return response()->json(['data' => $formattedData]);
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

   public static function view(Request $request, $id)
    {
        $collection = CashCollection::with([
            'agent',
            'ledgers.account'
        ])
        ->where('id', $id)
        ->firstOrFail();

        $ledger = $collection->ledgers->first();

        $recentCollections = CashCollection::where('agent_id', $collection->agent_id)
            ->latest('collection_date')
            ->limit(5)
            ->get();

        return view(
            'admin.ledgers.view-ledger',
            compact('collection', 'ledger', 'recentCollections')
        );
    }


    public static function filter($agentId = null, $dateFrom = null, $dateTo = null)
    {
        $query = CashCollection::with([
            'agent',
            'ledgers.account',
        ]);

        // Apply filters if provided
        if ($agentId) {
            $query->where('agent_id', $agentId);
        }

        if ($dateFrom) {
            $query->whereDate('collection_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('collection_date', '<=', $dateTo);
        }

        $collections = $query->orderBy('collection_date', 'desc')->get();

        $formattedData = $collections->map(function ($item, $index) {
            $ledger = $item->ledgers->first(); // one ledger per collection

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
        // Fetch single cash collection with related agent and ledger account
        $collection = CashCollection::with([
            'agent',
            'ledgers.account',
        ])
        ->where('id', $id)
        ->firstOrFail();

        // Get first ledger (assuming one ledger per collection)
        $ledger = $collection->ledgers->first();

        // PDF options
        Pdf::setOptions([
            'isRemoteEnabled' => true,
            'defaultFont'     => 'dejavusans',
        ]);

        // Load view for ledger PDF
        $pdf = Pdf::loadView(
            'admin.ledgers.export-pdf-ledger',
            [
                'collection' => $collection,
                'ledger'     => $ledger,
            ]
        )->setPaper('a4', 'portrait');

        $fileName = 'LEDGER_' . $collection->id . '.pdf';

        return $pdf->download($fileName);
    }




}
