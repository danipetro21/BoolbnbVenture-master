<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

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
    Bar
  },
  data() {
    return {
      property: parseInt(this.propid),
      messages: [],
      chartData: {
        labels: [

        ],
        datasets: [
          {
            label: "Number of Views",
            backgroundColor: "rgba(54, 162, 235, 0.5)",
            borderColor: "rgba(54, 162, 235, 1)",
            borderWidth: 1,
            hoverBackgroundColor: "rgba(54, 162, 235, 0.7)",
            hoverBorderColor: "rgba(54, 162, 235, 1)",
            data: [],
          },
        ],
      },
      chartOptions: {
        responsive: true
      }

    };
  },
  mounted() {
    this.getStatProp();
    this.getPropertyMessages(this.property);

  },
  methods: {
    // metodo che ottiene i messaggi associati a una proprietà
    getPropertyMessages(propertyId) {
      axios
        .get(API + `messages/${propertyId}`)
        .then((response) => {
          this.messages = response.data.response;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getSenderName(email) {
      var name = email.split(".")[0];
      return name.charAt(0).toUpperCase() + name.slice(1);
    },
    async deleteItem(message) {
      this.$swal({
        title: 'Are you sure?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Delete',
        cancelButtonText: 'Back'
      }).then(async (result) => {
        if (result.value) {
          try {
            await this.$inertia.post("/messages/" + message.id, {
              _method: "DELETE",
            });
            // Remove the deleted property from the properties array
            this.messages = this.messages.filter(
              (p) => p.id !== message.id
            );
            // Show success alert
            this.$swal.fire({
              icon: 'success',
              title: 'Message deleted successfully',
              showConfirmButton: false,
              timer: 1500
            });
          } catch (error) {
            console.error("Failed to delete message:", error);
          }
        }
      })
    },
    getStatProp() {
      axios
        .get(API + `stats/` + this.propid)
        .then(response => {
          const date = [];
          const statsData = [];
          const stats = response.data.data;
          if (stats && stats.length > 0) {
            console.log(stats);
            stats.forEach(element => {
              date.push(element.date)
              statsData.push(element.views)
            });
            this.chartData = {
              labels: date,
              datasets: [{
                label: 'Views per day',
                backgroundColor: '#FF7D94',
                data: statsData
              }]
            };
          } else {
            this.$swal({
              title: 'There are no stats for your property',
              text: '',
              icon: 'error',
              confirmButtonText: 'OK'
            })
          }
        })

        .catch(error => {
          console.error(error);
        });
    }
  },
};
</script>
<template>
  <AuthenticatedLayout>
    <!-- Parte messaggi proprietà -->
    <div class="w-5/6 mx-auto px-6">
      <!-- Card messaggi-->
      <div class="container mt-20 mx-auto">
        <div v-if="messages.length === 0" class="text-center text-2xl font-bold text-gray-500 dark:text-gray-400">
          No message received &#128532;
        </div>

        <!-- TAB MESSAGGI -->
        <div v-else class="relative overflow-auto bg-white shadow-md rounded-lg border flex flex-col lg:flex-row mb-5"
          v-for="message in messages" :key="message.id">

          <div class="text-sm text-left w-full lg:w-1/4">
            <div class="text-xs text-gray-600 uppercase">
              <div class="px-6 py-3 border-b font-bold bg-gray-200">Sender</div>
            </div>
            <div class="px-6 py-4 font-medium text-gray-600">
              {{ message.email }}
            </div>
          </div>

          <div class="text-sm text-left w-full lg:w-1/3">
            <div class="text-xs text-gray-600 uppercase">
              <div class="px-6 py-3 border-b font-bold bg-gray-200">Message</div>
            </div>
            <div class="px-6 py-4 font-medium text-gray-600 overflow-y-auto">
              {{ message.message }}
            </div>
          </div>

          <div class="text-sm text-left w-full lg:w-1/6">
            <div class="text-xs text-gray-600 uppercase">
              <div class="px-6 py-3 border-b font-bold bg-gray-200">Date</div>
              <div class="px-6 py-4 font-medium text-gray-600">
                {{ message.received_at }}
              </div>
            </div>
          </div>

          <div class="text-sm text-left w-full lg:w-1/4">
            <div class="text-xs text-gray-600 uppercase">
              <div class="px-6 py-3 border-b font-bold bg-gray-200">Actions</div>
              <div class="px-6 py-4 font-medium text-gray-600 flex">
                <a class="mr-5 bg-rose-600 hover:bg-rose-800 text-white font-bold py-2 px-4 rounded" :href="
                  'mailto:' +
                  message.email +
                  '?subject=' +
                  encodeURIComponent(
                    message.message
                  ) +
                  '&body=Hello%20' +
                  encodeURIComponent(
                    getSenderName(message.email) +
                    '!'
                  )
                ">Reply</a>

                <button @click="deleteItem(message)"
                  class="bg-black hover:bg-gray-700 text-white font-bold py-2 px-4 rounded uppercase">
                  Delete
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Parte statistiche proprietà -->
    <div class="w-5/6 mx-auto px-6">
      <!-- Stats card-->
      <div class="container mt-20 mx-auto">
        <h2 class="font-bold text-xl text-center mb-5">
          Property Statistics
        </h2>
        <div class="w-5/6 mx-auto px-4 mb-5 flex flex-wrap justify-center gap-5">
          <Bar :data="chartData" :options="chartOptions" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style lang="scss" scoped>
.ms_risposta {
  &:hover {
    text-decoration: underline;
  }
}
</style>
