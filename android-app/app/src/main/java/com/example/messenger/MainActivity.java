package com.talviro.chat;
import android.os.Bundle;
import android.widget.TextView;
import androidx.appcompat.app.AppCompatActivity;
import com.google.firebase.messaging.FirebaseMessaging;
public class MainActivity extends AppCompatActivity {
 @Override protected void onCreate(Bundle savedInstanceState) {
  super.onCreate(savedInstanceState); setContentView(R.layout.activity_main);
  TextView status=findViewById(R.id.statusText);
  status.setText("Talviro Chat\\nFirebase connected setup\\n\\nNext: deploy Laravel API, then sign in and start chatting.");
  FirebaseMessaging.getInstance().getToken().addOnCompleteListener(task -> {
   if (task.isSuccessful()) { String token=task.getResult(); /* Register token only after authenticated login. Never log token. */ }
  });
 }
}