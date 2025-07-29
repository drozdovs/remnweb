import { useEffect, useState } from 'react'

export default function Home() {
  const [user, setUser] = useState(null)
  const [plans, setPlans] = useState([])

  useEffect(() => {
    fetch('http://localhost:8000/api/user.php', { credentials: 'include' })
      .then(res => res.ok ? res.json() : null)
      .then(data => setUser(data))
  }, [])

  useEffect(() => {
    fetch('http://localhost:8000/api/plans_public.php')
      .then(res => res.json())
      .then(data => setPlans(data))
  }, [])

  if (!user) {
    return <p><a href="/login">Login</a></p>
  }

  const subscribe = async plan => {
    const res = await fetch('http://localhost:8000/subscribe.php', {
      method: 'POST',
      credentials: 'include',
      body: new URLSearchParams({ plan })
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
      <a href="http://localhost:8000/logout.php">Logout</a>
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
