import * as session from '../../lib/session';

export default function handler(req, res) {
  const token = req.cookies.session;
  session.destroy(token);
  res.setHeader('Set-Cookie', 'session=; Path=/; HttpOnly; Max-Age=0');
  res.status(200).end();
}
