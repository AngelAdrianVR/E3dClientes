<template>
    <!-- sidebar -->
    <div class="h-full hidden md:block shadow-lg relative bg-[#101010]">
        <i @click="updateSideNavSize(false)" v-if="small"
            class="fa-solid fa-angle-right text-center text-xs pt-[2px] text-white rounded-full size-5 bg-primary absolute top-8 -right-3 cursor-pointer hover:scale-125 transition-transform ease-linear duration-150"></i>
        <i @click="updateSideNavSize(true)" v-else
            class="fa-solid fa-angle-left text-center text-xs pt-[2px] text-white rounded-full size-5 bg-primary absolute top-8 -right-3 cursor-pointer hover:scale-125 transition-transform ease-linear duration-150"></i>
        <div class="h-full overflow-auto">
            <!-- Logo -->
            <div class="flex items-center justify-center mt-7">
                <Link v-if="small" :href="route('dashboard')">
                <figure class="px-3 -mt-4 pb-[20px]">
                    <img class="w-16 mx-auto" src="@/../../public/images/isoLogoEmblems.png" alt="Logo">
                </figure>
                </Link>
                <Link v-else :href="route('dashboard')">
                <figure class="px-3 h-16">
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
                    icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>',
                    route: route('catalog-product-company.index'),
                    active: route().current('catalog-product-company.*'),
                    options: [],
                    dropdown: false,
                    show: true
                },
                {
                    label: 'Cotizaciones',
                    icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" /></svg>',
                    route: route('quotes.index'),
                    active: route().current('quotes.*'),
                    options: [],
                    dropdown: false,
                    show: true
                },
                {
                    label: 'Diseños',
                    icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="-0.855 -0.855 24 24" height="22" width="22" id="Shopping-Basket-2--Streamline-Core"><desc>Shopping Basket 2 Streamline Icon: https://streamlinehq.com</desc><g id="shopping-basket-2--shopping-basket"><path id="Vector" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M19.625389714285713 7.932533357142858H2.6646102857142857C2.4237190714285712 7.927597714285714 2.184897642857143 7.976954142857143 1.9656595714285712 8.076940714285715C1.7464214999999998 8.176927285714285 1.5524984999999998 8.32499657142857 1.398219857142857 8.510162785714286C1.2439412142857142 8.695169785714285 1.1332872857142857 8.9126565 1.0745372142857144 9.146223857142857C1.0157871428571428 9.379950428571428 1.0102146428571428 9.623866714285715 1.0584565714285714 9.859822285714285L2.8252574999999998 18.693667714285713C2.900406642857143 19.061771142857143 3.1021311428571425 19.392140785714286 3.3957222857142857 19.626822642857142C3.689154214285714 19.861663714285715 4.0556655 19.986169285714286 4.431411214285714 19.978527H17.858588785714286C18.234493714285712 19.986169285714286 18.601005 19.861663714285715 18.894436928571427 19.626822642857142C19.187868857142856 19.392140785714286 19.38975257142857 19.061771142857143 19.4647425 18.693667714285713L21.231543428571428 9.859822285714285C21.279785357142856 9.623866714285715 21.274212857142857 9.379950428571428 21.215462785714287 9.146223857142857C21.156712714285714 8.9126565 21.046058785714283 8.695169785714285 20.891780142857144 8.510162785714286C20.7375015 8.32499657142857 20.5435785 8.176927285714285 20.32434042857143 8.076940714285715S19.866280928571427 7.927597714285714 19.625389714285713 7.932533357142858Z" stroke-width="1.71"></path><path id="Vector_2" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M14.357307428571428 2.3111545714285713L17.569614857142856 7.932533357142858" stroke-width="1.71"></path><path id="Vector_3" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M4.720385142857142 7.932533357142858L7.932692571428571 2.3111545714285713" stroke-width="1.71"></path></g></svg>',
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