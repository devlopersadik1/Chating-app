package com.talviro.chat;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.os.Build;
import androidx.core.app.NotificationCompat;
import com.google.firebase.messaging.FirebaseMessagingService;
import com.google.firebase.messaging.RemoteMessage;
public class ChatFirebaseMessagingService extends FirebaseMessagingService {
 private static final String CHANNEL_ID="chat_messages";
 @Override public void onNewToken(String token) { super.onNewToken(token); /* Upload after authenticated login. */ }
 @Override public void onMessageReceived(RemoteMessage message) {
  String title=message.getNotification()!=null && message.getNotification().getTitle()!=null ? message.getNotification().getTitle() : "New message";
  String body=message.getNotification()!=null && message.getNotification().getBody()!=null ? message.getNotification().getBody() : "You have a new message";
  NotificationManager manager=(NotificationManager)getSystemService(Context.NOTIFICATION_SERVICE);
  if (Build.VERSION.SDK_INT>=Build.VERSION_CODES.O) manager.createNotificationChannel(new NotificationChannel(CHANNEL_ID,"Chat messages",NotificationManager.IMPORTANCE_HIGH));
  Intent intent=new Intent(this,MainActivity.class); intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP|Intent.FLAG_ACTIVITY_SINGLE_TOP);
  PendingIntent pending=PendingIntent.getActivity(this,0,intent,PendingIntent.FLAG_UPDATE_CURRENT|PendingIntent.FLAG_IMMUTABLE);
  manager.notify((int)System.currentTimeMillis(),new NotificationCompat.Builder(this,CHANNEL_ID).setSmallIcon(android.R.drawable.sym_action_chat).setContentTitle(title).setContentText(body).setAutoCancel(true).setContentIntent(pending).build());
 }
}