<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { computed, ref } from "vue";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const emailError = ref("");
const passwordError = ref("");

const isFormValid = computed(() => !emailError.value && !passwordError.value);

const validateEmail = () => {
    const emailRegex = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/;
    emailError.value = emailRegex.test(form.email)
        ? ""
        : "Invalid email address";
};

const validatePassword = () => {
    const passwordRegex =
        /^(?=.*\d)(?=.*[a-zA-Z])(?=.*[!@#$%^&*()_+,\-./:;<=>?@[\\\]^_`{|}~]).{8,32}$/;

    if (form.password.length < 8) {
        passwordError.value = "Password must be at least 8 characters";
    } else if (form.password.length > 32) {
        passwordError.value = "Password must be at most 32 characters";
    } else if (!passwordRegex.test(form.password)) {
        passwordError.value =
            "Password must contain at least 1 special character, 1 letter, and 1 number";
    } else {
        passwordError.value = "";
    }
};

const submit = () => {
    validateEmail();
    validatePassword();

    if (isFormValid.value) {
        form.post(route("login"), {
            onFinish: () => form.reset("password"),
        });
    }
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full form-input"
                    v-model="form.email"
                    @input="validateEmail"
                />

                <InputError class="mt-2" :message="emailError" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full form-input"
                    v-model="form.password"
                    @input="validatePassword"
                />

                <InputError class="mt-2" :message="passwordError" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400"
                        >Remember me</span
                    >
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton
                    class="ml-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
