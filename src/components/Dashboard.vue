<template>
  <employer-dashboard v-if="$bus.session.auth == 3"/>
  <employee-dashboard v-else-if="$bus.session.auth == 4"/>
</template>

<script>
import EmployerDashboard from './employer/Dashboard'
import EmployeeDashboard from './employee/Dashboard'

export default {
  name: 'dashboard',
  components: {
    EmployerDashboard,
    EmployeeDashboard
  }
}
</script>
