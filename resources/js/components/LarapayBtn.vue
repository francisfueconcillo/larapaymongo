<template>
  <button 
    type="button" 
    class="btn btn-primary" 
    @click="goTo('/purchase/' + itemid)"
  >
    Purchase
  </button>
</template>

<script>
    export default {
      props: {
        itemid: { type: String, default: null },
      },
      methods: {
        goTo(page) {
            window.location.href = page;
        },
      },
    }
</script>