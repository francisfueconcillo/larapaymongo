<template>

  <div id="nav-tab-gcash" class="tab-pane fade">
    <p>Pay using your GCash.</p>
    <p>Amount: {{ currency+" "+ price }}</p>
    <button 
        type="button" 
        class="subscribe btn btn-primary btn-block rounded-pill shadow-sm"
        @click="submitHandler"
    > Pay Now  
    </button>
  </div>

</template>


<script>
    export default {
        props: {
            transactionid: { type: String, required: true },
            price: { type: String, required: true },
            currency: { type: String, required: true },
        },
        methods: {
            submitHandler() {
                // Source.create('gcash', this.transactionid);
                console.log('pay by gcash');
            },
        }
    }
</script>