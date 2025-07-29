import dbm from '../../lib/db';
import * as session from '../../lib/session';

export default function handler(req, res) {
  const admin = session.get(req.cookies.admin, 'admin');
  if (!admin) return res.status(401).end();
  const db = dbm.load();
  if (req.method === 'POST') {
    const { name, price, trial } = req.body;
    const p = db.plans.find(x => x.name === name);
    if (p) {
      p.price = price;
      p.trial = !!trial;
    } else {
      db.plans.push({ id: db.plans.length + 1, name, price, trial: !!trial });
    }
    dbm.save(db);
  }
  res.status(200).json(db.plans);
}
