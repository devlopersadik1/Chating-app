<?php
declare(strict_types=1);
require_once __DIR__.'/../vendor/autoload.php';
use App\Database; use App\Http;
try {
 $data=Http::body(); $name=trim((string)($data['display_name']??'')); $email=strtolower(trim((string)($data['email']??''))); $password=(string)($data['password']??'');
 if ($name==='' || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($password)<10) Http::json(['error'=>'Provide name, valid email, and password of at least 10 characters'],422);
 $db=Database::connect(); $stmt=$db->prepare('INSERT INTO users(display_name,email,password_hash) VALUES(?,?,?)');
 $stmt->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT)]);
 Http::json(['ok'=>true,'user_id'=>(int)$db->lastInsertId()],201);
} catch (PDOException $e) { if ((string)$e->getCode()==='23000') Http::json(['error'=>'Email already registered'],409); Http::json(['error'=>'Database error'],500); }
catch (Throwable $e) { Http::json(['error'=>'Server configuration error'],500); }