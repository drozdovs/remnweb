import dbm from '../../lib/db';
import * as session from '../../lib/session';

export default function handler(req, res) {
  const token = req.cookies.session;
  const uid = session.get(token, 'user');
  if (!uid) return res.status(401).end();
  const db = dbm.load();
  const user = db.users.find(u => u.id === uid);
  res.status(200).json(user);
}
