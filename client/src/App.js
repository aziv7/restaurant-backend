import logo from './logo.svg'
import './App.css'

function App() {
  const es = new EventSource(
    'http://127.0.0.1:8000/connect?topic=' + encodeURIComponent('channel123')
  )
  es.addEventListener('message', (messageEvent) => {
    var eventData = JSON.parse(messageEvent.data)
    console.log(eventData)
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
