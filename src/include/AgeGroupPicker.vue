<template>
  <v-select
    label="Age group"
    :items="items"
    return-object
    v-model="age"
    prepend-icon="people"
    :rules="[
      (required ? $vfRule('required') : (e) => true)
    ]"
    :required="required"
  >
    <template
      slot="selection"
      slot-scope="data"
    >Ages {{ data.item.text ? data.item.text : data.item.from + ' to ' + data.item.to }}</template>
    <template
      slot="item"
      slot-scope="data"
    >Ages {{ data.item.text ? data.item.text : data.item.from + ' to ' + data.item.to }}</template>
  </v-select>
</template>

<script>
export default {
  name: 'age-group-picker',
  props: {
    value: Object,
    required: {
      type: Boolean,
      default: false
    }
  },
  data: () => ({
    age: null,
    items: [
      { from: 18, to: 24 },
      { from: 25, to: 34 },
      { from: 35, to: 44 },
      { from: 45, to: 54 },
      { from: 55, text: '55 and above' },
    ]
  }),
  watch: {
    age(e) {
      this.$emit('input', e)
    },
    value(e) {
      this.age = e
    }
  }
}
</script>
