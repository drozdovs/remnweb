import { useEffect, useState } from 'react'

export default function Home() {
  const [user, setUser] = useState(null)
  const [plans, setPlans] = useState([])

  useEffect(() => {
    fetch('/api/user', { credentials: 'include' })
      .then(res => res.ok ? res.json() : null)
      .then(data => setUser(data))
  }, [])

  useEffect(() => {
    fetch('/api/plans_public')
      .then(res => res.json())
      .then(data => setPlans(data))
  }, [])

  if (!user) {
    return <p><a href="/login">Login</a></p>
  }

  const subscribe = async plan => {
    const res = await fetch('/api/subscribe', {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ plan })
    })
    const data = await res.json()
    if (data.status === 'trial') {
      alert('Trial activated!')
    } else if (data.confirmation && data.confirmation.confirmation_url) {
      window.location = data.confirmation.confirmation_url
    }
  }

  return (
    <div>
      <h1>Welcome, {user.email}</h1>
      <a href="/api/logout" onClick={e=>{e.preventDefault(); fetch('/api/logout').then(()=>window.location='/')}}>Logout</a>
      <div>
        {plans.map(p => (
          <button key={p.name} onClick={() => subscribe(p.name)}>
            {p.trial ? 'Try' : 'Buy'} {p.name} ({p.price})
          </button>
        ))}
      </div>
    </div>
  )
}
