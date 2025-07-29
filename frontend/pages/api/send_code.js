import dbm from '../../lib/db';
import { send } from '../../lib/mailer';

export default async function handler(req, res) {
  if (req.method !== 'POST') return res.status(405).end();
  const { email } = req.body;
  if (!email) return res.status(400).end();
  const db = dbm.load();
  const code = Math.floor(100000 + Math.random() * 900000).toString();
  const expiry = Date.now() + 10 * 60 * 1000;
  db.codes = db.codes.filter(c => c.email !== email);
  db.codes.push({ email, code, expiry });
  if (!db.users.find(u => u.email === email)) {
    const id = db.users.length + 1;
    db.users.push({ id, email, blocked: false, plan: null, trial_used: false });
  }
  dbm.save(db);
  await send(email, 'Your login code', `Code: ${code}`);
  res.status(200).end();
}
