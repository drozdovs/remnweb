import dbm from '../../lib/db';
import * as session from '../../lib/session';

export default function handler(req, res) {
  const admin = session.get(req.cookies.admin, 'admin');
  if (!admin) return res.status(401).end();
  const db = dbm.load();
  res.status(200).json(db.users);
}
