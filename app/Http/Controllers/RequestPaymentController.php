<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;



class RequestPaymentController extends Controller
{
    public function payment(Request $request)
    {


        $validator = Validator::make($request->all(), [
            "nama" => "required|string",
            "telpon" => "required|string",
            "email" => "required|string",
            "total" => "required|numeric",
            "invoices" => "required|string",
            "invoice_tiket" => "required|string",
            "status_transaksi" => "required|string",
        ]);


        if ($validator->fails()) {
            return response()->json([
                "message" => "failed",
                "errors" => $validator->errors()
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $validated = $validator->validated();

        try {
          


            // DB::table('customers')
            //     ->insert($validated);

            $customer = Customer::create([
                "nama" => $request->nama,
                "telpon" => $request->telpon,
                "email" => $request->email,
                "kuantiti" => $request->total,
                "total" => $request->total * 100000,
                "invoices" => $request->invoices,
                "invoice_tiket" => $request->invoice_tiket,
                "status_transaksi" => $request->status_transaksi
            ]);


            $requestBody = array(
                'order' =>
                array(
                    'amount' => $request->total * 100000,
                    'invoice_number' => $request->invoices,
                    'disable_retry_payment' => true,
                    'callback_url' => 'http://127.0.0.1:8000/redirect/' . $request->invoices,
                    'line_items' =>
                    array(
                        0 =>
                        array(
                            'name' => 'Tiket konser',
                            'price' => 100000,
                            'quantity' => $request->total
                        ),
                    ),
                ),
                'payment' =>
                array(
                    'payment_due_date' => 60,
                ),
                'customer' =>
                array(
                    'name' => $request->nama,
                    'email' => $request->email,
                    'phone' => $request->hp
                ),
            );
            $requestId = rand(1, 100000);
            $dateTime = gmdate("Y-m-d H:i:s");
            $isoDateTime = date(DATE_ISO8601, strtotime($dateTime));
            $dateTimeFinal = substr($isoDateTime, 0, 19) . "Z";
            $clientId = 'BRN-0218-1668854741147';
            $secretKey = 'SK-S4fMVZXAjnPsqIeHaJqc';
            // $getUrl = 'https://api.doku.com';
            $getUrl = 'https://api-sandbox.doku.com';
            $targetPath = '/checkout/v1/payment';
            $url = $getUrl . $targetPath;

            // Generate digest
            $digestValue = base64_encode(hash('sha256', json_encode($requestBody), true));
            // Prepare signature component
            $componentSignature = "Client-Id:" . $clientId . "\n" .
                "Request-Id:" . $requestId . "\n" .
                "Request-Timestamp:" . $dateTimeFinal . "\n" .
                "Request-Target:" . $targetPath . "\n" .
                "Digest:" . $digestValue;
            // Generate signature
            // echo $componentSignature."<br>";
            $signature = base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));
            // Execute request
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
            // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'Client-Id:' . $clientId,
                    'Request-Id:' . $requestId,
                    'Request-Timestamp:' . $dateTimeFinal,
                    'Signature:' . "HMACSHA256=" . $signature,
                )
            );
            // Set response json
            $responseJson = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            //  echo $responseJson; 

            if (is_string($responseJson) && $httpcode == 200) {
                //echo $responseJson;
                $content = json_decode($responseJson, true);
                $result = $content['response']['payment']['url'];


            }

            return Redirect::to($result);
        } catch (\Exception $e) {
            // return Redirect::to('/');
            echo($e);
            
        }
    }

}