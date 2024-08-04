# How to run the application

1. copy `.env.example` to `.env`
2. run `docker compose up --build` to run all services
3. run `docker exec -it email-api-app bash` to enter the app container
4. run `php public/migrate.php` to run migration
5. run `php public/run_worker.php` to run the worker to send the email

# How to setup OAuth2

1. run `openssl genrsa -out private.key 2048` to generate private.key
2. run `openssl rsa -in private.key -pubout -out public.key` to generate public.key
3. run `openssl rand -base64 32` to generate encryption.key and put the value to ENCRYPTION_KEY in .env
4. run `chown www-data:www-data ...` if got permission problem inside app container

# How to test the REST API

1. Get access token from `POST /access-token`

```
curl --location 'http://localhost:8080/access-token' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'grant_type=client_credentials' \
--data-urlencode 'client_id=email_api' \
--data-urlencode 'client_secret=email_api'
```

2. Send Email from `POST /send-email`

- Make sure you have filled the .env, you can use Mailtrap for testing

```
curl --location 'http://localhost:8080/send-email' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJlbWFpbF9hcGkiLCJqdGkiOiJjNzUwNDM0OTEzZjNlODI5NzNjOTJlMzJiNjJmNzE4NmVkZGExYWU0ZDEwYmJlZTE4NWNkNTMyZjk2NTljMGQwMDQ3MzIxMTljZmY4NDU1ZCIsImlhdCI6MTcyMjc3NjUyMi4yODg4MjgsIm5iZiI6MTcyMjc3NjUyMi4yODg4MywiZXhwIjoxNzIyNzgwMTIyLjI3NjQ4Mywic3ViIjoiZW1haWxfYXBpIiwic2NvcGVzIjpbXX0.A9mvtqt6JSU3u_uLoqxHEAjDGxWc3F-8-1bkjwokA3cFhQu-uSMi3h7DkDSEJV2bNyMRqu_N0MM8r8ivxAltv8A9YUNLzTmXTpU5cFZVyWx0x9v6ihoFpvJNBbSgMxTpM2DLDB8X8_GoZfOYUtU8oQLYEYVr_vXIaGVpKYz8Nvqg-n7trLwCUKgLCMDqxyJX1qbfd6ffahjbk7LDA-MEpjd2Jxy-y9b1n9GtdqZKA5-XrYOfqsdb4Sd_sV-SKfE_qdJaLeDck2trW9kCfjg-3jUNEU7y9Ghhc6of1wwOHNBxr6PiPPpjRdbPxdVFdsX2XRV5jCV5UI8Ou5-WyO7-pg' \
--data-raw '{
    "to": "mad@gmail.com",
    "subject": "Billing November",
    "body": "You have $1000 billing for November"
}'
```
