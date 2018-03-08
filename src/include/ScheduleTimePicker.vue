<template>
<v-dialog
  ref="dialog"
  persistent
  v-model="modal"
  lazy
  full-width
  width="290px"
  :return-value.sync="time"
>
  <v-text-field
    slot="activator"
    :label="label"
    v-model="time"
    prepend-icon="access_time"
    :rules="[required ? $vfRule('required') : undefined]"
    :required="required"
    readonly
  />
  <v-time-picker v-model="time" format="24hr" actions>
    <v-spacer></v-spacer>
    <v-btn flat @click="modal = false">Cancel</v-btn>
    <v-btn flat color="primary" @click="$refs.dialog.save(time)">OK</v-btn>
  </v-time-picker>
</v-dialog>
</template>

<script>
export default {
  name: 'schedule-time-picker',
  props: {
    value: [Object, String],
    label: {
      type: String,
      default: 'Set schedule time'
    },
    required: {
      type: Boolean,
      default: false
    }
  },
  data: () => ({
    modal: false,
    time: null
  }),
  watch: {
    value(e) {
      this.time = e
    },
    time(e) {
      this.$emit('input', e)
    }
  }
}
</script>
