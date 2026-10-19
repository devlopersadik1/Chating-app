CREATE DATABASE IF NOT EXISTS messenger_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE messenger_app;

CREATE TABLE users (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 display_name VARCHAR(80) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 avatar_url VARCHAR(500) NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE devices (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NOT NULL,
 fcm_token VARCHAR(512) NOT NULL UNIQUE,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_devices_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE conversations (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 kind ENUM('direct','group') NOT NULL DEFAULT 'direct',
 title VARCHAR(120) NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE conversation_members (
 conversation_id BIGINT UNSIGNED NOT NULL,
 user_id BIGINT UNSIGNED NOT NULL,
 joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (conversation_id,user_id),
 FOREIGN KEY (conversation_id) REFERENCES conversations(id) ON DELETE CASCADE,
 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE messages (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 conversation_id BIGINT UNSIGNED NOT NULL,
 sender_id BIGINT UNSIGNED NOT NULL,
 body TEXT NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_messages_conversation_time(conversation_id,created_at),
 FOREIGN KEY (conversation_id) REFERENCES conversations(id) ON DELETE CASCADE,
 FOREIGN KEY (sender_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE message_receipts (
 message_id BIGINT UNSIGNED NOT NULL,
 user_id BIGINT UNSIGNED NOT NULL,
 delivered_at DATETIME NULL,
 read_at DATETIME NULL,
 PRIMARY KEY(message_id,user_id),
 FOREIGN KEY(message_id) REFERENCES messages(id) ON DELETE CASCADE,
 FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE call_sessions (
 id CHAR(36) PRIMARY KEY,
 conversation_id BIGINT UNSIGNED NOT NULL,
 caller_id BIGINT UNSIGNED NOT NULL,
 callee_id BIGINT UNSIGNED NOT NULL,
 call_type ENUM('audio','video') NOT NULL,
 status ENUM('ringing','accepted','rejected','missed','ended') NOT NULL DEFAULT 'ringing',
 started_at DATETIME NULL,
 ended_at DATETIME NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(conversation_id) REFERENCES conversations(id) ON DELETE CASCADE,
 FOREIGN KEY(caller_id) REFERENCES users(id),
 FOREIGN KEY(callee_id) REFERENCES users(id)
) ENGINE=InnoDB;