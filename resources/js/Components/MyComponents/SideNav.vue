<template>
    <!-- sidebar -->
    <div class="h-screen hidden md:block shadow-lg relative bg-[#101010]">
        <i @click="updateSideNavSize(false)" v-if="small"
            class="fa-solid fa-angle-right text-center text-xs pt-[2px] text-white rounded-full size-5 bg-primary absolute top-8 -right-3 cursor-pointer hover:scale-125 transition-transform ease-linear duration-150"></i>
        <i @click="updateSideNavSize(true)" v-else
            class="fa-solid fa-angle-left text-center text-xs pt-[2px] text-white rounded-full size-5 bg-primary absolute top-8 -right-3 cursor-pointer hover:scale-125 transition-transform ease-linear duration-150"></i>
        <div class="h-full overflow-auto">
            <!-- Logo -->
            <div class="flex items-center justify-center mt-7">
                <Link v-if="small" :href="route('dashboard')">
                <figure class="px-3 pb-[20px]">
                    <img class="w-10 mx-auto" src="@/../../public/images/isoLogoEmblems.png" alt="Logo">
                </figure>
                </Link>
                <Link v-else :href="route('dashboard')">
                <figure class="px-3 h-[59px]">
                    <img class="w-56 mx-auto" src="@/../../public/images/logo.png" alt="Logo">
                </figure>
                </Link>
            </div>
            <nav class="pr-2 text-white h-[82%] overflow-auto">
                <!-- Con barra pequeña -->
                <section v-if="small">
                    <div v-for="(menu, index) in menus" :key="index">
                        <button v-if="menu.show" @click="handleClickInMenu(index)" :active="menu.active"
                            :title="menu.label"
                            class="w-full text-center py-2 pr-3 pl-5 justify-between rounded-r-[10px] mt-2 transition ease-linear duration-150"
                            :class="menu.active ? 'bg-[#393939] text-primary border-l-2 border-primary' : 'hover:text-primary hover:bg-[#393939] text-[#9A9A9A]'">
                            <span v-html="menu.icon"></span>
                        </button>
                    </div>
                </section>
                <!-- Con barra grande -->
                <section v-else v-for="(menu, index) in menus" :key="index">
                    <!-- Con submenues -->
                    <div v-if="menu.show">
                        <Accordion v-if="menu.options.length" :icon="menu.icon" :active="menu.active"
                            :title="menu.label" :id="index">
                            <div v-for="(option, index2) in menu.options" :key="index2">
                                <button @click="handleClickInMenu(index)" v-if="option.show" :active="option.active"
                                    :title="option.label"
                                    class="w-full text-start pl-6 pr-2 mt-2 flex justify-between text-xs rounded-md py-1 transition ease-linear duration-150"
                                    :class="option.active ? 'bg-[#393939] text-primary' : 'hover:text-primary hover:bg-gradient-to-r from-gray-800 to-black1 text-gray-700'">
                                    <p class="w-full truncate"> {{ option.label }}</p>
                                </button>
                            </div>
                        </Accordion>
                        <!-- Sin submenues -->
                        <button v-else-if="menu.show" @click="handleClickInMenu(index)" :active="menu.active"
                            :title="menu.label"
                            class="w-full text-start pl-5 pr-3 py-2 mt-2 border-l-2 text-xs rounded-r-[10px] transition ease-linear duration-150"
                            :class="menu.active ? 'bg-[#393939] text-primary border-primary' : 'hover:text-primary border-transparent hover:bg-[#393939] text-[#9A9A9A]'">
                            <p class="w-full truncate flex items-center">
                                <span class="mr-2" v-html="menu.icon"></span>
                                <span>{{ menu.label }}</span>
                            </p>
                        </button>
                    </div>
                </section>
            </nav>
        </div>
    </div>

    <ConfirmationModal :show="showGoToRouteConfirmation" @close="showGoToRouteConfirmation = false">
        <template #title>
            <h1>Proceso pendiente</h1>
        </template>
        <template #content>
            <p>
                Tienes un proceso sin completar en esta vista. Si cambias de vista, se borrarán los cambios o
                procesos que no has finalizado. ¿Continuar de todas formas?
            </p>
        </template>
        <template #footer>
            <div class="flex items-center space-x-1">
                <CancelButton @click="showGoToRouteConfirmation = false">Cancelar</CancelButton>
                <DangerButton @click="goToRoute()">Continuar</DangerButton>
            </div>
        </template>
    </ConfirmationModal>
