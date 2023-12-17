<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Session;
use Carbon\Carbon;

class InvoiceController extends Controller
{

    public function create(Request $request)
    {
        $request->validate([
            'START' => 'required|date',
            'END' => 'required|date|after_or_equal:START',
            'CUSTOMER_ID' => 'required|exists:customers,id',
        ]);

        $startDate = Carbon::parse($request->input('START'));
        $endDate = Carbon::parse($request->input('END'));
        $customerId = $request->input('CUSTOMER_ID');

        $invoice = Invoice::create([
            'customer_id' => $customerId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_price' => $this->calculateInvoiceTotal($customerId, $startDate, $endDate),
        ]);

        return response()->json(['invoice_id' => $invoice->id], 201);
    }

    /**
     * Show the details of a specific invoice.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $invoice = Invoice::with(['customer', 'customer.users.sessions'])->findOrFail($id);

        // Implement logic to format and return the required details

        return response()->json($invoice);
    }

    /**
     * Calculate the total price for an invoice.
     *
     * @param int $customerId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return float
     */
    private function calculateInvoiceTotal($customerId, $startDate, $endDate)
    {
        $totalPrice = 0.00;

        // Get all users for the customer
        $users = User::where('customer_id', $customerId)->get();

        foreach ($users as $user) {
            // Check if the user registered within the invoice period
            if ($user->registration_date >= $startDate && $user->registration_date <= $endDate) {
                // Calculate the price for registration event
                $totalPrice += 50.00; // Adjust the price as needed
            }

            // Check if the user activated or made an appointment within the invoice period
            $userSessions = Session::where('user_id', $user->id)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('activated', [$startDate, $endDate])
                        ->orWhereBetween('appointment', [$startDate, $endDate]);
                })
                ->get();

            // Keep track of events to avoid redundant invoicing for the same event
            $invoicedEvents = [];

            foreach ($userSessions as $session) {
                if (!in_array($session->activated, $invoicedEvents) && $session->activated) {
                    // Calculate the price for activation event
                    $totalPrice += 100.00; // Adjust the price as needed
                    $invoicedEvents[] = $session->activated;
                }

                if (!in_array($session->appointment, $invoicedEvents) && $session->appointment) {
                    // Calculate the price for appointment event
                    $totalPrice += 200.00; // Adjust the price as needed
                    $invoicedEvents[] = $session->appointment;
                }
            }
        }

        return $totalPrice;
    }
}
