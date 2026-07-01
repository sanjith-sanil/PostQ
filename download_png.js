const zlib = require('zlib');
const https = require('https');
const fs = require('fs');

const diagram = `graph LR
    classDef default fill:#ffffff,stroke:#000000,stroke-width:1.5px,color:#000000,font-family:sans-serif;
    
    subgraph Col1 [1. REGISTRATION & KEYGEN]
        direction TB
        A[User: Enter Credentials] --> B[Scrypt Hashing]
        B --> C{Split Key}
        C -->|Half 1| D[Auth Key]
        C -->|Half 2| E[Encryption Key]
        F[Client: NTRU Prime KeyGen] --> G[NTRU Key Pair]
        E --> H[AES Encrypt Private Key]
        G --> H
    end

    subgraph Col2 [2. ZERO-KNOWLEDGE AUTH]
        direction TB
        I[Server: Bcrypt Hashing] --> J[Store in Database]
        K[User: Login Request] --> L[Server: Verify Bcrypt]
        L --> M[Return Encrypted Key]
        M --> N[Client: Decrypt Private Key]
    end

    subgraph Col3 [3. POST-QUANTUM KEY EXCHANGE]
        direction TB
        O[Initiate Friend Request] --> P[Generate Shared Secret]
        P --> Q[NTRU Encapsulation]
        Q --> R[Encrypt with Friend PubKey]
        R --> S[Server: Forward Request]
    end

    subgraph Col4 [4. SECURE COMMUNICATION]
        direction TB
        T[Text Message Input] --> U[AES Encryption + Counter]
        U --> V[Server: Store Ciphertext]
        W[WebRTC Call Init] --> X[P2P Connection Established]
        X --> Y[DTLS & SRTP Secure Stream]
    end
    
    D -.->|Transmitted| I
    H -.->|Transmitted| J
    Col1 ==> Col2
    Col2 ==> Col3
    Col3 ==> Col4`;

const compressed = zlib.deflateSync(Buffer.from(diagram, 'utf8'));
const encoded = compressed.toString('base64').replace(/\+/g, '-').replace(/\//g, '_');
const url = `https://kroki.io/mermaid/png/${encoded}`;

const file = fs.createWriteStream('d:/PostQ-master/img/postq_architecture.png');
https.get(url, (response) => {
    response.pipe(file);
    file.on('finish', () => {
        file.close();
        console.log('Download completed. Image saved to img/postq_architecture.png');
    });
}).on('error', (err) => {
    console.error('Error:', err.message);
});
