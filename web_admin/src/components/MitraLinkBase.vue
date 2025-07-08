<template>
    <div>
        <div v-if="typeView == 'name'">
            <routerLink target="_blank " class="text-decoration-underline text-success" :to="{
            name: !type ? 'app.deposit.saldo-mitra' : `app.${type}.deposit.saldo-mitra`,
            query: { user_id: mitra.id },
        }
            ">
                {{ mitra.name }}
            </routerLink>
        </div>
        <div v-else>
            <div>
                <routerLink target="_blank " class="text-decoration-underline text-success" :to="{
            name: !type ? 'app.deposit.saldo-mitra' : `app.${type}.deposit.saldo-mitra`,
            query: { user_id: mitra.id },
        }
            ">
                    {{ mitra.name }}
                </routerLink>
            </div>
            <div>
                {{ mitra.phone }}
            </div>
            <div>
                {{ mitra.email }}
            </div>
        </div>
    </div>

</template>
<script>

export default {
    props: {
        typeView: String,
        type: String,
        mitra: {
            type: Object,
            default: () => {
                return {}
            }
        }
    },

}
</script>