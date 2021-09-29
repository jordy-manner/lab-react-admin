import * as React from 'react'
import { Admin, Resource, ListGuesser } from 'react-admin'
import DataProvider from './DataProvider'

const App = () => <Admin dataProvider={DataProvider} >
  <Resource name="posts" list={ListGuesser} />
</Admin>

export default App
