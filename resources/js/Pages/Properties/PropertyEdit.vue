<script>
import AuthenticatedLayoutVue from "@/Layouts/AuthenticatedLayout.vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";

export default {
  components: {
    AuthenticatedLayoutVue,
    Link,
  },
  props: {
    property: Object,
    services: Array,
    image: String,
  },
  setup(props) {
    const form = useForm({
      title: props.property.title,
      description: props.property.description,
      img: props.property.img,
      room_number: props.property.room_number,
      bath_number: props.property.bath_number,
      mq2: props.property.mq2,
      address: props.property.address,
      bed_number: props.property.bed_number,
      price_per_night: props.property.price_per_night,
      visible: props.property.visible,
      services: props.property.services.map((service) => service.id),
    });

    function updateProperty() {
      router.post(`/properties/${props.property.id}`, {
        _method: "put",
        img: form.img,
        title: form.title,
        description: form.description,
        room_number: form.room_number,
        bath_number: form.bath_number,
        mq2: form.mq2,
        address: form.address,
        bed_number: form.bed_number,
        price_per_night: form.price_per_night,
        visible: form.visible,
        services: form.services.map((service) => service.toString()),
      });
    }

    const autocomplete = ref(null);

    function fillInAddress() {
      let place = autocomplete.value.getPlace();
      if (place && place.formatted_address) {
        form.address = place.formatted_address;
      }
    }

    onMounted(() => {
      const input = document.getElementById("formAddress");
      const options = {
        types: ["address"],
        componentRestrictions: { country: "us" },
      };
      autocomplete.value = new google.maps.places.Autocomplete(
        input,
        options
      );
      autocomplete.value.addListener("place_changed", fillInAddress);
    });

    return { form, updateProperty };
  },
};
</script>

