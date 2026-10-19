<?php
declare(strict_types=1);
function envv(string $k, ?string $d=null): ?string { $v=getenv($k); return ($v===false||$v==='')?$d:$v; }
$https=(!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off') || (($_SERVER['HTTP_X_FORWARDED_PROTO']??'')==='https');
if(session_status()!==PHP_SESSION_ACTIVE){ini_set('session.use_strict_mode','1');ini_set('session.use_only_cookies','1');ini_set('session.cookie_httponly','1');ini_set('session.cookie_secure',$https?'1':'0');ini_set('session.cookie_samesite','Strict');session_name('talviro_admin');session_start();}
header('X-Content-Type-Options: nosniff'); header('X-Frame-Options: DENY'); header('Referrer-Policy: no-referrer');
header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline'; form-action 'self'; frame-ancestors 'none'; base-uri 'self'");
$h=envv('DB_HOST');$n=envv('DB_NAME');$u=envv('DB_USER');$p=envv('DB_PASS');$key=envv('ADMIN_AUDIT_HASH_KEY');
if(!$h||!$n||!$u||$p===null||!$key||strlen($key)<32){http_response_code(500);exit('Server configuration incomplete.');}
try{$pdo=new PDO("mysql:host=$h;dbname=$n;charset=utf8mb4",$u,$p,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);}
catch(Throwable $e){error_log('Talviro admin DB error: '.$e->getMessage());http_response_code(500);exit('Service unavailable.');}
function e(?string $v):string{return htmlspecialchars($v??'',ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
function csrf_token():string{if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(32));return $_SESSION['csrf'];}
function require_csrf():void{$s=$_POST['csrf']??'';if(!is_string($s)||!hash_equals($_SESSION['csrf']??'',$s)){http_response_code(403);exit('Invalid request token.');}}
function admin_user():?array{return isset($_SESSION['admin_id'],$_SESSION['admin_role'])?['id'=>(int)$_SESSION['admin_id'],'role'=>(string)$_SESSION['admin_role'],'name'=>(string)($_SESSION['admin_name']??'')]:null;}
function require_admin():array{$a=admin_user();if(!$a){header('Location: login.php');exit;}return $a;}
function require_owner():array{$a=require_admin();if($a['role']!=='owner'){http_response_code(403);exit('Owner permission required.');}return $a;}
function audit(PDO $pdo,string $action,?string $type=null,?string $id=null,array $details=[]):void{$a=admin_user();$key=(string)getenv('ADMIN_AUDIT_HASH_KEY');$ip=hash_hmac('sha256',(string)($_SERVER['REMOTE_ADDR']??''),$key);$q=$pdo->prepare('INSERT INTO admin_audit_logs(admin_id,action,target_type,target_id,details_json,ip_hash) VALUES(?,?,?,?,?,?)');$q->execute([$a['id']??null,$action,$type,$id,json_encode($details,JSON_INVALID_UTF8_SUBSTITUTE),$ip]);}
