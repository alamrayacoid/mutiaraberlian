Vue.component('vue-select', {

	props: ['name', 'id', 'title', 'options', 'disabled', 'search', 'value', 'styles'],

    mounted: function(){
    	var vm = this;

        if(!this.search){
            this.$select2 = $(this.$el).select2({
                data: this.options,
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'select2-modul-keuangan-dropdown',
                containerCssClass: 'select2-modul-keuangan-content',
            })
            .on('change', function(e){
                vm.$emit('input', $(e.target).val())
            });

            if(this.value){
                // alert(this.value);
                $(this.$el).val(this.value).trigger('change.select2');
            }
        }
        else{
            this.$select2 = $(this.$el).select2({
                data: this.options,
                dropdownCssClass: 'select2-modul-keuangan-dropdown',
                containerCssClass: 'select2-modul-keuangan-content',
            })
            .on('change', function(e){
                vm.$emit('input', $(e.target).val())
            });

            if(this.value){
                // alert(this.value);
                $(this.$el).val(this.value).trigger('change.select2');
            }
        }
    },

    watch: {
    	options: function(newOpts){
            if(!this.search){
                this.$select2.empty().select2({
                    data: newOpts,
                    minimumResultsForSearch: Infinity,
                    dropdownCssClass: 'select2-modul-keuangan-dropdown',
                    containerCssClass: 'select2-modul-keuangan-content',
                })

                if(this.value){
                    // alert(this.value);
                    $(this.$el).val(this.value).trigger('change.select2');
                }
            }else{
                this.$select2.empty().select2({
                    data: newOpts,
                    // minimumResultsForSearch: Infinity,
                    dropdownCssClass: 'select2-modul-keuangan-dropdown',
                    containerCssClass: 'select2-modul-keuangan-content',
                })

                if(this.value){
                    // alert(this.value);
                    $(this.$el).val(this.value).trigger('change.select2');
                }
            }
    	}
    },

    template: `
      	<select class="form-control" :id="id" :name="name" :title="title" :disabled="disabled" :style="'border: 0px;'+styles"></select>
    `,
});