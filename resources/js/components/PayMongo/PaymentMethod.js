import PaymentIntent from './PaymentIntent';

const PAYMONGO_API = process.env.MIX_PAYMONGO_API_URL || 'https://api.paymongo.com/v1';
const PAYMONGO_PUBLIC_KEY = process.env.MIX_PAYMONGO_PUBLIC_KEY;

export default {
  create(cardData, clientkey, parentComponent) { 
    const details = { 
      ...cardData.attributes.details,
      exp_month: parseInt(cardData.attributes.details.exp_month),
      exp_year: parseInt(cardData.attributes.details.exp_year),
    };
  
    const attributes = {
      ...cardData.attributes,
      details,
    };
    
    axios.post(
      PAYMONGO_API + '/payment_methods',
      { 
        data: { attributes } 
      }, {
        headers: {
          // Base64 encoded public PayMongo API key.
          Authorization: `Basic ${window.btoa(PAYMONGO_PUBLIC_KEY)}`
        }
      }
    ).then(response => {
      const resp = response.data.data;
    
      if (response.status === 200 && resp.id.startsWith('pm_')) {
        PaymentIntent.attach(resp.id, clientkey, parentComponent);
      } else {
        Promise.reject(new Error('Invalid Payment Method response'))
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