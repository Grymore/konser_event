<?php

namespace App\Http\Controllers;
use App\Mail\KirimEmail;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SentEmailController extends Controller
{
    public function kirimemail() {

        $data = [
            'title' => 'Selamat datang!',
            'url' => 'https://aantamim.id',
            'papa' => 'semnagat okok'
        ];


        $emailcust = json_decode(DB::table('customers')
            ->where('email', 'padndu@sdl.com')
            ->get('email'), true);
            


            Mail::to($emailcust)->send(new KirimEmail($data));

            return view('pesanemail');

}
}
