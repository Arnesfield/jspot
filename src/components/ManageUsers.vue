<template>
<v-container fluid>
  <v-data-table
    :headers="headers"
    :items="users"
    :loading="loading"
    class="elevation-1"
  >
    <v-progress-linear
      height="3"
      slot="progress"
      color="warning"
      indeterminate
    />
    <template slot="no-data">
      <manage-no-data :loading="loading" :fetch="fetch"/>
    </template>
    <template slot="items" slot-scope="props">
      <td>{{ props.item.id }}</td>
      <td>{{ props.item.email }}</td>
      <td>{{ props.item.fname }}</td>
      <td>{{ props.item.lname }}</td>
      <td>{{ wrap.userType(props.item.type) }}</td>
      <td>{{ props.item.contact }}</td>
      <td>{{ wrap.date(props.item.created_at) }}</td>
      <td>{{ wrap.date(props.item.updated_at) }}</td>
      <td>
        <div class="justify-center layout">
          <v-tooltip top>
            <v-icon
              color="success"
              slot="activator"
              v-if="props.item.status == 1"
            >check_circle</v-icon>
            <v-icon
              color="error"
              slot="activator"
              v-else
            >cancel</v-icon>
            <span>{{ wrap.status(props.item.status) }}</span>
          </v-tooltip>
        </div>
      </td>
      <td class="justify-center layout px-0">
        <v-tooltip top>
          <v-btn slot="activator" icon class="mx-0" @click="edit(props.item)">
            <v-icon color="teal">edit</v-icon>
          </v-btn>
          <span>Edit</span>
        </v-tooltip>
        <v-tooltip top>
          <v-btn slot="activator" icon class="mx-0" @click="doDelete(props.item)">
            <v-icon color="pink">delete</v-icon>
          </v-btn>
          <span>Delete</span>
        </v-tooltip>
      </td>
    </template>
  </v-data-table>

  <dialog-add-user :mode="dialogMode" :userToEdit="selected"/>

</v-container>
</template>

<script>
import ManageNoData from '@/include/ManageNoData'
import DialogAddUser from '@/include/dialogs/DialogAddUser'
import wrap from '@/assets/js/wrap'
import qs from 'qs'

export default {
  name: 'manage-users',
  components: {
    ManageNoData,
    DialogAddUser
  },
  data: () => ({
    url: '/users',
    headers: [
      { text: 'Id', value: 'id', align: 'left', sortable: false },
      { text: 'Email', value: 'email' },
      { text: 'First Name', value: 'fname' },
      { text: 'Surname', value: 'lname' },
      { text: 'Type', value: 'type' },
      { text: 'Contact', value: 'contact', sortable: false },
      { text: 'Date Created', value: 'created_at' },
      { text: 'Date Updated', value: 'updated_at' },
      { text: 'Status', value: 'status', sortable: false },
      { text: 'Actions', sortable: false }
    ],
    users: [],
    loading: false,
    dialogMode: 'add',
    selected: null
  }),
  computed: {
    wrap: () => wrap
  },

  created() {
    this.$bus.$on('add--user', () => {
      this.dialogMode = 'add'
      this.$bus.dialog.ManageUsers.add = true
    })
    this.$bus.$on('dialog--manage-user.add', (to, from) => {
      if (!to) {
        // reset from edit to add on dialog close
        this.dialogMode = 'add'
        this.selected = null
      }
    })
    this.$bus.$on('update--manage-users', this.fetch)
    this.fetch()
  },

  methods: {
    edit(item) {
      this.selected = item
      this.dialogMode = 'edit'
      this.$bus.dialog.ManageUsers.add = true
    },
    doDelete(item) {
      this.$bus.$emit('dialog--delete.show', {
        item: item,
        title: 'Delete User',
        subtitle: 'User ID: ' + item.id,
        msg: '<div class="body-1">Are you sure you want to delete this user?</div>',
        fn: (onSuccess, onError) => {
          this.$http.post('/users/delete', qs.stringify({
            id: item.id
          })).then((res) => {
            console.error(res.data)
            if (!res.data.success) {
              throw new Error('Request failure.')
            }
            // update users
            this.$bus.$emit('update--manage-users')
            onSuccess()
          }).catch(e => {
            console.error(e)
            onError()
          })
        }
      })
    },
    
    fetch() {
      this.loading = true
      this.$http.post(this.url).then((res) => {
        if (!res.data.success) {
          throw new Error('Request failure.')
        }
        this.users = res.data.users
        this.loading = false
      }).catch(e => {
        console.error(e)
        this.loading = false
      })
    }
  }
}
</script>