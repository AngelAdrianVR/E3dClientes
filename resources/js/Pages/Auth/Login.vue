<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    id: null,
    password: '',
    // remember: true,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const showPassword = ref(false);
const password = ref('');

const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value;
};
</script>

<template>
    <Head title="Iniciar sesión" />

    <AuthenticationCard>
        <template #logo>
            <!-- <AuthenticationCardLogo /> -->
        </template>

        <figure class="pb-4 pt-12 motion-preset-bounce motion-delay-[800ms]">
            <img class="w-[50%] mx-auto" src="@/../../public/images/logo.png" alt="Logo">
        </figure>

        <p class="text-secondary font-bold text-center">Exclusivo clientes</p>

        <div class="mb-4 mt-9 text-left w-full mx-auto">
            <span class="inline-block px-3 text-gray-600 font-bold">Iniciar sesión</span>
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <!-- <div>
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="ID de cliente"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div> -->
            <div class="relative">
                <i class="fa-solid fa-user absolute top-3 left-4 text-[#838083]"></i>
                <TextInput
                id="id"
                v-model="form.id"
                type="number"
                class="mt-1 block w-full placeholder:text-[#9A9A9A] text-gray-800 bg-[#F0F0F0] pl-11"
                required
                autofocus
                placeholder="ID de cliente"
                />
                <InputError class="mt-2" :message="form.errors.id" />
            </div>

            <div class="mt-4 relative">
                <div class="flex justify-center items-center">
                <TextInput
                    id="password"
                    v-model="form.password"
                    :type="showPassword ? 'text' : 'password'"
                    class="mt-1 block w-full placeholder:text-[#9A9A9A] text-gray-800 bg-[#F0F0F0] pl-11"
                    required
                    autocomplete="current-password"
                    placeholder="Contraseña"
                />
                <i
                    :class="showPassword ? 'fa fa-eye-slash' : 'fa fa-eye'"
                    class="text-gray-400 ml-2 cursor-pointer absolute right-3 top-4"
                    @click="togglePasswordVisibility"
                ></i>
                <i class="fa-solid fa-lock absolute top-4 left-4 text-[#838083]"></i>
                </div>
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- <div class="block mt-4 ml-3">
                <label class="flex items-center">
                    <Checkbox v-model:checked="form.remember" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">No cerrar sesión</span>
                </label>
            </div> -->
            <div class="flex flex-col items-center justify-center mt-4 space-y-4">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-[#9A9A9A] hover:text-gray-900 rounded-md"
                    >
                    Olvidé mi contraseña
                    </Link>

                <PrimaryButton class="bg-gradient-to-r from-[#D90537] to-[#A00429] !rounded-lg px-24"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    >
                    Iniciar sesión <i class="fa-solid fa-arrow-right ml-2"></i>
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
