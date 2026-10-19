<?php
declare(strict_types=1);
namespace App;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
final class FcmSender {
 public static function sendToToken(string $token,string $title,string $body,array $data=[]): string {
  $credentials=getenv('FIREBASE_CREDENTIALS') ?: '';
  if ($credentials==='' || !is_file($credentials)) throw new \RuntimeException('Firebase credentials are not configured.');
  $messaging=(new Factory())->withServiceAccount($credentials)->createMessaging();
  $stringData=[]; foreach($data as $key=>$value) if(is_scalar($value)) $stringData[(string)$key]=(string)$value;
  $message=CloudMessage::withTarget('token',$token)->withNotification(Notification::create($title,$body))->withData($stringData);
  $messaging->send($message); return 'sent';
 }
}