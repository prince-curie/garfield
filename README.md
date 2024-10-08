# Garfield
A task where I get to play with the static analysis tool Larastan while completing an assigned task.
## About Garfield

Garfield contains two branches. The `feature/create-user` branch seeds about twenty(20) randomly generated data into the users table and has a command that accepts a user's id and updates the user data with randomly generated data.
The `feature/send-updated-data-to-provider` branch uses an observer class to listen for when a user's data is updated and sends only the changed attributes to a cache. Once the cache contains a thousand (1000) records. The records are logged and removed from the cache. 

Available commands
Run Ide-helper
```bash
composer generate-model-doc
```

Run linter
```bash
composer code-lint
```
Run static analysis
```bash
composer code-analyses
```

## Setting Up Locally

Clone the repository
run `composer install`
run `cp .env.example .env`
run `php artisan key:generate`
Create a new file names `database.sqlite` in the database folder
Run `php artisan migrate:fresh --seed`

## Testing
Automated test is likely to exist in future.
For now, you can run the command `php artisan user:update-details {user_id}`.
For example `php artisan user:update-details 1`
This updates the user with id one (1).

If you are on the feature/send-updated-data-to-provider. You can check the logs after make up to one thousand updates.

## Security Vulnerabilities

- Any user with physical access to the terminal can change and user's detail by randomly choosing a number and that needs to be restricted.

## Further Updates 
- Write automated test.
- Fix security vulnerablities.