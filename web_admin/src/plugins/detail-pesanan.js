

// mendefinisikan object mixin
const detailMixin = {
  methods: {
    tempInvoice(user) {

      var html = ``;
      html += `<div id="invoice-POS">`;
      html += `<div id="top">`;
      html += `<div class="logo"></div>`;

      html += `<div class="info text-start">`;
      html += `<div>Nama Pelanggan: ${user.pelanggan.nama}</div>`;
      html += `<div>Kode : ${user.id_transaksi}</div>`;
      html += `<div>Tanggal: ${this.$filters.dateTime(user.tanggal_transaksi)}</div>`;
      html += `</div>`;
      html += `</div>`;

      html += `<table class=" text-start table-sm table table-striped jambo_table bulk_action table-bordered">`;
      html += `
        <tr>
          <td>No</td>
          <td>Menu</td>
          <td>Qty</td>
          <td>Harga</td>
        </tr>
      `;
      for (let index = 0; index < user.details.length; index++) {
        const element = user.details[index];

        var dicountT = ``;
        if (element.diskon > 0) {
          dicountT = `<div class="text-danger">Diskon: ${this.$filters.currency(element.diskon)}</div>`;
        }
        var hargaT = `<div>@${this.$filters.currency(element.harga)}</div>`;
        html += `
        <tr>
          <td>${index + 1}</td>
          <td>${element.menu.nama_menu}
          ${hargaT}
          ${dicountT}
          </td>
          <td>${element.jumlah}</td>
          <td>${this.$filters.currency(element.subtotal)}</td>
        </tr>
      `;
      }

      html += `
        <tr class="tabletitle">
          <td colspan="3" class="text-end Rate">Total</td>
          <td class="payment">${this.$filters.currency(user.total)}</td>
        </tr>`;
      html += `</table>`;

      html += `</div>`;
      return html;
    },
    openDetail(user) {
      var html = this.tempInvoice(user);
      this.$swal
        .fire({
          width: 600,
          title: "Detail Pesanan",
          html: html,
          showCancelButton: true,
          confirmButtonText: "Cetak",
          cancelButtonText: "Tutup",
        }).then((result) => {
          if (result.isConfirmed) {
            this.cetakInvoice(user);
          }
        });

    },
    cetakInvoice(user) {
      var css = `
        @media print {
            @page {
                size: 105mm  auto; /* Sets custom size (Letter) */
            }
        }
      `;


      const printContent = this.tempInvoice(user);
      const myWindow = window.open('', '', 'width=800,height=600');
      var html = `
          <html>
            <head>
              <title>Print </title>
              <link rel="stylesheet" href="${this.$PUBLIC_PATH}/vendors/bootstrap-5.0.2-dist/css/bootstrap.min.css">
              <style>
                ${css}
                </style>
            </head>
            <body>
              <div class="text-center">

              <img
                  src="${this.$PUBLIC_PATH}/Logo_Zonerone.png"
                  class=""
                  style="width: 100px; height: 100px;"
              />
              </div>
              ${printContent}
            </body>
          <script>
            window.onload = function() {
              window.print();
            };
            </scr` + `ipt>
          </html>
        `;
      // myWindow.document.close();

      myWindow.document.open();
      myWindow.document.write(html);
      myWindow.document.close();
    }
  }
}
export default detailMixin;
