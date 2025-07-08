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
                                Nama
                            </td>
                            <td class="font-weight-bold">
                                :&nbsp;&nbsp;&nbsp;
                                {{ data.name }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-grey-light py-3">Email</td>
                            <td class="font-weight-bold">
                                :&nbsp;&nbsp;&nbsp;
                                {{ data.email }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-grey-light py-3">Phone</td>
                            <td class="font-weight-bold">
                                :&nbsp;&nbsp;&nbsp;
                                {{ data.phone }}
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

