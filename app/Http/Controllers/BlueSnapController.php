<?php

namespace App\Http\Controllers;

use App\Models\Shopper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentLinkMail;
use GuzzleHttp\Client;

class BlueSnapController extends Controller
{
    public function index()
    {
        $shoppers = Shopper::all();
        return view('admin.shoppers.index', compact('shoppers'));
    }
    public function create()
    {
        return view('admin.shoppers.create');
    }
    public function store(Request $request)
    {
        $client = new Client();
        $headers = [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Basic ' . base64_encode(env('BLUESNAP_API_USERNAME') . ':' . env('BLUESNAP_API_PASSWORD'))
        ];

        $body = [
            "firstName" => $request->first_name,
            "lastName"  => $request->last_name,
            "email"     => $request->email
        ];

        try {
            $response = $client->post(env('BLUESNAP_SANDBOX_URL') . '/vaulted-shoppers', [
                'headers' => $headers,
                'json'    => $body
            ]);

            $data = json_decode($response->getBody(), true);
            $shopperId = $data['vaultedShopperId'];

            // Store in database
            $shopper = Shopper::create([
                'shopper_id' => $shopperId,
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'status'     => 'pending',
                'payment_link' => null
            ]);

            return redirect()->route('shoppers.index')->with('success', 'Shopper created! ID: ' . $shopperId);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed: ' . $e->getMessage());
        }
    }
    public function updatePaymentLink(Request $request)
    {
        $request->validate([
            'shopper_id' => 'required|exists:shoppers,id',
            'payment_link' => 'required|url',
        ]);

        $shopper = Shopper::findOrFail($request->shopper_id);
        $shopper->payment_link = $request->payment_link;
        $shopper->save();

        return response()->json(['message' => 'Payment link updated successfully!']);
    }

    public function sendPaymentLink(Request $request)
    {
        $request->validate([
            'shopper_id' => 'required|exists:shoppers,id',
            'email' => 'required|email',
            'payment_link' => 'required|url',
        ]);

        $shopper = Shopper::findOrFail($request->shopper_id);

        try {
            if ($shopper->email) {
                Mail::to($request->email)->send(new PaymentLinkMail($request->payment_link));

                // ✅ Update status after email is sent
                $shopper->status = 'Email Sent';
                $shopper->save();
            }
        } catch (\Exception $e) {
            // ❌ If mail fails, set status to "Failed"
            $shopper->status = 'Failed to Send Email';
            $shopper->save();
        }

        return response()->json(['message' => 'Payment link sent successfully!']);
    }
}

