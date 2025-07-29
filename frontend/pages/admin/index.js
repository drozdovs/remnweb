import { useEffect, useState } from 'react'

export default function AdminHome() {
  const [admin, setAdmin] = useState(null)
  const [plans, setPlans] = useState([])
  const [users, setUsers] = useState([])

  useEffect(() => {
    fetch('http://localhost:8000/api/admin_user.php', { credentials: 'include' })
      .then(res => res.ok ? res.json() : null)
      .then(data => setAdmin(data))
  }, [])

  useEffect(() => {
    if (admin)
      fetch('http://localhost:8000/api/plans.php', { credentials: 'include' })
        .then(res => res.json())
        .then(data => setPlans(data))
  }, [admin])

  useEffect(() => {
    if (admin)
      fetch('http://localhost:8000/api/users.php', { credentials: 'include' })
        .then(res => res.json())
        .then(data => setUsers(data))
  }, [admin])

  if (!admin) return <p><a href="/admin/login">Login</a></p>

  const updatePlan = async (name, price, trial) => {
    await fetch('http://localhost:8000/api/plans.php', {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ name, price, trial })
    })
  }

  return (
    <div>
      <h1>Admin Panel</h1>
      <a href="/admin/login" onClick={async e => {e.preventDefault(); await fetch('http://localhost:8000/api/admin_logout.php', {credentials:'include'}); window.location.reload()}}>Logout</a>
      <h2>Plans</h2>
      <table>
        <thead><tr><th>Name</th><th>Price</th><th>Trial</th><th></th></tr></thead>
        <tbody>
          {plans.map(p => (
            <tr key={p.id}>
              <td>{p.name}</td>
              <td><input defaultValue={p.price} onBlur={e=>updatePlan(p.name, e.target.value, p.trial)} /></td>
              <td><input type="checkbox" defaultChecked={p.trial === '1' || p.trial === 1} onChange={e=>updatePlan(p.name, p.price, e.target.checked)} /></td>
              <td></td>
            </tr>
          ))}
        </tbody>
      </table>
      <h2>Users</h2>
      <table>
        <thead><tr><th>Email</th><th>Blocked</th><th></th></tr></thead>
        <tbody>
          {users.map(u => (
            <tr key={u.id}>
              <td>{u.email}</td>
              <td>{u.blocked === '1' || u.blocked === 1 ? 'Yes' : 'No'}</td>
              <td>
                <button onClick={async ()=>{
                  await fetch('http://localhost:8000/api/block_user.php', {
                    method:'POST', credentials:'include', headers:{'Content-Type':'application/json'},
                    body: JSON.stringify({id:u.id, block: u.blocked === '1' || u.blocked === 1 ? 0 : 1})})
                  const res = await fetch('http://localhost:8000/api/users.php', {credentials:'include'})
                  setUsers(await res.json())
                }}>
                  {u.blocked === '1' || u.blocked === 1 ? 'Unblock' : 'Block'}
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  )
}
