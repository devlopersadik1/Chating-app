<?php
declare(strict_types=1);require __DIR__.'/config.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){?><!doctype html><html><head><meta charset="utf-8"><title>Sign out</title></head><body><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><button>Confirm sign out</button></form></body></html><?php exit;}
require_csrf();if(admin_user())audit($pdo,'admin.logout');$_SESSION=[];
if(ini_get('session.use_cookies')){$p=session_get_cookie_params();setcookie(session_name(),'',['expires'=>time()-42000,'path'=>$p['path'],'domain'=>$p['domain'],'secure'=>$p['secure'],'httponly'=>$p['httponly'],'samesite'=>'Strict']);}
session_destroy();header('Location: login.php');exit;
