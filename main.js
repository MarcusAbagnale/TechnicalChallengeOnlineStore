new Vue({
  el: '#app',
  data: {
    newProduct: '',
    products: []
  },
  mounted() {
    this.getProducts();
  },
  methods: {
    getProducts() {
      fetch('app/products_api.php')
        .then(response => response.json())
        .then(data => {
          this.products = data;
        })
        .catch(error => {
          console.error('Error getting products:', error);
        });
    },
    addProduct() {
      if (this.newProduct !== '') {
        this.saveSale(this.newProduct);
        this.newProduct = '';
      }
    },
    removeProduct(index) {
      this.products.splice(index, 1);
    },
    saveSale(product) {
      fetch('sales_api.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(product)
      })
      .then(response => {
        if (response.ok) {
          console.log('Sale saved successfully!');
        } else {
          console.error('Error saving sale:', response.status);
        }
      })
      .catch(error => {
        console.error('Error saving sale:', error);
      });
    }
  }
});
