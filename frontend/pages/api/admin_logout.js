import * as session from '../../lib/session';

export default function handler(req, res) {
  session.destroy(req.cookies.admin);
  res.setHeader('Set-Cookie', 'admin=; Path=/; HttpOnly; Max-Age=0');
  res.status(200).end();
}
