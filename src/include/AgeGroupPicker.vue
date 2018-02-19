<template>
  <v-select
    label="Age group"
    :items="items"
    return-object
    v-model="age"
    @input="updateValue"
    prepend-icon="date_range"
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
    required: {
      type: Boolean,
      default: false
    }
  },
  data: () => ({
    age: null,
    items: [
      { from: 18, to: 24 },
      { from: 25, to: 32 },
      { from: 33, to: 40 },
      { from: 41, to: 49 },
      { from: 50, text: '50 and above' },
    ]
  }),
  methods: {
    updateValue() {
      this.$emit('input', this.age)
    }
  }
}
</script>
