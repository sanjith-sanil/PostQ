<div align="center">

# 🛡️ PostQ

**Post-Quantum Secure Messenger**

A web-based end-to-end encrypted messenger application built with post-quantum cryptography — designed to stay secure even in the era of quantum computing.

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)]()
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E?logo=javascript&logoColor=black)]()
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?logo=mysql&logoColor=white)]()

</div>

---

## ⚠️ Security Disclaimer

> **This project is for educational and research purposes only.**
>
> The messaging protocol and implementation have not undergone professional security audits. Cryptography in JavaScript is inherently difficult to secure, and the libraries used here are not designed for production-grade secure operations. **Do not use this in production for sensitive data.**

---

## ✨ Features

- **🔐 Post-Quantum Encryption** — Uses NTRU Prime (lattice-based) for key exchange, resistant to quantum attacks
- **🔑 End-to-End Encryption** — All messages are encrypted/decrypted client-side; the server never sees plaintext
- **💬 Real-Time Messaging** — Instant encrypted messaging between connected users
- **📹 Video & Audio Calls** — Peer-to-peer WebRTC calls secured with DTLS+SRTP
- **🛡️ Zero-Knowledge Auth** — Password is split: one half for client-side encryption (never sent), one half for server authentication
- **🌙 Modern Dark UI** — Glassmorphism design with smooth animations and responsive layout
- **💾 Remember Me** — Optional local session persistence via browser localStorage

---

## 🚀 Getting Started

### Prerequisites

You need a local web server environment with:
- **PHP 7.4+** (tested with PHP 8.2)
- **MySQL / MariaDB**

