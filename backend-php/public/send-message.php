<?php
declare(strict_types=1);
require_once __DIR__.'/../vendor/autoload.php';
use App\Auth; use App\Http;
Http::json(['error'=>'Messaging is disabled until verified authentication and conversation membership checks are implemented'],501);
// Do not accept sender_id from client input.