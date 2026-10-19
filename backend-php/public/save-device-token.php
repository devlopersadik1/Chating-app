<?php
declare(strict_types=1);
require_once __DIR__.'/../vendor/autoload.php';
use App\Auth; use App\Http;
Http::json(['error'=>'FCM token registration is disabled until verified authentication is implemented'],501);