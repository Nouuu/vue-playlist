<template>
  <v-container fill-height fluid>
    <v-row align="center" justify="center">
      <v-col>
        <v-progress-circular
            :size="100"
            :width="8"
            color="green"
            indeterminate></v-progress-circular>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "Logout",
  mounted() {
    this.$http.post(this.$api_url + 'user/logout.php').then(() => {
      sessionStorage.clear()
      this.$router.push('/login')

    }).catch((response) => {
      console.log(response);
    })

  }
}
</script>

<style scoped>

</style>