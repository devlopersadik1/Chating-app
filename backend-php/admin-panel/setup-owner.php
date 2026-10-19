<?php
declare(strict_types=1);
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
require __DIR__.'/config.php';
if($argc<3||!filter_var($argv[1],FILTER_VALIDATE_EMAIL)){fwrite(STDERR,"Usage: php setup-owner.php owner@example.com \"Owner Name\"\n");exit(2);}
if((int)$pdo->query("SELECT COUNT(*) FROM admin_users WHERE role='owner'")->fetchColumn()>0){fwrite(STDERR,"Owner already exists.\n");exit(3);}
$email=strtolower(trim($argv[1]));$name=trim($argv[2]);
if($name===''||mb_strlen($name)>100){fwrite(STDERR,"Invalid name.\n");exit(2);}
fwrite(STDOUT,"Enter a unique password (12+ characters): ");system('stty -echo');$pw=trim((string)fgets(STDIN));system('stty echo');fwrite(STDOUT,"\n");
if(strlen($pw)<12){fwrite(STDERR,"Password too short.\n");exit(2);}
$q=$pdo->prepare("INSERT INTO admin_users(name,email,password_hash,role,is_active) VALUES(?,?,?,'owner',1)");
try{$q->execute([$name,$email,password_hash($pw,PASSWORD_DEFAULT)]);}catch(Throwable $e){fwrite(STDERR,"Creation failed; check DB/email.\n");exit(4);}
$pw='';fwrite(STDOUT,"Owner created. Delete setup-owner.php now.\n");
