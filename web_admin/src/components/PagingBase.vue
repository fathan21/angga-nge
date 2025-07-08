<template>

    <div class="row">
        <div class="col-sm-5">
            Showing
            {{ (options.current_page - 1) * options.per_page + 1 }} to
            {{
                    options.total_row > options.per_page * options.current_page
                        ? options.per_page * options.current_page
                        : options.total_row
            }}
            of {{ options.total_row }} entries
        </div>
        <div class="col-sm-7 text-right">
            <b-pagination align="end" v-model="current_page" :total-rows="options.total_row"
                :per-page="options.per_page" first-text="First" prev-text="Prev" next-text="Next" last-text="Last">
            </b-pagination>
        </div>
    </div>
</template>
<script>

export default {
    props:['options','modelValue'],
    data() {
        return {
            current_page: 1
        }
    },
    watch:{
        current_page: {
            handler:function(){
                this.$emit('update:modelValue', this.current_page);
            }
        }
    }
}
</script>