// Calls /payment/source/{method}/{referId} to 
// create/retrieve Source status and/or Checkout URL

export default {

  create(type, referId, parentComponent) {


    axios.get('/payment/source/' + type + '/' +referId).then(response => {
      const resp = response.data;

      if (response.status === 200 ) {
        if (resp.code === 'reuse' || resp.code === 'new') {
          window.location.href = resp.checkout_url;
        } else if(resp.code === 'paid') {
          window.location.href = '/payment/details/' + referId;
        } else {
          Promise.reject(new Error('Invalid Source status code from application.'))
        }        
      } else {
        Promise.reject(new Error('Invalid Source response'))
      }
    }).catch(err => {
      let errorMessages = [];

      errorMessages.push('An error occured. Please try again.')
      parentComponent.errors = errorMessages;
      parentComponent.status = 'fail';
      Promise.reject(new Error('Multiple error messages.'));
    });
  }
}