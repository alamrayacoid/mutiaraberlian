Vue.component('vue-select', {

	props: ['name', 'id', 'title', 'options', 'disabled', 'search', 'value', 'styles'],

    mounted: function(){
    	var vm = this;

        if(!this.search){
            this.$select2 = $(this.$el).select2({
                theme: "bootstrap",
                dropdownAutoWidth: true,
                minimumResultsForSearch: Infinity,
                width: '100%',
                data: this.options
            }).on('change', function(e){
                vm.$emit('option-change', $(e.target).val())
            });

            // if(this.value){
            //     // alert(this.value);
            //     $(this.$el).val(this.value).trigger('change.select2');
            // }
        }else{
            this.$select2 = $(this.$el).select2({
                theme: "bootstrap",
                dropdownAutoWidth: true,
                width: '100%',
                data: this.options
            }).on('change', function(e){
                vm.$emit('option-change', $(e.target).val())
            });

            // if(this.value){
            //     // alert(this.value);
            //     $(this.$el).val(this.value).trigger('change.select2');
            // }
        }
    },

    watch: {
    	options: function(newOpts){
            if(!this.search){
                this.$select2.empty().select2({
                    theme: "bootstrap",
                    dropdownAutoWidth: true,
                    minimumResultsForSearch: Infinity,
                    width: '100%',
                    data: newOpts
                })

                // if(this.value){
                //     // alert(this.value);
                //     $(this.$el).val(this.value).trigger('change.select2');
                // }
            }else{
                this.$select2.empty().select2({
                    theme: "bootstrap",
                    dropdownAutoWidth: true,
                    width: '100%',
                    data: newOpts
                })

                // if(this.value){
                //     // alert(this.value);
                //     $(this.$el).val(this.value).trigger('change.select2');
                // }
            }
    	}
    },

    template: `
      	<select class="form-control form-control-sm" :name="name" :id="id" :title="title" :disabled="disabled"></select>
    `,
});