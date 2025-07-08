<template>
    <div v-if="modelValue" class="modal-backdrop fade show" @click="close" style="z-index:10"></div>
  <div
    class="modal fade show"
    :style="modelValue?'display:block;z-index:11':''"
  >
    <div class="modal-dialog" :class="size">
      <div class="modal-content">
        <slot></slot>
      </div>
    </div>
  </div>
</template>
<script>
// import { watch } from "vue";
export default {
  props: {
    modelValue: Boolean,
    size: String
  },
  // emits: ["on-change", "on-blur", "on-focus"],
  setup(props, { emit }) {
    const close = () => {
      emit("update:modelValue", false);
    };
    // watch(
    //   () => props.modelValue,
    //   (selection, prevSelection) => {
    //     console.log(selection, prevSelection);
    //   }
    // );
    return { close };
  },
};
</script>
