<?php
declare(strict_types=1);
require __DIR__.'/config.php';
if(admin_user()){header('Location: dashboard.php');exit;}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 require_csrf();
 $email=strtolower(trim((string)($_POST['email']??'')));$pw=(string)($_POST['password']??'');$key=(string)getenv('ADMIN_AUDIT_HASH_KEY');
 $eh=hash_hmac('sha256',$email,$key);$ih=hash_hmac('sha256',(string)($_SERVER['REMOTE_ADDR']??''),$key);
 $q=$pdo->prepare('SELECT COUNT(*) FROM admin_login_attempts WHERE attempted_at > (NOW() - INTERVAL 15 MINUTE) AND (email_hash=? OR ip_hash=?)');$q->execute([$eh,$ih]);$blocked=(int)$q->fetchColumn()>=10;
 $pdo->prepare('INSERT INTO admin_login_attempts(email_hash,ip_hash) VALUES(?,?)')->execute([$eh,$ih]);
 $q=$pdo->prepare('SELECT id,name,email,password_hash,role,is_active FROM admin_users WHERE email=? LIMIT 1');$q->execute([$email]);$u=$q->fetch();
 if(!$blocked&&$u&&(int)$u['is_active']===1&&password_verify($pw,$u['password_hash'])){
  session_regenerate_id(true);$_SESSION['admin_id']=(int)$u['id'];$_SESSION['admin_role']=$u['role'];$_SESSION['admin_name']=$u['name'];$_SESSION['csrf']=bin2hex(random_bytes(32));
  $pdo->prepare('UPDATE admin_users SET last_login_at=NOW() WHERE id=?')->execute([$u['id']]);audit($pdo,'admin.login');header('Location: dashboard.php');exit;
 }
 usleep(250000);$error='Login failed. Check your details or try again later.';
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Talviro Chat Admin</title><style>body{font-family:system-ui;background:#0b1020;color:#f5f7ff;display:grid;place-items:center;min-height:100vh;margin:0}.card{width:min(390px,90vw);padding:28px;border:1px solid #29324c;border-radius:20px;background:#121a2e}label{display:block;margin:14px 0 6px}input{box-sizing:border-box;width:100%;padding:13px;border-radius:10px;border:1px solid #3a4563;background:#0b1020;color:white}button{margin-top:18px;width:100%;padding:13px;border:0;border-radius:10px;background:#6c7cff;color:white;font-weight:700}.err{color:#ff9eaa}</style></head><body><main class="card"><h1>Talviro Chat</h1><p>Owner & Admin Console</p><?php if($error):?><p class="err"><?=e($error)?></p><?php endif;?><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><label for="email">Admin email</label><input id="email" name="email" type="email" maxlength="190" required autocomplete="username"><label for="password">Password</label><input id="password" name="password" type="password" required autocomplete="current-password"><button>Sign in securely</button></form></main></body></html>
