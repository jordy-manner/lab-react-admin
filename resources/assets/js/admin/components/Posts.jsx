import * as React from "react"
import {
  List,
  Datagrid,
  TextField,
  EditButton,
  Edit,
  Create,
  SimpleForm,
  TextInput,
  Show,
  SimpleShowLayout,
  RichTextField
} from 'react-admin';

const postFilters = [
  <TextInput source="q" label="Search" alwaysOn/>
];


export const PostList = props => {
  return (
      <List filters={postFilters} {...props}>
          <Datagrid>
            <TextField source="id"/>
            <TextField source="title"/>
            <TextField source="content"/>
            <EditButton/>
          </Datagrid>
      </List>
  )
}

const PostTitle = ({record}) => {
  return <span>Article {record ? `"${record.title}"` : ''}</span>;
}

export const PostEdit = props => (
    <Edit title={<PostTitle/>} {...props}>
      <SimpleForm>
        <TextInput disabled source="id"/>
        <TextInput source="title"/>
        <TextInput multiline source="content"/>
      </SimpleForm>
    </Edit>
);

export const PostCreate = props => (
    <Create {...props}>
      <SimpleForm>
        <TextInput source="title"/>
        <TextInput multiline source="content"/>
      </SimpleForm>
    </Create>
);

export const PostShow = (props) => (
    <Show {...props}>
      <SimpleShowLayout>
        <TextField source="title"/>
        <RichTextField source="content"/>
      </SimpleShowLayout>
    </Show>
)