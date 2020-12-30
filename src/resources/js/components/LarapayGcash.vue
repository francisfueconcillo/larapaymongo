<template>

  <div id="nav-tab-gcash" class="tab-pane fade">
    Charge {{ currency+" "+ price }} by GCash
  </div>

</template>


<script>
    export default {
        props: {
            price: { type: String, required: true },
            currency: { type: String, required: true },
        },
    }
</script>