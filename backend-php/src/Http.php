<?php
declare(strict_types=1);
namespace App;
final class Http {
 public static function json(array $data, int $status=200): never {
  http_response_code($status); header('Content-Type: application/json; charset=utf-8'); header('Cache-Control: no-store');
  echo json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); exit;
 }
 public static function body(): array {
  $data=json_decode(file_get_contents('php://input') ?: '',true);
  if (!is_array($data)) self::json(['error'=>'Invalid JSON body'],400);
  return $data;
 }
}