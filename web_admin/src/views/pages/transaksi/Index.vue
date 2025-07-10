<template>
  <div class="col-md-12 col-sm-12">
    <div class="x_panel">
      <div class="x_title">
        
        <ul class="nav navbar-right panel_toolbox">
          <li>
            &nbsp;
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        <div class="col-12 col-md-7">
          <Menu @set="setMenu" />
        </div>
        <div class="col-12 col-md-7">
          
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Menu from './components/Menu.vue';
export default {
  component:{
    Menu
  },
  data() {
    return {
      pelanggan:'',
      details:[],
    };
  },
  mounted() {
    
  },
  computed: {
    grand_total(){
      return this.details.reduce((p,c)=>{
        return p+c.subtotal;
      },0);
    } 
  },
  watch: {
    
  },
  methods: {
    setMenu(item) {
      var cek = this.details.findIndex(v=>v.id_menu == item.id_menu);
      if(cek) {
        this.details[cek].jumlah = this.details[cek].jumlah+1;
        this.details[cek].subtotal = this.details[cek].jumlah*parseInt(item.harga);  
      }else{
        this.details.push({
          menu: item,
          id_menu: item.id_menu,
          jumlah: 1,
          subtotal: 1*parseInt(item.harga)
        })
      }
    },
    loadCat() {
      this.$axios
        .get("/app/menu-cats", { params:{} })
        .then((res) => {
          this.cats = res.data;

        })
        .catch((res) => {
          this.$root.notif(res.message, {
            type: "error",
            position: "top",
          });
        })
        .finally(() => {
          this.loading = false;
        });
    }
  },
};
</script>
