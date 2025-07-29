import { useState } from 'react'

export default function Login() {
  const [email, setEmail] = useState('')
  const [sent, setSent] = useState(false)
  const [code, setCode] = useState('')
  const [error, setError] = useState('')

  async function send(e) {
    e.preventDefault()
    setError('')
    const res = await fetch('http://localhost:8000/api/send_code.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email })
    })
    if (res.ok) setSent(true)
    else setError('Failed to send code')
  }

  async function verify(e) {
    e.preventDefault()
    setError('')
    const res = await fetch('http://localhost:8000/api/verify_code.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify({ email, code })
    })
    if (res.ok) window.location.href = '/'
    else setError('Invalid code')
  }

  return (
    <div>
      <h1>Login</h1>
      {!sent ? (
        <form onSubmit={send}>
          <input type="email" value={email} onChange={e=>setEmail(e.target.value)} placeholder="Email" required />
          <button type="submit">Send Code</button>
        </form>
      ) : (
        <form onSubmit={verify}>
          <input value={code} onChange={e=>setCode(e.target.value)} placeholder="Code" required />
          <button type="submit">Login</button>
        </form>
      )}
      {error && <p style={{color:'red'}}>{error}</p>}
    </div>
  )
}
