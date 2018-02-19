<template>
<v-layout>
  <v-flex xs12 sm12 md4>
    <v-select
      label="Month"
      :items="months"
      v-model="month"
      prepend-icon="date_range"
      @input="updateValue"
      :rules="[
        (required ? $vfRule('required') : (e) => true)
      ]"
      :required="required"
    >
  </v-select>
  </v-flex>
  &nbsp;
  <v-flex xs12 sm6 md4>
    <v-text-field
      label="Day"
      v-model="day"
      @input="updateValue"
      :rules="[
        (required ? $vfRule('required') : (e) => true)
      ]"
      :required="required"
    />
  </v-flex>
  &nbsp;
  <v-flex xs12 sm6 md4>
    <v-text-field
      label="Year"
      v-model="year"
      @input="updateValue"
      :rules="[
        (required ? $vfRule('required') : (e) => true)
      ]"
      :required="required"
    />
  </v-flex>
</v-layout>
</template>

<script>
import months from '@/assets/js/months'

export default {
  name: 'birthdate-picker',
  props: {
    value: Object,
    required: {
      type: Boolean,
      default: false
    }
  },
  data: () => ({
    months: months,
    month: null,
    day: null,
    year: null
  }),

  watch: {
    value(e) {
      if (e !== null) {
        this.month = e.month
        this.day = e.day
        this.year = e.year
      }
    }
  },

  methods: {
    updateValue() {
      this.$emit('input', {
        month: this.month,
        day: this.day,
        year: this.year
      })
    }
  }
}
</script>
