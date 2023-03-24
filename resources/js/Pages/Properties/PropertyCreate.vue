<script>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import AuthenticatedLayoutVue from "@/Layouts/AuthenticatedLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";

export default {
  components: {
    AuthenticatedLayoutVue,
    Link,
    InputLabel,
    InputError,
  },

  setup() {
    const form = useForm({
      title: "",
      description: "",
      img: "",
      room_number: "",
      bath_number: "",
      mq2: "",
      address: "",
      bed_number: "",
      price_per_night: "",
      visible: 1,
      services: [],
    });

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

    const submit = () => {
      form.post(route("properties.store"), {
        data: { visible: form.visible },
        onSuccess: () => form.reset(),
      });
    };

    return { form, submit };
  },
};
</script>

<template>
  <AuthenticatedLayoutVue>
    <div class="py-12">
      <div class="w-9/12 mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
          <form enctype="multipart/form-data" @submit.prevent="submit">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="">
                <div class="mb-4">
                  <InputLabel for="formTitle" value="Title*" />
                  <input type="text" v-model="form.title"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:border-rose-500 focus:ring-rose-500"
                    id="formTitle" placeholder="Enter the property title" />
                  <InputError :message="form.errors.title" class="mt-1" />
                </div>
                <div class="mb-4">
                  <InputLabel for="formDescription" value="Description*" />
                  <input type="text" v-model="form.description"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:border-rose-500 focus:ring-rose-500"
                    id="formDescription" placeholder="Enter the property description" />
                  <InputError :message="form.errors.description" class="mt-1" />
                </div>
                <div class="mb-4">
                  <InputLabel for="formImage" value="Image*" />
                  <input type="file" name="image"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:border-rose-500 focus:ring-rose-500"
                    id="formImage" placeholder="Enter the image of the property" @input="
                      form.img = $event.target.files[0]
                    " accept="image/jpeg,image/png,image/jpg" />
                  <InputError :message="form.errors.img" class="mt-1" />
                </div>

                <div class="mb-4">
                  <div class="mb-4">
                    <InputLabel for="formRoomNumber" value="Room Number*" />
                    <input type="text" v-model="form.room_number"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:border-rose-500 focus:ring-rose-500"
                      id="formRoomNumber" placeholder="Enter the number of rooms" />
                    <InputError :message="form.errors.room_number" class="mt-1" />
                  </div>

                  <div class="mb-4">
                    <InputLabel for="formBathNumber" value="Bath Number*" />
                    <input type="text" v-model="form.bath_number"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:border-rose-500 focus:ring-rose-500"
                      id="formBathNumber" placeholder="Enter the number of baths" />
                    <InputError :message="form.errors.bath_number" class="mt-1" />
                  </div>
                  <div class="mb-4">
                    <InputLabel for="formSquareMeters" value="Square Meters*" />
                    <input type="text" v-model="form.mq2"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:border-rose-500 focus:ring-rose-500"
                      id="formSquareMeters" placeholder="Enter the square meters of the property" />
                    <InputError :message="form.errors.mq2" class="mt-1" />
                  </div>
                  <div class="mb-4">
                    <InputLabel for="formAddress" value="Address*" />

                    <input type="text" v-model="form.address"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:border-rose-500 focus:ring-rose-500"
                      id="formAddress" placeholder="Enter the property location" @focus="initializeAutocomplete"
                      maxlength="" />
                    <InputError :message="form.errors.address" class="mt-1" />
                  </div>
                  <div class="mb-4">
                    <InputLabel for="formBedNumber" value="Bed Number*" />

                    <input type="text" v-model="form.bed_number"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:border-rose-500 focus:ring-rose-500"
                      id="formBedNumber" placeholder="Enter the number of beds" />
                    <InputError :message="form.errors.bed_number" class="mt-1" />
                  </div>

                  <div class="mb-4">
                    <InputLabel for="formPrice" value="Price per night*" />
                    <input type="text" v-model="form.price_per_night"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:border-rose-500 focus:ring-rose-500"
                      id="formPrice" placeholder="Enter the price per night" />
                    <InputError :message="
                      form.errors.price_per_night
                    " class="mt-1" />
                  </div>
                  <div class="mb-4">
                    <InputLabel for="formVisibility" value="Do you want to make the property visible on the website?" />
                    <input type="checkbox" v-model="form.visible"
                      class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:border-rose-500 focus:ring-rose-500"
                      id="formVisibility" :true-value="1" :false-value="0" />
                  </div>
                </div>
                <div class="mb-4">
                  <InputLabel for="formServices" value="Services*" />
                  <InputError :message="form.errors.services" class="mt-1" />

                  <ul class="flex flex-wrap gap-4">
                    <li v-for="service in $page.props
                      .services" :key="service.id">
                      <input id="formServices" type="checkbox" v-model="form.services" :value="service.id"
                        class="form-checkbox h-5 w-5 text-gray-600 focus:border-rose-500 focus:ring-rose-500" />
                      {{ service.name }}
                    </li>
                  </ul>
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
