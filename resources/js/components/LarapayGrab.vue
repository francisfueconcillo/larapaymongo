<template>

  <!-- credit card info-->
  <div id="nav-tab-grabpay" class="tab-pane fade">
    Charge {{ currency+" "+ price }} by GrabPay
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