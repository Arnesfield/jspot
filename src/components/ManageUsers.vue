<template>
<v-container>
  <v-data-table
    :items="users"
  >
  </v-data-table>
</v-container>
</template>

<script>
export default {
  name: 'manage-users',
  data: () => ({
    users: []
  })
}
</script>
