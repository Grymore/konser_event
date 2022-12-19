<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CallbackController extends Controller
{
    public function callback($request)
    {

        $sukses = "../images/75_smile.gif";
        $pending = "../images/waiting.gif";
        $gagal = "../images/fail.gif";
        $gambar1 = "../images/giphy.gif";
        $cekinvoice = json_decode(DB::table('customers')->where('invoices', $request)->get(), true);
        $dataQR = DB::table('qr_codes')
            ->join('customers', 'qr_codes.customer_id', '=', 'customers.id')
            ->where('customers.invoices', $request)->get();
        
        $invoice_tiket = json_decode(DB::table('qr_codes')
            ->join('customers', 'qr_codes.customer_id', '=', 'customers.id')
            ->where('customers.invoices', $request)->get('customers.invoice_tiket'), true);

        $banyak = DB::table('qr_codes')
            ->join('customers', 'qr_codes.customer_id', '=', 'customers.id')
            ->where('customers.invoices', $request)->count();

     
        $path = storage_path('public/images/21.png');   

        if (isset($cekinvoice[0]['invoices']) == $request && $cekinvoice[0]['status_transaksi'] == "SUCCESS") {

            return view('print_ticket', [
                "invoice" => $request,
                "invoice_tiket" => $invoice_tiket['0']['invoice_tiket'],
                "id" => $cekinvoice[0]['id'],
                "gambar" => $sukses,
                "title" => $cekinvoice[0]['status_transaksi'],
                "qr_strings" => $dataQR,
                "kuantiti" => $banyak,
                "gambaran" => $path
            ]);

        } elseif (isset($cekinvoice[0]['invoices']) == $request && $cekinvoice[0]['status_transaksi'] == "PENDING") {
            return view('redirect', [
                "invoice" => $request,
                "gambar" => $pending,
                "title" => $cekinvoice[0]['status_transaksi'],
                "body" => "Segera selesaikan Pembayaran anda untuk mendapatkan e-ticket"
            ]);
        } elseif (isset($cekinvoice[0]['invoices']) == $request && $cekinvoice[0]['status_transaksi'] == "FAILED") {
            return view('redirect', [
                "invoice" => $request,
                "gambar" => $gagal,
                "title" => $cekinvoice[0]['status_transaksi'],
                "body" => "Mohon coba kembali"
            ]);
        } else {
            return view('notfound', [
                "title" => "Data Tidak ditemukan",
                "body" => "Coba kontak customer service",
                "gambar" => $gambar1
            ]);

        }

    }
}
