import dbm from '../../lib/db';
import * as session from '../../lib/session';

export default function handler(req, res) {
  if (req.method !== 'POST') return res.status(405).end();
  const admin = session.get(req.cookies.admin, 'admin');
  if (!admin) return res.status(401).end();
  const { id, block } = req.body;
  const db = dbm.load();
  const u = db.users.find(x => x.id == id);
  if (u) {
    u.blocked = !!block;
    dbm.save(db);
  }
  res.status(200).end();
}