<template>
  <AuthenticatedLayoutVue>
    <div class="py-12">
      <div class="w-9/12 mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
          <form @submit.prevent="updateProperty()">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div>
                <div class="mb-4">
                  <label for="formBookTitle" class="block text-gray-700 text-lg font-bold mb-2">Title:
                  </label>
                  <input type="text" v-model="form.title"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:border-rose-500 focus:ring-rose-500"
                    id="formBookTitle" placeholder="Enter Title" />
                  <div v-if="$page.props.errors.title" class="text-red-500">
                    {{ $page.props.errors.title }}
                  </div>
                </div>
                <div class="mb-4">
                  <label for="formBookAuthor" class="block text-gray-700 text-lg font-bold mb-2">Description:</label>
                  <input type="text" v-model="form.description"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:border-rose-500 focus:ring-rose-500"
                    id="formBookAuthor" placeholder="Enter Author" />
                  <div v-if="$page.props.errors.description" class="text-red-500">
                    {{ $page.props.errors.description }}
                  </div>
                </div>
                <div class="mb-4 flex flex-column flex-col items-center">
                  <label for="formBookImage" class="block text-gray-700 text-lg font-bold mb-2">Image:</label>
                  <div>
                    <img :src="form.img" alt="" class="w-32 h-32" />
                  </div>
                  <br />
                  <input type="file" name="img"
                    class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:border-rose-500 focus:ring-rose-500 w-80 my-button"
                    id="formBookAuthor" placeholder="Enter Author" @input="
                      form.img = $event.target.files[0]
                    " accept="image/jpeg,image/png,image/jpg" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border border-gray-300 rounded-md p-4">
                  <div>
                    <label for="formRoomNumber" class="block text-gray-700 text-lg font-bold mb-2">Room Number:</label>
                    <input type="text" v-model="form.room_number"
                      class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:border-rose-500 focus:ring-rose-500 w-20 border-gray-400"
                      id="formRoomNumber" placeholder="Enter Room Number" />
                    <div v-if="
                      $page.props.errors.room_number
                    " class="text-red-500">
                      {{ $page.props.errors.room_number }}
                    </div>
                  </div>

                  <div class="mb-4">
                    <label for="formBookAuthor" class="block text-gray-700 text-lg font-bold mb-2">Bath Number:</label>
                    <input type="text" v-model="form.bath_number"
                      class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:border-rose-500 focus:ring-rose-500 w-20"
                      id="formBookAuthor" placeholder="Enter Author" />
                    <div v-if="
                      $page.props.errors.bath_number
                    " class="text-red-500">
                      {{ $page.props.errors.bath_number }}
                    </div>
                  </div>
                  <div class="mb-4">
                    <label for="formBookAuthor" class="block text-gray-700 text-lg font-bold mb-2">Square Meters:</label>
                    <input type="text" v-model="form.mq2"
                      class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:border-rose-500 focus:ring-rose-500 w-20"
                      id="formBookAuthor" placeholder="Enter Author" />
                    <div v-if="$page.props.errors.mq2" class="text-red-500">
                      {{ $page.props.errors.mq2 }}
                    </div>
                  </div>
                  <div class="mb-4">
                    <label for="formBookAuthor" class="block text-gray-700 text-lg font-bold mb-2">Address:</label>
                    <input type="text" v-model="form.address"
                      class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:border-rose-500 focus:ring-rose-500 w-full"
                      id="formAddress" placeholder="Enter Author" @focus="initializeAutocomplete" />
                    <div v-if="$page.props.errors.address" class="text-red-500">
                      {{ $page.props.errors.address }}
                    </div>
                  </div>
                  <div class="mb-4">
                    <label for="formBookAuthor" class="block text-gray-700 text-lg font-bold mb-2">Bed Number:</label>
                    <input type="text" v-model="form.bed_number"
                      class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:border-rose-500 focus:ring-rose-500 w-20 h-8"
                      id="formBookAuthor" placeholder="Enter Author" />

                    <div v-if="$page.props.errors.bed_number" class="text-red-500">
                      {{ $page.props.errors.bed_number }}
                    </div>
                  </div>

                  <div class="mb-4">
                    <label for="formBookAuthor" class="block text-gray-700 text-lg font-bold mb-2">Price per
                      night:</label>
                    <input type="text" v-model="form.price_per_night"
                      class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:border-rose-500 focus:ring-rose-500 w-20"
                      id="formBookAuthor" placeholder="Enter Author" />
                    <div v-if="
                      $page.props.errors
                        .price_per_night
                    " class="text-red-500">
                      {{
                        $page.props.errors
                          .price_per_night
                      }}
                    </div>
                  </div>
                </div>
                <br />
                <div class="mb-4">
                  <label for="formBookAuthor" class="block text-gray-700 text-lg font-bold mb-2">Visible:</label>
                  <input type="checkbox" v-model="form.visible"
                    class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:border-rose-500 focus:ring-rose-500"
                    id="formBookAuthor" :true-value="1" :false-value="0" placeholder="Visible?" />
                  <div v-if="$page.props.errors.visible" class="text-red-500">
                    {{ $page.props.errors.visible }}
                  </div>
                </div>
                <br />
                <div class="mb-4">
                  <label class="block text-gray-700 text-lg font-bold mb-2" for="formServices">Services:</label>
                  <div class="flex flex-wrap gap-4">
                    <template v-for="service in $page.props
                      .services">
                      <label :for="'service-' + service.id" class="inline-flex items-center">
                        <input type="checkbox" id="service-{{ service.id }}"
                          class="form-checkbox h-5 w-5 text-gray-600 focus:border-rose-500 focus:ring-rose-500"
                          v-model="form.services" :value="service.id" :checked="
                            property.services
                              .map((s) => s.id)
                              .includes(
                                service.id
                              )
                          " />
                        <span class="ml-2 text-gray-700">{{ service.name }}</span>
                      </label>
                    </template>
                  </div>
                  <div v-if="$page.props.errors.services" class="text-red-500">
                    {{ $page.props.errors.services }}
                  </div>
                </div>
              </div>
            </div>

            <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                <button type="submit" :disabled="form.processing"
                  class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-pink-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-pink-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                  Save
                </button>
              </span>

              <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                <Link :href="route('properties.index')"
                  class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                Cancel
                </Link>
              </span>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayoutVue>
</template>
<style lang="scss" scoped>
.my-button {
  background-color: #f0f0f0;
  /* colore di sfondo */
  color: black;
  /* colore del testo */
  padding: 2px 20px;
  /* spazio interno */
  border: none;
  /* bordo */
  border-radius: 14px;
  /* angoli arrotondati */
  cursor: pointer;
  /* cursore */
}
</style>
