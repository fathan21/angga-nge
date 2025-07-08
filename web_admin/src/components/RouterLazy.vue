<template>
    <div>
        <template v-if="refd">
            <component :is="refd" />
        </template>
    </div>
    <!-- <ModalWindow /> -->
</template>
<script>
import { shallowRef, defineComponent } from "vue";
// import { defineAsyncComponent } from "vue";
// import RouterLoading from "./RouterLoading.vue";
// import RouterError from "./RouterError.vue";
// import {  shallowRef } from 'vue'

// Async component without options
// const asyncModal = defineAsyncComponent(() => import('./Modal.vue'))

// Async component with options
// const asyncModalWithOptions=  defineAsyncComponent({
//   loader: () => import(/* webpackChunkName: "app-users" */ temp),
//   delay: 200,
//   timeout: 3000,
//   errorComponent: RouterError,
//   loadingComponent: RouterLoading
// })

// export default {
//     props:{
//         temp:{
//             default: null
//         }
//     },
//   components: {
//     // asyncModalWithOptions:asyncModalWithOptions(this.temp)
//   }
// }

export default defineComponent({
    props: {
        temp: {
            type: String,
            required: true,
        },
    },
    setup(props) {
        const refd = shallowRef("");
        if (props.temp) {
            import(/* webpackChunkName: "[request]" */ `@/views/pages/${props.temp}.vue`)
                // import(`${props.temp}`)
                .then((module) => {
                    refd.value = module.default;
                });
        }
        return {
            refd,
        };
        //     // use shallowRef to remove unnecessary optimizations
        //     // const currentIcon = shallowRef('')

        //     // import(`../icons/icon-${props.name}.vue`).then(val => {
        //     //   // val is a Module has default
        //     //   currentIcon.value = val.default
        //     // })

        //     asyncModalWithOptions.value = defineAsyncComponent({
        //         loader: () => import(props.temp),
        //         delay: 200,
        //         timeout: 3000,
        //         errorComponent: RouterError,
        //         loadingComponent: RouterLoading,
        //     });

        //     return {
        //         asyncModalWithOptions,
        //     };
    },
});
</script>
