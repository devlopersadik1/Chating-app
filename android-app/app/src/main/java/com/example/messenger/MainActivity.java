package com.example.messenger;
import android.os.Bundle;
import android.widget.TextView;
import androidx.appcompat.app.AppCompatActivity;
import com.google.firebase.messaging.FirebaseMessaging;
public class MainActivity extends AppCompatActivity {
 @Override protected void onCreate(Bundle savedInstanceState) {
  super.onCreate(savedInstanceState); setContentView(R.layout.activity_main);
  TextView status=findViewById(R.id.statusText);
  status.setText("Messenger Starter\nJava + XML • PHP + MySQL • Firebase FCM\n\nAuthentication, messaging and WebRTC calling still need implementation.");
  FirebaseMessaging.getInstance().getToken().addOnCompleteListener(task -> {
   if (task.isSuccessful()) { String token=task.getResult(); /* Send only after verified login. Never log token. */ }
  });
 }
}