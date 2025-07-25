<template>
  <span class="badge" :class="badgeColor">
    {{ label ? label : status }}
  </span>
</template>

<script>
export default {
  props: {
    status: [String, Number],
    label: [String, Number],
  },

  computed: {
    badgeColor() {
      let badgeColor = "bg-primary";
      if (this.label) {
        let labelC = this.label.toLowerCase().trim();
        switch (labelC) {
          case "konfirmasi refund":
          case "konfirmasi rebook":
            return "bg-purple";
          case "success":
          case "payment":
          case "issued":
          case "refund":
          case "rebook":
          case "approved":
            badgeColor = "bg-success";
            return badgeColor;
          case "process":
          case "open":
          case "booking":
            badgeColor = "bg-primary";
            return badgeColor;
          case "Pending":
          case "pending":
          case "waiting issued":
          case "MENUNGGU KONFIRMASI REFUND MITRA":
          case "menunggu konfirmasi refund mitra":
          case "MENUNGGU KONFIRMASI REBOOK MITRA":
          case "menunggu konfirmasi rebook mitra":
            badgeColor = "bg-warning";
            return badgeColor;
          case "failed":
          case "cancel":
          case "cancelled":
          case "canceled":
          case "reject":
          case "rejected":
          case "CANCELLED":
          case "FAILED":
          case "close":
          case "nonaktif":
            badgeColor = "bg-danger";
            return badgeColor;
          case "expired":
            badgeColor = "bg-purple";
            return badgeColor;
        }
      }

      switch (this.status) {
        case "success":
        case "payment":
        case "Active":
          badgeColor = "bg-success";
          return badgeColor;
        case "process":
        case "open":
          badgeColor = "bg-primary";
          return badgeColor;
        case "pending":
        case "Pending":
        case "waiting issued":
          badgeColor = "bg-warning";
          return badgeColor;
        case "failed":
        case "cancel":
        case "CANCELLED":
        case "close":
        case "Deactive":
        case "nonaktif":
          badgeColor = "bg-danger";
          return badgeColor;
      }
      return badgeColor;
    },
  },
};
</script>
