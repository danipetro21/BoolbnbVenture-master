<script>
import AnimatedPlaceholderVue from "../Components/AnimatedPlaceholder.vue";

export default {
    components: {
        AnimatedPlaceholderVue,
    },
};
</script>

<template>
    <div class="box mb-5">
        <img src="" alt="" class="card-img-top" />
        <AnimatedPlaceholderVue
            height="250px"
            width="270px"
            borderRadius="20px"
        />
        <div class="text p-3">
            <h5 class="ms_title font-bold text-3xl text-zinc-800 mb-2">
                <AnimatedPlaceholderVue
                    height="35px"
                    width="100%"
                    borderRadius="8px"
                />
                <AnimatedPlaceholderVue
                    height="35px"
                    width="80%"
                    borderRadius="8px"
                />
            </h5>
            <h6 class="ms_address text-zinc-600">
                <AnimatedPlaceholderVue
                    height="20px"
                    width="100%"
                    borderRadius="8px"
                />
            </h6>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.ms_title {
    display: flex;
    flex-direction: column;
    gap: 8px;
    width: 100%;
}
</style>
