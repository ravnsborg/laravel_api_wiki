

## About
This REST API can be used to access the data layer of my demo Wiki personal information tool. It enables seamless management of articles and categories currently with more functionality to follow. It provides a structured, reliable interface for creating, retrieving, updating, and deleting these data layers.

---

## Setup
1. In the terminal, run the following command to install the framework and other composer packages that are needed.

   ``` composer install```

2. Once packages are installed, copy the `.env.example` to `.env` and add your DB configuration
    ```
    APP_URL=http://127.0.0.1:1234 // Add your own port number
    APP_PORT=1234

    # This config must be updated to match actaul frontend URL for CORS authentication
    FRONT_END_SITE_URL=http://localhost:5173
   ```

3. Generate a new application key.
    ```
   php artisan key:generate
   ```
4. Generate a new JWT secret key.
    ```
   php artisan jwt:secret
   ```

5. Run DB migrations to setup your table schema
   ```
   php artisan migrate
   ```


6. Setup the node package manager then start it up. ** If npm is not install, install `node` first

    ```sh
    brew install node
    ```

    ```
    npm install
    npm run dev
    ```
7. After any environment changes, you need to clear the config cache.
    ```
   php artisan config:clear
   ```
---

## Code Style with Pint "manual"

This is using Laravel's code style preset called Pint. To ensure that your code is formatted correctly, run:

    ./vendor/bin/pint

This will reformat all files to match the coding style. Not just the files you've changed.

## Code Style with Pint "automatic"
Another option is to have Pint run automatically on code commits.

Run the following commands.

    mkdir -p .git/hooks
    touch .git/hooks/pre-commit
    chmod +x .git/hooks/pre-commit    

Then edit this file: `.git/hooks/pre-commit`


    #!/bin/sh
    
    echo "Running Laravel Pint via Sail..."
    
    ./vendor/bin/pint
    
    if [ $? -ne 0 ]; then
    echo "Pint failed. Please fix formatting before committing."
    exit 1
    fi

---

### Test "Seeder" Content
There are DB seeders in this applicaiton for populating your database with sample content if you so choose.

    ./vendor/bin/sail artisan db::seed


### Login and Authentication
To authenticate, send a `POST` request to `/api/login` with JSON body of your credentials.
```
    {
        "email": "your-email@example.com",
        "password": "your-password"
    } 
``` 
A generated Bearer token is now avaiilable to be used on your endpoints.
```
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJh...",
  "token_type": "bearer",
  "expires_in": 3600
}
```

d
