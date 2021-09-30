import * as React from 'react'
import { Admin, Resource, ListGuesser } from 'react-admin'
import Dashboard from "./Dashboard";
import dataProvider from '../data-provider'
import {PostList, PostEdit, PostCreate, PostShow} from './Posts'

const App = () => <Admin dashboard={Dashboard} dataProvider={dataProvider} >
  <Resource name="posts" show={PostShow} list={PostList} edit={PostEdit} create={PostCreate} />
</Admin>

export default App
