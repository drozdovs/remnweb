# RemnWeb Billing Example

This is a minimal billing demo for selling VPN subscriptions.
The backend is written in PHP and exposes simple API endpoints. The frontend
is built with Next.js.

Authentication uses one-time codes that are sent to the user's email via SMTP.
Payments are processed with the YooKassa API and a call is made to the
Remnawave API defined by `REMNAWAVE_API_URL`.

## Setup

1. Install PHP 8 and Composer.
2. Run `composer install` to install dependencies.
3. Create a MySQL database and user and put the connection details in `.env`.
   An admin account will be created automatically using `ADMIN_USER` and
   `ADMIN_PASS` from the environment file.
4. Copy `.env.example` to `.env` and fill in your configuration values.
5. Install Node.js dependencies inside the `frontend` directory:

```bash
cd frontend
npm install
```

6. Start the PHP built-in server:

```bash
php -S localhost:8000 -t public
```

7. In another terminal, run the Next.js development server:

```bash
npm run dev
```

8. Open `http://localhost:3000` in your browser to use the interface. The admin
   panel is available at `/admin`.

All data is stored in MySQL. Default subscription plans (basic, pro and trial)
are inserted automatically. The trial plan can only be used once per user and
can be edited through the admin panel. The `Billing` class performs a simple
request to the Remnawave API; replace it with real API calls for production use.
