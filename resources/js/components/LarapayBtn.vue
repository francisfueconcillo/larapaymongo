<template>
  <button 
    type="button" 
    class="btn btn-primary" 
    @click="goTo('/payment/' + referid)"
  >
    Purchase
  </button>
</template>

<script>
    export default {
      props: {
        referid: { type: String, default: null },
      },
      methods: {
        goTo(page) {
            window.location.href = page;
        },
      },
    }
</script>