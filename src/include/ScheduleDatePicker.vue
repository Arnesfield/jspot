<template>
<v-dialog
  ref="dialog"
  persistent
  v-model="modal"
  lazy
  full-width
  width="290px"
  :return-value.sync="date"
>
  <v-text-field
    slot="activator"
    :label="label"
    v-model="date"
    prepend-icon="event"
    :rules="[required ? $vfRule('required') : undefined]"
    :required="required"
    readonly
  ></v-text-field>
  <v-date-picker v-model="date" scrollable>
    <v-spacer></v-spacer>
    <v-btn flat @click="modal = false">Cancel</v-btn>
    <v-btn flat color="primary" @click="$refs.dialog.save(date)">OK</v-btn>
  </v-date-picker>
</v-dialog>
</template>

<script>
export default {
  name: 'schedule-date-picker',
  props: {
    value: [Object, String],
    label: {
      type: String,
      default: 'Set schedule date'
    },
    required: {
      type: Boolean,
      default: false
    }
  },
  data: () => ({
    modal: false,
    date: null
  }),
  watch: {
    value(e) {
      this.date = e
    },
    date(e) {
      this.$emit('input', e)
    }
  }
}
</script>
