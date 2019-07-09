Vue.component('vue-inputmask', {

    props: ['placeholder', 'name', 'id', 'disabled', 'css', 'value', 'minus', 'leading', 'types'],

    mounted: function () {
      var vm = this; var radix = '.'; var separator = ','; var digit = 2;

      if(this.types == 'number')
        radix = separator = ''; digit = 2;

      $(this.$el).inputmask("currency", {
          radixPoint: radix,
          groupSeparator: separator,
          digits: digit,
          allowMinus: vm.minus,
          autoGroup: true,
          prefix: (vm.leading) ? vm.leading : '', //Space after $, this will not truncate the first character.
          rightAlign: false,
          oncleared: function () {  }
      }).on('keyup', function(e){
        vm.$emit('input', $(e.target).val());
      })
    },

    methods: {
      // handleInput (e) {
      //   this.$emit('input', 20000)
      // }
    },

    template: `
        <input type="text" :name="name" class="form-control text-right modul-keuangan" :id="id" :disabled="disabled" :style="css" :value="(value) ? value : ''">
    `,
});