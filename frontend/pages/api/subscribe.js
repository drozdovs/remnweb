import dbm from '../../lib/db';
import * as session from '../../lib/session';

export default async function handler(req, res) {
  if (req.method !== 'POST') return res.status(405).end();
  const token = req.cookies.session;
  const uid = session.get(token, 'user');
  if (!uid) return res.status(401).end();
  const { plan } = req.body;
  const db = dbm.load();
  const user = db.users.find(u => u.id === uid);
  const p = db.plans.find(x => x.name === plan);
  if (!p) return res.status(400).end();
  if (p.trial) {
    if (user.trial_used) return res.status(400).json({ error: 'used' });
    user.trial_used = true;
    user.plan = p.name;
    dbm.save(db);
    return res.status(200).json({ status: 'trial' });
  }
  // Placeholder for YooKassa and Remnawave integration
  return res.status(200).json({ confirmation: { confirmation_url: 'https://example.com/pay' } });
}
