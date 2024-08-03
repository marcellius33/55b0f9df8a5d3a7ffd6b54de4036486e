# How to run the application

1. copy .env.example to .env
2. run `docker compose up --build` to run all services
3. run `docker exec -it email-api-app bash` to enter the app container
4. run `php public/migrate.php` to run migration
5. run `php public/run_worker.php` to run the worker to send the email

TODO

1. Authentication middleware
2. Worker / Queue to send email (ok)
3. Store email message to DB (ok)
4. Documentation
