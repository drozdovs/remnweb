import dbm from '../../lib/db';

export default function handler(req, res) {
  const db = dbm.load();
  res.status(200).json(db.plans);
}
