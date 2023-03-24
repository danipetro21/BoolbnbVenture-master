<script>
import { Link } from "@inertiajs/vue3";
import PropertyCardSkeleton from "../../Components/PropertyCardSkeleton.vue";

export default {
    components: {
        Link,
        PropertyCardSkeleton,
    },
    props: {
        info: Object,
        sponsored: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            isLoading: true,
            imageSrc: null,
        };
    },
    methods: {
        loadImage(src) {
            return new Promise((resolve) => {
                const image = new Image();
                image.onload = () => {
                    resolve(src);
                };
                image.src = src;
            });
        },
        async onImageLoaded() {
            this.imageSrc = await this.loadImage(this.info.img);
            this.isLoading = false;
        },
    },

    mounted() {
        this.onImageLoaded();
    },
};
</script>

<template>
    <div>
        <template v-if="isLoading || !imageSrc">
            <PropertyCardSkeleton />
        </template>
        <template v-else>
            <Link :href="'/property/' + info.id">
                <div class="box mb-5">
                    <img
                        :src="imageSrc || info.img"
                        :alt="info.title"
                        class="card-img-top"
                        @load="onImageLoaded"
                    />

                    <div class="text p-3">
                        <h5
                            class="ms_title font-bold text-3xl text-zinc-800 mb-2"
                        >
                            {{ info.title }}
                        </h5>
                        <h6 class="ms_address text-zinc-600">
                            <i class="uil uil-map-marker"></i>
                            {{ info.address }}
                        </h6>
                        <h6
                            class="ms_price bg-neutral-800 text-white rounded-full py-1.5 px-4"
                        >
                            <span>&euro; {{ info.price_per_night }} </span>
                        </h6>
                    </div>
                    <div
                        v-if="sponsored"
                        class="ms_type bg-rose-600 text-white rounded-full pb-1 pt-1.5 px-2.5"
                    >
                        <i class="uil uil-rocket" title="Sponsored"></i>
                    </div>
                </div>
            </Link>
        </template>
    </div>
</template>

<style lang="scss" scoped>
a {
    text-decoration: none !important;
}

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

    .ms_type {
        position: absolute;
        top: 10px;
        right: 10px;
    }
}
</style>
