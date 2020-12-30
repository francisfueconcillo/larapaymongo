<template>
  <div>
    <p class="alert alert-danger" v-if="status==='fail'">
      Payment failed:
      <ul>
        <li v-for="(err, i) in errors" :key="i">
          {{ err }}
        </li>
      </ul>  
    </p>

    <p class="alert alert-success" v-if="status==='success'">
      Payment was successful.
    </p>

    <p class="alert alert-success" v-if="status==='processing'">
      Processing... please wait for a few seconds.
    </p>
  </div>  
  
      
</template>

<script>
    export default {
      props: {
        status: { type: String, default: null },
        errors: { type: Array, default: [] },
      },
    }
</script>