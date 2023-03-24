<script>
import Footer from "@/Components/Footer.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PropertyCard from "@/Pages/Properties/PropertyCard.vue";
import { Head, Link } from "@inertiajs/vue3";
import axios from "axios";

const API_URL = "http://localhost:8000/api/";
const API_VER = "v1/";
const API = API_URL + API_VER;

export default {
    components: {
        Head,
        Link,
        PropertyCard,
        AuthenticatedLayout,
        Footer,
    },
    data() {
        return {
            searchAddress: "",
            properties: [],
            pagination: {},
            search: {
                city: "",
                // default
                radius: "20",
            },
        };
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
        submitSearchForm() {
            this.$inertia.visit(
                route("property.search", { address: this.searchAddress }),
                {
                    method: "GET",
                    preserveState: true,
                }
            );
        },

        getProperty(page = 1) {
            axios
                .get(API + "sponsored?page=" + page)
                .then((res) => {
                    const data = res.data;
                    const success = data.success;
                    const properties = data.response;
                    const pagination = data.response;

                    if (success) {
                        this.properties = properties;
                        this.pagination = pagination;
                    }
                })
                .catch((err) => console.error(err));
        },
    },
    mounted() {
        this.getProperty();
        this.initAutocomplete();
    },
};
</script>

<template>
    <Head title="Welcome" />
    <AuthenticatedLayout>
        <div>
            <div class="min-h-screen">
                <!-- Page Content -->
                <main>
                    <!-- JUMBOTRON -->
                    <div class="w-9/12 mx-auto py-20">
                        <div class="w-full md:max-w-7xl mx-auto px-4 mb-5">
                            <div
                                class="flex flex-col md:flex-row-reverse md:space-x-reverse md:space-x-5 items-center"
                            >
                                <div class="w-full sm:w-4/5 md:w-3/5 lg:w-1/2">
                                    <img
                                        src="jumbo/Glamping-pana.svg"
                                        class="block mx-auto md:mx-0 w-full"
                                        alt=""
                                        loading="lazy"
                                    />
                                </div>
                                <div class="w-full md:w-1/2">
                                    <div class="mb-3">
                                        <h2
                                            class="font-bold text-5xl text-center"
                                        >
                                            Search for your next destination
                                        </h2>

                                        <h3
                                            class="text-2xl mb-10 mt-5 text-center"
                                        >
                                            Explore our properties
                                            <span class="font-bold">Now</span>
                                        </h3>

                                        <!-- SEARCH -->
                                        <div
                                            class="max-w-md mx-auto flex justify-center"
                                        >
                                            <!-- search form -->
                                            <form
                                                class="w-full flex relative"
                                                @submit.prevent="
                                                    submitSearchForm
                                                "
                                            >
                                                <!-- search icon -->
                                                <div
                                                    class="absolute grid place-items-center h-full w-12 text-gray-300"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-6 w-6 mr-2"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                                        />
                                                    </svg>
                                                </div>

                                                <!-- search bar -->
                                                <input
                                                    class="peer h-full w-full outline-none text-sm text-gray-700 px-10 rounded-full"
                                                    type="text"
                                                    v-model="searchAddress"
                                                    ref="searchAddressInput"
                                                    placeholder="Insert your next destination.."
                                                    @input="
                                                        getAddressSuggestions
                                                    "
                                                />
                                                <button
                                                    class="ml-3 text-white px-4 bg-gray-800 rounded-full hover:bg-gray-900"
                                                    type="submit"
                                                >
                                                    Search
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FEATURED -->
                    <div class="w-9/12 container mx-auto">
                        <div class="w-full flex justify-center">
                            <h2 class="font-bold text-4xl px-10 mb-4">
                                Featured Listing
                            </h2>
                        </div>

                        <!-- SPONSORHIP PROPERTY -->
                        <div class="p-12">
                            <div class="max-w-7xl mx-auto">
                                <div
                                    class="flex flex-wrap gap-6 container mx-auto justify-center"
                                >
                                    <!-- card -->
                                    <div
                                        class="box mb-5"
                                        v-for="property in properties"
                                        :key="property.id"
                                    >
                                        <Link
                                            :href="
                                                route('property.show', {
                                                    id: property.id,
                                                })
                                            "
                                            target="_blank"
                                        >
                                            <PropertyCard
                                                :info="property"
                                                :sponsored="true"
                                            />
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottone per tutte le proprietà -->
                    <div class="w-full">
                        <div class="mb-10 flex justify-center">
                            <Link :href="route('property.search')">
                                <h3
                                    class="bg-transparent hover:bg-neutral-800 font-semibold hover:text-white py-2 px-4 border border-neutral-800 hover:border-transparent rounded-full py-1.5 px-4"
                                >
                                    See more
                                </h3>
                            </Link>
                        </div>
                    </div>

                    <!-- Boolbnb info -->
                    <div class="w-9/12 container mx-auto">
                        <div class="w-full md:max-w-7xl mx-auto px-4 mb-5">
                            <div class="flex flex-col md:flex-row items-center">
                                <div
                                    class="w-full sm:w-4/5 md:w-3/5 lg:w-1/2 p-8"
                                >
                                    <div class="mb-3">
                                        <h2
                                            class="font-bold text-4xl sm:text-center md:text-left mb-5"
                                        >
                                            Our Vision
                                        </h2>
                                        <p class="mb-4 leading-8 text-gray-600">
                                            We believe that staying in a local
                                            home can provide a more immersive
                                            and enriching travel experience than
                                            staying in a hotel. Our platform
                                            allows guests to discover and book a
                                            wide variety of accommodations, from
                                            cozy apartments to luxurious villas,
                                            in almost any corner of the world.
                                            <br />
                                            We also empower hosts to share their
                                            homes and local expertise with
                                            guests, creating a meaningful
                                            connection between travelers and
                                            their destinations. Our ultimate
                                            goal is to make travel more
                                            accessible, enjoyable, and
                                            sustainable for everyone.
                                        </p>
                                        <img
                                            src="https://images.unsplash.com/photo-1519643381401-22c77e60520e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=873&q=80"
                                            alt="house-interior"
                                            class="rounded-xl"
                                        />
                                    </div>
                                </div>

                                <!-- Our mission -->
                                <div
                                    class="w-full sm:w-4/5 md:w-3/5 lg:w-1/2 p-8"
                                >
                                    <div class="mb-3">
                                        <img
                                            src="https://images.unsplash.com/photo-1615873968403-89e068629265?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1032&q=80"
                                            alt="house-interior"
                                            class="rounded-xl"
                                        />
                                        <h2
                                            class="font-bold text-4xl sm:text-center mb-5 md:text-left mt-5"
                                        >
                                            Our Mission
                                        </h2>
                                        <p class="mb-4 leading-8 text-gray-600">
                                            Boolbnb's mission is to create a
                                            world where anyone can feel at home,
                                            anywhere they go. We aim to achieve
                                            this by providing a platform where
                                            travelers can easily book unique and
                                            personalized accommodations that
                                            suit their needs and preferences.
                                            <br />
                                            We are committed to promoting
                                            sustainability and responsible
                                            travel practices to ensure that the
                                            places we visit today will still be
                                            around for future generations to
                                            enjoy. <br />
                                            Our mission is to create a world
                                            where travel is more than just a
                                            destination, it's a journey towards
                                            greater understanding, appreciation,
                                            and connection.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <Footer></Footer>
                </main>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style lang="scss" scoped>
@use "../../scss/general.scss" as *;
@use "../../scss/app.scss" as *;

[type="text"][data-v-4307d078] {
    border-width: 0;
}

// a {
//     text-decoration: none !important;
// }

.box {
    position: relative;
    cursor: pointer;
    width: 270px;
    height: 350px;

    .text {
        padding: 10px;

        .ms_title {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* number of lines to show */
            line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .ms_address {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            /* number of lines to show */
            line-clamp: 1;
            -webkit-box-orient: vertical;
        }

        .ms_price {
            position: absolute;
            top: 10px;
            left: 10px;
        }
    }

    img {
        height: 250px;
        width: inherit;
        border-radius: 20px;
        object-fit: cover;
    }
}
</style>
