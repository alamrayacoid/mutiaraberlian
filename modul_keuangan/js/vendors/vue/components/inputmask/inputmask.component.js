Vue.component('vue-inputmask', {

    props: ['placeholder', 'name', 'id', 'disabled', 'css', 'value', 'minus'],

    mounted: function () {
      var vm = this
      $(this.$el).inputmask("currency", {
          radixPoint: ".",
          groupSeparator: ",",
          digits: 2,
          allowMinus: vm.minus,
          autoGroup: true,
          prefix: '', //Space after $, this will not truncate the first character.
          rightAlign: true,
          oncleared: function () {  }
      }).on('keyup', function(e){
        vm.$emit('input', {val: $(e.target).val(), id: vm.id});
      });
    },

    template: `
        <input type="text" :name="name" class="form-control text-right modul-keuangan" :id="id" :disabled="disabled" :style="css" :value="(value) ? value : ''">
    `,
});