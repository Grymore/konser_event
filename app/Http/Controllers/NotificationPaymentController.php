<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Mail\KirimEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationPaymentController extends Controller
{
    public function notification()
    {

        $notificationHeader = getallheaders();
        $notificationBody = file_get_contents('php://input');
        $notificationPath = '/api/notify';
        // $clientId = 'BRN-0218-1668854741147';
        $secretKey = 'SK-S4fMVZXAjnPsqIeHaJqc';

        $digest = base64_encode(hash('sha256', $notificationBody, true));
        $rawSignature = "Client-Id:" . $notificationHeader['Client-Id'] . "\n"
            . "Request-Id:" . $notificationHeader['Request-Id'] . "\n"
            . "Request-Timestamp:" . $notificationHeader['Request-Timestamp'] . "\n"
            . "Request-Target:" . $notificationPath . "\n"
            . "Digest:" . $digest;

        $signature = base64_encode(hash_hmac('sha256', $rawSignature, $secretKey, true));
        $finalSignature = 'HMACSHA256=' . $signature;


        if ($finalSignature == $notificationHeader['Signature']) {

            $response = json_decode($notificationBody, true);
            $invoice = $response['order']['invoice_number'];
            $statusTrx = $response['transaction']['status'];
            $amount = $response['order']['amount'];
            $payment_channel = $response['channel']['id'];
            $transaction_date = $response['transaction']['date'];
            
            
            

            $cariin = json_decode(DB::table('customers')->where('invoices', $invoice)->get('invoices'), true);
            $id_cust = json_decode(DB::table('customers')->where('invoices', $invoice)->get('id'), true);
            $nama_cust = json_decode(DB::table('customers')->where('invoices', $invoice)->get('nama'), true);
            $kuantiti = json_decode(DB::table('customers')->where('invoices', $invoice)->get('kuantiti'), true);
            $emailcust = json_decode(DB::table('customers')->where('invoices', $invoice)->get('email'), true);
            $tiket_invoice = json_decode(DB::table('customers')->where('invoices', $invoice)->get('invoice_tiket'), true);
            $waktu_doku_notify = json_decode(DB::table('customers')->where('invoices', $invoice)->get('time_doku_notify'), true);


            $gambar1 = "../images/giphy.gif";


            $data = [
                'title' => 'Selamat datang!',
                'url' => 'http://127.0.0.1:8000/redirect/' . $cariin[0]['invoices'],
                'invoice' => $tiket_invoice[0]['invoice_tiket'],
                'nama' => $nama_cust[0]['nama'],
                'kuantiti' => $kuantiti[0]['kuantiti'],
                'total' => $amount,
                'gambar' => $gambar1,
                'status' => $statusTrx
            ];
            //buat grand total buat variable disni

            if (isset($cariin[0]['invoices']) == $invoice && $statusTrx != "SUCCESS") {

                DB::table('customers')
                    ->where('invoices', $invoice)
                    ->update(['status_transaksi' => $statusTrx]);

                return response()->json([
                    "pesan" => "status berhasil diupdate",
                    "data" => $cariin,
                    "status" => $statusTrx
                ]);

            } elseif (isset($cariin[0]['invoices']) == $invoice && $statusTrx == "SUCCESS" && $waktu_doku_notify[0]['time_doku_notify'] == NULL ) {
                
                
                $va_number = isset($response['virtual_account_info']['virtual_account_number']);

                Log::debug(DB::table('customers')
                    ->where('invoices', $invoice)
                    ->update([
                        'status_transaksi' => $statusTrx,
                        'updated_at' => now(+7),
                        'channel_pembayaran' => $payment_channel,
                        'virtual_account_number' => $va_number,
                        'time_doku_notify' => $transaction_date


                    ]));

                for ($x = 1; $x <= $kuantiti[0]['kuantiti']; $x++) {
                    $waktu = now(+7);
                    DB::table('qr_codes')
                        ->insert(
                            [
                                'customer_id' => $id_cust[0]['id'],
                                'created_at' => $waktu,
                                'qr_string' => md5($x . $id_cust[0]['id'] . now(+7))
                            ],

                        );
                }

                Mail::to($emailcust)->send(new KirimEmail($data));



                return response()->json([
                    "pesan" => "Pembelian berhasil",
                    "email" => "Email terkirim ke " . $emailcust[0]['email'],
                    "data" => $cariin,
                    "status" => $response['transaction']['status']
                ]);

            }
            elseif(isset($cariin[0]['invoices']) == $invoice && $statusTrx == "SUCCESS" && $waktu_doku_notify[0]['time_doku_notify'] != NULL){
                return response()->json([
                    "data" => "Sudah pernah di notify"
                ]);

            }
             else {

                return response()->json([
                    "pesan" => "gagal update datanya ora ada",
                    "invoices" => $invoice,
                    "re" => $waktu_doku_notify[0]['time_doku_notify']
                ]);
            }




        } else {

            return response()->json([
                "pesan" => "invalid signature",
                "dataHeadr" => $notificationHeader['Signature'],
                "dataBody" => $notificationBody,
                "digest" => $digest,
                "siganture" => $finalSignature
            ], 400);
        }
    }
}