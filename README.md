# RemnWeb Billing Example (Next.js)

This is a minimal billing demo for selling VPN subscriptions. The entire application runs on Next.js and stores data in a JSON file.

Authentication uses one-time codes that are sent via SMTP. Payments are processed with the YooKassa API and the Remnawave API defined by `REMNAWAVE_API_URL`.

## Setup

1. Copy `.env.example` to `.env` and fill in your configuration values.
2. Install Node.js dependencies:

```bash
cd frontend
npm install
```

   On first run the app will create `data/db.json` with a default admin account and plans.

3. Start the development server:

```bash
npm run dev
```

4. Open `http://localhost:3000` in your browser. The admin panel is available at `/admin`.

Data is stored in a simple JSON file and can be swapped for any other storage backend if needed.
