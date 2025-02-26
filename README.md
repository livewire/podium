# Podium

An example app for fielding questions from your audience.

![A screenshot of the app](/screenshot.png)

## Installation

After cloning this repo, run the following commands from your project root:

```bash
cp .env.example .env
composer install
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan flux:activate
npm install
npm run dev
```

Now, open the project in your browser (Typically `http://podium.test`) and use the following credentials to log in:

```
User: test@example.com
Pass: password
```

## Project Structure

These are the key files to reference in this project:

```
podium/
├── routes/
│   └── web.php                      # Main web routes
│
└── resources/
    └── views/
        ├── dashboard.blade.php      # Question dashboard page
        └── livewire/
            └── question/
                ├── show.blade.php   # Question list item
                └── create.blade.php # Create question button & modal
```

## References

- [Flux "Q&A Board" demo page →](https://fluxui.dev/demos)
- [Livewire $js() documentation](https://livewire.laravel.com/docs/actions#javascript-actions)
