<template>
  <div>
    <v-select
      label="name"
      class="style-chooser"
      :filterable="false"
      :options="options"
      @search="onSearch"
      v-model="selected"
      placeholder="Pilih Operator"
    >
      <template v-slot:no-options="{}">
        ketik untuk mencari operator..
      </template>
      <template v-slot:option="option">
        <div class="">{{ option.name }}</div>
      </template>
      <template v-slot:selected-option="option">
        <div class="text-nowrap">
          {{ option.name }}
        </div>
      </template>
    </v-select>
  </div>
</template>

<script>
let self;
import _debounce from "lodash/debounce";
import { ref, watch, onMounted, nextTick } from "vue";
export default {
  emits: ["on-change", "on-blur", "on-focus", "update:modelValue"],
  props: ["category", "type", "modalValue"],
  setup(props, { emit }) {
    const selected = ref(props.value);
    const type = ref(props.type);
    let options = ref([]);

    let searchAjax = _debounce((loading, search) => {
      self.$axios
        .get("/ppob/operator", {
          params: {
            q: escape(search),
            type: type.value,
            category: props.category,
          },
        })
        .then((res) => {
          options.value = Object.values(res.data.data);
        })
        .finally(() => {
          if (loading) {
            loading(false);
          }
        });
    }, 350);

    let onSearch = (search, loading) => {
      if (search.length) {
        loading(true);
        searchAjax(loading, search);
      } else {
        searchAjax(loading, search);
      }
    };

    watch(selected, (newSelected) => {
      if (newSelected) {
        emit("update:modelValue", selected.value.name);
      } else {
        emit("update:modelValue", "");
      }
    });

    watch(
      () => props.category,
      () => {
        onSearch("");
      }
    );

    onMounted(() => {
      nextTick(() => onSearch(""));
    });

    return { selected, searchAjax, onSearch, options };
  },
  created() {
    self = this;
  },
};
</script>
