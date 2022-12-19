<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class DownloadTicketController extends Controller
{
    public function print($request)
    {

        $path = base_path("public/images/banner.webp");
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $image = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $cariin = json_decode(DB::table('customers')->where('invoices', $request)->get('invoices'), true);

        $dataQR = DB::table('qr_codes')
            ->join('customers', 'qr_codes.customer_id', '=', 'customers.id')
            ->where('customers.invoices', $request)->get();

        $banyak = DB::table('qr_codes')
            ->join('customers', 'qr_codes.customer_id', '=', 'customers.id')
            ->where('customers.invoices', $request)->count();
            
        $invoice_tiket = json_decode(DB::table('qr_codes')
            ->join('customers', 'qr_codes.customer_id', '=', 'customers.id')
            ->where('customers.invoices', $request)->get('customers.invoice_tiket'), true);

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadview("download_2", [
            "body" => $request,
            "qr_strings" => $dataQR,
            "kuantiti" => $banyak,
            "banner" => $image,
            "invoice_tiket" => $invoice_tiket[0]['invoice_tiket']
        ])->setPaper('f4', 'landscape');


        // return view('print_ticket',([
        //     'qr_strings' => $dataQR
        // ]));


        // return $pdf->stream('invoice.pdf');

        // <div class="ticket-id"> <img src="data:image/png;base64, {!! base64_encode(QrCode::size(100)->generate($qrcode->qr_string)) !!} "></div>


        return $pdf->stream('E-Ticket-'.$invoice_tiket[0]['invoice_tiket'].".pdf");



    }
}
