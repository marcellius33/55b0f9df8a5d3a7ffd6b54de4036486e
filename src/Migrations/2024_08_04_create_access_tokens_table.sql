CREATE TABLE IF NOT EXISTS access_tokens (
    id VARCHAR(255) PRIMARY KEY,
    client_id VARCHAR(255),
    scope TEXT,
    expires_at TIMESTAMP
);
