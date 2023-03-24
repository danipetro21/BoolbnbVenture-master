<script>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";
import tt from "@tomtom-international/web-sdk-maps";
import axios from "axios";

const API_URL = "http://localhost:8000/api/";
const API_VER = "v1/";
const API = API_URL + API_VER;

export default {
  components: {
    Link,
    AuthenticatedLayout,
    InputError,
    InputLabel,
  },

  props: {
    property: Object,
  },

  data() {
    return {
      email: "",
      message: "",
    };
  },

  methods: {
    sendMessage() {
      axios
        .post(API + "messages/store/" + this.property.id, {
          email: this.email,
          message: this.message,
        })
        .then(
          this.$swal({
            title: "Message Sent!!",
            text: "we will get back to you as soon as possible",
            icon: "success",
            confirmButtonText: "ok",
          })
        )
        .catch((error) => {
          console.error(error);
        });

      // svuota i campi email e message
      this.email = "";
      this.message = "";
    },
  },

  mounted() {
    const mapElement = document.getElementById("map");
    const map = tt.map({
      key: "6uYBjkiGRyeXjP2gGPa3qswccH9dGDbj",
      container: mapElement,
      center: [this.property.lon, this.property.lat],
      zoom: 15,
    });
    new tt.Marker()
      .setLngLat([this.property.lon, this.property.lat])
      .addTo(map);
  },
};
</script>

<template>
  <AuthenticatedLayout>
    <div>
      <div class="min-h-screen bg-gray-100">
        <!-- Page Content -->
        <main>
          <div class="container lg:w-9/12 mx-auto px-4 py-4">
            <h1 class="text-4xl font-bold">
              {{ property.title }}
            </h1>
            <p class="my-3">{{ property.address }}</p>

            <!-- img -->
            <div class="flex flex-col md:flex-row items-center gap-5">
              <div class="w-full">
                <div class="mb-2">
                  <img :src="property.img" class="w-full mt-3" :alt="property.title" />
                </div>
              </div>

              <!-- maps -->
              <div class="w-full">
                <div class="mb-2">
                  <div id="map" class="rounded-xl mt-3 h-96"></div>
                </div>
              </div>
            </div>

            <!-- bottom part -->
            <div class="p-3 flex flex-col lg:flex-row md:items-center mt-3 gap-10">
              <div class="w-full lg:w-3/5" id="room">
                <h5 class="font-bold text-2xl mt-6 mb-2">
                  Entire rental unit
                </h5>
                <!-- Stanze -->
                <span>{{ property.room_number }} Bedrooms</span>
                &#8901;
                <span>{{ property.bed_number }} Beds</span>
                &#8901;
                <span>{{ property.bath_number }} Bathrooms</span>

                <!-- descrizione -->
                <div class="mt-6 border-t border-neutral-300">
                  <p class="text-zinc-600 py-10">
                    {{ property.description }}
                  </p>
                </div>

                <!-- Servizi -->
                <div id="servizi">
                  <h5 class="font-bold mb-3 text-xl">
                    What this place offers
                  </h5>
                  <div class="flex flex-wrap">
                    <div v-for="service in property.services" :key="service.id" class="p-2">
                      <span class="text-zinc-600">
                        {{ service.name }}
                      </span>
                      <span class="font-bold ml-2 text-zinc-600">
                        &#8901;
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MESSAGE FORM -->

              <div class="flex justify-center py-2 lg:w-2/5">
                <div class="contact-form">
                  <div class="title mb-2">
                    <span class="font-bold">{{
                      property.price_per_night
                    }}
                      &euro;</span>
                    night
                  </div>
                  <p class="description my-4">
                    Ask information about our accommodation
                  </p>

                  <form @submit.prevent="sendMessage">
                    <div class="form-group">
                      <InputLabel for="formEmail" value="Email*" />
                      <input type="email" v-model="email" class="form-control rounded border-white mb-3 form-input w-full"
                        required />
                      <!-- <InputError :message="form.errors.email" class="mt-1" /> -->
                    </div>
                    <div class="form-group">
                      <InputLabel for="formMessage" value="Message*" />
                      <textarea v-model="message" class="form-control rounded border-white mb-3 form-text-area w-full"
                        required></textarea>
                      <!-- <InputError :message="form.errors.message" class="mt-1" /> -->
                    </div>
                    <button type="submit"
                      class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                      Send message
                    </button>
                  </form>
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
@use "../../../scss/partials/variables" as *;

.mx-auto {
  img {
    border-radius: 20px;
    height: 385px;
    object-fit: cover;
  }

  #servizi {
    padding: 40px 0;
    border-top: 1px solid lightgray;
    border-bottom: 1px solid lightgray;
  }

  .contact-form {
    padding: 40px 50px;
    background-color: #ffffff;
    border-radius: 12px;
    max-width: 500px;
    max-height: 600px;
    box-shadow: rgba(0, 0, 0, 0.12) 0px 6px 16px;
  }

  .contact-form textarea {
    resize: none;
  }

  .contact-form .form-input,
  .form-text-area {
    background-color: #f0f4f5;
    height: 50px;
    padding-left: 16px;
  }

  .contact-form .form-text-area {
    background-color: #f0f4f5;
    height: auto;
    padding-left: 16px;
  }

  .contact-form .form-control::placeholder {
    color: #aeb4b9;
    font-weight: 500;
    opacity: 1;
  }

  .contact-form .form-control:-ms-input-placeholder {
    color: #aeb4b9;
    font-weight: 500;
  }

  .contact-form .form-control::-ms-input-placeholder {
    color: #aeb4b9;
    font-weight: 500;
  }

  .contact-form .form-control:focus {
    border-color: $primary_pink;
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.07), 0 0 8px $primary_pink;
  }

  .contact-form .title {
    text-align: center;
    font-size: 24px;
    font-weight: 500;
  }

  .contact-form .description {
    color: #aeb4b9;
    font-size: 14px;
    text-align: center;
  }

  .contact-form .submit-button-wrapper {
    text-align: center;
  }

  .contact-form .submit-button-wrapper input {
    cursor: pointer;
    border: none;
    border-radius: 4px;
    background-color: $primary_pink;
    color: white;
    text-transform: uppercase;
    padding: 10px 60px;
    font-weight: 500;
    letter-spacing: 2px;
  }

  .contact-form .submit-button-wrapper input:hover {
    background-color: $primary_pink;
  }
}
</style>
