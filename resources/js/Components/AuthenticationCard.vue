<template>
    <div class="h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 relative overflow-hidden">
        <div>
            <slot name="logo" />
        </div>

        <div class="w-full sm:max-w-5xl h-[80%] mt-6 pl-6 overflow-hidden lg:flex justify-end">
            <figure class="hidden lg:block absolute -top-40 left-0 motion-preset-expand motion-delay-[100ms]">
                <img class="w-[80%]" draggable="false" src="@/../../public/images/login_decoration1.webp" alt="Logo">
            </figure>
            <div class="col-span-2 mt-28 pr-4 lg:pl-16  motion-preset-slide-right motion-ease-spring-bouncier motion-delay-[400ms]">
                <slot />
            </div>
            <figure class="hidden lg:block absolute -bottom-24 -right-40 motion-preset-expand motion-delay-[100ms]">
                <img draggable="false" class="w-2/3" src="@/../../public/images/login_decoration2_1.webp" alt="Logo">
            </figure>
        </div>
    </div>
</template>
