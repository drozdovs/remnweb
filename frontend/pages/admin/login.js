import { useState } from 'react'

export default function AdminLogin() {
  const [user, setUser] = useState('')
  const [pass, setPass] = useState('')
  const [error, setError] = useState('')

  async function handle(e) {
    e.preventDefault()
    setError('')
    const res = await fetch('http://localhost:8000/api/admin_login.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify({ user, pass })
    })
    if (res.ok) window.location.href = '/admin'
    else setError('Invalid credentials')
  }

  return (
    <div>
      <h1>Admin Login</h1>
      <form onSubmit={handle}>
        <input value={user} onChange={e=>setUser(e.target.value)} placeholder="User" required />
        <input type="password" value={pass} onChange={e=>setPass(e.target.value)} placeholder="Password" required />
        <button type="submit">Login</button>
      </form>
      {error && <p style={{color:'red'}}>{error}</p>}
    </div>
  )
}
