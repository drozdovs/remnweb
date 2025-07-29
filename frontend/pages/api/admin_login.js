import dbm from '../../lib/db';
import * as session from '../../lib/session';

export default function handler(req, res) {
  if (req.method !== 'POST') return res.status(405).end();
  const { user, pass } = req.body;
  const db = dbm.load();
  let admin = db.admins.find(a => a.user === user);
  if (!admin) {
    // initial admin
    if (db.admins.length === 0 && user === process.env.ADMIN_USER && pass === process.env.ADMIN_PASS) {
      admin = { id: 1, user, pass };
      db.admins.push(admin);
      if (db.plans.length === 0) {
        db.plans.push({ id:1, name:'basic', price:'100', trial:false });
        db.plans.push({ id:2, name:'pro', price:'200', trial:false });
        db.plans.push({ id:3, name:'trial', price:'0', trial:true });
      }
      dbm.save(db);
    } else {
      return res.status(401).end();
    }
  }
  if (admin.pass !== pass) return res.status(401).end();
  const token = session.create(admin.id, 'admin');
  res.setHeader('Set-Cookie', `admin=${token}; Path=/; HttpOnly`);
  res.status(200).end();
}
