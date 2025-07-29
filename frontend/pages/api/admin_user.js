import dbm from '../../lib/db';
import * as session from '../../lib/session';

export default function handler(req, res) {
  const token = req.cookies.admin;
  const id = session.get(token, 'admin');
  if (!id) return res.status(401).end();
  const db = dbm.load();
  const admin = db.admins.find(a => a.id === id);
  res.status(200).json(admin);
}
