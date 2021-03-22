<template>
  <router-view></router-view>
</template>

<script>
export default {
  data() {
    return {
      propertySearch: {
        location: null,
        startDate: null,
        endDate: null,
        bedroomType: null
      }
    }
  }
}
</script>
