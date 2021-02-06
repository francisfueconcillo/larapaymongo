<template>

  <!-- credit card info-->
  <div id="nav-tab-grabpay" class="tab-pane fade">
     <p>Pay using your GrabPay.</p>
    <p>Amount: {{ currency+" "+ price }}</p>
    <button 
        type="button" 
        class="subscribe btn btn-primary btn-block rounded-pill shadow-sm"
        @click="submitHandler"
    > Pay Now  
    </button>

    <div class="p-3">
      <larapay-notify :status="status" :errors="errors"></larapay-notify>
    </div>
  </div>

</template>


<script>
  import Source from './PayMongo/Source';
  export default {
      props: {
          transactionid: { type: String, required: true },
          price: { type: String, required: true },
          currency: { type: String, required: true },
      },
      data () {
            return {
                errors: [],
                status: null,
            }
      },
      methods: {
          submitHandler() {
              Source.create('grab_pay', this.transactionid, this);
          },
      }
  }
</script>