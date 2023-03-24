<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import axios from "axios";
import PropertyCardSkeleton from "../Components/PropertyCardSkeleton.vue";

const API_URL = "http://localhost:8000/api/";
const API_VER = "v1/";
const API = API_URL + API_VER;

export default {
    components: {
        AuthenticatedLayout,
        Head,
        Link,
        PropertyCardSkeleton,
    },
    data() {
        return {
            properties: [],
            sponsorships: [],
            selectedPropertyId: 0,
            imageLoadingStates: {},
            showFlashMessage: false,
        };
    },

    mounted() {
        this.getUserProperties();
        this.alertBar();
        if (this.$page.props.flash.message) {
            this.showFlashMessage = true;
            setTimeout(() => {
                this.showFlashMessage = false;
            }, 4000);
        }
    },

    methods: {
        // metodo che permette l'eliminazione di una proprietà
        async deleteItem(property) {
            this.$swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Delete Property",
                cancelButtonText: "Back",
            }).then(async (result) => {
                if (result.value) {
                    try {
                        await this.$inertia.post("/properties/" + property.id, {
                            _method: "DELETE",
                        });
                        // Rimuovi la proprietà cancellata dall'array di proprietà
                        this.properties = this.properties.filter(
                            (p) => p.id !== property.id
                        );
                        this.$swal.fire({
                            icon: "success",
                            title: "Property deleted successfully",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    } catch (error) {
                        console.error("Failed to delete property:", error);
                    }
                }
            });
        },
        getUserProperties() {
            axios
                .get(API + `${this.$page.props.auth.user.id}/properties`)
                .then((response) => {
                    this.properties = response.data.sort(
                        (a, b) =>
                            new Date(b.created_at) - new Date(a.created_at)
                    );
                    this.properties.forEach((property) => {
                        this.imageLoadingStates = {
                            ...this.imageLoadingStates,
                            [property.id]: true,
                        };
                    });
                })
                .catch((error) => console.error(error));
        },
        alertBar() {
            setTimeout(() => {
                const alertDiv = document.querySelector(".alert_bar");
                if (alertDiv) {
                    alertDiv.remove();
                }
            }, 2000);
        },
    },
};
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <!-- Header -->
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-2xl">Ciao {{ $page.props.auth.user.name }}</h2>

                <Link
                    :href="route('properties.create')"
                    class="bg-neutral-800 hover:bg-neutral-900 text-white py-2 px-4 rounded-md"
                >
                    Add new property
                </Link>
            </div>
            <!-- alert bar -->
            <div
                class="alert_bar bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3"
                role="alert"
                v-if="showFlashMessage"
            >
                <div class="flex">
                    <div>
                        <p class="text-sm">
                            {{ $page.props.flash.message }}
                        </p>
                    </div>
                </div>
            </div>
        </template>

        <!-- cards -->
        <div class="mt-10">
            <div class="w-5/6 mx-auto">
                <div
                    class="flex flex-wrap gap-3 container mx-auto justify-center"
                >
                    <div v-for="property in properties" :key="property.id">
                        <div class="box mb-8">
                            <div class="ms_image">
                                <img
                                    :src="property.img"
                                    class="card-img-top"
                                    :alt="property.title"
                                />
                                <div
                                    class="ms_commands flex justify-around items-center px-3 text-xl"
                                >
                                    <span>
                                        <Link
                                            :href="
                                                route(
                                                    'properties.edit',
                                                    property.id
                                                )
                                            "
                                        >
                                            <i
                                                class="uil uil-edit"
                                                title="Edit property"
                                            ></i>
                                        </Link>
                                    </span>

                                    <span>
                                        <Link
                                            :href="
                                                route('prop.stats', {
                                                    propid: property.id,
                                                })
                                            "
                                        >
                                            <i
                                                class="uil uil-analytics"
                                                title="View statistics"
                                            ></i>
                                        </Link>
                                    </span>
                                    <span>
                                        <Link
                                            :href="
                                                route('prop.sponsorship', {
                                                    propid: property.id,
                                                })
                                            "
                                        >
                                            <i
                                                class="uil uil-rocket"
                                                title="Sponsor property"
                                            ></i>
                                        </Link>
                                    </span>
                                    <span @click="deleteItem(property)">
                                        <i
                                            title="Delete property"
                                            class="uil uil-trash-alt"
                                        ></i>
                                    </span>
                                </div>
                            </div>
                            <Link
                                :href="
                                    route('property.show', {
                                        id: property.id,
                                    })
                                "
                                target="_blank"
                            >
                                <div class="text p-3">
                                    <h5
                                        class="ms_title font-bold text-3xl text-zinc-800 mb-2"
                                    >
                                        {{ property.title }}
                                    </h5>
                                    <h6 class="ms_address text-zinc-600">
                                        <i class="uil uil-map-marker"></i>
                                        {{ property.address }}
                                    </h6>
                                    <h6
                                        class="ms_price bg-neutral-800 text-white rounded-full py-1.5 px-4"
                                    >
                                        <span
                                            >&euro;
                                            {{ property.price_per_night }}
                                        </span>
                                    </h6>

                                    <!-- sponsorship tag nella proprietà -->
                                    <div
                                        v-if="property.sponsorships"
                                        class="p-2"
                                    >
                                        <span
                                            v-for="sponsorship in property.sponsorships"
                                            class="ms_type bg-rose-600 text-white rounded-full pb-1 pt-1.5 px-2.5"
                                        >
                                            <i
                                                class="uil uil-rocket"
                                                title="Sponsored"
                                            ></i>
                                        </span>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style lang="scss" scoped>
@use "../../scss/partials/variables" as *;

a {
    text-decoration: none !important;
}

.box {
    position: relative;
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

            &:hover {
                text-decoration: underline;
            }
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

        .ms_type {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    }

    .ms_image {
        position: relative;
        border-radius: 20px;

        &:hover {
            .ms_commands {
                visibility: visible;
            }
        }

        .ms_commands {
            visibility: hidden;
            filter: drop-shadow(0mm -4mm 4mm #00000094);
            backdrop-filter: blur(1px) saturate(180%);
            -webkit-backdrop-filter: blur(1px) saturate(180%);
            background-color: #5e5e5e94;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
            top: 200px;
            bottom: 0px;
            left: 0px;
            right: 0px;
            position: absolute;
        }

        .ms_commands .uil {
            color: white;
        }

        .ms_commands .uil-trash-alt,
        .ms_commands .uil-edit {
            cursor: pointer;
        }

        @media screen and (max-width: 768px) {
            /* Tablet */
            .ms_commands {
                visibility: visible !important;
            }
        }

        img {
            height: 250px;
            width: 100%;
            border-radius: 20px;
            object-fit: cover;
        }
    }
}
</style>
