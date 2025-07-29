const { v4: uuid } = require('uuid');
const sessions = {};

function create(userId, type='user') {
  const token = uuid();
  sessions[token] = { userId, type, created: Date.now() };
  return token;
}

function get(token, type='user') {
  const s = sessions[token];
  if (s && s.type === type) return s.userId;
  return null;
}

function destroy(token) {
  delete sessions[token];
}

module.exports = { create, get, destroy };
