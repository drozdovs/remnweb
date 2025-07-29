const fs = require('fs');
const path = require('path');
const dbFile = process.env.DB_FILE || path.join(process.cwd(), 'data', 'db.json');

function load() {
  if (!fs.existsSync(dbFile)) {
    fs.mkdirSync(path.dirname(dbFile), { recursive: true });
    fs.writeFileSync(dbFile, JSON.stringify({ users: [], admins: [], plans: [], codes: [] }, null, 2));
  }
  return JSON.parse(fs.readFileSync(dbFile, 'utf8'));
}

function save(data) {
  fs.writeFileSync(dbFile, JSON.stringify(data, null, 2));
}

module.exports = { load, save };
