# Voice, video and group calling

Calling is **not implemented yet** in this starter. To add it safely:
1. Add an Android WebRTC library and camera/microphone capture.
2. Implement authenticated real-time signaling for call invite/accept/reject/end, SDP offer/answer and ICE candidates.
3. Add incoming-call push notifications and call timeout/missed-call handling.
4. Configure STUN and TURN for restrictive networks.
5. For group calls, use an SFU such as LiveKit rather than a simple peer mesh for larger groups.

PHP/MySQL should store call metadata; FCM notifies users; WebRTC carries media. Test on real devices and poor networks before claiming calls work.