<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\MesinAbsensi;
use App\Models\User;
use Carbon\Carbon;
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

    public function getRequest()
    {
        $mesin = MesinAbsensi::firstOrCreate(['sn' => request('SN')]);
        $mesin->with([
            'commands' => function ($query) {
                $query->whereNull('completed_at');
            }
        ]);
        $mesin->update(['last_sync' => now()]);
        $commands = $mesin->commands;
        if ($commands->isEmpty()) {
            return response('OK', 200, ['Content-Type' => 'text/plain']);
        }
        $temp = "";
        foreach ($commands as $command) {
            $temp .= $command->command . "\n";
            $command->update(['transmit_at' => now()]);
        }
        if ($temp == "") {
            return response('OK', 200, ['Content-Type' => 'text/plain']);
        }
    }

    public function postCdata()
    {
        if (!request()->has('SN')) {
            return response('Error: sn is required', 400, ['Content-Type' => 'text/plain']);
        }
        if (!request()->has('table')) {
            return response('Error: table is required', 400, ['Content-Type' => 'text/plain']);
        }
        $count = 0;
        $sn = request('SN');
        $mesin = MesinAbsensi::firstOrCreate(['sn' => $sn]);
        $mesin->update(['last_sync' => now()]);
        switch (request('table')) {
            case 'ATTLOG':
                $rows = explode("\n", request()->getContent());
                $rows = array_filter($rows, function ($row) {
                    return !empty($row);
                });
                $rows = array_map(function ($row) {
                    return explode("\t", $row);
                }, $rows);
                $userPins = array_map(function ($row) {
                    return $row[0];
                }, $rows);
                $users = Employee::whereIn('pin', $userPins)->get();
                foreach ($rows as $row) {
                    $user = $users->firstWhere('pin', $row[0]);
                    if (empty($user)) {
                        continue;
                    }
                    $temp_waktu = Carbon::parse($row[1]);

                    $targetTime = $temp_waktu->copy()->setTime(12, 0);
                    if ($targetTime->greaterThan($temp_waktu)) {
                        $status = 'out';
                        $row[2] = 1;
                    } else {
                        $status = 'in';
                        $row[2] = 0;
                    }

                    $user->attendanceLogs()->create([
                        'datetime' => $temp_waktu,
                        'status' => $status,
                    ]);
                }
                break;

            default:
                # code...
                break;
        }

        return response('OK', 200, ['Content-Type' => 'text/plain']);
    }
}
