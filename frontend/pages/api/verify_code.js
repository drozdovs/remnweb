import dbm from '../../lib/db';
import * as session from '../../lib/session';

export default function handler(req, res) {
  if (req.method !== 'POST') return res.status(405).end();
  const { email, code } = req.body;
  const db = dbm.load();
  const c = db.codes.find(x => x.email === email && x.code === code);
  if (!c || c.expiry < Date.now()) return res.status(400).end();
  db.codes = db.codes.filter(x => x !== c);
  dbm.save(db);
  const user = db.users.find(u => u.email === email);
  const token = session.create(user.id, 'user');
  res.setHeader('Set-Cookie', `session=${token}; Path=/; HttpOnly`);
  res.status(200).end();
}