</template>

<script>
import Accordion from './Accordion.vue';
import { Link } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import DangerButton from "@/Components/DangerButton.vue";
import CancelButton from "@/Components/MyComponents/CancelButton.vue";

export default {
    data() {
        return {
            small: true,
            collapsedMenu: null,
            routeToGo: null,
            showGoToRouteConfirmation: false,
            menus: [
                {
                    label: 'Productos',
                    icon: '<svg width="30" height="26" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3.16745 7.92503L6.28317 16.2336M11.9953 13.6372L14.5918 12.5986M1.39551 6.86012L9.81951 3.48946C8.44228 7.07025 12.6054 8.11039 13.5955 5.09594L16.8523 13.1438C17.0657 13.6732 17.1105 13.9116 16.5547 14.1869L5.96632 18.4547C5.52512 18.6325 5.40132 18.5799 5.22351 18.1387L1.07947 7.60294C0.937201 7.24999 0.954307 7.03794 1.39551 6.86012ZM11.4159 1.40001L10.0847 4.35824C9.39447 6.13319 11.7611 7.5137 12.7471 5.54154L14.0783 2.534C14.2262 2.23818 14.2262 2.04097 13.8811 1.84375L12.1555 1.10419C11.7702 0.923147 11.5965 0.951948 11.4159 1.40001ZM12.328 5.14711C12.328 5.71894 11.881 6.18249 11.3296 6.18249C10.7782 6.18249 10.3312 5.71894 10.3312 5.14711C10.3312 4.57529 10.7782 4.11173 11.3296 4.11173C11.881 4.11173 12.328 4.57529 12.328 5.14711Z" stroke="currentColor" stroke-width="0.986078" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                    route: route('catalog-product-company.index'),
                    active: route().current('catalog-product-company.*'),
                    options: [],
                    dropdown: false,
                    show: true
                },
                {
                    label: 'Cotizaciones',
                    icon: '<svg width="30" height="22" viewBox="0 0 15 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.55269 1V5.2193L10.579 4.42105L11.7194 5.2193V1M9.55269 1H11.7194M9.55269 1H3.7369M11.7194 1L12.9737 1C13.886 1 14 1.45614 14 2.36842V16.1667C14 16.9649 13.886 17.307 12.9737 17.307H3.7369M3.7369 1L2.02632 1C1.22813 0.999786 1.00003 1.52889 1 2.36842V16.1667C1.00005 16.9649 1.22813 17.307 2.02632 17.307H2.36848M3.7369 1V17.307M3.7369 17.307H2.36848M2.36848 4.07895V17.307M6.47374 8.52632C8.43321 8.52632 9.53181 8.52632 11.4913 8.52632M6.47374 10.0088C7.49801 10.0088 8.07228 10.0088 9.09655 10.0088M6.47374 11.2632C8.43321 11.2632 9.53181 11.2632 11.4913 11.2632" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                    route: route('quotes.index'),
                    active: route().current('quotes.*'),
                    options: [],
                    dropdown: false,
                    show: true
                },
                {
                    label: 'Diseños',
                    icon: '<svg width="29" height="22" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.56364 11.6909L6.09091 13.2182M4.56364 11.6909L3.18182 14.3945M4.56364 11.6909L6.77737 9.50909L7.54525 10.2727M6.09091 13.2182L3.42424 14.6727M6.09091 13.2182L8.31313 11.0364L7.54525 10.2727M3.18182 14.3945L2.89091 14.9636L3.42424 14.6727M3.18182 14.3945L3.21951 14.4377M3.42424 14.6727L3.38713 14.6301M3.21951 14.4377L2.96364 14.8909L3.38713 14.6301M3.21951 14.4377L3.26374 14.4885M3.38713 14.6301L3.34947 14.5869M3.26374 14.4885L3.03636 14.8182L3.34947 14.5869M3.26374 14.4885L3.28625 14.5143M3.34947 14.5869L3.32522 14.5591M3.28625 14.5143L3.18182 14.6727L3.32522 14.5591M3.28625 14.5143L3.32522 14.5591M9.50909 6.63111L3.90909 1L1 3.76364L2.43095 5.18668M9.50909 6.63111L12.7818 3.4C12.9273 3.25455 13.0727 3.18182 13.1455 3.25455L13.2389 3.35351M9.50909 6.63111L11.1091 8.24M11.1091 8.24L17 14.1636L14.1636 16.8545L12.8473 15.5455M11.1091 8.24L14.3091 4.92727C14.4545 4.78182 14.5273 4.70909 14.3818 4.56364L14.3389 4.51817M13.2389 3.35351L14.6 1.94545C14.7455 1.8 14.8909 1.8 14.9636 1.87273L15.8364 2.67273C15.9818 2.81818 15.9818 2.89091 15.8364 3.03636L14.3389 4.51817M13.2389 3.35351L14.3389 4.51817M12.8473 15.5455C13.2477 15.1194 13.4723 14.8806 13.8727 14.4545M12.8473 15.5455L11.4578 14.1636M11.4578 14.1636C12.0601 13.5672 12.3977 13.2328 13 12.6364M11.4578 14.1636L10.2364 12.949M10.2364 12.949C10.6908 12.4861 10.9456 12.2265 11.4 11.7636M10.2364 12.949L8.97131 11.6909M8.97131 11.6909C9.38014 11.2365 9.60935 10.9817 10.0182 10.5273M8.97131 11.6909L6.33859 9.07273M7.54525 10.2727C8.19857 9.60529 8.56486 9.23108 9.21818 8.56364M6.33859 9.07273C6.7815 8.5899 7.02982 8.31919 7.47273 7.83636M6.33859 9.07273L5 7.74154M5 7.74154C5.48283 7.23894 5.75353 6.95715 6.23636 6.45455M5 7.74154L3.73685 6.48537M3.73685 6.48537C4.45735 5.76329 4.86131 5.35844 5.58182 4.63636M3.73685 6.48537L2.43095 5.18668M2.43095 5.18668C2.9798 4.63095 3.28751 4.31937 3.83636 3.76364" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                    route: route('design-authorizations.index'),
                    active: route().current('design-authorizations.*'),
                    options: [],
                    dropdown: false,
                    show: true
                },

                //ejemplo para usar submenues
                //     label: 'Comunidad',
                //     icon: '<i class="fa-solid fa-people-roof text-sm mr-2"></i>',
                //     // route: route('posts.index'),
                //     active: route().current('posts.*') || route().current('community-events.*')|| route().current('neighbors.*'),
                //     options: [
                //         {
                //             label: 'Muro de noticias',
                //             route: route('posts.index'),
                //             show: true,
                //         },
                //         {
                //             label: 'Eventos',
                //             route: route('community-events.index'),
                //             show: true,
                //         },
                //         {
                //             label: 'Directorio de vecinos',
                //             route: route('neighbors.index'),
                //             show: true,
                //         },
                //     ],
                //     dropdown: true,
                //     show: true
                // },
            ],
        }
    },
    components: {
        ApplicationMark,
        Accordion,
        DropdownLink,
        Dropdown,
        Link,
        ConfirmationModal,
        DangerButton,
        CancelButton,
    },
    methods: {
        handleClickInMenu(index) {
            if (this.menus[index].options.length) {
                if (this.collapsedMenu === index) {
                    this.collapsedMenu = null;
                } else {
                    this.collapsedMenu = index;
                }
            } else {
                // revisar si hay proceso pendiente para no cambiar de vista sin preguntar
                const pendentProcess = JSON.parse(localStorage.getItem('pendentProcess'));
                if (pendentProcess) {
                    this.routeToGo = this.menus[index].route;
                    this.showGoToRouteConfirmation = true;
                } else {
                    this.goToRoute(this.menus[index].route)
                }
            }
        },
        goToRoute(route = null) {
            // resetear variable de local storage a false
            localStorage.setItem('pendentProcess', false);

            // ir a la ruta solicitada
            if (route) {
                this.$inertia.get(route);
            } else {
                this.$inertia.get(this.routeToGo);
            }
        },
        logout() {
            this.$inertia.post(route('logout'));
        },
        updateSideNavSize(is_small) {
            this.small = is_small;
            localStorage.setItem('is_sidenav_small', is_small);
        }
    },
    mounted() {
        const is_small = localStorage.getItem('is_sidenav_small');
        if (is_small !== null) {
            this.small = JSON.parse(is_small); // Convertirlo a booleano si es necesario
        }
    }
}
</script>