<script>
import Footer from "@/Components/Footer.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PropertyCard from "@/Pages/Properties/PropertyCard.vue";
import { Head, Link } from "@inertiajs/vue3";

export default {
  components: {
    AuthenticatedLayout,
    PropertyCard,
    Link,
    Head,
    Footer,
  },
  data() {
    return {
      openModal: false,
      filter: {
        room_number: null,
        bath_number: null,
        mq2: null,
        price_per_night: null,
        bed_number: null,
      },
      searchAddress: this.$page.props.address || "",
      searchLocation: this.$props.searchLocation || [],
      properties: this.$props.properties || [],
      pagination: this.$props.pagination || {},
      errorMessage: null,
      search: {
        city: "",
        radius: "",
      },
    };
  },

  computed: {
    hasError() {
      return this.errorMessage !== null;
    },
  },

  methods: {
    initAutocomplete() {
      const input = this.$refs.searchAddressInput;
      const options = {
        types: ["address"],
        componentRestrictions: { country: "us" },
        fields: ["formatted_address", "geometry"],
      };
      const autocomplete = new google.maps.places.Autocomplete(
        input,
        options
      );

      autocomplete.addListener("place_changed", () => {
        const place = autocomplete.getPlace();
        if (place.geometry) {
          this.searchAddress = place.formatted_address;
          this.submitSearchForm();
        }
      });
    },
    submitFilters() {
      this.submitSearchForm();
      this.openModal = false;
    },

    submitSearchForm(page = 1) {
      this.$inertia.post(
        route("property.search"),
        {
          address: this.searchAddress || null,
          page: page,
          filters: this.filter,
          radius: this.search.radius || 20,
        },
        {
          preserveState: true,
          onSuccess: (page) => {
            if (page.props.error) {
              this.$emit("error", page.props.error);
            } else {
              this.properties = Object.assign(
                [],
                page.props.properties
              );
              this.pagination = Object.assign(
                {},
                page.props.pagination
              );
            }
          },
        }
      );
    },

    createPageArray(currentPage, totalPages, delta = 2) {
      let pages = [];
      for (
        let i = Math.max(1, currentPage - delta);
        i <= Math.min(totalPages, currentPage + delta);
        i++
      ) {
        pages.push(i);
      }
      return pages;
    },
  },

  mounted() {
    const baseURL = window.location.origin;
    const url = new URL(this.$inertia.page.url, baseURL);
    this.searchAddress = url.searchParams.get("address") || "";
    this.submitSearchForm();
    this.initAutocomplete();
  },
};
</script>
<template>
  <Head title="Welcome" />
  <AuthenticatedLayout>
    <div>
      <div class="min-h-screen bg-gray-100">
        <main>
          <!-- SEARCH -->
          <div class="w-full flex justify-center justify-center items-center">
            <div class="w-full my-5 text-center">
              <div class="mb-3">
                <div>
                  <h2 class="font-bold text-5xl mb-4">
                    Search for your next destination
                  </h2>

                  <form @submit.prevent="submitSearchForm" class="flex justify-center flex-col md:flex-row">
                    <div class="my-4 flex justify-center">
                      <div class="flex flex-col">
                        <label for="destination" class="text-zinc-700 font-bold">Destination</label>
                        <input type="text" ref="searchAddressInput" v-model="searchAddress"
                          placeholder="Insert your next destination.." @input="
                            getAddressSuggestions
                          "
                          class="border-gray-300 focus:border-rose-500 focus:ring-rose-500 rounded-full shadow-sm m-1" />
                      </div>
                    </div>

                    <div class="my-4 flex justify-center md:items-end">
                      <button type="submit"
                        class="bg-zinc-700 hover:bg-zinc-900 text-white font-bold py-2 px-4 m-1 rounded-full">
                        Search
                      </button>
                      <button type="button"
                        class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 m-1 rounded-full"
                        @click="openModal = true">
                        Filters
                      </button>
                    </div>
                  </form>
                  <div v-if="hasError" class="alert alert-danger">
                    {{ errorMessage }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="fixed z-40 top-0 left-0 w-full h-full bg-black opacity-75" v-if="openModal"
            @click="openModal = false"></div>
          <div class="fixed z-40 top-0 left-0 w-full h-full flex items-center justify-center" v-if="openModal">
            <div class="bg-white p-8 rounded-lg overflow-hidden shadow-lg">
              <h3 class="text-lg font-bold mb-4">Filters</h3>
              <div class="flex">
                <div class="m-4">
                  <label class="block text-gray-700 font-medium mb-2" for="room_number">Room Number</label>
                  <input class="w-full border border-gray-400 p-2 rounded-lg" type="number" id="room_number"
                    v-model="filter.room_number" placeholder="Insert max room number" />
                </div>
                <div class="m-4">
                  <label class="block text-gray-700 font-medium mb-2" for="bath_number">Bath Number</label>
                  <input class="w-full border border-gray-400 p-2 rounded-lg" type="number" id="bath_number"
                    v-model="filter.bath_number" placeholder="Insert max bath number" />
                </div>
                <div class="m-4">
                  <label class="block text-gray-700 font-medium mb-2" for="bed_number">Bed Number</label>
                  <input class="w-full border border-gray-400 p-2 rounded-lg" type="number" id="bed_number"
                    v-model="filter.bed_number" placeholder="Insert max bed number" />
                </div>
              </div>
              <div class="flex">
                <div class="m-4">
                  <label class="block text-gray-700 font-medium mb-2" for="room_number">Km</label>
                  <input class="w-full border border-gray-400 p-2 rounded-lg" type="number" id="room_number"
                    v-model="search.radius" placeholder="Default 20 Km" />
                </div>
                <div class="m-4">
                  <label class="block text-gray-700 font-medium mb-2" for="mq2">Square Meters</label>
                  <input class="w-full border border-gray-400 p-2 rounded-lg" type="number" id="mq2" v-model="filter.mq2"
                    placeholder="Insert max square meters" />
                </div>
                <div class="m-4">
                  <label class="block text-gray-700 font-medium mb-2" for="price_per_night">Price Per Night</label>
                  <input class="w-full border border-gray-400 p-2 rounded-lg" type="number" id="price_per_night"
                    v-model="filter.price_per_night" placeholder="Insert max price per night" />
                </div>
              </div>
              <div class="flex justify-end">
                <button class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded mr-4"
                  @click="submitFilters">
                  Submit
                </button>
                <button class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                  @click="openModal = false">
                  Cancel
                </button>
              </div>
            </div>
          </div>

          <!-- Cards -->
          <div class="w-full">
            <div class="flex justify-center mb-5">
              <span class="text-sm text-gray-700 dark:text-gray-400">
                Showing {{ pagination.from }} to
                {{ pagination.to }} of
                {{ pagination.total }} results
              </span>
            </div>
            <div :key="properties.length" class="flex flex-wrap gap-4 container mx-auto justify-center">
              <div v-for="property in properties" :key="property.id">
                <div class="box my-3">
                  <Link :href="
                    route('property.show', {
                      id: property.id,
                    })
                  " target="_blank">
                  <PropertyCard :info="property" />
                  </Link>
                </div>
              </div>
            </div>
          </div>

          <nav class="flex items-center justify-center mt-10" v-if="pagination.total">
            <div>
              <button :disabled="pagination.current_page === 1" @click="
                submitSearchForm(
                  pagination.current_page - 1
                )
              "
                class="relative inline-flex items-center px-3 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                Prev
              </button>

              <button v-for="page in createPageArray(
                pagination.current_page,
                pagination.last_page
              )" :key="page" @click="submitSearchForm(page)"
                class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                :class="{
                  'text-blue-600 font-bold':
                    page === pagination.current_page,
                }">
                {{ page }}
              </button>

              <button :disabled="
                pagination.current_page ===
                pagination.last_page
              " @click="
  submitSearchForm(
    pagination.current_page + 1
  )
"
                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                Next
              </button>
            </div>
          </nav>
        </main>
      </div>
    </div>
    <Footer></Footer>
  </AuthenticatedLayout>
</template>
