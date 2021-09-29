import React from 'react'
import {render} from 'react-dom'
import App from './app/components/App'

const $app = document.getElementById('app')
const username = $app.dataset.username || 'World'

render(<App username={username}/>, $app)