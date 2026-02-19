<?php

namespace App\Http\Controllers;

use App\Helpers\ReceivePaymentHelper;
use Illuminate\Http\Request;

class ReceivePaymentController extends Controller
{
    public function index()
    {
        try{ return ReceivePaymentHelper::index(); } catch (\Exception $e) { return $e->getMessage(); }
    }

    public function add(Request $request)
    {
        try{ return ReceivePaymentHelper::add($request); } catch (\Exception $e) { return $e->getMessage(); }
    }

   


    public function save(Request $request)
    {
        try{ return ReceivePaymentHelper::save($request); } catch (\Exception $e) { return $e->getMessage(); }
    }

    public function getall(Request $request)
    {
        try{ return ReceivePaymentHelper::getall($request); } catch (\Exception $e) { return $e->getMessage(); }
    }

    public function edit(Request $request, $id)
    {
        try{ return ReceivePaymentHelper::edit($request, $id); } catch (\Exception $e) { return $e->getMessage(); }
        
    }

    public function update(Request $request)
    {
        try{ return ReceivePaymentHelper::update($request); } catch (\Exception $e) { return $e->getMessage(); }
        
    }

    public function view(Request $request, $id)
    {
        try{ return ReceivePaymentHelper::view($request, $id); } catch (\Exception $e) { return $e->getMessage(); }
        
    }

    public function viewReceipt(Request $request, $payment)
    {
        try { 
            return ReceivePaymentHelper::viewReceipt($request, $payment); 
        } catch (\Exception $e) { 
            return $e->getMessage(); 
        }
    }

     public function pdf(Request $request, $id)
    {
        try{ return ReceivePaymentHelper::pdf($request, $id); } catch (\Exception $e) { return $e->getMessage(); }
        
    }




   public function filter(Request $request)
    {
        try {
            $clientId  = $request->input('client_id');
            $dateFrom  = $request->input('date_from');
            $dateTo    = $request->input('date_to');

            return ReceivePaymentHelper::filter($clientId, $dateFrom, $dateTo);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while filtering payments: ' . $e->getMessage()
            ], 500);
        }
    }




    public function destroy(Request $request, $id)
    {
        try{ return ReceivePaymentHelper::destroy($request, $id); } catch (\Exception $e) { return $e->getMessage(); }
        
    }

     public function update_status(Request $request)
    {
        try{ return ReceivePaymentHelper::update_status($request); } catch (\Exception $e) { return $e->getMessage(); }
        
    }
}
