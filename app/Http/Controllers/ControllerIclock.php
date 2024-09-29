<?php

namespace App\Http\Controllers;

use App\Models\MesinAbsensi;
use Illuminate\Http\Request;

class ControllerIclock extends Controller
{
    public function getCdata()
    {
        if (!request()->has('SN')) {
            return response('Error: sn is required', 400, ['Content-Type' => 'text/plain']);
        }
        $sn = request('SN');
        $mesin = MesinAbsensi::firstOrCreate(['sn' => $sn]);
        $mesin = MesinAbsensi::where('sn', $sn)->first();
        $mesin->update(['last_sync' => now()]);

        if (request()->has('options') && request('options') == 'all') {
            return response(
                "GET OPTION FROM: {$sn}\n"
                    . "ATTLOGStamp=None\n"
                    . "OPERLOGStamp=None\n"
                    . "ATTPHOTOStamp=None\n"
                    . "USERPHOTOStamp=None\n"
                    . "ErrorDelay=60\n"
                    . "Delay=10\n"
                    . "TransTimes=00:00;14: 05\n"
                    . "TransInterval=1\n"
                    . "TransFlag=1110011000\n"
                    . "TimeZone=7\n"
                    . "Realtime=1\n"
                    . "Encrypt=None\n",
                200,
                [
                    'Content-Type' => 'text/plain',
                    'Cache-Control' => 'no-cache',
                    'Pragma' => 'no-cache',
                ]
            );
        }
        return response('OK', 200, ['Content-Type' => 'text/plain']);
    }
}
