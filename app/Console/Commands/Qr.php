<?php

namespace App\Console\Commands;

use QrCode;
use Illuminate\Console\Command;
use Illuminate\Foundation\Auth\User;

class Qr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Qr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = \App\Models\User::all();

        foreach ($users as $i) {
            $qrCode = str_random(10);
            QrCode::format('png')->size(200)->generate(route('register', ['qr' => $qrCode]), public_path('storage/qr/' . $qrCode . '.png'));
            $i->qr_code = $qrCode;
            $i->save();
        }
    }
}
