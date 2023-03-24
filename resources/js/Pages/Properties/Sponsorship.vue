<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";

import axios from "axios";

const API_URL = "http://localhost:8000/api/";
const API_VER = "v1/";
const API = API_URL + API_VER;

export default {
  props: {
    propid: String,
  },
  components: {
    AuthenticatedLayout,
    Link,
  },
  data() {
    return {
      property: parseInt(this.propid),
      sponsorships: [],
      showModal: false
    };
  },
  methods: {
    // recupera tutte le sponsorship
    getSponsorship() {
      axios.get(API + `sponsorship/{property_id}`)
        .then((res) => {
          const data = res.data;
          const success = data.success;
          const service = data.response;

          if (success) {
            this.sponsorships = service;
          }

        })
        .catch(err => console.error(err));
    },
  },
  mounted() {
    this.getSponsorship();
  }
}
</script>
<template>
  <AuthenticatedLayout>
    <div>
      <div class="min-h-screen bg-gray-100">
        <!-- Page Content -->
        <main class="w-9/12 mx-auto">
          <h1 class="flex justify-center text-4xl mt-8 text-center">Choose your plan</h1>

          <!-- Modale pagamento andato a buon fine -->
          <div class="fixed inset-0 z-50 overflow-auto bg-gray-500 bg-opacity-75" v-if="showModal">
            <div class="relative bg-white w-96 mx-auto my-12 rounded-lg shadow-lg">
              <div class="px-4 py-4">
                <div class="text-5xl text-center my-5"><i class="uil uil-check-circle"></i></div>
                <h1 class="text-2xl font-bold text-gray-900">Pagamento andato a buon fine</h1>
                <p class="mt-1 text-gray-600 mb-5">Il tuo pagamento è stato elaborato con successo!</p>
              </div>
            </div>
          </div>

          <!-- Card sponsorship -->
          <div class="container mt-20 mx-auto">
            <div class="w-full mx-auto px-4 mb-5 flex flex-wrap justify-center gap-5">
              <div class="bg-white rounded-xl shadow-md overflow-hidden text-center py-7 px-10 my_box"
                v-for="sponsorship in sponsorships" :key="sponsorship.id">
                <div class="px-4 py-2">
                  <h2 class="text-xl mb-2 text-center tracking-wider">{{ sponsorship.type }}</h2>
                  <div class="my-4">
                    <p class="text-rose-600 text-5xl font-bold my-10">{{ sponsorship.cost }} &euro;</p>
                    <p class="text-gray-600 text-xl mb-10">{{ sponsorship.duration.split(':')[0] }} hours</p>
                  </div>

                  <a
                    :href="route('payment', { cost: sponsorship.cost, sponsorshipId: sponsorship.id, property: property })">
                    <button
                      class="w-full bg-neutral-800 hover:bg-neutral-600 text-white font-semibold py-2 px-6 rounded-full shadow">Buy</button>
                  </a>


                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>

  </AuthenticatedLayout>
</template>


<style lang="scss" scoped>
.my_box {
  width: 300px;
  height: 350px;
}
</style>