> 💡 The easiest way on Windows is to install [XAMPP](https://www.apachefriends.org/index.html), which bundles Apache, PHP, and MySQL together.

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd PostQ
   ```

2. **Configure the database connection**
   ```bash
   cp sqlconfig.php_example sqlconfig.php
   ```
   Edit `sqlconfig.php` and fill in your database credentials:
   ```php
   $sqlservername = "127.0.0.1";
   $sqlusername   = "root";        // your MySQL username
   $sqlpassword   = "";            // your MySQL password
   $sqldbname     = "postq";       // your database name
   ```

3. **Create the database**
   
   Using MySQL CLI:
   ```bash
   mysql -u root -e "CREATE DATABASE IF NOT EXISTS postq;"
   ```
   Or create a database named `postq` through phpMyAdmin.

4. **Run the installation script**
   
   Option A — Via the built-in PHP dev server:
   ```bash
   php -S localhost:8000
   ```
   Then open `http://localhost:8000/install.php` in your browser.
   
   Option B — If using XAMPP, copy the project folder to `C:\xampp\htdocs\PostQ` and open `http://localhost/PostQ/install.php`.

5. **Start using PostQ!**
   
   Navigate to `http://localhost:8000/index.html` (or the equivalent URL for your setup).

6. **(Optional) Enable email verification**
   
   Edit `mail_config.php` and configure your PHP `mail()` function to send emails from your server.

---

## 📖 How to Use

1. **Register** — Open the app, enter a username and password, and click **Register**. The app generates your post-quantum encryption keys automatically.
2. **Sign In** — Enter your credentials and click **Sign In**. Your password is hashed client-side; only the authentication half is sent to the server.
3. **Add a Friend** — Click **New Friend** in the sidebar, enter a friend's username, and click **Add Friend**. A shared secret is generated and securely exchanged using NTRU Prime.
4. **Accept a Friend Request** — Click **Friend Requests** in the sidebar to see and accept/reject pending requests.
5. **Chat** — Click on a friend's name in the sidebar to open the chat. All messages are encrypted with AES using the shared secret.
6. **Video/Audio Call** — Click the **Call** button in an open chat to start a peer-to-peer WebRTC call.

---

## 🏗️ Architecture

### Tech Stack

| Layer | Technology |
|-------|-----------|
| **Frontend** | HTML5, CSS3 (dark glassmorphism theme), JavaScript (jQuery) |
| **Backend** | PHP |
| **Database** | MySQL / MariaDB |
| **Crypto — Public Key** | NTRU Prime (post-quantum, lattice-based) |
| **Crypto — Symmetric** | AES (with message counter to prevent replay attacks) |
| **Crypto — Password Hash** | Scrypt (client-side) + Bcrypt (server-side) |
| **Video Calls** | WebRTC (DTLS + SRTP) |

### Project Structure

```
PostQ/
├── index.html              # Main application entry point
├── style.css               # Dark glassmorphism UI theme
├── sqlconfig.php_example   # Database config template
├── install.php             # Database table creation script
│
├── # Client-side cryptography
├── ntru.js                 # NTRU Prime key generation & encapsulation
├── polynomial.js           # Polynomial arithmetic for NTRU (modified)
├── aes.js                  # AES encryption/decryption wrappers
│
├── # Application logic (client)
├── postq.js                # Page initialization & message sending
├── signin.js               # Login, registration, scrypt hashing
├── addFriend.js            # Friend addition with key exchange
├── getFriendList.js        # Sidebar menu generation
├── handleFriendRequests.js # Accept/reject friend requests
├── show.js                 # View switching & message display
├── call.js                 # WebRTC video/audio call logic
├── signalMsg.js            # WebRTC signaling message helper
├── NTRUkeygenWorker.js     # Web Worker for NTRU key generation
│
├── # Backend (PHP)
├── register.php            # User registration endpoint
├── login.php               # Authentication endpoint
├── addFriend.php           # Friend request creation
├── acceptRequest.php       # Accept friend request
├── rejectRequest.php       # Reject friend request
├── getPublicKey.php        # Retrieve a user's public key
├── getFriendList.php       # Retrieve friend list
├── getMessages.php         # Retrieve chat messages
├── sendMsg.php             # Send encrypted message
├── getNewRequests.php      # Retrieve pending friend requests
├── getSymkeyRequests.php   # Retrieve symmetric key change requests
├── initChangeSymkey.php    # Initiate symmetric key change
├── acceptChangeSymkey.php  # Accept symmetric key change
├── helpers.php             # Shared PHP utility functions
├── sqlconnect.php          # Database connection helper
├── verify.php              # Email verification endpoint
├── mail_config.php         # Email configuration
│
├── external/               # Third-party JS libraries
├── img/                    # Screenshots and protocol diagrams
├── database_setup.sql      # Database schema
└── README.md               # This file
```

---

## 🔒 The Protocol

### Attack Model

The assumed attacker is powerful but passive (e.g., a lawful intercept scenario). The attacker can read every entry in the database and has full access to the codebase, but cannot modify the served code.

### Registration

1. User enters a username and password.
2. The password is hashed on the client side with **Scrypt** to produce a 256-bit key.
3. The key is split in two:
   - **Encryption Key** (first 128 bits) — stays on the client, never sent to the server.
   - **Authentication Key** (last 128 bits) — sent to the server, hashed again with Bcrypt before storage.
4. An **NTRU Prime** key pair is generated on the client.
5. The public key is sent to the server in plaintext. The private key is AES-encrypted with the encryption key, then stored on the server.

### Login

1. The password is hashed and split the same way as during registration.
2. The username + authentication key are sent to the server for verification.
3. The server returns the user's AES-encrypted private key.
4. The client decrypts the private key using the encryption key.

### Adding a Friend (Key Exchange)

1. User A generates a random shared secret.
2. The shared secret is encrypted with User B's NTRU public key (post-quantum secure).
3. The encrypted shared secret is sent to the server.
4. User A also encrypts the shared secret with their own encryption key for storage.

### Accepting a Friend Request

1. User B decrypts the shared secret using their NTRU private key.
2. User B re-encrypts the shared secret with their own encryption key for local storage.

### Messaging

1. User retrieves and decrypts the shared secret from the server.
2. Messages are encrypted with **AES** using the shared secret.
3. A counter is prepended to each message before encryption to prevent replay attacks and ensure ciphertext uniqueness.

### Video/Audio Calls

WebRTC-based peer-to-peer calls use the same encrypted signaling channel as chat messages. Media is transmitted over DTLS+SRTP as per the WebRTC standard.

---

## 🔬 Post-Quantum Cryptography

### Why Post-Quantum?

Current public-key algorithms (RSA, ECDH, Diffie-Hellman) rely on mathematical problems that quantum computers can solve efficiently:

- **[Shor's Algorithm](https://en.wikipedia.org/wiki/Shor's_algorithm)** — Factorizes large numbers in polynomial time, breaking RSA and DH.
- **[Grover's Algorithm](https://en.wikipedia.org/wiki/Grover%27s_algorithm)** — Reduces brute-force search time for symmetric keys by half (e.g., AES-128 becomes effectively AES-64).

### AES (Symmetric Key)

AES is not broken by quantum computers — Grover's algorithm only halves the effective key length. PostQ uses AES with sufficient key sizes to remain secure in a post-quantum world.

### NTRU Prime (Public Key)

PostQ uses **[NTRU Prime](https://ntruprime.cr.yp.to/ntruprime-20160511.pdf)** (2016), a lattice-based public key cryptosystem that relies on the [Closest Vector Problem](https://en.wikipedia.org/wiki/Lattice_problem#Closest_vector_problem_.28CVP.29). Unlike classic NTRU, NTRU Prime operates over fields (not just rings), eliminating certain algebraic attacks.

---

## 📦 External Libraries

All libraries except Polynomial.js are unmodified and stored in the `external/` directory.

| Library | Purpose |
|---------|---------|
| [jquery.scrollTo](https://github.com/flesler/jquery.scrollTo) | Smooth scroll to new messages |
| [scrypt-js](https://github.com/ricmoo/scrypt-js) | Client-side Scrypt hash generation |
| [aes-js](https://github.com/ricmoo/aes-js) | Client-side AES encryption |
| [secure-random](https://github.com/jprichardson/secure-random) | Cryptographic random number generation |
| [jquery-csv](https://github.com/evanplaice/jquery-csv) | CSV parsing for data exchange |
| [Polynomial.js](https://github.com/infusion/Polynomial.js/) | Polynomial arithmetic — **modified** for truncated polynomial ring Zp/f |
| [js-sha512](https://github.com/emn178/js-sha512) | SHA-512 hash generation |
| [adapter.js](https://webrtc.github.io/adapter/adapter-latest.js) | WebRTC cross-browser compatibility |

---

## 🛣️ Future Development

- [ ] Automatic shared secret rotation after a set number of messages or time period
- [ ] "Forgot my password" functionality
- [ ] Hybrid encryption: combine NTRU with a classical algorithm (ECDH/RSA) as a fallback
- [ ] Dockerize the project for easier setup and contribution
- [ ] End-to-end integration tests
- [ ] CI/CD pipeline for automated testing on pull requests
