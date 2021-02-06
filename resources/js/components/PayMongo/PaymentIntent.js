const PAYMONGO_API = process.env.MIX_PAYMONGO_API_URL || 'https://api.paymongo.com/v1';
const PAYMONGO_PUBLIC_KEY = process.env.MIX_PAYMONGO_PUBLIC_KEY;
const PROCESSING_TIMEOUT = 2000;

let retries = 0;

export default {
  attach(paymentMethodId, clientKey, parentComponent) {

    // Get the payment intent id from the client key
    const paymentIntentId = clientKey.split('_client')[0];
    retries = 0;

    axios.post(
      PAYMONGO_API + '/payment_intents/' + paymentIntentId + '/attach',
      {
        data: {
          attributes: {
            client_key: clientKey,
            payment_method: paymentMethodId
          }
        }
      }, {
        headers: {
          // Base64 encoded public PayMongo API key.
          Authorization: `Basic ${window.btoa(PAYMONGO_PUBLIC_KEY)}`
        }
        }
    ).then(response => {
      const resp = response.data.data;

      switch(resp.attributes.status) {
        case 'awaiting_payment_method':
          let pmError = resp.attributes.last_payment_error || 'Payment process incomplete. Please try again.';
          parentComponent.errors = [pmError];
          parentComponent.status = 'fail';
          Promise.reject(new Error(pmError));
          break;

        case 'processing':
          parentComponent.status = 'processing';
          setTimeout(()=>{
            this.retrieve(paymentIntentId);
            retries++;
          }, PROCESSING_TIMEOUT);
          break;

        case 'succeeded':
          parentComponent.status = 'success';
          break;

        case 'awaiting_next_action':
          parentComponent.status = null;
          parentComponent.show3dsModal = true;   
          parentComponent.url3ds = resp.attributes.next_action.redirect.url;  
          break;

        default: 
          parentComponent.errors = ['Unknown error. Please try again.'];
          parentComponent.status = 'fail';            
      }

    }).catch(err => {
      let errorMessages = [];
      
      if (err.response.status !== 200) {
        try {
          err.response.data.errors.forEach((e, index) => {
            errorMessages.push(e.detail);
          });
        } catch {
          errorMessages.push('Unknown error.')
        }

        parentComponent.errors = errorMessages;
        parentComponent.status = 'fail';
        Promise.reject(new Error(errorMessages));
      }
    });
  },


  retrieve(clientKey, parentComponent) {

    const paymentIntentId = clientKey.split('_client')[0];
                
    axios.get(
      PAYMONGO_API + '/payment_intents/' + paymentIntentId + '?client_key=' + clientKey,
      {
        headers: {
          // Base64 encoded public PayMongo API key.
          Authorization: `Basic ${window.btoa(PAYMONGO_PUBLIC_KEY)}`
        }
      }
    ).then(response => {
      const resp = response.data.data;


      switch(resp.attributes.status) {
        case 'awaiting_payment_method':
          let pmError = resp.attributes.last_payment_error || 'Payment process incomplete. Please try again.';
          parentComponent.errors = [pmError];
          parentComponent.status = 'fail';
          Promise.reject(new Error(pmError));
          break;

        case 'processing':
          if (retries < 3) {
            parentComponent.status = 'processing';
            setTimeout(()=>{
              this.retrieve(paymentIntentId);
              retries++;
            }, PROCESSING_TIMEOUT);
          }
          break;

        case 'succeeded':
          parentComponent.status = 'success';
          break;

        // case 'awaiting_next_action':
        //   parentComponent.status = null;
        //   parentComponent.show3dsModal = true;   
        //   parentComponent.url3ds = resp.attributes.next_action.redirect.url;  
        //   break;

        default: 
          parentComponent.errors = ['An error occured. Please try again.'];
          parentComponent.status = 'fail';            
      }

    }).catch(err => {
      let errorMessages = [];
      
      if (err.response.status !== 200) {
        try {
          err.response.data.errors.forEach((e, index) => {
            errorMessages.push(e.detail);
          });
        } catch {
          errorMessages.push('An error occured. Please try again.')
        }

        parentComponent.errors = errorMessages;
        parentComponent.status = 'fail';
        Promise.reject(new Error('Multiple errors.'));
      }
    });
    
  }

}