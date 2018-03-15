<template>
<v-layout v-if="date">
  <v-flex xs12 sm12 md4>
    <v-select
      label="Month"
      :items="months"
      v-model="date.month"
      prepend-icon="date_range"
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
      v-model="date.day"
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
      v-model="date.year"
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
    date: {
      month: null,
      day: null,
      year: null
    }
  }),

  watch: {
    date: {
      deep: true,
      handler(e) {
        this.$emit('input', e)
      }
    },
    value(e) {
      this.date = e
    }
  }
}
</script>
