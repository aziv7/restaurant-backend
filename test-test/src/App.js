import logo from './logo.svg'
import './App.css'
import Pusher from 'pusher-js'
import useEffect from 'react'
import Echo from 'laravel-echo'
function App() {
  window.Echo = new Echo({
    broadcaster: 'pusher',
    disableStats: true,
    wsPort: process.env.REACT_APP_PORT,
    wsHost: process.env.REACT_APP_SERVER,
    key: process.env.REACT_APP_KEY,
    forceTLS: false,
  })

  window.Echo.channel('channel').listen('test', (e) => {
    console.log(e)
  })

  return (
    <div className='App'>
      <header className='App-header'>
        <img src={logo} className='App-logo' alt='logo' />
        <p>
          Edit <code>src/App.js</code> and save to reload.
        </p>
        <a
          className='App-link'
          href='https://reactjs.org'
          target='_blank'
          rel='noopener noreferrer'
        >
          Learn React
        </a>
      </header>
    </div>
  )
}

export default App
