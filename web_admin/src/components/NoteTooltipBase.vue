<template>
    <div class="notes-list">
        <tippy :content="notes">
            <i class="fa fa-comment text-info" />
        </tippy>
    </div>
</template>
  
  <!-- 
    The below is optional in case you 
    installed the plugin globally
   -->
<script>
import { Tippy } from 'vue-tippy'

export default {
    components: [Tippy],
    props: {
        notes: String
    }
}
</script>
<style lang="scss">
// .notes-list {
.tippy-box {
    background-color: white;
    color: black;
}

.tippy-box[data-placement^='top']>.tippy-arrow::before {
    border-top-color: white;
}

.tippy-box[data-placement^='bottom']>.tippy-arrow::before {
    border-bottom-color: white;
}

.tippy-box[data-placement^='left']>.tippy-arrow::before {
    border-left-color: white;
}

.tippy-box[data-placement^='right']>.tippy-arrow::before {
    border-right-color: white;
}

// }
</style>