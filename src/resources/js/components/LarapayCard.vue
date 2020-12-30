<template>
    <div id="nav-tab-card" class="tab-pane fade show active">
        <form role="form">
            <div v-if="status !== 'success'"> 
                <div class="form-group">
                    <label for="cardNumber">Card number</label>
                    <div class="input-group">
                        <input 
                            type="number" 
                            name="cardNumber" 
                            placeholder="16-digit card number" 
                            class="form-control" 
                            required 
                            v-model="cardData.attributes.details.card_number"
                            maxlength="16"
                        >
                        <div class="input-group-append">
                            <span class="input-group-text text-muted">
                                <i class="fa fa-cc-visa mx-1"></i>
                                <i class="fa fa-cc-amex mx-1"></i>
                                <i class="fa fa-cc-mastercard mx-1"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label><span class="hidden-xs">Expiration</span></label>
                            <div class="input-group">
                                <input 
                                    type="number" 
                                    placeholder="MM" 
                                    name="exp_month" 
                                    class="form-control" 
                                    required 
                                    v-model="cardData.attributes.details.exp_month"
                                    min="1" max="12"
                                >
                                <input 
                                    type="number" 
                                    placeholder="YY" 
                                    name="exp_year" 
                                    class="form-control" 
                                    required 
                                    v-model="cardData.attributes.details.exp_year"
                                    maxlength="4"
                                    min="2020" max="2050"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group mb-4">
                            <label data-toggle="tooltip" title="3-digits code on the back of your card">CVV
                                <i class="fa fa-question-circle"></i>
                            </label>
                            <input 
                                type="text" 
                                required 
                                class="form-control" 
                                v-model="cardData.attributes.details.cvc"
                                maxlength="3"
                                minlength="3"
                            >
                        </div>
                    </div>

                </div>

                <div class="form-group">
                    <label for="name">Full name</label>
                    <input type="text" name="name" required class="form-control" placeholder="Name on the card" v-model="cardData.attributes.billing.name">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" required class="form-control" placeholder="Email address" v-model="cardData.attributes.billing.email">
                </div>
        
                <button 
                    type="button" 
                    class="subscribe btn btn-primary btn-block rounded-pill shadow-sm"
                    @click="submitHandler"
                > Confirm  
                </button>
            </div>

            <div class="p-3">
                <larapay-notify :status="status" :errors="errors"></larapay-notify>
            </div>

             <div class="p-3">
                <larapay-3ds v-if="show3dsModal" :url3ds="url3ds"></larapay-3ds>
            </div>
            
      
    
        </form>
    </div>
</template>

<script>
    import PaymentMethod from './PayMongo/PaymentMethod';
    import PaymentIntent from './PayMongo/PaymentIntent';
    export default {
        props: {
            clientkey: { type: String, required: true },
        },
        data () {
            return {
                errors: [],
                status: null,
                url3ds: null,
                show3dsModal: false,
                cardData: {
                    attributes: {
                        details: {
                            card_number: null,
                            exp_month: null,
                            exp_year:null,
                            cvc: null,
                        },
                        billing: {
                            address: {
                                line1: null,
                                line2: null,
                                city: null,
                                state: null,
                                postal_code: null,
                                country: null,
                            },
                            name: null,
                            email: null,
                            phone: null,
                        },
                        type: 'card'
                    }
                },
            }
        },
        methods: {
            submitHandler() {
                PaymentMethod.create(this.cardData, this.clientkey, this);
            },
            modalCloseHandler() {
                this.show3dsModal = false;
                PaymentIntent.retrieve(this.clientkey, this);
            }
        },
        watch: {
            status() {
                if (this.status === 'success') {
                    // TODO - call backend success url  
                }
            }
        },
    }
</script>



