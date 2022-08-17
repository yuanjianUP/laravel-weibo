<?php

namespace App\Console\Commands;

use http\Client\Curl\User;
use Illuminate\Console\Command;

class GenerateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larabbs:make-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '快速生成用户token';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = $this->ask('输入用户id');
        $user = \App\Models\User::find($userId);
        if(!$user){
            return $this->error('用户不存在');
        }
        $ttl = 365*24*60;
        $this->info(auth('api')->setTTL($ttl)->login($user));
    }
}
