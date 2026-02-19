<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ledgerHelper;
class LedgerController extends Controller
{
     public function index()
    {
        try{ return ledgerHelper::index(); } catch (\Exception $e) { return $e->getMessage(); }
    }

  
    public function getall(Request $request)
    {
        try{ return ledgerHelper::getall($request); } catch (\Exception $e) { return $e->getMessage(); }
    }



    public function view(Request $request, $id)
    {
        try{ return ledgerHelper::view($request, $id); } catch (\Exception $e) { return $e->getMessage(); }
        
    }

     public function pdf(Request $request, $id)
    {
        try{ return ledgerHelper::pdf($request, $id); } catch (\Exception $e) { return $e->getMessage(); }
        
    }

 
    
   public function filter(Request $request)
    {
        try {
            $agentId      = $request->input('client_id');
            $dateFrom      = $request->input('from_date');
            $dateTo        = $request->input('to_date');

            return ledgerHelper::filter($agentId, $dateFrom, $dateTo);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error filtering ledger: ' . $e->getMessage()
            ], 500);
        }
    }
}
