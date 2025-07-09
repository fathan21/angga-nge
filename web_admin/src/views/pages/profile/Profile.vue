<template>
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content" style="min-height: 100px">
                    <table class="table text-16">
                        <tr>
                            <td
                                class="text-grey-light py-3"
                                style="width: 100px"
                            >
                                Username
                            </td>
                            <td class="font-weight-bold">
                                :&nbsp;&nbsp;&nbsp;
                                {{ data.username }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
    computed: {
        ...mapGetters({
            data: "auth/user",
        }),
    },
};
</script>

