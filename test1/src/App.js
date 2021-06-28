import logo from './logo.svg'
import axios from 'axios'
import Echo from 'laravel-echo'
import { useEffect, useState } from 'react'
import Pusher from 'pusher-js'

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: process.env.REACT_APP_KEY,
  wsHost: process.env.REACT_APP_SERVER,
  wsPort: process.env.REACT_APP_PORT,
  forceTLS: false,
  disableStats: true,
})
function App() {
  let a = []
  /*const toArray = (p) => {
    let test = false
    arr.map((e) => {
      if (e.id == p.id) test = true
    })

    if (!test) setArr([p, ...arr])
  }*/
  const [arr, setArr] = useState([])
  const [obj, setObj] = useState(null)
  const [form, setForm] = useState({ name: '', prix: '', description: '' })
  const subForm = (e) => {
    e.preventDefault()
    axios
      .post(
        `http://127.0.0.1:8000/api/plat?nom=${form.name}&prix=${form.prix}&description=${form.description}&image-name=img&image-src=img`
      )
      .then(function (response) {
        // handle success
        console.log(response)
      })
      .catch(function (error) {
        // handle error
        console.log(error)
      })
  }
  useEffect(() => {
    window.Echo.channel('channel1234').listen('Test', (e) => {
      //console.log(e)

      setObj(e.plat)
      console.log(obj)
    })
  }, [])
  useEffect(() => {
    if (obj != null) {
      let test = false
      arr.map((e) => {
        if (e.id === obj.id) test = true
      })
      setArr([...arr, obj])
    }
  }, [obj])
  return (
    <div>
      <form onSubmit={subForm}>
        nom:{' '}
        <input
          type='text'
          name='name'
          value={form.name}
          onChange={(e) => {
            setForm({ ...form, name: e.target.value })
          }}
        />
        prix:{' '}
        <input
          type='number'
          name='prix'
          value={form.prix}
          onChange={(e) => {
            setForm({ ...form, prix: e.target.value })
          }}
        />
        description:{' '}
        <textarea
          name='description'
          value={form.description}
          onChange={(e) => {
            setForm({ ...form, description: e.target.value })
          }}
        />
        <button type='submit'>ajouter</button>
      </form>

      {arr != null && arr.map((e, i) => <p key={i}>{JSON.stringify(e)}</p>)}
    </div>
  )
}

export default App
