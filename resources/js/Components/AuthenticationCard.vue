<template>
    <div class="h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <slot name="logo" />
        </div>

        <div class="w-full sm:max-w-5xl h-[80%] mt-6 pl-6 overflow-hidden lg:border border-[#9A9A9A] rounded-lg lg:grid grid-cols-4 gap-x-2">
            <div class="col-span-2 mt-28 pr-4 lg:pl-16">
                <slot />
            </div>
            <figure class="hidden lg:block col-span-2">
                <img class="h-[750px]" src="@/../../public/images/Vector_login.png" alt="Logo">
            </figure>
        </div>
    </div>
</template>
