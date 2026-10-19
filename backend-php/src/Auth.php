<?php
declare(strict_types=1);
namespace App;
final class Auth {
 public static function requireUserId(): int {
  // Intentionally fail closed. Implement JWT signature/session validation before enabling protected endpoints.
  Http::json(['error'=>'Authentication adapter is not configured'],501);
 }
}