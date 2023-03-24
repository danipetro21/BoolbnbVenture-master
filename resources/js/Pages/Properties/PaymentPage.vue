<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
const API_URL = "http://localhost:8000/api/";
const API_VER = "v1/";
const API = API_URL + API_VER;

import { create } from 'braintree-web-drop-in';
import axios from 'axios';

export default {

  props: {
    cost: String,
    sponsorshipId: String,
    property: String
  },
  components: {
    AuthenticatedLayout,
  },
  data() {
    return {
      clientToken: '',
      dropinInstance: null,
      amount: this.cost,
      error: null,
      successF: false,
    }
  },
  mounted() {
    this.getClientToken();
  },
  methods: {
    // crea il token
    getClientToken() {
      axios.get(API + 'token')
        .then(response => {
          this.clientToken = response.data.token;

          this.initBraintree();
        })
        .catch(error => {
          console.error(error);
          this.error = 'Could not get client token';
        });
    },
    initBraintree() {
      const { clientToken } = this;
      create({
        authorization: clientToken,
        container: '#dropin-container',
      }, (error, dropinInstance) => {
        if (error) {
          console.error(error);
          this.error = 'Could not initialize Braintree';
        } else {
          this.dropinInstance = dropinInstance;
        }
      });
    },
    // manda il pagamento
    sendPayment() {
      const { dropinInstance, amount } = this;
      dropinInstance.requestPaymentMethod((error, payload) => {
        if (error) {
          console.error(error);
          this.error = 'Could not obtain payment method';
        } else {
          const { nonce } = payload;
          axios.post(API + 'transaction/create', { payment_method_nonce: nonce, amount: amount })
            .then(response => {
              if (response.data.success) {
                this.$swal({
                  title: 'Payment successful',
                  text: "of" + " " + this.amount + " " + "€",
                  icon: 'success',
                  confirmButtonText: "ok"
                }).then(() => {
                  this.buySponsorship();
                });
              } else {
                this.$swal({
                  title: 'Payment not successful',
                  text: 'Your payment of ' + this.amount + ' € could not be processed.',
                  icon: 'error',
                  confirmButtonText: 'RETRY'
                }).then(() => {
                  location.reload()
                });
              }
            })
            .catch(error => {
              console.error(error);
              this.error = 'Could not complete payment';
            });
        }
      });
    },

    //associa proprietà a sponsorship selezionata
    buySponsorship() {
      axios
        .post(API + 'sponsorship/buy', {
          property_id: this.property,
          sponsorship_id: this.sponsorshipId,
        })
        .then((res) => {
          const data = res.data;
          const success = data.success;
          const message = data.message;

          if (success) {
            window.location.href = "/dashboard";
          }
        })
        .catch(err => console.error(err));
    }
  }
}
</script>


<template>
  <AuthenticatedLayout>
    <div class="flex flex-col mt-10 items-center h-screen">
      <div id="dropin-container" class="w-3/4 p-4 rounded-lg shadow-lg"></div>
      <form class="mt-4 w-3/4 flex flex-col justify-center items-center">
        <div class="mb-4 text-center">
          <label for="amount" class="text-2xl font-bold text-rose-500 text-center">Amount</label>
          <h2 class="text-4xl font-bold text-center">{{ amount }} &euro;</h2>
        </div>

        <button @click.prevent="sendPayment"
          class="py-3 px-8 bg-zinc-700 text-white font-medium rounded-lg hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-700 focus:ring-opacity-50 transition-all duration-300">Pay
          Now</button>
      </form>
    </div>
  </AuthenticatedLayout>
</template> 

  
<style>
#dropin-container {
  min-height: 300px;
  width: 500px;
}
</style>